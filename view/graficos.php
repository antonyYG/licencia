<?php
require_once "../config/conexion.php";

// Función para obtener los datos de licencias por zona
function obtenerDatosLicencias($zona)
{
    $con = new conexion();
    $conexion = $con->conectar();
    
    $sql = "SELECT condicion, COUNT(*) AS total FROM licencia 
            INNER JOIN tienda ON licencia.idtienda = tienda.idtienda 
            WHERE tienda.id_zona = $zona
            GROUP BY condicion";
    
    $resultado = mysqli_query($conexion, $sql);
    
    $labels = ['Cuenta con licencia', 'No cuenta con licencia'];
    $cuenta_con_licencia = 0;
    $no_cuenta_con_licencia = 0;
    $total_tiendas = 0;
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $condicion = $fila['condicion'];
        $total = $fila['total'];
        
        if ($condicion == 1) {
            $cuenta_con_licencia = $total;
        } else {
            $no_cuenta_con_licencia = $total;
        }
        
        $total_tiendas += $total;
    }
    
    // Obtener el nombre de la zona
    $nombre_zona = obtenerNombreZona($zona);
    
    mysqli_close($conexion);
    
    $data = array(
        'nombre_zona' => $nombre_zona,
        'labels' => $labels,
        'cuenta_con_licencia' => $cuenta_con_licencia,
        'no_cuenta_con_licencia' => $no_cuenta_con_licencia,
        'total_tiendas' => $total_tiendas
    );
    
    return $data;
}

// Función para obtener el nombre de la zona
function obtenerNombreZona($zona)
{
    $con = new conexion();
    $conexion = $con->conectar();

    $sql = "SELECT nombre_zona FROM zonas WHERE id_zona = $zona";
    $resultado = mysqli_query($conexion, $sql);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $nombre_zona = $fila['nombre_zona'];
    } else {
        $nombre_zona = "Zona " . $zona;
    }

    mysqli_close($conexion);

    return $nombre_zona;
}

// Función para obtener los datos de cada tienda en una zona específica
// Función para obtener los datos de cada tienda en una zona específica
// Función para obtener los datos de cada tienda en una zona específica
function obtenerDatosTiendasZona($zona)
{
    $con = new conexion();
    $conexion = $con->conectar();

    $sql = $sql = "SELECT tienda.nombres_per, tienda.apellidop_per, tienda.apellidom_per, tienda.ubic_tienda, giro.nombregiro, licencia.condicion, licencia.EstadoITSE
        FROM tienda
        LEFT JOIN licencia ON tienda.idtienda = licencia.idtienda
        LEFT JOIN giro ON licencia.idgiro = giro.idgiro
        WHERE tienda.id_zona = $zona";


    $resultado = mysqli_query($conexion, $sql);
    $datos = array();

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $nombre = $fila['nombres_per'];
        $apellidoPaterno = $fila['apellidop_per'];
        $apellidoMaterno = $fila['apellidom_per'];
        $direccion = $fila['ubic_tienda'];
        $giro = $fila['nombregiro'];
        $estadoLicencia = $fila['condicion'] == 1 ? 'activo' : 'inactivo';
        $estadoITSE = $fila['EstadoITSE'] == 1 ? 'activo' : 'inactivo';

        $datos[] = array(
            'Nombre' => $nombre,
            'Apellido Paterno' => $apellidoPaterno,
            'Apellido Materno' => $apellidoMaterno,
            'Direccion' => $direccion,
            'GIRO' => $giro,
            'Estado Licencia' => $estadoLicencia,
            'Estado ITSE' => $estadoITSE
        );
    }

    mysqli_close($conexion);

    return $datos;
}




// Obtener los datos de licencias para cada zona
$final_data = array();
for ($i = 1; $i <= 24; $i++) {
    $data = obtenerDatosLicencias($i);
    $final_data['grafico' . $i] = $data;
}

// Obtener los datos de las tiendas para cada zona
for ($i = 1; $i <= 24; $i++) {
    $data = obtenerDatosTiendasZona($i);
    $final_data['tabla' . $i] = $data;
}

// Establecer la cabecera de respuesta como JSON
header('Content-Type: application/json');

// Imprimir los datos en formato JSON
echo json_encode($final_data);
?>


