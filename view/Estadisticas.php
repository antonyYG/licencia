<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once 'head.php'; ?>
    <link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <h1 class="text-titles">Estadísticas gráficas por zonas de Chilca</h1>
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
                                       placeholder="Buscar zona por nombre... Ej: 'Vista Alegre', 'Atalaya', etc.">
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

            function obtenerConexion($host, $dbname, $usuario, $contraseña) {
                try {
                    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $usuario, $contraseña);
                    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $conexion;
                } catch (PDOException $e) {
                    die('Error de conexión: ' . $e->getMessage());
                }
            }

            // Zonas de Chilca
            $nombres_zonas = [
                1 => "Mirador Peñaloza",
                2 => "Nueva Argentina",
                3 => "Hualashuata Nueva Generación",
                4 => "Pichkana",
                5 => "Buenos Aires",
                6 => "Vista Alegre",
                7 => "Atalaya",
                8 => "Héroes de Azapampa",
                9 => "Bosques de Azapampa",
                10 => "Villa Retama",
                11 => "Peje",
                12 => "Los Jazmines"
            ];

            $conexion = obtenerConexion($host, $dbname, $usuario, $contraseña);

            // Generar tarjetas dinámicamente
            foreach ($nombres_zonas as $id_zona => $nombre_zona) {
                $sql = "SELECT 
                            SUM(CASE WHEN licencia.condicion = 1 THEN 1 ELSE 0 END) as cuenta_con_licencia,
                            SUM(CASE WHEN licencia.condicion = 0 OR licencia.condicion IS NULL THEN 1 ELSE 0 END) as no_cuenta_con_licencia,

                            COUNT(*) as total_tiendas
                        FROM tienda
                        LEFT JOIN licencia ON licencia.idtienda = tienda.idtienda
                        WHERE tienda.id_zona = $id_zona";
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
                            <canvas id="myChart<?php echo $id_zona; ?>" class="chart"></canvas>
                        </div>
                        <div class="table-container mt-3">
                            <div class="stats-card"><strong>Cuenta con licencia:</strong> <?php echo $cuenta_con_licencia; ?></div>
                            <div class="stats-card"><strong>No cuenta con licencia:</strong> <?php echo $no_cuenta_con_licencia; ?></div>
                            <div class="stats-card"><strong>Total de tiendas:</strong> <?php echo $total_tiendas; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } $conexion = null; ?>
        </div>
    </div>
</section>

<script>
    const zonasData = {
        <?php
        $conexion = obtenerConexion($host, $dbname, $usuario, $contraseña);
        foreach ($nombres_zonas as $id_zona => $nombre_zona) {
            $sql = "SELECT 
                        SUM(CASE WHEN licencia.condicion = 1 THEN 1 ELSE 0 END) as cuenta_con_licencia,
                        SUM(CASE WHEN licencia.condicion = 0 OR licencia.condicion IS NULL THEN 1 ELSE 0 END) as no_cuenta_con_licencia,

                        COUNT(*) as total_tiendas
                    FROM tienda
                    LEFT JOIN licencia ON licencia.idtienda = tienda.idtienda
                    WHERE tienda.id_zona = $id_zona";
            $stmt = $conexion->query($sql);
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "$id_zona: {
                cuenta_con_licencia: " . ($datos['cuenta_con_licencia'] ?? 0) . ",
                no_cuenta_con_licencia: " . ($datos['no_cuenta_con_licencia'] ?? 0) . ",
                total_tiendas: " . ($datos['total_tiendas'] ?? 0) . "
            },";
        }
        $conexion = null;
        ?>
    };

    document.addEventListener('DOMContentLoaded', function() {
        inicializarGraficos();
        inicializarBuscador();
    });

    function inicializarGraficos() {
        for (let i = 1; i <= 12; i++) {
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
                        legend: { position: 'bottom' },
                        title: {
                            display: true,
                            text: `Total: ${data.total_tiendas} tiendas`
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
            const term = this.value.toLowerCase().trim();
            let visibleCount = 0;

            zoneCards.forEach(card => {
                const name = card.getAttribute('data-zone-name').toLowerCase();
                const header = card.querySelector('h3');
                if (term === '' || name.includes(term)) {
                    card.classList.remove('zone-hidden');
                    visibleCount++;
                    if (term !== '') {
                        const regex = new RegExp(`(${term})`, 'gi');
                        header.innerHTML = header.textContent.replace(regex, '<span class="search-highlight">$1</span>');
                    }
                } else card.classList.add('zone-hidden');
            });

            searchResults.textContent = term === '' 
                ? 'Mostrando todas las zonas'
                : `Mostrando ${visibleCount} de ${zoneCards.length} zonas`;
        });

        clearSearch.addEventListener('click', () => {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        });
    }

    document.getElementById('exportButton').addEventListener('click', () => {
        alert('Funcionalidad de exportación a Excel - Por implementar');
    });
</script>

<?php require_once 'script.php'; ?>
</body>
</html>
