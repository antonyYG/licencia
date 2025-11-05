<?php
// Función para generar la URL base del proyecto
function getBaseUrlPath($projectFolderName) {
    // La variable SCRIPT_NAME devuelve algo como /licencia3/view/mapamiembros.php
    $script_name = $_SERVER['SCRIPT_NAME'];
    
    // Buscamos la primera aparición de la carpeta del proyecto en la ruta
    $pos = strpos($script_name, $projectFolderName);
    
    if ($pos !== false) {
        // Obtenemos la ruta base (ej: /licencia3/)
        return substr($script_name, 0, $pos) . $projectFolderName . '/';
    }
    // Si falla, volvemos a la ruta absoluta simple
    return '/' . $projectFolderName . '/';
}

// DEFINIMOS LA RUTA BASE DEL PROYECTO.
// ¡ADVERTENCIA! DEBES VERIFICAR QUE 'licencia3' SEA EL NOMBRE EXACTO DE TU CARPETA EN htdocs.
$PROJECT_FOLDER_NAME = 'licencia3';
$BASE_URL_RESOURCES = getBaseUrlPath($PROJECT_FOLDER_NAME);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    // ARCHIVO HEAD
    require_once "head.php";
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

    <script src="https://unpkg.com/esri-leaflet"></script>
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css" />
    <script src="https://unpkg.com/esri-leaflet-geocoder"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.awesome-markers/dist/leaflet.awesome-markers.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet.awesome-markers/dist/leaflet.awesome-markers.min.js"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">

    <link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css"> 

    <script src="<?php echo $licencia3; ?>data/line.js"></script>
    <script src="<?php echo $licencia3; ?>data/point.js"></script>
    <script src="<?php echo $licencia3; ?>data/polygon.js"></script>
    <script src="<?php echo $licencia3; ?>data/nepaldata.js"></script>
    <script src="<?php echo $licencia3; ?>data/usstates.js"></script>

    <style>
        #map {
            height: 1000px;
            width: 100%;
        }
        /* ... (Estilos omitidos por brevedad) ... */
    </style>

</head>

<body>
    <?php
    // MENU
    require_once "menu.php";
    ?>

    <section class="full-box dashboard-contentPage">
        <nav class="full-box dashboard-Navbar">
            <ul class="full-box list-unstyled text-right">
                <li class="pull-left">
                    <a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
                </li>
            </ul>
        </nav>
        <div class="full-box text-center" style="padding: 30px 10px;">
            <div id="map" style="height: 1000px;"></div>
        </div>
    </section>

    <script>
    // **INICIO DE LA LÓGICA DEL MAPA**

    // Crea un nuevo mapa centrado en la ubicación y con zoom
    var mymap = L.map('map').setView([-12.062277, -75.287693], 15);

    // Añade una capa de mapa base de Google Streets
    var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });
    googleStreets.addTo(mymap);

    var tiendaLayer = L.layerGroup().addTo(mymap);

    <?php
    // INICIO DE CONEXIÓN Y CONSULTA PHP
    // Usando credenciales de desarrollo local (root/sin contraseña)
    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "licencia3";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
    $sql = "SELECT t.nombres_per, t.ubic_tienda, t.latitud, t.longitud, t.celular, l.nombre_comercial, l.vigencia_lic, l.fecha_ingreso, l.fecha_expedicion, l.tipo_lic, l.condicion, l.exp_num, l.EstadoITSE FROM tienda t JOIN licencia l ON t.idtienda = l.idtienda";
    $result = $conn->query($sql);
    $tiendas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tiendas[] = $row;
        }
    }
    $conn->close();
    ?>

    // Iteración para crear marcadores en JavaScript
    <?php foreach ($tiendas as $tienda): ?>
        var nombrePersona = "<?php echo htmlspecialchars($tienda['nombres_per']); ?>";
        var latitud = "<?php echo htmlspecialchars($tienda['latitud']); ?>";
        var longitud = "<?php echo htmlspecialchars($tienda['longitud']); ?>";
        var nombreComercial = "<?php echo htmlspecialchars($tienda['nombre_comercial']); ?>";
        var ubicacion = "<?php echo htmlspecialchars($tienda['ubic_tienda']); ?>";
        var vigenciaLic = "<?php echo htmlspecialchars($tienda['vigencia_lic']); ?>";
        var fechaIngreso = "<?php echo htmlspecialchars($tienda['fecha_ingreso']); ?>";
        var tipoAnuncio = "<?php echo htmlspecialchars($tienda['fecha_expedicion']); ?>";
        var tipoLic = "<?php echo htmlspecialchars($tienda['tipo_lic']); ?>";
        var condicion = "<?php echo htmlspecialchars($tienda['condicion']); ?>";
        var expNum = "<?php echo htmlspecialchars($tienda['exp_num']); ?>";
        var estadoitse = "<?php echo htmlspecialchars($tienda['EstadoITSE']); ?>";
        var celular = "<?php echo htmlspecialchars($tienda['celular']); ?>";

        var lat = parseFloat(latitud);
        var lon = parseFloat(longitud);

        if (!isNaN(lat) && !isNaN(lon) && lat !== 0 && lon !== 0) {

            var popupContent = "<div style='font-size: 16px;'>"
                + "<b>PROPIETARIO DEL PREDIO:</b><br>" + nombrePersona + "</span><br><br>"
                + "<b>Nombre Comercial:</b><br>" + nombreComercial + "</span><br><br>"
                + "<b>Dirección:</b><br>" + ubicacion + "</span><br><br>"
                + "<b>Celular:</b><br>" + celular + "</span><br><br>"
                + "<b>Vigencia de Licencia:</b><br>" + vigenciaLic + "</span><br><br>"
                + "<b>Fecha de Expedición:</b><br>" + fechaIngreso + "</span><br><br>"
                + "<b>Tipo de Anuncio:</b><br>" + tipoAnuncio + "</span><br><br>"
                + "<b>Tipo de Licencia:</b><br>";

            if (tipoLic == "1") {
                popupContent += "<span style='color: orange; background-color: #FFDAB9; padding: 4px 8px; border-radius: 4px;'>Indeterminado</span>";
            } else if (tipoLic == "2") {
                popupContent += "<span style='color: red; background-color: #F08080; padding: 4px 8px; border-radius: 4px;'>Temporal</span>";
            }

            popupContent += "<br><br><b>Estado Licencia:</b><br>";
            var markerColor1 = 'blue';

            if (condicion == "1") {
                popupContent += "<span style='color: green; background-color: #90EE90; padding: 4px 8px; border-radius: 4px;'>ACTIVO</span>";
                markerColor1 = 'green';
            } else if (condicion == "0") {
                popupContent += "<span style='color: red; background-color: #F08080; padding: 4px 8px; border-radius: 4px;'>INACTIVO</span>";
                markerColor1 = 'red';
            }

            popupContent += "<br><br><b>Estado ITSE:</b><br>";

            if (estadoitse == "1") {
                popupContent += "<span style='color: green; background-color: #90EE90; padding: 4px 8px; border-radius: 4px;'>ACTIVO</span>";
            } else if (estadoitse == "0") {
                popupContent += "<span style='color: red; background-color: #F08080; padding: 4px 8px; border-radius: 4px;'>INACTIVO</span>";
            }

            // Botón de imprimir con RUTA ABSOLUTA GENERADA POR PHP
            popupContent += "</div><br><br><button type='button' class='btn btn-light btn-raised btn-sm' onclick=\"imprimir('<?php echo $BASE_URL_RESOURCES; ?>public/pdf/tramitelicencia.php?idtramite=" + expNum + "','1200', '500')\"><i class='zmdi zmdi-print'></i></button>";

            var tiendaIcon = L.AwesomeMarkers.icon({
                icon: 'shopping-cart',
                prefix: 'fa',
                markerColor: markerColor1
            });

            var tiendaMarker = L.marker([lat, lon], {
                icon: tiendaIcon
            }).addTo(tiendaLayer);

            tiendaMarker.bindPopup(popupContent);
        }
    <?php endforeach; ?>
    </script>


    <?php require_once "script.php"; ?>
    <script type="text/javascript" src="<?php echo $BASE_URL_RESOURCES; ?>public/toastr/js/toastr.min.js"></script>
    <script type="text/javascript" src="<?php echo $BASE_URL_RESOURCES; ?>script/registrotramite.js"></script>
</body>

</html>