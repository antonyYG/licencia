<?php
// Función para generar la URL base del proyecto
function getBaseUrlPath($projectFolderName) {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $pos = strpos($script_name, $projectFolderName);
    if ($pos !== false) {
        return substr($script_name, 0, $pos) . $projectFolderName . '/';
    }
    return '/' . $projectFolderName . '/';
}

$PROJECT_FOLDER_NAME = 'licencia3';
$BASE_URL_RESOURCES = getBaseUrlPath($PROJECT_FOLDER_NAME);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once "head.php"; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script src="https://unpkg.com/esri-leaflet"></script>
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css"/>
    <script src="https://unpkg.com/esri-leaflet-geocoder"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.awesome-markers/dist/leaflet.awesome-markers.css"/>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.awesome-markers/dist/leaflet.awesome-markers.min.js"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">

    <style>
        #map {
            height: 1000px;
            width: 100%;
        }
    </style>
</head>

<body>
<?php require_once "menu.php"; ?>

<section class="full-box dashboard-contentPage">
    <nav class="full-box dashboard-Navbar">
        <ul class="full-box list-unstyled text-right">
            <li class="pull-left">
                <a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
            </li>
        </ul>
    </nav>

    <div class="full-box text-center" style="padding: 30px 10px;">
        <div id="map"></div>
    </div>
</section>

<?php
// === Conexión y consulta SQL ===
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "licencia3";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Traemos tiendas con o sin licencia
$sql = "
    SELECT 
        t.idtienda,
        t.nombres_per,
        t.ubic_tienda,
        t.latitud,
        t.longitud,
        t.celular,
        l.exp_num,
        l.nombre_comercial,
        l.vigencia_lic,
        l.condicion,
        l.EstadoITSE
    FROM tienda t
    LEFT JOIN licencia l ON t.idtienda = l.idtienda
";
$result = $conn->query($sql);

$tiendas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tiendas[] = $row;
    }
}
$conn->close();
?>

<script>
var mymap = L.map('map').setView([-12.062277, -75.287693], 15);
var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});
googleStreets.addTo(mymap);

var tiendaLayer = L.layerGroup().addTo(mymap);

// Los datos PHP convertidos a JSON seguro
var tiendas = <?php echo json_encode($tiendas, JSON_UNESCAPED_UNICODE); ?>;

// Recorremos todas las tiendas
tiendas.forEach(function(tienda) {
    var lat = parseFloat(tienda.latitud);
    var lon = parseFloat(tienda.longitud);
    if (isNaN(lat) || isNaN(lon) || lat === 0 || lon === 0) return;

    var tieneLicencia = tienda.exp_num && tienda.exp_num.trim() !== "";
    var color = tieneLicencia ? 'green' : 'red';

    var popupContent = `
        <div style="font-size: 14px;">
            <b>Propietario:</b> ${tienda.nombres_per}<br>
            <b>Nombre Comercial:</b> ${tienda.nombre_comercial || '—'}<br>
            <b>Dirección:</b> ${tienda.ubic_tienda}<br>
            <b>Celular:</b> ${tienda.celular || '—'}<br>
            <b>Estado Licencia:</b> 
                <span style="color:${color};font-weight:bold;">
                    ${tieneLicencia ? 'Con Licencia' : 'Sin Licencia'}
                </span>
        </div>
    `;

    var icono = L.AwesomeMarkers.icon({
        icon: 'store',
        prefix: 'fa',
        markerColor: color
    });

    L.marker([lat, lon], { icon: icono })
        .addTo(tiendaLayer)
        .bindPopup(popupContent);
});
</script>

<?php require_once "script.php"; ?>
<script type="text/javascript" src="<?php echo $BASE_URL_RESOURCES; ?>public/toastr/js/toastr.min.js"></script>
</body>
</html>
