<?php

require_once "../config/conexion.php";
$con = new Conexion();
$conexion = $con->conectar();

// Realizar consulta SQL para obtener los usuarios
$sql = "SELECT * FROM usuario";
$resultado = mysqli_query($conexion, $sql);

$response = array();

if ($resultado) {
    $usuarios = array();

    // Recorrer los resultados de la consulta y almacenar los usuarios en un array
    while ($row = mysqli_fetch_assoc($resultado)) {
        $usuarios[] = $row;
    }

    $response['status'] = 'success';
    $response['usuario'] = $usuarios;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error al obtener los usuarios: ' . mysqli_error($conexion);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
