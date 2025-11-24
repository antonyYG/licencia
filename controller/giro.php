<?php 

require_once "../config/conexion.php";
require_once "../model/Giro.php";
$giros=new Giro(); //instanciar la clase

// Iniciar sesión para registrar quién realiza la operación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function registrar_log($accion, $detalle) {
    $usuario = isset($_SESSION['nombres']) ? $_SESSION['nombres'] : 'Sistema';
    $fecha = date('Y-m-d H:i:s');
    $linea = "[$fecha] [$usuario] $accion - $detalle\n";
    $ruta = __DIR__ . '/../logs/giros.log';
    @file_put_contents($ruta, $linea, FILE_APPEND);
}

$idgiro=isset($_POST['idgiro'])? limpiar($_POST['idgiro']) : "";
$giro=isset($_POST['nombregiro'])? limpiar($_POST['nombregiro']) : "";

switch ($_GET['boton']) {
    case 'listar':
        $lista=$giros->listagiro();
        if (!$lista) {
            die("error");
        }else{
            $arreglo=array("data"=>[]);
            while ($data=mysqli_fetch_assoc($lista)) {
                $arreglo["data"][]=$data;
            }
            echo json_encode($arreglo);
        }
        mysqli_free_result($lista);
        break;
    case 'insertar':
        // Validación case-insensitive de duplicados
        if ($giros->existeGiro($giro)) {
            echo "dup"; // Duplicado
            registrar_log('CREAR_DUPLICADO_BLOQUEADO', "Intento de crear giro existente: '$giro'");
            break;
        }
        $inserta=$giros->insertar($giro);
        if ($inserta) {
            echo "1";
            registrar_log('CREAR', "Giro creado: '$giro'");
        }else{
            echo "0";
        }
        break;
    case 'editar':
        // Validación de duplicado en edición (excluyendo el mismo id)
        $conTmp = (new conexion())->conectar();
        $giro_esc = mysqli_real_escape_string($conTmp, $giro);
        $id_int = (int)$idgiro;
        $dupQuery = mysqli_query($conTmp, "SELECT COUNT(*) AS total FROM giro WHERE LOWER(nombregiro)=LOWER('$giro_esc') AND idgiro <> $id_int");
        $dupRow = $dupQuery ? $dupQuery->fetch_array() : ["total"=>0];
        if (((int)$dupRow['total']) > 0) {
            echo "dup";
            registrar_log('EDITAR_DUPLICADO_BLOQUEADO', "Intento de renombrar a existente: '$giro' (id=$id_int)");
            break;
        }
        $editar=$giros->editar($idgiro,$giro);
        if ($editar) {
            echo "1";
            registrar_log('EDITAR', "Giro id=$idgiro actualizado a: '$giro'");
        }else{
            echo "0";
        }
        break;
    case 'eliminar':
        $elimina = $giros->eliminar($idgiro);
        if ($elimina) {
            echo "1";
            registrar_log('ELIMINAR', "Giro id=$idgiro eliminado");
        } else {
            echo "0";
        }
        break;
    default:
        
        break;
}
