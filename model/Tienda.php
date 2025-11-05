<?php

require_once "../config/conexion.php";

/**
 * 
 */
class Tienda extends conexion
{
    public function listatienda()
    {
        $con = parent::conectar();
        $sql = mysqli_query($con, "SELECT idtienda, numruc, nombres_per, apellidop_per, apellidom_per FROM tienda");
        return $sql;
    }

    public function insertar($ruc, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona, $celular)
    {
        $con = parent::conectar();
        $sql = mysqli_query($con, "INSERT INTO tienda (numruc, nombres_per, apellidop_per, apellidom_per, ubic_tienda, area_tienda, latitud, longitud, id_zona, celular) VALUES ('$ruc', '$nombres', '$apellidop', '$apellidom', '$ubicacion', '$area', '$latitud', '$longitud','$zona', '$celular')");
        if ($sql) {
            return true;
        } else {
            return false;
        }
    }

    public function editar($idtienda, $ruc, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona,$celular)
    {
        $con = parent::conectar();
        $sql = mysqli_query($con, "UPDATE tienda SET numruc = '$ruc', nombres_per = '$nombres', apellidop_per = '$apellidop', apellidom_per = '$apellidom', ubic_tienda = '$ubicacion', area_tienda = '$area', latitud = '$latitud', longitud = '$longitud', id_zona = '$zona', celular= '$celular' WHERE idtienda = '$idtienda'");
        if ($sql) {
            return true;
        } else {
            return false;
        }
    }

    public function mostraredit($idtienda)
    {
        $con = parent::conectar();
        $sql = mysqli_query($con, "SELECT t.idtienda, t.numruc, t.nombres_per, t.apellidop_per, t.apellidom_per, t.ubic_tienda, t.area_tienda, t.latitud, t.longitud, t.celular, t.id_zona FROM tienda t WHERE t.idtienda = '$idtienda'");
        return $sql;
    }
}
?>
