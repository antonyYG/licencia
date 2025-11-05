<?php 
require_once "../model/Usuario.php";
$Usuario=new Usuario();
	
$idusuario=isset($_POST['idusuario'])? limpiar($_POST['idusuario']): "";
$nombres=isset($_POST['nombres'])? limpiar($_POST['nombres']): "";
$apellidop=isset($_POST['apellidop'])? limpiar($_POST['apellidop']): "";
$apellidom=isset($_POST['apellidom'])? limpiar($_POST['apellidom']): "";
$direccion=isset($_POST['direccion'])? limpiar($_POST['direccion']): "";
$dni=isset($_POST['dni'])? limpiar($_POST['dni']): "";
$rol=isset($_POST['rol'])? limpiar($_POST['rol']): "";
$correo=isset($_POST['correo'])? limpiar($_POST['correo']): "";
$contrasena=isset($_POST['contrasena'])? limpiar($_POST['contrasena']): "";
$repitecontrasena=isset($_POST['repitecontrasena'])? limpiar($_POST['repitecontrasena']): "";

switch ($_GET['boton']) {
	case 'listar':
		$lista=$Usuario->listaruser();
		if (!$lista) {
			die("error");
		}else{
			$arreglo=array("data"=>[]);
			while ($data=mysqli_fetch_assoc($lista)) {
				$data['condicion']=($data['condicion']==true ?'<button type="button" data-id='.$data['idpersona'].' class="btn btn-success btn-raised btn-sm inactivo"><i class="fas fa-toggle-on"></i></button>':'<button type="button" data-id='.$data['idpersona'].' class="btn btn-danger btn-raised btn-sm activo"><i class="fas fa-toggle-off"></i></button>');
				$data['edit']='<button type="button" data-id='.$data['idpersona'].' class="btn btn-primary btn-raised btn-sm actua"><i class="zmdi zmdi-edit"></i></button>';
				$arreglo["data"][]=$data;
			}
			echo json_encode($arreglo);
		}
		mysqli_free_result($lista);
		break;
	case 'insertar':
		$inserta = $Usuario->insertaruser($nombres, $apellidop, $apellidom, $direccion, $dni, $contrasena, $correo, $rol);
		if ($inserta === "correo_duplicado") {
				echo "correo_duplicado";
			} elseif ($inserta === "dni_duplicado") {
				echo "dni_duplicado";
			} elseif ($inserta === true) {
				echo "1";
			} else {
				echo "0";
			}
		break;
	case 'editar':
    // Validar que no exista otro usuario con el mismo correo
    $con = (new conexion())->conectar();
    $verificaCorreo = mysqli_query($con, "SELECT idpersona FROM usuario WHERE correo='$correo' AND idpersona != '$idusuario'");
    if (mysqli_num_rows($verificaCorreo) > 0) {
        echo "correo_duplicado";
        exit;
    }

    $edita = $Usuario->editaruser($idusuario, $nombres, $apellidop, $apellidom, $direccion, $dni, $contrasena, $correo, $rol);

    if ($edita) {
        echo "1";
    } else {
        echo "0";
    }
    break;
	case 'activo':
		$activo=$Usuario->activo($idusuario);
		if ($activo) {
			echo "1";
		}else{
			echo "0";
		}
		break;
	case 'inactivo':
		$inactivo=$Usuario->inactivo($idusuario);
		if ($inactivo) {
			echo "1";
		}else{
			echo "0";
		}
		break;
	case 'motrarpersona':
		$mostrar = $Usuario->mostrarpersona($idusuario);
		$data = array();

		foreach ($mostrar as $row) {
			$data['idusuario'] = $row['idpersona'];
			$data['nombres'] = $row['nombres'];
			$data['apellidop'] = $row['apellidop'];
			$data['apellidom'] = $row['apellidom'];
			$data['direccion'] = $row['direccion'];
			$data['dni'] = $row['dni'];
			$data['correo'] = $row['correo'];
			$data['rol'] = $row['tipo_usuario'];
		}

		echo json_encode($data);
		break;
	case 'login':
				$login=$Usuario->login($dni);
				if ($row=mysqli_fetch_array($login)) {
					$verifica=password_verify($contrasena, $row['contrasena']);
					if ($verifica) {
						session_start();
						$_SESSION['nombres']=$row['nombres'];
						$_SESSION['estado']=$row['condicion'];
						if ($row['condicion']=='1') {
							echo "3";
						}else{
							echo "4";
						}
					}else{
						echo "5";
					}
				}else{
					echo "6";
				}
		break;
	case 'cerrar':
		session_start();
		foreach ($_SESSION as $index => $value) {
			unset($_SESSION[$index]);
		}
		session_destroy();
		header("Location:../index.php");
		break;
	default:
		// code...
		break;
}