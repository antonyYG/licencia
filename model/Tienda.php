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
        $sql = mysqli_query($con, "SELECT idtienda, numruc, dni, nombres_per, apellidop_per, apellidom_per FROM tienda");
        return $sql;
    }

    public function insertar($ruc, $dni, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona, $celular)
    {
        $con = parent::conectar();
        $sql = mysqli_query($con, "INSERT INTO tienda (numruc, nombres_per, apellidop_per, apellidom_per, ubic_tienda, area_tienda, dni, latitud, longitud, id_zona, celular) VALUES ('$ruc', '$nombres', '$apellidop', '$apellidom', '$ubicacion', '$area', '$dni', '$latitud', '$longitud','$zona', '$celular')");
        if ($sql) {
            return true;
        } else {
            return false;
        }
    }

    public function editar($idtienda, $ruc, $dni, $nombres, $apellidop, $apellidom, $ubicacion, $area, $latitud, $longitud, $zona,$celular)
    {
        $con = parent::conectar();
        $sql = mysqli_query($con, "UPDATE tienda SET numruc = '$ruc', nombres_per = '$nombres', apellidop_per = '$apellidop', apellidom_per = '$apellidom', ubic_tienda = '$ubicacion', area_tienda = '$area', dni = '$dni', latitud = '$latitud', longitud = '$longitud', id_zona = '$zona', celular= '$celular' WHERE idtienda = '$idtienda'");
        if ($sql) {
            return true;
        } else {
            return false;
        }
    }

    public function mostraredit($idtienda)
    {
        $con = parent::conectar();
        $sql = mysqli_query($con, "SELECT t.idtienda, t.numruc, t.dni, t.nombres_per, t.apellidop_per, t.apellidom_per, t.ubic_tienda, t.area_tienda, t.latitud, t.longitud, t.celular, t.id_zona FROM tienda t WHERE t.idtienda = '$idtienda'");
        return $sql;
    }

    public function eliminar($idtienda)
    {
        $con = parent::conectar();

        // Verificar si hay licencias asociadas
        $check_licencias = mysqli_query($con, "SELECT COUNT(*) as count FROM licencia WHERE idtienda = '$idtienda'");
        $row_licencias = mysqli_fetch_assoc($check_licencias);
        if ($row_licencias['count'] > 0) {
            return "dependencias"; // Código especial para dependencias
        }

        // Verificar si hay intervenciones asociadas
        $check_intervenciones = mysqli_query($con, "SELECT COUNT(*) as count FROM intervenciones WHERE idtienda = '$idtienda'");
        $row_intervenciones = mysqli_fetch_assoc($check_intervenciones);
        if ($row_intervenciones['count'] > 0) {
            return "dependencias"; // Código especial para dependencias
        }

        // Si no hay dependencias, proceder con la eliminación
        $sql = mysqli_query($con, "DELETE FROM tienda WHERE idtienda = '$idtienda'");
        if ($sql) {
            return true;
        } else {
            return false;
        }
    }
}
?>
