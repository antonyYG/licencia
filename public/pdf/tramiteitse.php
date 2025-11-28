<?php
session_start();
if (!isset($_SESSION['nombres']) || empty($_SESSION['nombres'])) {
    echo "Debe ingresar al sistema correctamente";
} else {
    require_once "fpdf/fpdf.php";
    require_once "../../config/conexion.php";
    $con = new conexion();
    $conexion = $con->conectar();
    $idtramite = $_GET['idtramite'];

    $tramite = mysqli_query($conexion, "SELECT l.idlicencia,l.exp_num,l.idtienda,t.numruc,t.nombres_per,t.apellidop_per,t.apellidom_per,t.ubic_tienda,t.area_tienda,l.idgiro,g.nombregiro,l.nombre_comercial,l.numrecibo_tesoreria,l.num_resolucion,l.vigencia_lic,l.fecha_ingreso,l.fecha_expedicion,l.qr,l.condicion,l.tipo_lic,l.num_tipolic,l.NumResITSE,l.expedicionITSE,l.vigenciaITSE, l.nivel_riesgo, l.EstadoITSE FROM `licencia` l INNER JOIN tienda t ON l.idtienda = t.idtienda
                INNER JOIN giro g ON l.idgiro = g.idgiro WHERE l.exp_num='$idtramite' limit 1");
    $resulta = mysqli_fetch_array($tramite);

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // Agregar imagen de fondo si existe, o usar membretada.jpeg para certificado
    $pdf->Image('membretada.jpeg', 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

    // Título del certificado
    $pdf->SetFont('Arial', 'B', 30);
    $pdf->SetXY(25, 50);
    $pdf->Cell(170, 10, strtoupper(utf8_decode("Certificado ITSE")), 0, 1, 'C');

    // Subtítulo
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetXY(25, 70);
    $pdf->Cell(170, 10, strtoupper(utf8_decode("Autorizacion de Funcionamiento")), 0, 1, 'C');

    // Número de resolución
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->SetXY(60, 90);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(90, 6, utf8_decode('N° ' . $resulta['NumResITSE'] . '-' . date('Y') . '-MDCH/GDEYT-ODC'), 0, 1, 'C', $pdf->Image('../../files/img/relleno.jpg', 57, 89, 96, 8));

    // Otorgado a
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(85, 105);
    $pdf->Cell(40, 5, 'OTORGADO A:', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetXY(28, 115);
    $pdf->Cell(160, 20, '', 1, 1);

    if (strlen(strtoupper(utf8_decode($resulta['nombres_per'] . ' ' . $resulta['apellidop_per'] . ' ' . $resulta['apellidom_per']))) <= '30') {
        $pdf->SetXY(48, 121);
        $pdf->Cell(128, 6, strtoupper(utf8_decode($resulta['nombres_per'] . ' ' . $resulta['apellidop_per'] . ' ' . $resulta['apellidom_per'])), 0, 1, 'C');
    } else {
        $pdf->SetXY(52, 118);
        $pdf->MultiCell(120, 6, strtoupper(utf8_decode($resulta['nombres_per'] . ' ' . $resulta['apellidop_per'] . ' ' . $resulta['apellidom_per'])), 0, 'C', 0);
    }

    // Detalles del certificado
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetXY(30, 140);
    $pdf->Cell(50, 5, utf8_decode('Nº DE RUC:'), 0, 1, 'L');
    $pdf->SetXY(30, 150);
    $pdf->Cell(50, 5, utf8_decode('ESTABLECIMIENTO:'), 0, 1, 'L');
    $pdf->SetXY(30, 160);
    $pdf->Cell(50, 5, utf8_decode('GIRO:'), 0, 1, 'L');
    $pdf->SetXY(30, 170);
    $pdf->Cell(50, 5, utf8_decode('NOMBRE COMERCIAL:'), 0, 1, 'L');
    $pdf->SetXY(30, 180);
    $pdf->Cell(50, 5, utf8_decode('FECHA DE EXPEDICIÓN:'), 0, 1, 'L');
    $pdf->SetXY(30, 190);
    $pdf->Cell(50, 5, utf8_decode('FECHA DE VIGENCIA:'), 0, 1, 'L');
    $pdf->SetXY(30, 200);
    $pdf->Cell(50, 5, utf8_decode('NIVEL DE RIESGO:'), 0, 1, 'L');

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetXY(80, 140);
    $pdf->Cell(100, 5, utf8_decode(': ' . $resulta['numruc']), 0, 1, 'L');
    $pdf->SetXY(80, 150);
    $pdf->Cell(100, 5, utf8_decode(': ' . $resulta['ubic_tienda']), 0, 1, 'L');
    $pdf->SetXY(80, 160);
    $pdf->Cell(100, 5, utf8_decode(': ' . $resulta['nombregiro']), 0, 1, 'L');
    $pdf->SetXY(80, 170);
    $pdf->Cell(100, 5, utf8_decode(': ' . $resulta['nombre_comercial']), 0, 1, 'L');
    $pdf->SetXY(80, 180);
    $pdf->Cell(100, 5, utf8_decode(': ' . $resulta['expedicionITSE']), 0, 1, 'L');
    $pdf->SetXY(80, 190);
    $pdf->Cell(100, 5, utf8_decode(': ' . $resulta['vigenciaITSE']), 0, 1, 'L');
    $pdf->SetXY(80, 200);
    $pdf->Cell(100, 5, utf8_decode(': ' . (isset($resulta['nivel_riesgo']) && trim($resulta['nivel_riesgo']) !== '' ? $resulta['nivel_riesgo'] : 'INDETERMINADA')), 0, 1, 'L');

    // Fecha de emisión
    // Mover la fecha de emisión y el QR un poco hacia abajo para dejar espacio al nuevo campo
    $pdf->SetXY(30, 220);
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
    $pdf->Cell(150, 5, utf8_decode('Emitido en ' . $fecha_actual), 0, 0, 'L');

    $qrDir = __DIR__ . '/../../files/qr/';
    if (!is_dir($qrDir)) { @mkdir($qrDir, 0755, true); }

    // Generar contenido QR consistente con la vista de consulta (misma información)
    $qrData  = "N° Doc: " . ($resulta['num_tipolic'] ?? '-') . "\n";
    $qrData .= "Otorgado a: " . trim(($resulta['nombres_per'] ?? '') . ' ' . ($resulta['apellidop_per'] ?? '') . ' ' . ($resulta['apellidom_per'] ?? '')) . "\n";
    $qrData .= "Expediente: " . ($resulta['exp_num'] ?? '-') . "\n";
    $qrData .= "N° RUC: " . ($resulta['numruc'] ?? '-') . "\n";
    $qrData .= "Establecimiento: " . ($resulta['ubic_tienda'] ?? '-') . "\n";
    $qrData .= "Giro: " . ($resulta['nombregiro'] ?? '-') . "\n";
    $qrData .= "Nombre Comercial: " . ($resulta['nombre_comercial'] ?? '-') . "\n";
    $qrData .= "Área: " . ($resulta['area_tienda'] ?? '-') . "\n";
    $qrData .= "Fecha de Expedición ITSE: " . ($resulta['expedicionITSE'] ?? '-') . "\n";
    $qrData .= "Vigencia ITSE: " . ($resulta['vigenciaITSE'] ?? '-') . "\n";
    $qrData .= "Nivel de Riesgo: " . (!empty($resulta['nivel_riesgo']) ? $resulta['nivel_riesgo'] : 'INDETERMINADA') . "\n";
    $qrData .= "Estado ITSE: " . ((isset($resulta['EstadoITSE']) && $resulta['EstadoITSE'] == '1') ? 'ACTIVO' : 'INACTIVO') . "\n";

    require_once __DIR__ . '/../../public/phpqrcode/qrlib.php';
    $qrFilename = ($resulta['exp_num'] ?: 'itse_qr_' . time()) . '.png';
    $qrPath = $qrDir . $qrFilename;
    $gd_ok = function_exists('imagecreate') || extension_loaded('gd');
    if ($gd_ok) {
        QRcode::png($qrData, $qrPath, 'H', 5, 2);
    } else {
        if (!file_exists($qrPath) && !empty($resulta['qr']) && file_exists($qrDir . $resulta['qr'])) {
            $qrPath = $qrDir . $resulta['qr'];
            $qrFilename = $resulta['qr'];
        }
    }

    // Mover QR  hacia arriba respecto a su posición anterior
    $pdf->Image('../../files/qr/' . $qrFilename, 160, 235, 32);

    $pdf->Output('Certificado_ITSE.pdf', 'I');
}
