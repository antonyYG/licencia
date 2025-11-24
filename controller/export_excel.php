<?php
// Exportación a Excel de estadísticas por zona usando PhpSpreadsheet cuando está disponible
ini_set('display_errors', 0);

// Entrada
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;
$radius = isset($_GET['radius']) ? floatval($_GET['radius']) : 100.0; // metros

if ($lat === null || $lng === null) {
    http_response_code(400);
    header('Content-Type: application/json');
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
    header('Content-Type: application/json');
    echo json_encode([ 'error' => 'Fallo de conexión a BD' ]);
    exit;
}

// Radio en km y pequeña tolerancia
$radiusKm = $radius / 1000.0;
$epsilonKm = 0.005;
$radiusKmEff = $radiusKm + $epsilonKm;

error_log("=== PARÁMETROS DE BÚSQUEDA ===");
error_log("Centro: lat=$lat, lng=$lng");
error_log("Radio: $radius metros (${radiusKm} km)");
error_log("Radio efectivo: ${radiusKmEff} km (con tolerancia)");

function haversine_km($lat1, $lon1, $lat2, $lon2){
    $R = 6371.0;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(min(1, sqrt($a)));
    return $R * $c;
}

$sql = "SELECT IFNULL(l.condicion, 0) AS condicion,
               t.idtienda, t.nombres_per, l.nombre_comercial, t.latitud, t.longitud, t.ubic_tienda,
               (2 * 6371 * ASIN(SQRT(
                   POW(SIN((RADIANS(t.latitud) - RADIANS(?)) / 2), 2) +
                   COS(RADIANS(?)) * COS(RADIANS(t.latitud)) *
                   POW(SIN((RADIANS(t.longitud) - RADIANS(?)) / 2), 2)
               ))) AS distancia_km
        FROM tienda t
        LEFT JOIN licencia l ON l.idtienda = t.idtienda
        WHERE t.latitud IS NOT NULL AND t.longitud IS NOT NULL
          AND t.latitud <> 0 AND t.longitud <> 0
          AND (2 * 6371 * ASIN(SQRT(
               POW(SIN((RADIANS(t.latitud) - RADIANS(?)) / 2), 2) +
               COS(RADIANS(?)) * COS(RADIANS(t.latitud)) *
               POW(SIN((RADIANS(t.longitud) - RADIANS(?)) / 2), 2)
          ))) <= ?
        ORDER BY distancia_km ASC";

error_log("=== CONSULTA SQL ===");
error_log("SQL: " . $sql);
error_log("Parámetros: lat=$lat, lng=$lng, radiusKmEff=$radiusKmEff");

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([ 'error' => 'Error preparando consulta' ]);
    $conn->close();
    exit;
}

$stmt->bind_param('ddddddd', $lat, $lat, $lng, $lat, $lat, $lng, $radiusKmEff);
if (!$stmt->execute()) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([ 'error' => 'Error ejecutando consulta' ]);
    $stmt->close();
    $conn->close();
    exit;
}

$tiendas = [];
$conLic = 0;
$sinLic = 0;

error_log("Ejecutando consulta SQL...");
if (method_exists($stmt, 'get_result')) {
    $result = $stmt->get_result();
    if ($result) {
        error_log("Usando get_result");
        while ($row = $result->fetch_assoc()) {
            error_log("Tienda encontrada: " . json_encode($row));
            $latT = isset($row['latitud']) ? floatval($row['latitud']) : null;
            $lngT = isset($row['longitud']) ? floatval($row['longitud']) : null;
            $row['dist_km'] = ($latT !== null && $lngT !== null) ? haversine_km($lat, $lng, $latT, $lngT) : null;
            $tiendas[] = $row;
            if (intval($row['condicion']) === 1) { $conLic++; } else { $sinLic++; }
        }
    } else {
        error_log("Usando bind_result");
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
            error_log("Tienda encontrada: " . json_encode($row));
            $tiendas[] = $row;
            if (intval($row['condicion']) === 1) { $conLic++; } else { $sinLic++; }
        }
    }
} else {
    error_log("Usando bind_result alternativo");
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
        error_log("Tienda encontrada: " . json_encode($row));
        $tiendas[] = $row;
        if (intval($row['condicion']) === 1) { $conLic++; } else { $sinLic++; }
    }
}
error_log("Total tiendas recolectadas: " . count($tiendas));

$total = $conLic + $sinLic;
$pct = $total > 0 ? round(($conLic / $total) * 100, 2) : 0;

error_log("=== RESUMEN ===");
error_log("Total: $total, Con licencia: $conLic, Sin licencia: $sinLic, Porcentaje: $pct%");

// Coordenadas derivadas
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

// Intentar usar PhpSpreadsheet
$hasPhpSpreadsheet = false;
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    if (class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) { $hasPhpSpreadsheet = true; }
}

if ($hasPhpSpreadsheet) {
    // Generar XLSX
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Estadísticas');

    // Logo
    $logoPath = __DIR__ . '/../view/1.png';
    if (file_exists($logoPath)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath($logoPath);
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
    }

    $row = 5; // Comenzar después del logo
    $sheet->setCellValue('A'.$row, 'REPORTE DE TIENDAS DE CHILCA');
    $sheet->mergeCells('A'.$row.':E'.$row);
    $sheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $row += 2;
    // Saltar directamente a la tabla principal (omitimos información del rango en el Excel)
    // Dejar una fila en blanco
    $row++;

    // Encabezados de tiendas
    $headers = ['NOMBRE COMPLETO DEL PROPIETARIO', 'NOMBRE DE LA TIENDA', 'LUGAR UBICADO', 'LICENCIA SANITARIA', 'LICENCIA DE FUNCIONAMIENTO'];
    $sheet->fromArray($headers, NULL, 'A'.$row);
    
    // Estilo de encabezados
    $headerRange = 'A'.$row.':E'.$row;
    $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(11);
    $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
    $sheet->getStyle($headerRange)->getFont()->getColor()->setRGB('FFFFFF');
    $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
    $startDataRow = $row;
    $row++;

    // Datos de tiendas - DEBUG
    error_log("Total tiendas encontradas: " . count($tiendas));
    if (count($tiendas) > 0) {
        $storeCount = 0;
        foreach ($tiendas as $index => $t) {
            error_log("Procesando tienda $index: " . json_encode($t));
            $propietario = isset($t['nombres_per']) ? $t['nombres_per'] : '';
            $tienda = isset($t['nombre_comercial']) ? $t['nombre_comercial'] : '';
            $ubicacion = isset($t['ubic_tienda']) ? $t['ubic_tienda'] : '';
            
            // Verificar tipos de licencia
            $condicion = isset($t['condicion']) ? intval($t['condicion']) : 0;
            $licenciaSanitaria = ($condicion === 1) ? 'SI' : 'NO';
            $licenciaFuncionamiento = ($condicion === 1) ? 'SI' : 'NO';
            
            error_log("Fila $row: $propietario | $tienda | $ubicacion | $licenciaSanitaria | $licenciaFuncionamiento");
            
            $sheet->setCellValue('A'.$row, $propietario);
            $sheet->setCellValue('B'.$row, $tienda);
            $sheet->setCellValue('C'.$row, $ubicacion);
            $sheet->setCellValue('D'.$row, $licenciaSanitaria);
            $sheet->setCellValue('E'.$row, $licenciaFuncionamiento);
            
            $storeCount++;
            $row++;
        }
        error_log("Total tiendas procesadas: $storeCount");
    } else {
        $sheet->setCellValue('A'.$row, 'No hay tiendas dentro del radio seleccionado');
        $sheet->mergeCells('A'.$row.':E'.$row);
        $row++;
    }

    // Aplicar bordes y auto-ajustar columnas
    $endRow = $row - 1;
    if (count($tiendas) > 0) {
        $dataRange = 'A'.$startDataRow.':E'.$endRow;
        
        // Bordes
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $sheet->getStyle($dataRange)->applyFromArray($styleArray);
        
        // Auto-ajustar columnas
        foreach (range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    // Salida
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $fname = 'estadisticas_'.date('Ymd_His').'.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.$fname.'"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    $stmt->close();
    $conn->close();
    exit;
}

// Si PhpSpreadsheet no está disponible, mostrar error
http_response_code(500);
header('Content-Type: application/json');
echo json_encode(['error' => 'PhpSpreadsheet no está instalado. Por favor ejecute: composer require phpoffice/phpspreadsheet']);
$stmt->close();
$conn->close();
exit;
?>