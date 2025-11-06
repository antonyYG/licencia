<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once 'head.php'; ?>
    <link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            margin: 0 auto;
        }

        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 3px solid green;
            margin-bottom: 20px;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .table-container {
            flex: 1;
            overflow-y: auto;
        }

        .search-highlight {
            background-color: yellow;
            font-weight: bold;
        }

        .zone-hidden {
            display: none;
        }

        #exportButton {
            background-color: #00543A;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }

        .stats-card {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <?php require_once 'menu.php'; ?>

    <section class="full-box dashboard-contentPage">
        <nav class="full-box dashboard-Navbar">
            <ul class="full-box list-unstyled text-right">
                <li class="pull-left">
                    <a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid">
            <div class="page-header text-center">
                <h1 class="text-titles">Estadísticas gráficas por zonas</h1>
                <button id="exportButton" class="btn btn-success mb-3">Exportar a Excel</button>
            </div>

            <!-- BUSCADOR -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Buscador de Zonas</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" id="searchInput" class="form-control" 
                                           placeholder="Buscar zona por nombre... Ej: '24 de Junio', 'Andrea Arauco', etc.">
                                </div>
                                <div class="col-md-4">
                                    <button id="clearSearch" class="btn btn-secondary">Limpiar Búsqueda</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <small class="text-muted" id="searchResults">Mostrando todas las zonas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="zonesContainer">
                <?php
                // Configuración de la base de datos
                $host = 'localhost';
                $dbname = 'licencia3';
                $usuario = 'root';
                $contraseña = '';

                // Función para obtener conexión
                function obtenerConexion($host, $dbname, $usuario, $contraseña) {
                    try {
                        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $usuario, $contraseña);
                        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        return $conexion;
                    } catch (PDOException $e) {
                        die('Error de conexión: ' . $e->getMessage());
                    }
                }

                // Nombres de las zonas
                $nombres_zonas = [
                    1 => "24 de Junio",
                    2 => "Andrea Arauco", 
                    3 => "BARTOLOME",
                    4 => "Bruno Terreros",
                    5 => "Calle los Angeles",
                    6 => "Castilla",
                    7 => "Circunvalación",
                    8 => "Demetrio Maravi",
                    9 => "Dorregaray",
                    10 => "Echenique",
                    11 => "Eternidad",
                    12 => "Gamarra",
                    13 => "Heroina Rosa Perez",
                    14 => "Jr. Jose Antonio Sucre",
                    15 => "Jr. 19 de Abril",
                    16 => "Maria Miranda",
                    17 => "Maria Parado De Bellido", 
                    18 => "Miguel Grau",
                    19 => "Pedro Aliaga",
                    20 => "Raymondi",
                    21 => "Santos Bravo",
                    22 => "San Martin",
                    23 => "Ubaldo",
                    24 => "Alfonso Mercadillo"
                ];

                // Crear conexión
                $conexion = obtenerConexion($host, $dbname, $usuario, $contraseña);

                // Generar las tarjetas para cada zona
                for ($i = 1; $i <= 24; $i++) {
                    $nombre_zona = $nombres_zonas[$i];
                    
                    // Obtener datos de la zona
                    $sql = "SELECT 
                            SUM(CASE WHEN licencia.condicion = 1 THEN 1 ELSE 0 END) as cuenta_con_licencia,
                            SUM(CASE WHEN licencia.condicion = 0 THEN 1 ELSE 0 END) as no_cuenta_con_licencia,
                            COUNT(*) as total_tiendas
                            FROM licencia 
                            INNER JOIN tienda ON licencia.idtienda = tienda.idtienda 
                            WHERE tienda.id_zona = $i";
                    
                    $stmt = $conexion->query($sql);
                    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $cuenta_con_licencia = $datos['cuenta_con_licencia'] ?? 0;
                    $no_cuenta_con_licencia = $datos['no_cuenta_con_licencia'] ?? 0;
                    $total_tiendas = $datos['total_tiendas'] ?? 0;
                ?>
                <div class="col-md-6 zone-card" data-zone-name="<?php echo htmlspecialchars($nombre_zona); ?>">
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo $nombre_zona; ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myChart<?php echo $i; ?>" class="chart"></canvas>
                            </div>
                            <div class="table-container mt-3">
                                <div class="stats-card">
                                    <strong>Cuenta con licencia:</strong> <?php echo $cuenta_con_licencia; ?>
                                </div>
                                <div class="stats-card">
                                    <strong>No cuenta con licencia:</strong> <?php echo $no_cuenta_con_licencia; ?>
                                </div>
                                <div class="stats-card">
                                    <strong>Total de tiendas:</strong> <?php echo $total_tiendas; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php $conexion = null; ?>
            </div>
        </div>
    </section>

    <script>
        // Datos para los gráficos (extraídos del PHP)
        const zonasData = {
            <?php for ($i = 1; $i <= 24; $i++): ?>
            <?php 
            $sql = "SELECT 
                    SUM(CASE WHEN licencia.condicion = 1 THEN 1 ELSE 0 END) as cuenta_con_licencia,
                    SUM(CASE WHEN licencia.condicion = 0 THEN 1 ELSE 0 END) as no_cuenta_con_licencia,
                    COUNT(*) as total_tiendas
                    FROM licencia 
                    INNER JOIN tienda ON licencia.idtienda = tienda.idtienda 
                    WHERE tienda.id_zona = $i";
            
            $conexion_temp = obtenerConexion($host, $dbname, $usuario, $contraseña);
            $stmt = $conexion_temp->query($sql);
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $conexion_temp = null;
            ?>
            <?php echo $i; ?>: {
                cuenta_con_licencia: <?php echo $datos['cuenta_con_licencia'] ?? 0; ?>,
                no_cuenta_con_licencia: <?php echo $datos['no_cuenta_con_licencia'] ?? 0; ?>,
                total_tiendas: <?php echo $datos['total_tiendas'] ?? 0; ?>
            },
            <?php endfor; ?>
        };

        // Inicializar gráficos cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            inicializarGraficos();
            inicializarBuscador();
        });

        function inicializarGraficos() {
            for (let i = 1; i <= 24; i++) {
                const ctx = document.getElementById('myChart' + i).getContext('2d');
                const data = zonasData[i];
                
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Con Licencia', 'Sin Licencia'],
                        datasets: [{
                            data: [data.cuenta_con_licencia, data.no_cuenta_con_licencia],
                            backgroundColor: ['#28a745', '#dc3545'],
                            borderColor: ['#1e7e34', '#c82333'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: `Total: ${data.total_tiendas} tiendas`,
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                });
            }
        }

        function inicializarBuscador() {
            const searchInput = document.getElementById('searchInput');
            const clearSearch = document.getElementById('clearSearch');
            const searchResults = document.getElementById('searchResults');
            const zoneCards = document.querySelectorAll('.zone-card');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;

                zoneCards.forEach(card => {
                    const zoneName = card.getAttribute('data-zone-name').toLowerCase();
                    const header = card.querySelector('h3');
                    
                    if (searchTerm === '' || zoneName.includes(searchTerm)) {
                        card.classList.remove('zone-hidden');
                        visibleCount++;
                        
                        // Resaltar texto si hay búsqueda
                        if (searchTerm !== '') {
                            const regex = new RegExp(`(${searchTerm})`, 'gi');
                            header.innerHTML = header.textContent.replace(regex, '<span class="search-highlight">$1</span>');
                        }
                    } else {
                        card.classList.add('zone-hidden');
                    }
                });

                searchResults.textContent = searchTerm === '' 
                    ? 'Mostrando todas las zonas' 
                    : `Mostrando ${visibleCount} de ${zoneCards.length} zonas`;
            });

            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            });
        }

        // Función para exportar a Excel (simplificada)
        document.getElementById('exportButton').addEventListener('click', function() {
            // Aquí iría el código para exportar a Excel
            alert('Funcionalidad de exportación a Excel - Por implementar');
        });
    </script>

    <?php require_once 'script.php'; ?>
</body>
</html>