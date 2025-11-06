<?php 
require_once "config.php";
/**
 * 
 */
class conexion
{
	private $servidor=SERVIDOR;
	private $user=USUARIO;
	private $pass=CONTRASENA;
	private $bd=BD;

	public function conectar(){
		$conexion=mysqli_connect($this->servidor,$this->user,$this->pass,$this->bd);
		if ($conexion) {
			//echo "conectado";
		}else{
			echo "no conectado";
			exit;
		}	
		mysqli_set_charset($conexion, 'utf8');
		return $conexion;
	}
	
} #hoa

	function limpiar($str){
		$con=new conexion;
		$conexion=$con->conectar();
		$str=mysqli_real_escape_string($conexion, trim($str));
		return htmlspecialchars($str);
	}
