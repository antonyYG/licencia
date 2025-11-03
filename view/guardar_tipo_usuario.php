<?php
require_once "../config/conexion.php";
$con = new Conexion();
$conexion = $con->conectar();

// Obtener los datos del formulario
$idpersona = $_POST['idpersona'];
$tipoUsuario = $_POST['tipoUsuario'];

// Actualizar el tipo de usuario en la base de datos
$sql = "UPDATE usuario SET tipo_usuario = ? WHERE idpersona = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $tipoUsuario, $idpersona);

if ($stmt->execute()) {
    $response = array(
        'status' => 'success',
        'message' => 'Tipo de usuario guardado exitosamente'
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Error al guardar el tipo de usuario: ' . $stmt->error
    );
}

$stmt->close();
$conexion->close();

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
