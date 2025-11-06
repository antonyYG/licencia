<?php 
 require_once "../vendor/autoload.php";	
 require_once "../config/conexion.php";
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 /**
  * 
  */
 class Usuario extends conexion
 {
 	public function listaruser(){
 		$con=parent::conectar();
 		$sql=mysqli_query($con, "SELECT `idpersona`, `nombres`, `apellidop`, `apellidom`, `direccion`, `dni`, `contrasena`, `condicion` FROM `usuario`");
 		return $sql;
 	}

 	public function insertaruser($nombres,$apellidop,$apellidom,$direccion,$dni,$contrasena,$correo,$rol){
 		$con=parent::conectar();
    $checkCorreo = mysqli_query($con, "SELECT idpersona FROM usuario WHERE correo = '$correo' LIMIT 1");
    if (mysqli_num_rows($checkCorreo) > 0) {
        return "correo_duplicado";
    }
 		$incripta=password_hash($contrasena, PASSWORD_DEFAULT);
 		$sql=mysqli_query($con, "INSERT INTO `usuario`(`nombres`, `apellidop`, `apellidom`, `direccion`, `dni`, `contrasena`,`tipo_usuario`,`correo`) VALUES ('$nombres','$apellidop','$apellidom','$direccion','$dni','$incripta','$rol','$correo')");
 		if ($sql) {
			$usuario_id = mysqli_insert_id($con); // ID del usuario insertado

            // Enviar correo al administrador
            $this->enviarCorreoAdmin($usuario_id, $nombres, $apellidop, $apellidom, $dni);
 			return true;
 		}else{
 			return false;
 		}
 	}

  public function editaruser($idusuario, $nombres, $apellidop, $apellidom, $direccion, $dni, $contrasena, $correo, $rol)
  {
      $con = parent::conectar();
      $validarCorreo = mysqli_query($con, "SELECT idpersona FROM usuario WHERE correo = '$correo' AND idpersona != '$idusuario'");
      if (mysqli_num_rows($validarCorreo) > 0) {
          return "correo_existente";
      }
      if (empty($contrasena)) {
          $sql = mysqli_query($con, "UPDATE usuario 
              SET nombres = '$nombres',
                  apellidop = '$apellidop',
                  apellidom = '$apellidom',
                  direccion = '$direccion',
                  dni = '$dni',
                  correo = '$correo',
                  tipo_usuario = '$rol'
              WHERE idpersona = '$idusuario'");
      } else {
          $incripta = password_hash($contrasena, PASSWORD_DEFAULT);
          $sql = mysqli_query($con, "UPDATE usuario 
              SET nombres = '$nombres',
                  apellidop = '$apellidop',
                  apellidom = '$apellidom',
                  direccion = '$direccion',
                  dni = '$dni',
                  correo = '$correo',
                  tipo_usuario = '$rol',
                  contrasena = '$incripta'
              WHERE idpersona = '$idusuario'");
      }

      if ($sql) {
          return true;
      } else {
          return false;
      }
  }
 	public function activo($idusuario){
 		$con=parent::conectar();
 		$sql=mysqli_query($con, "update usuario set condicion=1 where idpersona='$idusuario'");
 		if ($sql) {
 			return true;
 		}else{
 			return false;
 		}
 	}

 	public function inactivo($idusuario){
 		$con=parent::conectar();
 		$sql=mysqli_query($con, "update usuario set condicion=0 where idpersona='$idusuario'");
 		if ($sql) {
 			return true;
 		}else{
 			return false;
 		}
 	}

 	public function mostrarpersona($idusuario){
      $con = parent::conectar();
      $sql = mysqli_query($con, "
          SELECT idpersona, nombres, apellidop, apellidom, direccion, dni, correo, tipo_usuario
          FROM usuario
          WHERE idpersona = '$idusuario'
      ");
      return $sql;
  }

   public function login($dni)
	{
		$con = parent::conectar();
		$sql = mysqli_query($con, "SELECT nombres, condicion, dni, contrasena, tipo_usuario FROM usuario WHERE dni='$dni'");
		return $sql;
	}


    public function contaruser(){
      $con=parent::conectar();
      $sql=mysqli_query($con, "select COUNT(idpersona) user from usuario");
      $fila = $sql->fetch_array();
      return $fila['user'];
    }

    public function contarlicencia(){
      $con=parent::conectar();
      $sql=mysqli_query($con, "select COUNT(idlicencia) licencia from licencia");
      $fila = $sql->fetch_array();
      return $fila['licencia'];
    }

    public function contartienda(){
      $con=parent::conectar();
      $sql=mysqli_query($con, "select COUNT(idtienda) tienda from tienda");
      $fila = $sql->fetch_array();
      return $fila['tienda'];
    }

    public function contargiro(){
      $con=parent::conectar();
      $sql=mysqli_query($con, "select COUNT(idgiro) giro from giro");
      $fila = $sql->fetch_array();
      return $fila['giro'];
    }
    public function contarzonas(){
      $con=parent::conectar();
      $sql=mysqli_query($con, "select COUNT(id_zona) zonas from zonas");
      $fila = $sql->fetch_array();
      return $fila['zonas'];
    }

	 // Método privado para enviar correo
    private function enviarCorreoAdmin($id, $nombres, $apellidop, $apellidom, $dni)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'd53aa07c60c441';
            $mail->Password   = '10020d1de49587';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 2525;

            $mail->setFrom('licencia@gmail.com', 'Sistema');

            require_once __DIR__ . '/../config/conexion.php';
            $con = new conexion();
            $cnx = $con->conectar();

            $sql = "SELECT correo FROM usuario WHERE tipo_usuario = 'Administrador'";
            $result = mysqli_query($cnx, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if (!empty($row['correo'])) {
                        $mail->addBCC($row['correo']);
                    }
                }
            } else {
                return;
            }
            $html = file_get_contents(__DIR__ . '/../view/email/Correo.html');
            $html = str_replace('{{id}}', $id, $html);
            $html = str_replace('{{nombre}}', "$nombres $apellidop $apellidom", $html);
            $html = str_replace('{{dni}}', $dni, $html);

            $mail->isHTML(true);
            $mail->Subject = 'Nuevo usuario registrado';
            $mail->Body    = $html;

            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }

 }
