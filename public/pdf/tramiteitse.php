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

    $tramite = mysqli_query($conexion, "SELECT l.idlicencia,l.exp_num,l.idtienda,t.numruc,t.nombres_per,t.apellidop_per,t.apellidom_per,t.ubic_tienda,t.area_tienda,l.idgiro,g.nombregiro,l.nombre_comercial,l.numrecibo_tesoreria,l.num_resolucion,l.vigencia_lic,l.fecha_ingreso,l.fecha_expedicion,l.qr,l.condicion,l.tipo_lic,l.num_tipolic,l.NumResITSE,l.expedicionITSE,l.vigenciaITSE FROM `licencia` l INNER JOIN tienda t ON l.idtienda = t.idtienda
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
    $pdf->Cell(170, 10, strtoupper(utf8_decode("Autorización de Funcionamiento")), 0, 1, 'C');

    // Número de resolución
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->SetXY(60, 90);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(90, 6, utf8_decode('N° ' . $resulta['NumResITSE'] . '-' . date('Y') . '-GDE-ODC-MPCH'), 0, 1, 'C', $pdf->Image('../../files/img/relleno.jpg', 57, 89, 96, 8));

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

    // Fecha de emisión
    $pdf->SetXY(30, 210);
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

    $pdf->Image('../../files/qr/' . $resulta['qr'], 160, 230, 32);

    $pdf->Output('Certificado_ITSE.pdf', 'I');
}
