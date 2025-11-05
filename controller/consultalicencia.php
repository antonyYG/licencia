<?php 

require_once "../model/RegistroTramite.php";

$licencia=new RegistroTramite();
$idtramite=isset($_POST['idtramite'])? limpiar($_POST['idtramite']): "";
switch ($_GET['boton']) {
	case 'buscar':
		$lis='';
				$tramita=$licencia->busquedatramite($idtramite);
				if ($row=$tramita->num_rows > 0) {
					while ($lista = mysqli_fetch_array($tramita)) {
	$lis .= '
	<tr>
		<th width="40%">N° de Doc</th>
		<td>' . $lista['num_tipolic'] . '</td>
	</tr>
	<tr>
		<th width="40%">Otorgado a DON(ÑA)</th>
		<td>' . $lista['nombres_per'] . ' ' . $lista['apellidop_per'] . ' ' . $lista['apellidom_per'] . '</td>
	</tr>
	<tr>
		<th width="40%">EXPEDIENTE</th>
		<td>' . $lista['exp_num'] . '</td>
	</tr>
	<tr>
		<th>N° DE RUC</th>
		<td>' . $lista['numruc'] . '</td>
	</tr>
	<tr>
		<th>ESTABLECIMIENTO UBICADO EN</th>
		<td>' . $lista['ubic_tienda'] . '</td>
	</tr>
	<tr>
		<th>GIRO O COMERCIO</th>
		<td>' . $lista['nombregiro'] . '</td>
	</tr>
	<tr>
		<th>NOMBRE COMERCIAL</th>
		<td>' . $lista['nombre_comercial'] . '</td>
	</tr>
	<tr>
		<th>AREA COMERCIAL</th>
		<td>' . $lista['area_tienda'] . '</td>
	</tr>
	<tr>
		<th>N° DE RECIBO DE TESORERIA</th>
		<td>' . $lista['numrecibo_tesoreria'] . '</td>
	</tr>
	<tr>
		<th>VIGENCIA</th>
		<td>' . $lista['vigencia_lic'] . '</td>
	</tr>
	<tr>
		<th>FECHA DE EXPEDICIÓN</th>
		<td>' . $lista['fecha_ingreso'] . '</td>
	</tr>
	<tr>
		<th>Tipo anuncio</th>
		<td>' . $lista['fecha_expedicion'] . '</td>
	</tr>
	<tr>
		<th>N° DE RESOLUCIÓN DE LICENCIA</th>
		<td>' . $lista['num_resolucion'] . '</td>
	</tr>
	<tr>
		<th>ESTADO LICENCIA</th>
		<td>';
	if ($lista['condicion'] == '1') {
		$lis .= 'activo';
	} else {
		$lis .= 'inactivo';
	}
	$lis .= '</td>
	</tr>
	<tr>
		<th>N° DE RESOLUCIÓN ITSE</th>
		<td>' . $lista['NumResITSE'] . '</td>
	</tr>
	<tr>
	<th>ESTADO ITSE</th>
    <td>';
    
// V E R I F I C A C I Ó N   C O R R E G I D A
// Soluciona el Warning: Undefined array key "estadoitse"
if (isset($lista['estadoitse']) && $lista['estadoitse'] == '1') {
    $lis .= 'activo';
} else {
    // Si no existe la clave O si existe pero no es '1' (es '0' o cualquier otro valor)
    $lis .= 'inactivo'; 
}

$lis .= '</td>
    </tr>';
}
	          
				}else{
					echo '<tr><td> <center><h4>No se pudo encontrar la licencia</h4></center></td></tr>';
				}
			echo $lis;
		break;
	
	default:
		// code...
		break;
}