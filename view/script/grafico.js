
    // Realizar una solicitud AJAX para obtener los datos del archivo grafico.php
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'graficos.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            /* Gráfico 1 */
            var labels1 = response.grafico1.labels;
            var cuenta_con_licencia1 = response.grafico1.cuenta_con_licencia;
            var no_cuenta_con_licencia1 = response.grafico1.no_cuenta_con_licencia;
            var total_tiendas1 = response.grafico1.total_tiendas;

            // Eliminar el total de licencias del conjunto de datos
            var data1 = {
                labels: ['Cuenta con licencia', 'No cuenta con licencia', 'Total de tiendas'],
                datasets: [{
                    label: 'Resumen total de licencia',
                    data: [cuenta_con_licencia1, no_cuenta_con_licencia1, total_tiendas1],
                    backgroundColor: [
                        'rgba(0, 255, 0, 0.8)', // Verde (Cuenta con licencia)
                        'rgba(255, 0, 0, 0.8)', // Rojo (No cuenta con licencia)
                        'rgba(0, 0, 255, 0.8)', // Azul (Total)
                    ],
                    borderColor: [
                        'rgba(0, 255, 0, 0.8)', // Verde (Cuenta con licencia)
                        'rgba(255, 0, 0, 0.8)', // Rojo (No cuenta con licencia)
                        'rgba(0, 0, 255, 0.8)', // Azul (Total)
                    ],
                    borderWidth: 1
                }]
            };

            // Configurar opciones del primer gráfico
            var options1 = {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 16,
                                weight: 'bold'
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

            // Crear el objeto Chart y dibujar el primer gráfico
            var ctx1 = document.getElementById('myChart1').getContext('2d');
            var myChart1 = new Chart(ctx1, {
                type: 'pie',
                data: data1,
                options: options1
            });


            /* Gráfico 2 */
            var labels2 = response.grafico2.labels;
            var cuenta_con_licencia2 = response.grafico2.cuenta_con_licencia;
            var no_cuenta_con_licencia2 = response.grafico2.no_cuenta_con_licencia;
            var total_tiendas2 = response.grafico2.total_tiendas;

            // Obtener el canvas del segundo gráfico
            var ctx2 = document.getElementById('myChart2').getContext('2d');

            // Configurar los datos del segundo gráfico
            var data2 = {
                labels: labels2.concat(['Total de tiendas']), // etiquetas del eje X
                datasets: [{
                    label: 'Resumen total de licencia', // título del gráfico
                    data: [cuenta_con_licencia2, no_cuenta_con_licencia2, total_tiendas2], // datos del gráfico
                    backgroundColor: [
                        'rgba(0, 255, 0, 0.8)', // Verde (Cuenta con licencia)
                        'rgba(255, 0, 0, 0.8)', // Rojo (No cuenta con licencia)
                        'rgba(0, 0, 255, 0.8)', // Azul (Total)
                    ],
                    borderColor: [
                        'rgba(0, 255, 0, 1)', // Verde (Cuenta con licencia)
                        'rgba(255, 0, 0, 1)', // Rojo (No cuenta con licencia)
                        'rgba(0, 0, 255, 1)', // Azul (Total)
                    ],
                    borderWidth: 1 // ancho del borde de las barras
                }]
            };


            // Configurar opciones del segundo gráfico
            var options2 = {
                responsive: true, // permitir que el gráfico se ajuste al tamaño del contenedor
                plugins: {
                    legend: {
                        position: 'top', // Posiciona la leyenda en la parte superior
                        labels: {
                            font: {
                                size: 16, // Tamaño de fuente de las etiquetas de la leyenda
                                weight: '700' // Peso de fuente de las etiquetas de la leyenda
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart',
                    animateRotate: false,
                    animateScale: true // Habilita la animación de escala
                }
            };
            // Crear el objeto Chart y dibujar el segundo gráfico
            var myChart2 = new Chart(ctx2, {
                type: 'pie', // cambiar el tipo de gráfico a pie
                data: data2, // datos del gráfico
                options: options2 // opciones del gráfico
            });
        }
    };
    xhr.send();

