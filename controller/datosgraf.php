<?php
require_once "../config/conexion.php";

// Conexión a la base de datos para la Zona 1
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión para la Zona 1
if ($conn->connect_error) {
    exit('Conexión fallida: '.$conn->connect_error);
}

// Obtener el total de tiendas
$sql_total_tiendas = 'SELECT COUNT(*) AS total_tiendas FROM tienda WHERE id_zona = 1';
$resultado_total_tiendas = $conn->query($sql_total_tiendas);
$total_tiendas = $resultado_total_tiendas->fetch_assoc()['total_tiendas'];

// Obtener los datos de la tabla licencia para la Zona 1
$sql = 'SELECT condicion, COUNT(*) AS total FROM licencia 
            INNER JOIN tienda ON licencia.idtienda = tienda.idtienda 
            WHERE tienda.id_zona = 1
            GROUP BY condicion';
$resultado = $conn->query($sql);

// Procesar los datos para el primer gráfico (Zona 1)
$labels = ['Cuenta con licencia', 'No cuenta con licencia'];
$cuenta_con_licencia = 0;
$no_cuenta_con_licencia = 0;
$total_tiendas = 0; // Variable para el total de tiendas en la zona 1

while ($fila = $resultado->fetch_assoc()) {
    $condicion = $fila['condicion'];
    $total = $fila['total'];

    if ($condicion == 1) {
        $cuenta_con_licencia = $total;
    } else {
        $no_cuenta_con_licencia = $total;
    }

    // Obtener el total de tiendas para la Zona 1
    $total_tiendas += $total;
}

// Cerrar conexión para la Zona 1
$conn->close();

/* --------------------------------------------------------------------- */
/* DATOS PARA EL GRAFICO 2 DE LA ZONA 2 */

// Conexión a la base de datos para la Zona 2
$conn2 = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión para la Zona 2
if ($conn2->connect_error) {
    exit('Conexión fallida: '.$conn2->connect_error);
}

// Obtener los datos de la tabla licencia para la Zona 2
$sql2 = 'SELECT condicion, COUNT(*) AS total FROM licencia 
            INNER JOIN tienda ON licencia.idtienda = tienda.idtienda 
            WHERE tienda.id_zona = 2
            GROUP BY condicion';
$resultado2 = $conn2->query($sql2);

// Procesar los datos para el segundo gráfico
$labels2 = ['Cuenta con licencia', 'No cuenta con licencia'];
$cuenta_con_licencia2 = 0;
$no_cuenta_con_licencia2 = 0;
$total_tiendas2 = 0; // Variable para almacenar el total de tiendas

while ($fila2 = $resultado2->fetch_assoc()) {
    $condicion2 = $fila2['condicion'];
    $total2 = $fila2['total'];

    if ($condicion2 == 1) {
        $cuenta_con_licencia2 = $total2;
    } else {
        $no_cuenta_con_licencia2 = $total2;
    }

    $total_tiendas2 += $total2; // Sumar al total de tiendas
}

// Cerrar conexión para la Zona 2
$conn2->close();

// Crear un array asociativo con los datos
$data = [
    'labels' => $labels,
    'cuenta_con_licencia' => $cuenta_con_licencia,
    'no_cuenta_con_licencia' => $no_cuenta_con_licencia,
    'total_tiendas' => $total_tiendas
];

// Enviar los datos como respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
