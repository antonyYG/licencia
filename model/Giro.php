<?php 
require_once "../config/conexion.php";

 /**
  * 
  */
 class Giro extends conexion
 {
 	public function listagiro(){
 		$con=parent::conectar();
 		$sql=mysqli_query($con, "SELECT idgiro,nombregiro FROM giro");
 		return $sql;
 	}
 	public function insertar($giro){
		$con = parent::conectar();

		// Verificar si ya existe
		$check = mysqli_query($con, "SELECT COUNT(*) as total FROM giro WHERE nombregiro='$giro'");
		$data = mysqli_fetch_assoc($check);

		if ($data['total'] > 0) {
			return "existe"; // Retorna mensaje especial
		}

		// Insertar si no existe
		$sql = mysqli_query($con, "INSERT INTO giro (nombregiro) VALUES('$giro')");
		return $sql ? true : false;
	}

 	public function editar($idgiro,$giro){
 		$con=parent::conectar();
 		$sql=mysqli_query($con, "UPDATE giro SET nombregiro='$giro' WHERE idgiro='$idgiro'");
 		if ($sql) {
 			return true;
 		}else{
 			return false;
 		}
 	}

 	public function eliminar($idgiro){
 		$con=parent::conectar();
 		$sql=mysqli_query($con, "DELETE FROM giro WHERE idgiro='$idgiro'");
 		if ($sql) {
 			return true;
 		}else{
 			return false;
 		}
 	}
 }
