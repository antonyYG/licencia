<?php
// Proxy local para consultas Nominatim, limitado a Chilca
// Evita CORS y añade encabezados requeridos por Nominatim

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Parámetro q requerido']);
  exit;
}

// Bounding box de Chilca (aprox)
$bbox = [
  'minLat' => -12.13,
  'maxLat' => -12.04,
  'minLng' => -75.25,
  'maxLng' => -75.15,
];

// Construir URL Nominatim
$base = 'https://nominatim.openstreetmap.org/search';
$params = http_build_query([
  'format' => 'json',
  'limit' => 6,
  'bounded' => 1,
  'viewbox' => $bbox['minLng'] . ',' . $bbox['maxLat'] . ',' . $bbox['maxLng'] . ',' . $bbox['minLat'],
  'q' => $q . ' Chilca, Huancayo, Junín, Perú',
]);
$url = $base . '?' . $params;

// Cache simple en disco (TTL 5 minutos)
$ttl = 300; // segundos
$key = 'nom_' . hash('sha256', $url);
$cacheFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $key . '.json';
if (is_file($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
  readfile($cacheFile);
  exit;
}

// Petición con cURL incluyendo User-Agent adecuado
$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_TIMEOUT => 10,
  CURLOPT_HTTPHEADER => [
    'Accept: application/json',
    'Accept-Language: es',
    'User-Agent: Licencia3-Chilca-Stats/1.0 (http://localhost/licencia3/)'
  ],
]);
$res = curl_exec($ch);
$err = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($err || $code >= 400) {
  http_response_code(502);
  echo json_encode(['error' => 'Error consultando Nominatim', 'status' => $code]);
  exit;
}

// Guardar cache y devolver
@file_put_contents($cacheFile, $res);
echo $res;
?>