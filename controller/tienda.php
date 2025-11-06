<?php

require_once "../model/Tienda.php";

$Tiendas = new Tienda();

$idtienda = isset($_POST['idtienda']) ? limpiar($_POST['idtienda']) : "";
$ruc = isset($_POST['ruc']) ? limpiar($_POST['ruc']) : "";
// Nuevo campo DNI (opcional) para tiendas
$dni = isset($_POST['dni']) ? limpiar($_POST['dni']) : "";
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
        // Se pasa el nuevo campo $dni al modelo para guardar
        $inserta = $Tiendas->insertar($ruc, $dni, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona, $celular);
        if ($inserta) {
            echo "1";
        } else {
            echo "0";
        }
        break;
    case 'editar':
        // Actualiza también el campo $dni
        $edita = $Tiendas->editar($idtienda, $ruc, $dni, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona, $celular);
        if ($edita) {
            echo "1";
        } else {
            echo "0";
        }
        break;
    case 'mostrartienda':
        $data = [];

        $mostra = $Tiendas->mostraredit($idtienda);

        if ($mostra && $row = mysqli_fetch_assoc($mostra)) {
            $data = [
                "idtienda"  => $row["idtienda"],
                "ruc"       => $row["numruc"],
                // Se incluye `dni` para ser mostrado en el formulario de edición
                "dni"       => $row["dni"],
                "nombres"   => $row["nombres_per"],
                "apellidop" => $row["apellidop_per"],
                "apellidom" => $row["apellidom_per"],
                "ubicacion" => $row["ubic_tienda"],
                "area"      => $row["area_tienda"],
                "latitud"   => $row["latitud"],
                "longitud"  => $row["longitud"],
                "zona"      => $row["id_zona"],
                "celular"   => $row["celular"]
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;

    default:
        // code...
        break;
}
?>
