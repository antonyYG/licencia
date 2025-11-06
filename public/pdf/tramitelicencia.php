<?php
ob_start(); // evita errores de "headers already sent"
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['nombres']) || empty($_SESSION['nombres'])) {
    die("Debe ingresar al sistema correctamente");
}

require_once "fpdf/fpdf.php";
require_once "../../config/conexion.php";

$con = new conexion();
$conexion = $con->conectar();
$idtramite = $_GET['idtramite'] ?? '';

$tramite = mysqli_query($conexion, "
    SELECT l.idlicencia, l.exp_num, l.idtienda, t.numruc, t.nombres_per, t.apellidop_per, t.apellidom_per,
           t.ubic_tienda, t.area_tienda, l.idgiro, g.nombregiro, l.nombre_comercial,
           l.numrecibo_tesoreria, l.num_resolucion, l.vigencia_lic, l.fecha_ingreso,
           l.fecha_expedicion, l.qr, l.condicion, l.tipo_lic, l.num_tipolic,
           l.NumResITSE, l.expedicionITSE, l.vigenciaITSE
    FROM licencia l
    INNER JOIN tienda t ON l.idtienda = t.idtienda
    INNER JOIN giro g ON l.idgiro = g.idgiro
    WHERE l.exp_num = '$idtramite'
    LIMIT 1
");

$resulta = mysqli_fetch_array($tramite);

if (!$resulta) {
    die("No se encontró el trámite solicitado.");
}

function iso($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->Image('membretada.jpeg', 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

// Título
$pdf->SetFont('Arial', 'B', 30);
$pdf->SetXY(25, 64);
$pdf->Cell(170, 10, strtoupper(iso("LICENCIA DE FUNCIONAMIENTO")), 0, 1, 'C');

// Tipo de licencia
$pdf->SetFont('Arial', 'B', 30);
$pdf->SetXY(53, 75);
if ($resulta['tipo_lic'] == '1') {
    $pdf->Cell(119, 12, strtoupper(iso("INDETERMINADA")), 0, 1, 'C');
} else {
    $pdf->Cell(115, 12, strtoupper(iso("TEMPORAL")), 0, 1, 'C');
}

// Marca de anulado
if ($resulta['condicion'] == '0') {
    $pdf->Image('../../files/img/anulado.png', 40, 40, 140);
}

// Número de licencia
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetXY(60, 90);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(
    90,
    6,
    iso('N° ' . $resulta['num_tipolic'] . '-' . date('Y') . '-GDE-MDCH'),
    0,
    1,
    'C',
    $pdf->Image('../../files/img/relleno.jpg', 57, 89, 96, 8)
);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(85, 102);
$pdf->Cell(40, 5, 'OTORGADO A:', 0, 1, 'C');

// Nombre del titular
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetXY(28, 112);
$pdf->Cell(160, 20, '', 1, 1);
$nombreCompleto = strtoupper(iso($resulta['nombres_per'] . ' ' . $resulta['apellidop_per'] . ' ' . $resulta['apellidom_per']));
if (strlen($nombreCompleto) <= 30) {
    $pdf->SetXY(48, 118);
    $pdf->Cell(128, 6, $nombreCompleto, 0, 1, 'C');
} else {
    $pdf->SetXY(52, 115);
    $pdf->MultiCell(120, 6, $nombreCompleto, 0, 'C', 0);
}

// Etiquetas
$pdf->SetFont('Arial', 'B', 8);
$labels = [
    'EXP. N°' => 136,
    'Nº DE RUC' => 146,
    'ESTABLECIMIENTO UBICADO EN' => 151,
    'GIRO O COMERCIO' => 156,
    'NOMBRE COMERCIAL' => 161,
    'AREA DEL LOCAL' => 166,
    'Nº DE RECIBO DE TESORERIA' => 171,
    'TIPO DE ANUNCIO' => 176,
    'Nº DE RESOLUCIÓN DE LICENCIA' => 181,
    'FECHA DE EXPEDICIÓN DE LICENCIA' => 186,
    'VIGENCIA DE LICENCIA' => 191,
    'Nº DE RESOLUCIÓN DE ITSE' => 196,
    'FECHA DE EXPEDICIÓN DE ITSE' => 201,
    'FECHA DE VIGENCIA DE ITSE' => 206,
    'VIGENCIA DE ITSE' => 211
];

foreach ($labels as $text => $y) {
    $pdf->SetXY(32, $y);
    $pdf->Cell(52, 4, iso($text), 0, 1, 'L');
}

// Valores
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(44, 136);
$pdf->Cell(43, 4, iso($resulta['exp_num']), 1, 1, 'L');

$pdf->SetXY(84, 146); $pdf->Cell(96, 4, iso(': ' . $resulta['numruc']), 0, 1, 'L');
$pdf->SetXY(84, 151); $pdf->Cell(96, 4, iso(': ' . $resulta['ubic_tienda']), 0, 1, 'L');
$pdf->SetXY(84, 156); $pdf->Cell(96, 4, iso(': ' . $resulta['nombregiro']), 0, 1, 'L');
$pdf->SetXY(84, 161); $pdf->Cell(96, 4, iso(': ' . $resulta['nombre_comercial']), 0, 1, 'L');
$pdf->SetXY(84, 166); $pdf->Cell(96, 4, iso(': ' . $resulta['area_tienda']), 0, 1, 'L');
$pdf->SetXY(84, 171); $pdf->Cell(96, 4, iso(': ' . $resulta['numrecibo_tesoreria']), 0, 1, 'L');
$pdf->SetXY(84, 176); $pdf->Cell(96, 4, iso(': ' . $resulta['fecha_expedicion']), 0, 1, 'L');
$pdf->SetXY(84, 181); $pdf->Cell(96, 4, iso(': N° ' . $resulta['num_resolucion'] . '-' . date('Y') . '-GDET-MPCH'), 0, 1, 'L');
$pdf->SetXY(84, 186); $pdf->Cell(96, 4, iso(': ' . $resulta['fecha_ingreso']), 0, 1, 'L');
$pdf->SetXY(84, 191);
$pdf->Cell(96, 4, iso(': ' . ($resulta['vigencia_lic'] === "0001-01-01" ? "Indeterminado" : $resulta['vigencia_lic'])), 0, 1, 'L');
$pdf->SetXY(84, 196); $pdf->Cell(90, 4, iso(': N° ' . $resulta['NumResITSE']), 0, 1, 'L');
$pdf->SetXY(84, 201); $pdf->Cell(90, 4, iso(': ' . $resulta['expedicionITSE']), 0, 1, 'L');
$pdf->SetXY(84, 206); $pdf->Cell(90, 4, iso(': ' . $resulta['vigenciaITSE']), 0, 1, 'L');

// Cálculo de vigencia de ITSE
$pdf->SetXY(84, 211);
$fecha_actual = new DateTime();
$expedicion_itse = new DateTime($resulta['expedicionITSE']);
$vigencia_itse = new DateTime($resulta['vigenciaITSE']);
$diferencia = $expedicion_itse->diff($vigencia_itse);
$dias_totales = $diferencia->days;
$dias_restantes = $vigencia_itse->diff($fecha_actual)->days;
if ($dias_restantes < 0) $dias_restantes = 0;
$anios_restantes = floor($dias_restantes / 365);
$dias_restantes = $dias_restantes % 365;
$resultado = '';
if ($anios_restantes > 0) $resultado .= $anios_restantes . ' año(s) ';
if ($dias_restantes > 0) $resultado .= $dias_restantes . ' día(s)';
$pdf->Cell(90, 4, iso(': ' . $resultado), 0, 1, 'L');

// Fecha actual (en español)
$pdf->SetXY(134, 216);
date_default_timezone_set('America/Lima');
$meses = [
    'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
    'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
    'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
    'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
];
$fecha = new DateTime();
$mes_en = $fecha->format('F');
$fecha_actual_texto = 'Chilca, ' . $fecha->format('d') . ' de ' . $meses[$mes_en] . ' del ' . $fecha->format('Y');
$pdf->Cell(52, 4, iso($fecha_actual_texto), 0, 0, 'L');

// Código QR
$pdf->Image('../../files/qr/' . $resulta['qr'], 162, 233, 32);

$pdf->Output('I', 'Licencia.pdf');
ob_end_flush();
