    <?php 
session_start();
if (!isset($_SESSION['nombres']) || empty($_SESSION['nombres'])) {
	echo "Debe ingresar al sistema correctamente";
}else{
//	require_once "borderl.php";
require_once "fpdf/fpdf.php";
require_once "../phpqrcode/qrlib.php";
require_once "../../config/conexion.php";
$con=new conexion();
$conexion=$con->conectar();
$idtramite=$_GET['idtramite'];

$tramite=mysqli_query($conexion, "SELECT l.idlicencia,l.exp_num,l.idtienda,t.numruc,t.nombres_per,t.apellidop_per,t.apellidom_per,t.ubic_tienda,t.area_tienda,l.idgiro,g.nombregiro,l.nombre_comercial,l.numrecibo_tesoreria,l.num_resolucion,l.vigencia_lic,l.fecha_ingreso,l.fecha_expedicion,l.qr,l.condicion,l.tipo_lic,l.num_tipolic,l.NumResITSE,l.expedicionITSE,l.vigenciaITSE FROM `licencia` l INNER JOIN tienda t ON l.idtienda = t.idtienda
			INNER JOIN giro g ON l.idgiro = g.idgiro WHERE l.exp_num='$idtramite' limit 1");
$resulta=mysqli_fetch_array($tramite);

// Construir el contenido del QR con los campos solicitados
$data = "Número de Expediente: " . $resulta['exp_num'] . PHP_EOL;
$data .= "Otorgado a: " . $resulta['nombres_per'] . ' ' . $resulta['apellidop_per'] . ' ' . $resulta['apellidom_per'] . PHP_EOL;
$data .= "Número de RUC: " . $resulta['numruc'] . PHP_EOL;
$data .= "Establecimiento ubicado en: " . $resulta['ubic_tienda'] . PHP_EOL;
$data .= "Giro o Comercio: " . $resulta['nombregiro'] . PHP_EOL;
$data .= "Vigencia: " . ($resulta['vigencia_lic'] === "0001-01-01" ? "Indeterminado" : $resulta['vigencia_lic']) . PHP_EOL;
$data .= "Estado: " . ($resulta['condicion'] == '1' ? "Activo" : "Inactivo") . PHP_EOL;
$data .= "Número de Resolución: " . $resulta['num_resolucion'] . PHP_EOL;
$data .= "Número de Resolución ITSE: " . $resulta['NumResITSE'] . PHP_EOL;

// Generar el QR temporalmente
$temp_qr = 'temp_qr.png';
QRcode::png($data, $temp_qr, 'H', 32, 5);



$pdf=new FPDF('P','mm','A4');
$pdf->AddPage(); //agregar una nueva pagina

// Agregar imagen de fondo
$pdf->Image('membretada.jpeg', 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

$pdf->SetFont('Arial','B',30);
	$pdf->SetXY(25,64);
	$pdf->Cell(170,10, strtoupper(utf8_decode("licencia de funcionamiento")),0,1,'C');

$pdf->SetFont('Arial','B',30);
if ($resulta['tipo_lic']=='1') {
	$pdf->SetXY(53,75);
	$pdf->Cell(119,12, strtoupper(utf8_decode("indeterminada")),0,1,'C');
}else{
	$pdf->SetXY(53,75);
	$pdf->Cell(115,12, strtoupper(utf8_decode("temporal")),0,1,'C');
}

if ($resulta['condicion']=='0') {
	$pdf->Image('../../files/img/anulado.png', 40,40,140); 
}

$pdf->SetFont('Arial','B',15);
$pdf->SetXY(60,90);
$pdf->SetTextColor(255,255,255); // color del texto
//$pdf->SetFillColor(255,0,0); // relleno de la celda
$pdf->Cell(90,6,utf8_decode('N° '.$resulta['num_tipolic'].'-'.date('Y').'-GDE-MDCH'),0,1,'C',$pdf->Image('../../files/img/relleno.jpg', 57, 89,96,8)); 

$pdf->SetTextColor(0,0,0);
$pdf->SetXY(85,102);
$pdf->Cell(40,5,'OTORGADO A:',0,1,'C');

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',18);

$pdf->SetXY(28,112);
$pdf->Cell(160,20,'',1,1);

if (strlen(strtoupper(utf8_decode($resulta['nombres_per'].' '.$resulta['apellidop_per'].' '.$resulta['apellidom_per'])))<='30') {
	$pdf->SetXY(48,118);
	$pdf->Cell(128,6,strtoupper(utf8_decode($resulta['nombres_per'].' '.$resulta['apellidop_per'].' '.$resulta['apellidom_per'])),0,1,'C');
}else{
	$pdf->SetXY(52,115);
	$pdf->MultiCell(120,6,strtoupper(utf8_decode($resulta['nombres_per'].' '.$resulta['apellidop_per'].' '.$resulta['apellidom_per'])),0,'C',0);
}

$pdf->SetFont('Arial','B',8);
$pdf->SetXY(32,136);
$pdf->Cell(12,4,utf8_decode('EXP. N:'),1,1,'L');
$pdf->SetXY(87,136);
$pdf->Cell(47,4,utf8_decode('MOTIVO:'),1,1,'L');
$pdf->SetXY(134,136);
$pdf->Cell(47,4,utf8_decode('APERTURA:'),1,1,'L');

$pdf->SetXY(32,146);
$pdf->Cell(52,4,utf8_decode('Nº DE RUC'),0,1,'L');
$pdf->SetXY(32,151);
$pdf->Cell(52,4,utf8_decode('ESTABLECIMIENTO UBICADO EN'),0,1,'L');
$pdf->SetXY(32,156);
$pdf->Cell(52,4,utf8_decode('GIRO O COMERCIO'),0,1,'L');
$pdf->SetXY(32,161);
$pdf->Cell(52,4,utf8_decode('NOMBRE COMERCIAL'),0,1,'L');
$pdf->SetXY(32,166);
$pdf->Cell(52,4,utf8_decode('AREA DEL LOCAL'),0,1,'L');
$pdf->SetXY(32,171);
$pdf->Cell(52,4,utf8_decode('Nº DE RECIBO DE TESORERIA'),0,1,'L');
$pdf->SetXY(32,176);
$pdf->Cell(52,4,utf8_decode('TIPO DE ANUNCIO'),0,1,'L');
$pdf->SetXY(32,181);
$pdf->Cell(52,4,utf8_decode('Nº DE RESOLUCIÓN DE LICENCIA'),0,1,'L');
$pdf->SetXY(32,186);
$pdf->Cell(52,4,utf8_decode('FECHA DE EXPEDICIÓN DE LICENCIA'),0,1,'L');
$pdf->SetXY(32,191);
$pdf->Cell(52,4,utf8_decode('VIGENCIA DE LICENCIA'),0,1,'L');
$pdf->SetXY(32,196);
$pdf->Cell(52,4,utf8_decode('Nº DE RESOLUCIÓN DE ITSE'),0,1,'L');
$pdf->SetXY(32,201);
$pdf->Cell(52,4,utf8_decode('FECHA DE EXPEDICIÓN DE ITSE'),0,1,'L');
$pdf->SetXY(32,206);
$pdf->Cell(52,4,utf8_decode('FECHA DE VIGENCIA DE ITSE'),0,1,'L');
$pdf->SetXY(32,211);
$pdf->Cell(52,4,utf8_decode('VIGENCIA DE ITSE'),0,1,'L');

$pdf->SetFont('Arial','',8);
$pdf->SetXY(44,136);
$pdf->Cell(43,4,utf8_decode($resulta['exp_num']),1,1,'L');
$pdf->SetXY(84,146);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['numruc']),0,1,'L');
$pdf->SetXY(84,151);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['ubic_tienda']),0,1,'L');
$pdf->SetXY(84,156);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['nombregiro']),0,1,'L');
$pdf->SetXY(84,161);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['nombre_comercial']),0,1,'L');
$pdf->SetXY(84,166);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['area_tienda']),0,1,'L');
$pdf->SetXY(84,171);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['numrecibo_tesoreria']),0,1,'L');
$pdf->SetXY(84,176);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['fecha_expedicion']),0,1,'L');
$pdf->SetXY(84,181);
$pdf->Cell(96,4,utf8_decode(': N° '.$resulta['num_resolucion'].'-'.date('Y').'-GDET-MPCH'),0,1,'L');
$pdf->SetXY(84,186);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['fecha_ingreso']),0,1,'L');
$pdf->SetXY(84,191);
$pdf->Cell(96, 4, utf8_decode(': ' . ($resulta['vigencia_lic'] === "0001-01-01" ? "Indeterminado" : $resulta['vigencia_lic'])), 0, 1, 'L');
// Formatear las fechas de ITSE
$expedicion_itse_formatted = ($resulta['expedicionITSE'] == '0000-00-00' || empty($resulta['expedicionITSE'])) ? 'No especificado' : date('d/m/Y', strtotime($resulta['expedicionITSE']));
$vigencia_itse_formatted = ($resulta['vigenciaITSE'] == '0000-00-00' || empty($resulta['vigenciaITSE'])) ? 'No especificado' : date('d/m/Y', strtotime($resulta['vigenciaITSE']));

$pdf->SetXY(84,196);
$pdf->Cell(90,4,utf8_decode(': N° '.$resulta['NumResITSE']),0,1,'L');
$pdf->SetXY(84,201);
$pdf->Cell(90,4,utf8_decode(': '.$expedicion_itse_formatted),0,1,'L');
$pdf->SetXY(84,206);
$pdf->Cell(90,4,utf8_decode(': '.$vigencia_itse_formatted),0,1,'L');
$pdf->SetXY(84, 211);

// Obtener la fecha actual
$fecha_actual = new DateTime();

// Obtener las fechas de expedición y vigencia del ITSE
if ($resulta['expedicionITSE'] != '0000-00-00' && !empty($resulta['expedicionITSE'])) {
    $expedicion_itse = new DateTime($resulta['expedicionITSE']);
} else {
    $expedicion_itse = null;
}
if ($resulta['vigenciaITSE'] != '0000-00-00' && !empty($resulta['vigenciaITSE'])) {
    $vigencia_itse = new DateTime($resulta['vigenciaITSE']);
} else {
    $vigencia_itse = null;
}

// Calcular la diferencia entre las fechas
if ($expedicion_itse && $vigencia_itse) {
    $diferencia = $expedicion_itse->diff($vigencia_itse);

    // Calcular los días totales de la diferencia
    $dias_totales = $diferencia->days;

    // Calcular los días restantes a partir de la fecha actual
    $dias_restantes = $vigencia_itse->diff($fecha_actual)->days;

    // Verificar si la fecha de vigencia ya ha pasado
    if ($dias_restantes < 0) {
        $dias_restantes = 0;
    }

    // Calcular los años y los días restantes
    $anios_restantes = floor($dias_restantes / 365);
    $dias_restantes = $dias_restantes % 365;

    // Construir el resultado
    $resultado = '';
    if ($anios_restantes > 0) {
        $resultado .= $anios_restantes . ' año(s) ';
    }
    if ($dias_restantes > 0) {
        $resultado .= $dias_restantes . ' día(s)';
    }
} else {
    $resultado = 'No especificado';
}

$pdf->Cell(90, 4,utf8_decode(': ' . $resultado), 0, 1, 'L');



$pdf->SetXY(134, 216);
date_default_timezone_set('America/Lima');
$meses = array(
    'January' => 'enero',
    'February' => 'febrero',
    'March' => 'marzo',
    'April' => 'abril',
    'May' => 'mayo',
    'June' => 'junio',
    'July' => 'julio',
    'August' => 'agosto',
    'September' => 'septiembre',
    'October' => 'octubre',
    'November' => 'noviembre',
    'December' => 'diciembre'
);
$fecha_actual = strftime("Chilca, " . '%d de %B del %Y');
$fecha_actual = strtr($fecha_actual, $meses);
$pdf->Cell(52, 4, utf8_decode($fecha_actual), 0, 0, 'L');

$pdf->Image($temp_qr, 162,233,32);
	//izquierda o derecha/arriba o abajo/tamaño de imagen

// Eliminar el archivo QR temporal después de usarlo
unlink($temp_qr);

$pdf->Output('Licencia.pdf', 'I');
	
}	

