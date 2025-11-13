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
    <style>
      /* Oculta gráficos antiguos, mostrando solo el nuevo pastel */
      .chart { display: none; }
      #estad-pie.chart { display: block; }
      /* Sugerencias clicables en móviles */
      .suggestion-item { padding: 6px 8px; border: 1px solid #eee; border-radius: 4px; margin-bottom: 6px; cursor: pointer; }
      .suggestion-item:hover { background: #f7f7f7; }
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
                                <button id="estad-export" class="btn btn-primary btn-sm">Exportar CSV</button>
                                <small style="display:block; color:#666; margin-top:4px;">CSV UTF-8 con encabezados, compatible con Excel.</small>
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
    <!--CODIGO JAVASCRIPT -->
<!--
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




-->



    <?php require_once 'script.php'; ?>

    <script type="text/javascript" src="script/tienda.js"></script>
    <script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
    <script type="text/javascript" src="script/validacion.js"></script>
    <!-- script/graficos.js eliminado para evitar mostrar zonas por defecto de Chupaca -->
    <script type="text/javascript" src="script/estadisticas.js"></script>

</body>

</html>