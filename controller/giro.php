<?php 

require_once "../model/Giro.php";
$giros=new Giro(); //instanciar la clase

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
		$inserta=$giros->insertar($giro);
		if ($inserta) {
			echo "1";
		}else{
			echo "0";
		}
		break;
	case 'editar':
		$editar=$giros->editar($idgiro,$giro);
		if ($editar) {
			echo "1";
		}else{
			echo "0";
		}
		break;
	case 'eliminar':
		$eliminar=$giros->eliminar($idgiro);
		if ($eliminar) {
			echo "1";
		}else{
			echo "0";
		}
		break;
	default:

		break;
}
