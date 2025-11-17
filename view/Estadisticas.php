<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once 'head.php'; ?>
    <link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- use version 0.19.3 -->
    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
    

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <!-- Leaflet (para mapa y radio de 100m) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Leaflet Geometry Util para cálculos de área -->
    <script src="https://unpkg.com/leaflet-geometryutil@0.10.0/src/leaflet.geometryutil.js"></script>
    <style>
      /* Oculta gráficos antiguos, mostrando solo el nuevo pastel */
      .chart { display: none; }
      #estad-pie.chart { display: block; }
      /* Sugerencias clicables en móviles */
      .suggestion-item { padding: 6px 8px; border: 1px solid #eee; border-radius: 4px; margin-bottom: 6px; cursor: pointer; }
      .suggestion-item:hover { background: #f7f7f7; }
      /* Estilos para el control de área */
      .leaflet-control-area-selection {
        background-color: white;
        border: 2px solid #ccc;
        border-radius: 5px;
        padding: 5px 8px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
      }
      .leaflet-control-area-selection:hover {
        background-color: #f0f0f0;
        border-color: #999;
      }
      .leaflet-control-area-selection.active {
        background-color: #ff4444;
        color: white;
        border-color: #cc0000;
      }
      /* Cursor personalizado para modo de selección */
      .area-selection-mode {
        cursor: crosshair !important;
      }
    </style>
    <style>
        /* Estilos para los gráficos */
        .chart {
            display: block;
            margin: 0 auto;
            /* Centrar horizontalmente el gráfico */
            max-width: 100%;
            /* Ajustar el ancho máximo según tus necesidades */
            height: auto;
        }

        /* Estilos para la sección de los gráficos */
        .dashboard-contentPage .card-body.custom-card-body {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            height: 400px;

        }

        /* Estilos para las tarjetas de los gráficos */
        .dashboard-contentPage .card {
            margin-bottom: 20px;
        }

        /* Estilos para el título del gráfico */
        h1,
        h3 {
            text-align: center;
        }

        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 3px solid green;
        }

        .card-header {
            flex: 0;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chart-container {
            flex: 1;
        }

        .table-container {
            flex: 1;
            overflow-y: auto;
        }

        .chart-container {
            position: relative;
            height: 300px;
            /* Altura inicial del gráfico */
            width: 100%;
        }

        @media (max-width: 767px) {
            .chart-container {
                height: 400px;
                /* Ajusta la altura del gráfico en pantallas más pequeñas */
            }
        }

        @media (max-width: 575px) {
            .chart-container {
                height: 500px;
                /* Ajusta la altura del gráfico en pantallas aún más pequeñas */
            }
        }
        #exportButton {
        background-color: #00543A;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
    }
    </style>
</head>

<body>
   
    <?php require_once 'menu.php'; ?>
    

    <section class="full-box dashboard-contentPage">
        <!-- NavBar -->
        <nav class="full-box dashboard-Navbar">
            <ul class="full-box list-unstyled text-right">
                <li class="pull-left">
                    <a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
                </li>
            </ul>
        </nav>
        <!-- Content page -->
        <div class="container-fluid">
        <div class="container-fluid">
    <div class="page-header text-center">
        <h1 class="text-titles">Estadísticas gráficas por zonas</h1>
        <button id="exportButton">Exportar a Excel</button>
    </div>
</div>

            <div class="row">
                <!-- Bloque interactivo: Buscador, Mapa y Gráfico -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Buscador de zonas en Chilca</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="estad-search">Buscar calles, lugares o direcciones</label>
                                <input type="text" id="estad-search" class="form-control" placeholder="Ej.: Calle Miguel Grau, Parque, Dirección exacta" />
                                <ul id="estad-suggestions" style="list-style:none; padding-left:0; margin-top:8px;"></ul>
                                <small id="estad-error" style="color:#b00020; display:none;">No se encontraron resultados en Chilca.</small>
                            </div>
                            <div id="estad-map" style="width: 100%; height: 380px; border: 1px solid #ddd; border-radius: 4px;"></div>
                            <div class="form-group" style="margin-top: 12px;">
                                <label for="estad-radius">Radio de búsqueda</label>
                                <input type="range" id="estad-radius" class="form-control" min="50" max="1000" step="50" value="100" />
                                <small id="estad-radius-value" style="display:block; margin-top:6px;">100 m</small>
                            </div>
                            <div id="estad-coords" style="margin-top: 12px;">
                                <h4>Coordenadas válidas dentro del rango</h4>
                                <div id="estad-coords-list" style="font-family: monospace; font-size: 13px;"></div>
                            </div>
                            <div style="margin-top: 12px;">
                                <button id="estad-export" class="btn btn-primary btn-sm">Exportar Excel</button>
                                <small style="display:block; color:#666; margin-top:4px;">Archivo Excel con formato profesional.</small>
                            </div>
                            <div style="margin-top: 12px;">
                                <canvas id="estad-pie" class="chart"></canvas>
                            </div>
                            <div id="estad-summary"></div>
                            <div id="estad-stores" style="margin-top:10px;"></div>
                        </div>
                    </div>
                </div>
                <!-- Se eliminaron los gráficos y tablas por zonas legacy -->
                
            </div>
        </div>

    </section>
    
    <script src="script/estadisticas.js"></script>
    
    <?php require_once 'script.php'; ?>

    <script type="text/javascript" src="script/tienda.js"></script>
    <script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
    <script type="text/javascript" src="script/validacion.js"></script>
    <!-- script/graficos.js eliminado para evitar mostrar zonas por defecto de Chupaca -->
    <script type="text/javascript" src="script/estadisticas.js"></script>

</body>

</html>