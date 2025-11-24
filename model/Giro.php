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
 
 	public function existeGiro($giro){
 		$con = parent::conectar();
 		$giro_limpio = mysqli_real_escape_string($con, $giro);
 		$sql = mysqli_query($con, "SELECT COUNT(*) AS total FROM giro WHERE LOWER(nombregiro) = LOWER('$giro_limpio')");
 		$fila = $sql ? $sql->fetch_array() : ["total"=>0];
 		return ((int)$fila['total']) > 0;
 	}
    public function insertar($giro){
        $con=parent::conectar();
        $giro = mysqli_real_escape_string($con, trim($giro));
        $sql=mysqli_query($con, "INSERT INTO giro (nombregiro) VALUES('$giro')");
        if ($sql) {
            return true;
        }else{
            return false;
        }
    }

    public function editar($idgiro,$giro){
        $con=parent::conectar();
        $idgiro = (int)$idgiro;
        $giro = mysqli_real_escape_string($con, trim($giro));
        $sql=mysqli_query($con, "UPDATE giro SET nombregiro='$giro' WHERE idgiro='$idgiro'");
        if ($sql) {
            return true;
        }else{
            return false;
        }
    }

 	public function eliminar($idgiro){
 		$con = parent::conectar();
 		$sql = mysqli_query($con, "DELETE FROM giro WHERE idgiro='$idgiro'");
 		if ($sql) {
 			return true;
 		}else{
 			return false;
 		}
 	}
 }