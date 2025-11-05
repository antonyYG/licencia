<?php 
require_once "../config/conexion.php";

/**
 * 
 */
class RegistroTramite extends conexion
{
	public function listartramite(){
		$con=parent::conectar();
		$sql=mysqli_query($con, "SELECT l.idlicencia,l.exp_num,l.idtienda,t.numruc,l.nombre_comercial,l.vigencia_lic,l.fecha_ingreso,l.fecha_expedicion,l.condicion,l.tipo_lic,l.NumResITSE,l.EstadoITSE,expedicionITSE,vigenciaITSE FROM `licencia` l INNER JOIN tienda t ON l.idtienda = t.idtienda");
		return $sql;
	}

	public function insertartramite($expediente, $nombres, $giro, $nombrecomercial, $recibotes, $numresolucion, $vigencia, $fechingreso, $fechexpedicion, $qr, $tipolicencia, $numdoc, $numresolucion_itse, $expedicion_itse, $vigencia_itse){
    $con = parent::conectar();
    $inserta = mysqli_query($con, "INSERT INTO `licencia`(`exp_num`, `idtienda`, `idgiro`, `nombre_comercial`, `numrecibo_tesoreria`, `num_resolucion`, `vigencia_lic`, `fecha_ingreso`, `fecha_expedicion`, `qr`, `tipo_lic`, `num_tipolic`, `NumResITSE`, `expedicionITSE`, `vigenciaITSE` ) VALUES ('$expediente', '$nombres', '$giro', '$nombrecomercial', '$recibotes', '$numresolucion', '$vigencia', '$fechingreso', '$fechexpedicion', '$qr', '$tipolicencia', '$numdoc', '$numresolucion_itse', '$expedicion_itse', '$vigencia_itse')");
    if ($inserta) {
        return true;
    } else {
        return false;
    }
}

	public function editartramite($idtramite, $expediente, $nombres, $giro, $recibotes, $vigencia, $fechingreso, $fechexpedicion, $numresolucion, $nombrecomercial, $estado, $tipolicencia, $numdoc, $numresolucion_itse, $estado_itse, $expedicion_itse, $vigencia_itse){
    $con = parent::conectar();
    $sql = mysqli_query($con, "UPDATE `licencia` SET `exp_num`='$expediente', `idtienda`='$nombres', `idgiro`='$giro', `nombre_comercial`='$nombrecomercial', `numrecibo_tesoreria`='$recibotes', `num_resolucion`='$numresolucion', `vigencia_lic`='$vigencia', `fecha_ingreso`='$fechingreso', `fecha_expedicion`='$fechexpedicion', `condicion`='$estado', `tipo_lic`='$tipolicencia', `num_tipolic`='$numdoc', `NumResITSE`='$numresolucion_itse', EstadoITSE='$estado_itse', expedicionITSE='$expedicion_itse', vigenciaITSE='$vigencia_itse' WHERE `idlicencia`='$idtramite'");
    if ($sql) {
        return true;
    } else {
        return false;
    }
}

	public function listartienda(){
		$con=parent::conectar();
		$sql=mysqli_query($con, "SELECT t.idtienda,t.numruc,t.nombres_per,t.apellidop_per,t.apellidom_per,t.ubic_tienda,t.area_tienda FROM `tienda` t");
		return $sql;
	}

	public function listagiro(){
		$con=parent::conectar();
		$sql=mysqli_query($con, "select idgiro,nombregiro from giro");
		return $sql;
	}

	public function ListTramite($idtramite){
		$con=parent::conectar();
		$sql=mysqli_query($con, "SELECT l.idlicencia,l.exp_num,l.idtienda,t.numruc,t.nombres_per,t.apellidop_per,t.apellidom_per,t.ubic_tienda,t.area_tienda,l.idgiro,g.nombregiro,l.nombre_comercial,l.numrecibo_tesoreria,l.num_resolucion,l.vigencia_lic,l.fecha_ingreso,l.fecha_expedicion,l.condicion,l.tipo_lic,l.num_tipolic,l.NumResITSE,l.EstadoITSE,l.expedicionITSE,l.vigenciaITSE FROM `licencia` l INNER JOIN tienda t ON l.idtienda = t.idtienda
			INNER JOIN giro g ON l.idgiro = g.idgiro WHERE idlicencia='$idtramite'");
		return $sql;
	}

	public function busquedatramite($idtramite){
		$con=parent::conectar();
		$sql=mysqli_query($con, "SELECT l.idlicencia,l.exp_num,l.idtienda,t.numruc,t.nombres_per,t.apellidop_per,t.apellidom_per,t.ubic_tienda,t.area_tienda,l.idgiro,g.nombregiro,l.nombre_comercial,l.numrecibo_tesoreria,l.num_resolucion,l.vigencia_lic,l.fecha_ingreso,l.fecha_expedicion,l.condicion,l.tipo_lic,l.num_tipolic,l.NumResITSE,l.EstadoITSE,l.expedicionITSE,l.vigenciaITSE FROM `licencia` l INNER JOIN tienda t ON l.idtienda = t.idtienda
			INNER JOIN giro g ON l.idgiro = g.idgiro WHERE exp_num='$idtramite' limit 1");
		return $sql;
	}

	public function selecciontipolic($tipo){
		$con=parent::conectar();
		$sql=mysqli_query($con, "SELECT num_tipolic FROM licencia WHERE tipo_lic='$tipo' ORDER BY num_tipolic DESC LIMIT 1");
		return $sql;
	}


}


