<?php 
 require_once "../config/conexion.php";

 /**
  * 
  */
 class Usuario extends conexion
 {
  public function listaruser(){
  		$con=parent::conectar();
		$sql=mysqli_query($con, "SELECT `idpersona`, `nombres`, `apellidop`, `apellidom`, `direccion`, `dni`, `correo`, `contrasena`, `condicion`, `tipo_usuario` FROM `usuario`");
  		return $sql;
  	}

 	public function insertaruser($nombres,$apellidop,$apellidom,$direccion,$dni,$correo,$contrasena,$tipoUsuario){
  		$con=parent::conectar();
  		$incripta=password_hash($contrasena, PASSWORD_DEFAULT);
		$sql=mysqli_query($con, "INSERT INTO `usuario`(`nombres`, `apellidop`, `apellidom`, `direccion`, `dni`, `correo`, `contrasena`, `condicion`, `tipo_usuario`) VALUES ('$nombres','$apellidop','$apellidom','$direccion','$dni','$correo','$incripta', 1, '$tipoUsuario')");
  		if ($sql) {
  			return true;
  		}else{
  			return false;
  		}
  	}

 	public function editaruser($idusuario,$nombres,$apellidop,$apellidom,$direccion,$dni,$correo,$contrasena,$tipoUsuario){
  		$con=parent::conectar();
  		if (empty($contrasena)) {
			$sql=mysqli_query($con, "UPDATE `usuario` SET `nombres`='$nombres',`apellidop`='$apellidop',`apellidom`='$apellidom',`direccion`='$direccion',`dni`='$dni', `correo`='$correo', `tipo_usuario`='$tipoUsuario' WHERE `idpersona`='$idusuario'");
  		}else{
  			$incripta=password_hash($contrasena, PASSWORD_DEFAULT);
			$sql=mysqli_query($con, "UPDATE `usuario` SET `nombres`='$nombres',`apellidop`='$apellidop',`apellidom`='$apellidom',`direccion`='$direccion',`dni`='$dni', `correo`='$correo', `contrasena`='$incripta', `tipo_usuario`='$tipoUsuario' WHERE `idpersona`='$idusuario'");
  		}
  		if ($sql) {
  			return true;
  		}else{
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
  		$con=parent::conectar();
		$sql=mysqli_query($con, "SELECT `idpersona`, `nombres`, `apellidop`, `apellidom`, `direccion`, `dni`, `correo`, `tipo_usuario` FROM `usuario` WHERE idpersona='$idusuario'");
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

 }