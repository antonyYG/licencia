<?php

require_once "../model/Tienda.php";

$Tiendas = new Tienda();

$idtienda = isset($_POST['idtienda']) ? limpiar($_POST['idtienda']) : "";
$ruc = isset($_POST['ruc']) ? limpiar($_POST['ruc']) : "";
$nombres = isset($_POST['nombres']) ? limpiar($_POST['nombres']) : "";
$apellidop = isset($_POST['apellidop']) ? limpiar($_POST['apellidop']) : "";
$apellidom = isset($_POST['apellidom']) ? limpiar($_POST['apellidom']) : "";
$ubicacion = isset($_POST['ubicacion']) ? limpiar($_POST['ubicacion']) : "";
$area = isset($_POST['area']) ? limpiar($_POST['area']) : "";
$latitud = isset($_POST['latitud']) ? limpiar($_POST['latitud']) : "";
$longitud = isset($_POST['longitud']) ? limpiar($_POST['longitud']) : "";
$zona = isset($_POST['zona']) ? limpiar($_POST['zona']) : "";
$celular = isset($_POST['celular']) ? limpiar($_POST['celular']) : "";

switch ($_GET['boton']) {
    case 'lista':
        $lista = $Tiendas->listatienda();
        if (!$lista) {
            die("error");
        } else {
            $arreglo = array("data" => []);
            while ($data = mysqli_fetch_assoc($lista)) {
                $data['edita'] = '<button data-id="'.$data['idtienda'].'" type="button" class="btn btn-primary btn-raised btn-sm actualizar"><i class="zmdi zmdi-edit"></i></button>';
                $arreglo["data"][] = $data;
            }
            echo json_encode($arreglo);
        }
        mysqli_free_result($lista);
        break;
    case 'insertar':
        $inserta = $Tiendas->insertar($ruc, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona, $celular);
        if ($inserta) {
            echo "1";
        } else {
            echo "0";
        }
        break;
    case 'editar':
        $edita = $Tiendas->editar($idtienda, $ruc, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona, $celular);
        if ($edita) {
            echo "1";
        } else {
            echo "0";
        }
        break;
case 'mostrartienda':
    $mostra = $Tiendas->mostraredit($idtienda);
    $data = array();
    foreach ($mostra as $row) {
        $data["idtienda"] = $row["idtienda"];
        $data["ruc"] = $row["numruc"];
        $data["nombres"] = $row["nombres_per"];
        $data["apellidop"] = $row["apellidop_per"];
        $data["apellidom"] = $row["apellidom_per"];
        $data["ubicacion"] = $row["ubic_tienda"];
        $data["area"] = $row["area_tienda"];
        $data["latitud"] = $row["latitud"];
        $data["longitud"] = $row["longitud"];
        $data["zona"] = $row["id_zona"];
        $data["celular"] = $row["celular"];
    }
    echo json_encode($data);
    break;

    default:
        // code...
        break;
}
?>
