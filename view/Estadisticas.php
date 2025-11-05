<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once 'head.php'; ?>
    <link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- use version 0.19.3 -->
    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
    <script src="pdfmake/build/pdfmake.min.js"></script>
    <script src="pdfmake/build/pdfmake.js"></script>
    <script src="pdfmake/build/pdfmake.js.map"></script>
    <script src="pdfmake/build/pdfmake.min.js.map"></script>
    <script src="pdfmake/build/vfs_fonts.js"></script>
    <script src="pdfmake/build/standard-fonts/Courier.js"></script>
    <script src="pdfmake/build/standard-fonts/Helvetica.js"></script>
    <script src="pdfmake/build/standard-fonts/Symbol.js"></script>
    <script src="pdfmake/build/standard-fonts/Times.js"></script>
    <script src="pdfmake/build/standard-fonts/ZapfDingbats.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
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
        <h1 class="text-titles">Estadisticas graficas por zonas</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por zona...">
            </div>
        </div>
        <button id="exportButton">Exportar a Excel</button>
    </div>
</div>

            <div class="row">
                <?php
                function obtenerConexion($host, $dbname, $usuario, $contraseña)
                {
                    // Crear la conexión a la base de datos
                    $dsn = "mysql:host=$host;dbname=$dbname";
                    $opciones = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    ];

                    try {
                        $conexion = new PDO($dsn, $usuario, $contraseña, $opciones);
                    } catch (PDOException $e) {
                        echo 'Error de conexión: ' . $e->getMessage();
                        exit;
                    }

                    return $conexion;
                }

                function obtenerDatosZona($zona, $conexion)
                {
                    // Obtener el total de licencias
                    $sql_total_licencias = "SELECT COUNT(*) AS total_licencias FROM licencia 
                                                                    INNER JOIN tienda ON licencia.idtienda = tienda.idtienda 
                                                                    WHERE tienda.id_zona = $zona";
                    $resultado_total_licencias = $conexion->query($sql_total_licencias);
                    $total_licencias = $resultado_total_licencias->fetch(PDO::FETCH_ASSOC)['total_licencias'];

                    // Obtener los datos de la tabla licencia
                    $sql = "SELECT licencia.condicion, COUNT(*) AS total FROM licencia 
                                                    INNER JOIN tienda ON licencia.idtienda = tienda.idtienda 
                                                    WHERE tienda.id_zona = $zona
                                                    GROUP BY licencia.condicion";
                    $resultado = $conexion->query($sql);

                    // Procesar los datos
                    $cuenta_con_licencia = 0;
                    $no_cuenta_con_licencia = 0;
                    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                        $condicion = $fila['condicion'];
                        $total = $fila['total'];

                        if ($condicion == 1) {
                            $cuenta_con_licencia = $total;
                        } else {
                            $no_cuenta_con_licencia = $total;
                        }
                    }

                    return [
                        'total_licencias' => $total_licencias,
                        'cuenta_con_licencia' => $cuenta_con_licencia,
                        'no_cuenta_con_licencia' => $no_cuenta_con_licencia,
                    ];
                }

                // Crear la conexión a la base de datos
                $conexion = obtenerConexion('localhost', 'licencia3', 'root', '');
                // Número total de zonas
                $total_zonas = 24;

                // Arreglos para almacenar los datos de cada zona
                $zonas = array();
                $total_licencias = array();
                $cuenta_con_licencia = array();
                $no_cuenta_con_licencia = array();

                // Obtener datos para cada zona
                for ($i = 1; $i <= $total_zonas; $i++) {
                    $zona = obtenerDatosZona($i, $conexion);
                    $zonas[$i] = $zona;
                    $total_licencias[$i] = $zona['total_licencias'];
                    $cuenta_con_licencia[$i] = $zona['cuenta_con_licencia'];
                    $no_cuenta_con_licencia[$i] = $zona['no_cuenta_con_licencia'];
                }

                $conexion = null;
                ?>
                <!--GRAFICO Y TABLA 1-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencia 24 de Junio</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart1" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[1]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[1]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[1]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!--GRAFICO Y TABLA 2-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Andrea Arauco</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart2" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[2]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[2]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[2]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!--GRAFICO Y TABLA 3-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias BARTOLOME</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart3" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[3]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[3]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[3]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 4-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Bruno Terreros</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart4" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[4]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[4]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[4]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 5-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Calle los Angeles</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart5" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[5]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[5]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[5]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 6-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Castilla</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart6" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[6]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[6]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[6]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 7-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Circunvalación</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart7" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[7]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[7]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[7]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 8-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Demetrio Maravi</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart8" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[8]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[8]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[8]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 9-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Dorregaray</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart9" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[9]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[9]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[9]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 10-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Echenique</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart10" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[10]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[10]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[10]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 11-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Eternidad</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart11" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[11]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[11]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[11]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 12-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Gamarra</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart12" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[12]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[12]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[12]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 13-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Heroina Rosa Perez</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart13" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[13]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[13]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[13]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 14-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Jr. Jose Antonio Sucre</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart14" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[14]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[14]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[14]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 15-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Jr. 19 de Abril</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart15" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[15]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[15]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[15]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 16-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Maria Miranda</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart16" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[16]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[16]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[16]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 17-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Maria Parado De Bellido</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart17" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[17]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[17]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[17]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 18-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Miguel Grau</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart18" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[18]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[18]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[18]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 19-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Pedro Aliaga</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart19" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[19]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[19]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[19]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 20-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Raymondi</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart20" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[20]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[20]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[20]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 21-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Santos Bravo</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart21" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[21]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[21]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[21]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 22-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias San Martin</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart22" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[22]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[22]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[22]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
                <!--GRAFICO Y TABLA 23-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Ubaldo</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart23" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[23]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[23]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[23]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                <!--GRAFICO Y TABLA 24-->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1>Resumen total de licencias Alfonso Mercadillo</h1>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart24" class="chart"></canvas>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped responsive">
                                <thead>
                                    <tr>
                                        <th>Cuenta con licencia</th>
                                        <td><?php echo $cuenta_con_licencia[24]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>No cuenta con licencia de funcionamiento</th>
                                        <td><?php echo $no_cuenta_con_licencia[24]; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de tiendas padronadas de establecimientos</th>
                                        <td><?php echo $total_licencias[24]; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!--//-->
                
            </div>
        </div>

    </section>
    
    <!--CODIGO JAVASCRIPT -->
<script>
    // Declarar la variable response fuera de la función xhr.onload
    var response;

    // Realizar una solicitud AJAX para obtener los datos del archivo graficos.php
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'graficos.php', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        response = JSON.parse(xhr.responseText);

        // Iterar sobre los gráficos
        for (var i = 1; i <= 24; i++) {
          var grafico = response['grafico' + i];

          var nombre_zona = grafico.nombre_zona;
          var labels = grafico.labels;
          var cuenta_con_licencia = grafico.cuenta_con_licencia;
          var no_cuenta_con_licencia = grafico.no_cuenta_con_licencia;
          var total_tiendas = grafico.total_tiendas;

          // Obtener el canvas del gráfico actual
          var ctx = document.getElementById('myChart' + i).getContext('2d');

          // Configurar los datos del gráfico actual
          var data = {
            labels: labels.concat(['Total de tiendas']),
            datasets: [{
              label: 'Resumen total de licencia',
              data: [cuenta_con_licencia, no_cuenta_con_licencia, total_tiendas],
              backgroundColor: [
                'rgba(0, 255, 0, 0.8)',
                'rgba(255, 0, 0, 0.8)',
                'rgba(0, 0, 255, 0.8)',
              ],
              borderColor: [
                'rgba(0, 255, 0, 1)',
                'rgba(255, 0, 0, 1)',
                'rgba(0, 0, 255, 1)',
              ],
              borderWidth: 1
            }]
          };

          // Configurar opciones del gráfico actual
          var options = {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
                labels: {
                  font: {
                    size: 16,
                    weight: '700'
                  }
                }
              }
            },
            animation: {
              duration: 2000,
              easing: 'easeInOutQuart',
              animateRotate: false,
              animateScale: true
            }
          };

          // Crear el objeto Chart y dibujar el gráfico actual
          var myChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: options
          });
        }
      }
    };
    xhr.send();
document.getElementById("searchInput").addEventListener("input", function() {
    var searchTerm = this.value.toLowerCase();
    var cards = document.querySelectorAll('.card');
    cards.forEach(function(card) {
        var headerText = card.querySelector('.card-header h1').textContent.toLowerCase();
        if (headerText.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

document.getElementById("exportButton").addEventListener("click", exportToExcel);

function exportToExcel() {
  var workbook = new ExcelJS.Workbook();
  var worksheet = workbook.addWorksheet('Estadísticas');

  var headerStyle = {
    font: { bold: true, size: 12 },
    fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: 'C6EFCE' } },
    border: { top: { style: 'thin' }, bottom: { style: 'thin' }, left: { style: 'thin' }, right: { style: 'thin' } },
    alignment: { horizontal: 'center' }
  };

  var dataStyle = {
    font: { size: 12 },
    border: { top: { style: 'thin' }, bottom: { style: 'thin' }, left: { style: 'thin' }, right: { style: 'thin' } },
    alignment: { horizontal: 'center' }
  };

  var rowIndex = 1;
  var colorIndex = 1; // Variable para alternar entre los colores

  for (var i = 1; i <= 24; i++) {
    var tablaDatos = response['tabla' + i];
    var grafico = response['grafico' + i];
    var nombre_zona = grafico.nombre_zona;
    var cuenta_con_licencia = grafico.cuenta_con_licencia;
    var no_cuenta_con_licencia = grafico.no_cuenta_con_licencia;
    var total_tiendas = grafico.total_tiendas;

    if (i > 1) {
      worksheet.addRow([]);
      rowIndex++;
    }

    // Agregar los encabezados de la tabla de tiendas
    var tiendasHeaderRow = worksheet.addRow(['Nombre', 'Apellido Paterno', 'Apellido Materno', 'Direccion', 'GIRO', 'Estado Licencia', 'Estado ITSE']);
    worksheet.getColumn(8).width = 5; // Ajustar el ancho de la columna de separación
    worksheet.getColumn(9).width = 1; // Ancho de la columna vacía
    tiendasHeaderRow.eachCell((cell) => {
      cell.fill = headerStyle.fill;
      cell.border = headerStyle.border;
      cell.font = headerStyle.font;
      cell.alignment = headerStyle.alignment;
    });

    tablaDatos.forEach((datos) => {
      var rowData = [
        datos.Nombre,
        datos['Apellido Paterno'],
        datos['Apellido Materno'],
        datos.Direccion,
        datos.GIRO,
        datos['Estado Licencia'],
        datos['Estado ITSE']
      ];
      worksheet.addRow(rowData);
    });

    // Agregar el nombre de la zona en dos celdas combinadas encima de las dos columnas
    worksheet.mergeCells(rowIndex, 10, rowIndex, 11);
    worksheet.getCell(rowIndex, 10).value = nombre_zona;
    worksheet.getCell(rowIndex, 10).fill = headerStyle.fill;
    worksheet.getCell(rowIndex, 10).border = headerStyle.border;
    worksheet.getCell(rowIndex, 10).font = headerStyle.font;
    worksheet.getCell(rowIndex, 10).alignment = headerStyle.alignment;

    // Agregar los valores de cuenta con licencia, no cuenta con licencia y total de tiendas en filas separadas
    worksheet.getCell(rowIndex + 1, 10).value = 'Cuenta con licencia';
    worksheet.getCell(rowIndex + 1, 11).value = cuenta_con_licencia;
    worksheet.getCell(rowIndex + 2, 10).value = 'No cuenta con licencia';
    worksheet.getCell(rowIndex + 2, 11).value = no_cuenta_con_licencia;
    worksheet.getCell(rowIndex + 3, 10).value = 'Total de tiendas';
    worksheet.getCell(rowIndex + 3, 11).value = total_tiendas;

    // Establecer estilos para las celdas combinadas y las celdas de cuenta con licencia, no cuenta con licencia y total de tiendas
    for (var j = 1; j <= 3; j++) {
      worksheet.getCell(rowIndex + j, 10).border = dataStyle.border;
      worksheet.getCell(rowIndex + j, 11).border = dataStyle.border;
      worksheet.getCell(rowIndex + j, 10).font = dataStyle.font;
      worksheet.getCell(rowIndex + j, 11).font = dataStyle.font;
      worksheet.getCell(rowIndex + j, 10).alignment = dataStyle.alignment;
      worksheet.getCell(rowIndex + j, 11).alignment = dataStyle.alignment;
    }

    rowIndex += Math.max(4, tablaDatos.length) + 1;

    // Aplicar color a las tablas alternadamente
    var color = colorIndex % 2 === 0 ? 'E7F4FF' : 'F5F5F5';
    colorIndex++;

    // Establecer los estilos de fuente, borde y alineación para todas las celdas de datos
    worksheet.columns.forEach((column) => {
      column.eachCell((cell) => {
        cell.font = dataStyle.font;
        cell.border = dataStyle.border;
        cell.alignment = dataStyle.alignment;
        column.width = Math.max(15, cell.toString().length + 6);
        if (cell.row >= rowIndex - tablaDatos.length && cell.row <= rowIndex) {
          cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: color } };
        }
      });
    });

    // Eliminar las líneas de borde adicionales
    worksheet.eachRow((row) => {
      row.getCell(8).border = { bottom: { style: 'none' } };
      row.getCell(9).border = { bottom: { style: 'none' } };
    });
  }

  workbook.xlsx.writeBuffer().then((buffer) => {
    var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    saveAs(blob, 'estadisticas.xlsx');
  });
}




</script>



    <?php require_once 'script.php'; ?>

    <script type="text/javascript" src="script/tienda.js"></script>
    <script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
    <script type="text/javascript" src="script/validacion.js"></script>
    <script type="text/javascript" src="script/graficos.js"></script>

</body>

</html>