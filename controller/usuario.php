<?php 
require_once "../model/Usuario.php";
$Usuario=new Usuario();

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
	
$idusuario=isset($_POST['idusuario'])? limpiar($_POST['idusuario']): "";
$nombres=isset($_POST['nombres'])? limpiar($_POST['nombres']): "";
$apellidop=isset($_POST['apellidop'])? limpiar($_POST['apellidop']): "";
$apellidom=isset($_POST['apellidom'])? limpiar($_POST['apellidom']): "";
$direccion=isset($_POST['direccion'])? limpiar($_POST['direccion']): "";
$dni=isset($_POST['dni'])? limpiar($_POST['dni']): "";
$contrasena=isset($_POST['contrasena'])? limpiar($_POST['contrasena']): "";
$repitecontrasena=isset($_POST['repitecontrasena'])? limpiar($_POST['repitecontrasena']): "";
$correo=isset($_POST['correo'])? limpiar($_POST['correo']): "";
$tipoUsuario=isset($_POST['tipo_usuario'])? limpiar($_POST['tipo_usuario']): "";

switch ($_GET['boton']) {
	case 'listar':
		$lista=$Usuario->listaruser();
		if (!$lista) {
			die("error");
		}else{
			$arreglo=array("data"=>[]);
			while ($data=mysqli_fetch_assoc($lista)) {
				$data['condicion']=($data['condicion']==true ?'<button type="button" data-id='.$data['idpersona'].' class="btn btn-success btn-raised btn-sm inactivo"><i class="fas fa-toggle-on"></i></button>':'<button type="button" data-id='.$data['idpersona'].' class="btn btn-danger btn-raised btn-sm activo"><i class="fas fa-toggle-off"></i></button>');
				$data['delete']='<button type="button" data-id='.$data['idpersona'].' class="btn btn-danger btn-raised btn-sm elimina"><i class="zmdi zmdi-delete"></i></button>';
				$data['edit']='<button type="button" data-id='.$data['idpersona'].' class="btn btn-primary btn-raised btn-sm actua"><i class="zmdi zmdi-edit"></i></button>';
				$arreglo["data"][]=$data;
			}
			echo json_encode($arreglo);
		}
		mysqli_free_result($lista);
		break;
	case 'insertar':
		$inserta = $Usuario->insertaruser(
			$nombres,
			$apellidop,
			$apellidom,
			$direccion,
			$dni,
			$contrasena,
			$correo,
			$tipoUsuario
		);
		if ($inserta === true) {
			echo "1"; // creado y correo enviado
		} elseif ($inserta === "correo_duplicado") {
			echo "2"; // correo duplicado
		} elseif ($inserta === "mail_error") {
			echo "3"; // creado pero hubo error enviando correo
		} else {
			echo "0"; // error general
		}
		break;
	case 'editar':
		$edita=$Usuario->editaruser($idusuario,$nombres,$apellidop,$apellidom,$direccion,$dni,$correo,$contrasena,$tipoUsuario);
		if ($edita) {
			echo "1";
		}else{
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
		$mostrar=$Usuario->mostrarpersona($idusuario);
		$data=array();
		foreach ($mostrar as $row) {
			$data['idusuario']=$row['idpersona'];
			$data['nombres']=$row['nombres'];
			$data['apellidop']=$row['apellidop'];
			$data['apellidom']=$row['apellidom'];
			$data['direccion']=$row['direccion'];
			$data['dni']=$row['dni'];
			$data['correo']=$row['correo'];
			$data['tipo_usuario']=$row['tipo_usuario'];
		}
		echo json_encode($data);
		break;
	case 'login':
			// Implementar control de intentos en sesión (3 intentos, bloqueo por 45 segundos)
			$max_attempts = 3;
			$lock_seconds = 45;

			// Verificar bloqueo activo
			if (isset($_SESSION['login_lock']) && $_SESSION['login_lock'] > time()) {
				$remaining = $_SESSION['login_lock'] - time();
				// Código 7 indica bloqueo temporal, enviamos también segundos restantes
				echo "7|" . $remaining;
				break;
			}

			$login=$Usuario->login($dni);
			if ($row=mysqli_fetch_array($login)) {
				$verifica=password_verify($contrasena, $row['contrasena']);
				if ($verifica) {
					// Login exitoso: resetear contadores
					unset($_SESSION['login_attempts']);
					unset($_SESSION['login_lock']);
					$_SESSION['nombres']=$row['nombres'];
					$_SESSION['estado']=$row['condicion'];
					if ($row['condicion']=='1') {
						echo "3"; // login correcto y activo
					}else{
						echo "4"; // login correcto pero condicional
					}
				} else {
					// Contraseña incorrecta: incrementar intentos
					if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
					$_SESSION['login_attempts']++;
					$attempts_left = $max_attempts - $_SESSION['login_attempts'];
					if ($_SESSION['login_attempts'] >= $max_attempts) {
						// Bloquear
						$_SESSION['login_lock'] = time() + $lock_seconds;
						unset($_SESSION['login_attempts']);
						// Devolver código bloqueo con segundos
						echo "7|" . $lock_seconds;
					} else {
						// Devolver código contraseña incorrecta y cuantos intentos quedan
						echo "5|" . $attempts_left;
					}
				}
			} else {
				// Usuario no encontrado: tratar como intento fallido también
				if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
				$_SESSION['login_attempts']++;
				$attempts_left = $max_attempts - $_SESSION['login_attempts'];
				if ($_SESSION['login_attempts'] >= $max_attempts) {
					// Bloquear
					$_SESSION['login_lock'] = time() + $lock_seconds;
					unset($_SESSION['login_attempts']);
					// Devolver código bloqueo con segundos
					echo "7|" . $lock_seconds;
				} else {
					// Devolver código de intento fallido y cuantos intentos quedan
					echo "5|" . $attempts_left;
				}
			}
		break;
	case 'check_lock':
			// Devuelve estado de bloqueo de login: 7|seconds o 0
			if (isset($_SESSION['login_lock']) && $_SESSION['login_lock'] > time()) {
				$remaining = $_SESSION['login_lock'] - time();
				echo "7|" . $remaining;
			} else {
				echo "0";
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