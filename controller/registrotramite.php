<?php 
require_once "../model/RegistroTramite.php";
require_once "../public/phpqrcode/qrlib.php";

$Tramite = new RegistroTramite();

$idtramite         = isset($_POST['idtramite']) ? limpiar($_POST['idtramite']) : "";
$expediente        = isset($_POST['expediente']) ? limpiar($_POST['expediente']) : "";
$giro              = isset($_POST['giro']) ? limpiar($_POST['giro']) : "";
$nombres           = isset($_POST['nombres']) ? limpiar($_POST['nombres']) : "";
$recibotes         = isset($_POST['recibotes']) ? limpiar($_POST['recibotes']) : "";
$vigencia          = isset($_POST['vigencia']) ? limpiar($_POST['vigencia']) : "";
$fechingreso       = isset($_POST['fechingreso']) ? limpiar($_POST['fechingreso']) : "";
$fechexpedicion    = isset($_POST['fechexpedicion']) ? limpiar($_POST['fechexpedicion']) : "";
$numresolucion     = isset($_POST['numresolucion']) ? limpiar($_POST['numresolucion']) : "";
$nombrecomercial   = isset($_POST['nombrecomercial']) ? limpiar($_POST['nombrecomercial']) : "";

$nombresLi         = isset($_POST['nombresLi']) ? limpiar($_POST['nombresLi']) : "";
$apellidopli       = isset($_POST['apellidopli']) ? limpiar($_POST['apellidopli']) : "";
$apellidomli       = isset($_POST['apellidomli']) ? limpiar($_POST['apellidomli']) : "";
$numruc            = isset($_POST['numruc']) ? limpiar($_POST['numruc']) : "";
$ubicacion         = isset($_POST['ubicacion']) ? limpiar($_POST['ubicacion']) : "";
$arealocal         = isset($_POST['arealocal']) ? limpiar($_POST['arealocal']) : "";
$nombregiros       = isset($_POST['nombregiros']) ? limpiar($_POST['nombregiros']) : "";
$estado            = isset($_POST['estado']) ? limpiar($_POST['estado']) : "";
$tipolicencia      = isset($_POST['tipolicencia']) ? limpiar($_POST['tipolicencia']) : "";
$numdoc            = isset($_POST['numdoc']) ? limpiar($_POST['numdoc']) : "";
$tipo              = isset($_POST['tipo']) ? limpiar($_POST['tipo']) : "";

// Campos ITSE
$numresolucion_itse = isset($_POST['numresolucion_itse']) ? limpiar($_POST['numresolucion_itse']) : "";
$estado_itse        = isset($_POST['estado_itse']) ? limpiar($_POST['estado_itse']) : "";
$expedicion_itse    = isset($_POST['expedicionitse']) ? limpiar($_POST['expedicionitse']) : "";
$vigencia_itse      = isset($_POST['vigenciaitse']) ? limpiar($_POST['vigenciaitse']) : "";

switch ($_GET['boton']) {
    case 'listartienda':
        $lista = $Tramite->listartienda();
        if (!$lista) die("error");
        $arreglo = ["data" => []];
        while ($data = mysqli_fetch_assoc($lista)) {
            $data["selec"] = '<button type="button" class="btn btn-success btn-raised btn-sm seleccion"><i class="zmdi zmdi-check"></i></button>';
            $arreglo["data"][] = $data;
        }
        echo json_encode($arreglo);
        mysqli_free_result($lista);
        break;

    case 'listartiendaedit':
        $lista = $Tramite->listartienda();
        if (!$lista) die("error");
        $arreglo = ["data" => []];
        while ($data = mysqli_fetch_assoc($lista)) {
            $data["selecedit"] = '<button type="button" class="btn btn-success btn-raised btn-sm seleccionedit"><i class="zmdi zmdi-check"></i></button>';
            $arreglo["data"][] = $data;
        }
        echo json_encode($arreglo);
        mysqli_free_result($lista);
        break;

    case 'insertar':
        $path = "../files/qr/";
        $recibotes_formato = $recibotes . "-MDCH/" . date("Y");

        $data  = "Número de Doc: $numdoc" . PHP_EOL;
        $data .= "Otorgado a: $nombresLi $apellidopli $apellidomli" . PHP_EOL;
        $data .= "Número de Expediente: $expediente" . PHP_EOL;
        $data .= "Número de RUC: $numruc" . PHP_EOL;
        $data .= "Establecimiento Ubicado en: $ubicacion" . PHP_EOL;
        $data .= "Giro o Comercio: $nombregiros" . PHP_EOL;
        $data .= "Nombre Comercial: $nombrecomercial" . PHP_EOL;
        $data .= "Área Comercial: $arealocal" . PHP_EOL;
        $data .= "Número de Recibo Tesorería: $recibotes_formato" . PHP_EOL;
        $data .= "Tipo de Anuncio: $fechexpedicion" . PHP_EOL;
        $data .= "Tipo de Licencia: " . ($tipolicencia == '1' ? "Indeterminado" : "Temporal") . PHP_EOL;
        $data .= "Número de Resolución Licencia: $numresolucion" . PHP_EOL;
        $data .= "Fecha de Expedición Licencia: $fechingreso" . PHP_EOL;
        $data .= "Vigencia de Licencia: " . ($vigencia === "0001-01-01" ? "Indeterminado" : $vigencia) . PHP_EOL;
        $data .= "Estado de Licencia: ACTIVO" . PHP_EOL;
        $data .= "Número de Resolución ITSE: $numresolucion_itse" . PHP_EOL;
        $data .= "Fecha Expedición ITSE: $expedicion_itse" . PHP_EOL;
        $data .= "Vigencia ITSE: " . ($vigencia_itse === "0001-01-01" ? "Indeterminado" : $vigencia_itse) . PHP_EOL;
        $data .= "Estado ITSE: ACTIVO" . PHP_EOL;

        $file = $path . $expediente . ".png";
        $qr   = $expediente . ".png";
        QRcode::png($data, $file, 'H', 32, 5);

        $inserta = $Tramite->insertartramite(
            $expediente, $nombres, $giro, $nombrecomercial,
            $recibotes_formato, $numresolucion, $vigencia,
            $fechingreso, $fechexpedicion, $qr, $tipolicencia,
            $numdoc, $numresolucion_itse, $expedicion_itse, $vigencia_itse
        );

        echo $inserta ? "1" : "0";
        break;

    case 'editar':
        $path = "../files/qr/";
        $recibotes_formato = $recibotes . "-MDCH/" . date("Y");

        $data  = "Número de Doc: $numdoc" . PHP_EOL;
        $data .= "Otorgado a: $nombresLi $apellidopli $apellidomli" . PHP_EOL;
        $data .= "Número de Expediente: $expediente" . PHP_EOL;
        $data .= "Número de RUC: $numruc" . PHP_EOL;
        $data .= "Establecimiento Ubicado en: $ubicacion" . PHP_EOL;
        $data .= "Giro o Comercio: $nombregiros" . PHP_EOL;
        $data .= "Nombre Comercial: $nombrecomercial" . PHP_EOL;
        $data .= "Área Comercial: $arealocal" . PHP_EOL;
        $data .= "Número de Recibo Tesorería: $recibotes_formato" . PHP_EOL;
        $data .= "Tipo de Anuncio: $fechexpedicion" . PHP_EOL;
        $data .= "Tipo de Licencia: " . ($tipolicencia == '1' ? "Indeterminado" : "Temporal") . PHP_EOL;
        $data .= "Número de Resolución Licencia: $numresolucion" . PHP_EOL;
        $data .= "Fecha de Expedición Licencia: $fechingreso" . PHP_EOL;
        $data .= "Vigencia: " . ($vigencia === "0001-01-01" ? "Indeterminado" : $vigencia) . PHP_EOL;
        $data .= "Estado de Licencia: " . ($estado == '1' ? "Activo" : "Inactivo") . PHP_EOL;
        $data .= "Número de Resolución ITSE: $numresolucion_itse" . PHP_EOL;
        $data .= "Fecha Expedición ITSE: $expedicion_itse" . PHP_EOL;
        $data .= "Vigencia ITSE: " . ($vigencia_itse === "0001-01-01" ? "Indeterminado" : $vigencia_itse) . PHP_EOL;
        $data .= "Estado ITSE: " . ($estado_itse == '1' ? "Activo" : "Inactivo") . PHP_EOL;

        $file = $path . $expediente . ".png";
        $qr   = $expediente . ".png";
        QRcode::png($data, $file, 'H', 32, 5);

        $edita = $Tramite->editartramite(
            $idtramite, $expediente, $nombres, $giro, $recibotes_formato,
            $vigencia, $fechingreso, $fechexpedicion, $numresolucion,
            $nombrecomercial, $estado, $tipolicencia, $numdoc,
            $numresolucion_itse, $estado_itse, $expedicion_itse, $vigencia_itse
        );

        echo $edita ? "1" : "0";
        break;

    case 'listarcomercio':
        $lista = $Tramite->listagiro();
        if (!$lista) die("error");
        $arreglo = ["data" => []];
        while ($data = mysqli_fetch_assoc($lista)) {
            $data["botonseleccion"] = '<button type="button" class="btn btn-success btn-raised btn-sm selecciongiro"><i class="zmdi zmdi-check"></i></button>';
            $arreglo["data"][] = $data;
        }
        echo json_encode($arreglo);
        mysqli_free_result($lista);
        break;

    case 'listarcomercioedit':
        $lista = $Tramite->listagiro();
        if (!$lista) die("error");
        $arreglo = ["data" => []];
        while ($data = mysqli_fetch_assoc($lista)) {
            $data["botonseleccion"] = '<button type="button" class="btn btn-success btn-raised btn-sm selecciongiroedit"><i class="zmdi zmdi-check"></i></button>';
            $arreglo["data"][] = $data;
        }
        echo json_encode($arreglo);
        mysqli_free_result($lista);
        break;

    case 'seleccion':
        $select = $Tramite->selecciontipolic($tipo);
        $dato = mysqli_fetch_array($select);
        $num = isset($dato["num_tipolic"]) ? $dato["num_tipolic"] : "000000";
        $nummostrar = str_pad(($num + 1), 6, "0", STR_PAD_LEFT);
        echo json_encode(["tipoli" => $nummostrar]);
        break;

    default:
        echo "acción no válida";
        break;
}
?>
