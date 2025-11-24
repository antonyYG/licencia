<?php
// Asegura salida JSON aunque existan warnings
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Parámetros
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;
$radius = isset($_GET['radius']) ? floatval($_GET['radius']) : 100.0; // metros

if ($lat === null || $lng === null) {
    http_response_code(400);
    echo json_encode([ 'error' => 'Parámetros lat y lng son obligatorios' ]);
    exit;
}

// Conexión a BD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "licencia3";

$conn = new mysqli($servername, $username, $password, $dbname);
@mysqli_set_charset($conn, 'utf8mb4');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([ 'error' => 'Fallo de conexión a BD' ]);
    exit;
}

// Radio en km
$radiusKm = $radius / 1000.0;
// Tolerancia para cubrir variaciones mínimas de geocodificación (5 metros)
$epsilonKm = 0.005;
$radiusKmEff = $radiusKm + $epsilonKm;

// Haversine en PHP para diagnóstico de distancia por tienda (km)
function haversine_km($lat1, $lon1, $lat2, $lon2){
    $R = 6371.0; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(min(1, sqrt($a)));
    return $R * $c;
}

// Consulta Haversine: distancia en km <= $radiusKm
$sql = "SELECT IFNULL(l.condicion, 0) AS condicion,
               t.idtienda, t.nombres_per, l.nombre_comercial, t.latitud, t.longitud, t.ubic_tienda
        FROM tienda t
        LEFT JOIN licencia l ON l.idtienda = t.idtienda
        WHERE t.latitud IS NOT NULL AND t.longitud IS NOT NULL
          AND t.latitud <> 0 AND t.longitud <> 0
          AND (2 * 6371 * ASIN(SQRT(
               POW(SIN((RADIANS(t.latitud) - RADIANS(?)) / 2), 2) +
               COS(RADIANS(?)) * COS(RADIANS(t.latitud)) *
               POW(SIN((RADIANS(t.longitud) - RADIANS(?)) / 2), 2)
          ))) <= ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode([ 'error' => 'Error preparando consulta' ]);
    $conn->close();
    exit;
}

$stmt->bind_param('dddd', $lat, $lat, $lng, $radiusKmEff);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode([ 'error' => 'Error ejecutando consulta' ]);
    $stmt->close();
    $conn->close();
    exit;
}

// Soporte para entornos sin mysqlnd
$tiendas = [];
$conLic = 0;
$sinLic = 0;

if (method_exists($stmt, 'get_result')) {
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $latT = isset($row['latitud']) ? floatval($row['latitud']) : null;
            $lngT = isset($row['longitud']) ? floatval($row['longitud']) : null;
            $row['dist_km'] = ($latT !== null && $lngT !== null) ? haversine_km($lat, $lng, $latT, $lngT) : null;
            $tiendas[] = $row;
            if (intval($row['condicion']) === 1) { $conLic++; } else { $sinLic++; }
        }
    } else {
        // Fallback a bind_result
        $stmt->store_result();
        $stmt->bind_result($condicion, $idtienda, $nombres_per, $nombre_comercial, $latitud, $longitud, $ubic_tienda);
        while ($stmt->fetch()) {
            $row = [
                'condicion' => $condicion ?? 0,
                'idtienda' => $idtienda,
                'nombres_per' => $nombres_per,
                'nombre_comercial' => $nombre_comercial,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'ubic_tienda' => $ubic_tienda,
                'dist_km' => ($latitud !== null && $longitud !== null) ? haversine_km($lat, $lng, floatval($latitud), floatval($longitud)) : null
            ];
            $tiendas[] = $row;
            if (intval($row['condicion']) === 1) { $conLic++; } else { $sinLic++; }
        }
    }
} else {
    // Sin get_result disponible
    $stmt->store_result();
    $stmt->bind_result($condicion, $idtienda, $nombres_per, $nombre_comercial, $latitud, $longitud, $ubic_tienda);
    while ($stmt->fetch()) {
        $row = [
            'condicion' => $condicion ?? 0,
            'idtienda' => $idtienda,
            'nombres_per' => $nombres_per,
            'nombre_comercial' => $nombre_comercial,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'ubic_tienda' => $ubic_tienda,
            'dist_km' => ($latitud !== null && $longitud !== null) ? haversine_km($lat, $lng, floatval($latitud), floatval($longitud)) : null
        ];
        $tiendas[] = $row;
        if (intval($row['condicion']) === 1) { $conLic++; } else { $sinLic++; }
    }
}

$total = $conLic + $sinLic;
$porcPos = $total > 0 ? round(($conLic / $total) * 100, 2) : 0;
$porcNeg = $total > 0 ? round(($sinLic / $total) * 100, 2) : 0;

// Lista de coordenadas estructurada basada en el conjunto filtrado
$coords = [];
foreach ($tiendas as $ti) {
    $latT = isset($ti['latitud']) ? floatval($ti['latitud']) : null;
    $lngT = isset($ti['longitud']) ? floatval($ti['longitud']) : null;
    if ($latT !== null && $lngT !== null) {
        $coords[] = [
            'idtienda' => isset($ti['idtienda']) ? intval($ti['idtienda']) : null,
            'latitud' => $latT,
            'longitud' => $lngT,
            'dist_km' => isset($ti['dist_km']) ? floatval($ti['dist_km']) : null,
        ];
    }
}

$response = [
    'center' => [ 'lat' => $lat, 'lng' => $lng ],
    'radius_m' => $radius,
    'total' => $total,
    'con_licencia' => $conLic,
    'sin_licencia' => $sinLic,
    'porcentaje_positivo' => $porcPos,
    'porcentaje_negativo' => $porcNeg,
    'tiendas' => $tiendas,
    'coords' => $coords
];

echo json_encode($response);

$stmt->close();
$conn->close();
?>