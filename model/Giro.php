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
 		$con=parent::conectar();
 		$sql=mysqli_query($con, "INSERT INTO giro (nombregiro) VALUES('$giro')");
 		if ($sql) {
 			return true;
 		}else{
 			return false;
 		}
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
