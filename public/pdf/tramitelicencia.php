<?php
require_once "../../vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
if (!isset($_SESSION['nombres']) || empty($_SESSION['nombres'])) {
	echo "Debe ingresar al sistema correctamente";
}else{
//	require_once "borderl.php";
require_once "fpdf/fpdf.php";
require_once "../../config/conexion.php";
$con=new conexion();
$conexion=$con->conectar();
$idtramite=$_GET['idtramite'];

$tramite = mysqli_query($conexion, "
    SELECT 
        l.idlicencia,
        l.exp_num,
        l.idtienda,
        t.numruc,
        t.nombres_per,
        t.apellidop_per,
        t.apellidom_per,
        t.ubic_tienda,
        t.area_tienda,
        t.correo,                        -- ← AGREGAR AQUÍ
        l.idgiro,
        g.nombregiro,
        l.nombre_comercial,
        l.numrecibo_tesoreria,
        l.num_resolucion,
        l.vigencia_lic,
        l.fecha_ingreso,
        l.fecha_expedicion,
        l.qr,
        l.condicion,
        l.tipo_lic,
        l.num_tipolic,
        l.NumResITSE,
        l.EstadoITSE,
        l.expedicionITSE,
        l.vigenciaITSE
        ,l.nivel_riesgo
    FROM licencia l
    INNER JOIN tienda t ON l.idtienda = t.idtienda
    INNER JOIN giro g ON l.idgiro = g.idgiro
    WHERE l.exp_num='$idtramite'
    LIMIT 1
");
$resulta=mysqli_fetch_array($tramite);



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
$pdf->Cell(90,6,utf8_decode('N° '.$resulta['num_tipolic'].'-'.date('Y').'-MDCH/GDEYT-SGC'),0,1,'C',$pdf->Image('../../files/img/relleno.jpg', 57, 89,96,8)); 

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
} else {
    $pdf->SetXY(52,115);
    $pdf->MultiCell(120,6,strtoupper(utf8_decode($resulta['nombres_per'].' '.$resulta['apellidop_per'].' '.$resulta['apellidom_per'])),0,'C',0);
}

// Diseño: etiquetas en negrita a la izquierda, valores a la derecha (siguiendo diseño provisto)
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(32,136);
$pdf->Cell(12,4,utf8_decode('EXP. N:'),1,0,'L');
$pdf->SetXY(87,136);
$pdf->Cell(47,4,utf8_decode('MOTIVO:'),1,0,'L');
$pdf->SetXY(134,136);
$pdf->Cell(47,4,utf8_decode('APERTURA:'),1,0,'L');

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
$pdf->SetXY(84,181);
$pdf->Cell(96,4,utf8_decode(': N° '.$resulta['num_resolucion'].'-'.date('Y').'-MDCH/GDEYT-SGC'),0,1,'L');
$pdf->SetXY(84,186);
$pdf->Cell(96,4,utf8_decode(': '.$resulta['fecha_ingreso']),0,1,'L');
$pdf->SetXY(84,191);
// Mostrar "INDETERMINADA" cuando tipo_lic == '1', si no mostrar la fecha de vigencia (o '-' si no existe)
if (isset($resulta['tipo_lic']) && $resulta['tipo_lic'] == '1') {
    $vigencia_text = 'INDETERMINADA';
} else {
    $vigencia_text = (!empty($resulta['vigencia_lic']) && $resulta['vigencia_lic'] !== '0001-01-01') ? $resulta['vigencia_lic'] : '-';
}
$pdf->Cell(96, 4, utf8_decode(': ' . $vigencia_text), 0, 1, 'L');

$fecha_actual = new DateTime();

// Obtener las fechas de expedición y vigencia del ITSE
$expedicion_itse = new DateTime($resulta['expedicionITSE']);
$vigencia_itse = new DateTime($resulta['vigenciaITSE']);

// Calcular la diferencia entre las fechas
$diferencia = $expedicion_itse->diff($vigencia_itse);

// Calcular los días totales de la diferencia
$dias_totales = $diferencia->days;

// Calcular los días restantes a partir de la fecha actual
$dias_restantes = $vigencia_itse->diff($fecha_actual)->days;

// Verificar si la fecha de vigencia ya ha pasado
if ($fecha_actual > $vigencia_itse) {
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

$pdf->SetXY(84,196);
$pdf->Cell(90,4,utf8_decode(': N° '.$resulta['NumResITSE'].'-'.date('Y').'-MDCH/GDEYT-ODC'),0,1,'L');

$pdf->SetXY(84,201);
$pdf->Cell(90,4,utf8_decode(': '.$resulta['expedicionITSE']),0,1,'L');

$pdf->SetXY(84,206);
$pdf->Cell(90,4,utf8_decode(': '.$resulta['vigenciaITSE']),0,1,'L');

$pdf->SetXY(84,211);
$pdf->Cell(90, 4,utf8_decode(': ' . $resultado), 0, 1, 'L');

// Mostrar Nivel de Riesgo al final (después de Vigencia ITSE)
$pdf->SetXY(32, 216);
$pdf->Cell(52,4,utf8_decode('NIVEL DE RIESGO'),0,1,'L');
$pdf->SetXY(84,216);
$pdf->Cell(90,4,utf8_decode(': '. ($resulta['nivel_riesgo'] ?: '-')),0,1,'L');



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

    $qrDir = __DIR__ . '/../../files/qr/';
    if (!is_dir($qrDir)) { @mkdir($qrDir, 0755, true); }

    // Generar contenido del QR con formato elegante (encabezado + detalles)
    $qrTitle = 'LICENCIA DE FUNCIONAMIENTO';
    $otorgado = trim(($resulta['nombres_per'] ?? '') . ' ' . ($resulta['apellidop_per'] ?? '') . ' ' . ($resulta['apellidom_per'] ?? '')) ?: '-';
    $vigencia_text = (isset($resulta['tipo_lic']) && $resulta['tipo_lic'] == '1') ? 'INDETERMINADA' : ((!empty($resulta['vigencia_lic']) && $resulta['vigencia_lic'] !== '0001-01-01') ? $resulta['vigencia_lic'] : '-');
    $qrDataLines = [
        $qrTitle,
        str_repeat('=', 24),
        'DETALLES DEL TRÁMITE:',
        '- OTORGADO A: ' . $otorgado,
        '- N° DE RUC: ' . ($resulta['numruc'] ?? '-'),
        '- ESTABLECIMIENTO UBICADO EN: ' . ($resulta['ubic_tienda'] ?? '-'),
        '- GIRO O COMERCIO: ' . ($resulta['nombregiro'] ?? '-'),
        '- NOMBRE COMERCIAL: ' . ($resulta['nombre_comercial'] ?? '-'),
        '- AREA DEL LOCAL: ' . ($resulta['area_tienda'] ?? '-'),
        '- N° DE RECIBO DE TESORERIA: ' . ($resulta['numrecibo_tesoreria'] ?? '-'),
        '- N° DE RESOLUCIÓN DE LICENCIA: ' . ($resulta['num_resolucion'] ? ('N° ' . $resulta['num_resolucion'] . '-' . date('Y') . '-MDCH/GDEYT-SGC') : '-'),
        '- FECHA DE EXPEDICIÓN DE LICENCIA: ' . ($resulta['fecha_ingreso'] ?? '-'),
        '- VIGENCIA DE LICENCIA: ' . $vigencia_text,
        '- N° DE RESOLUCIÓN ITSE: ' . ($resulta['NumResITSE'] ?? '-'),
        '- FECHA DE EXPEDICIÓN ITSE: ' . ($resulta['expedicionITSE'] ?? '-'),
        '- FECHA DE VIGENCIA ITSE: ' . ($resulta['vigenciaITSE'] ?? '-'),
        '- VIGENCIA ITSE: ' . ($resulta['vigenciaITSE'] ?? '-'),
        '- NIVEL DE RIESGO: ' . (!empty($resulta['nivel_riesgo']) ? $resulta['nivel_riesgo'] : 'INDETERMINADA'),
        '- ESTADO LICENCIA: ' . ((isset($resulta['condicion']) && $resulta['condicion']=='1') ? 'ACTIVO' : 'INACTIVO')
    ];
    $qrData = implode("\n", $qrDataLines) . "\n";

    // Generar/actualizar archivo QR (si GD disponible)
    require_once __DIR__ . '/../../public/phpqrcode/qrlib.php';
    $qrFilename = ($resulta['exp_num'] ?: 'qr_' . time()) . '.png';
    $qrPath = $qrDir . $qrFilename;
    $gd_ok = function_exists('imagecreate') || extension_loaded('gd');
    if ($gd_ok) {
        QRcode::png($qrData, $qrPath, 'H', 5, 2);
    } else {
        // no GD -> si existe imagen previa la usamos, si no dejamos el que venga
        if (!file_exists($qrPath) && !empty($resulta['qr']) && file_exists($qrDir . $resulta['qr'])) {
            $qrPath = $qrDir . $resulta['qr'];
            $qrFilename = $resulta['qr'];
        }
    }

    $pdf->Image('../../files/qr/' . $qrFilename, 162,233,32);
	//izquierda o derecha/arriba o abajo/tamaño de imagen
    // Guardar PDF en disco usando nombre único si ya existe (00001.pdf, 00001_1.pdf, 00001_2.pdf...)
    $licDir = __DIR__ . '/../../files/licencias/';
    if (!is_dir($licDir)) { @mkdir($licDir, 0755, true); }
    $baseName = $resulta['exp_num'] ?: 'lic_' . time();
    $candidate = $licDir . $baseName . '.pdf';
    if (!file_exists($candidate)) {
        // first-time file: keep simple base name
        $ruta_pdf = $candidate;
    } else {
        // generate incremental suffix + date (e.g. 00001_1_23-11-2025.pdf)
        $i = 1;
        $datePart = date('d-m-Y');
        do {
            $ruta_pdf = $licDir . $baseName . '_' . $i . '_' . $datePart . '.pdf';
            $i++;
        } while (file_exists($ruta_pdf));
    }
    $pdf->Output($ruta_pdf, 'F');

// Enviar el PDF al navegador (inline) para que el usuario lo vea al hacer click en "Generar Licencia"
if (file_exists($ruta_pdf)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($ruta_pdf) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    // Enviar el archivo al navegador
    readfile($ruta_pdf);
    // Nota: el script continuará y tratará de enviar el correo después de entregar el PDF
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host       = 'smtp.gmail.com'; // agregar host SMTP aquí
    $mail->SMTPAuth   = true;
    $mail->Username   = 'TU CORREO'; // agregar usuario SMTP aquí
    $mail->Password   = 'TU CONTRASEÑA'; // agregar contraseña SMTP aquí
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // agregar tipo de encriptación SMTP aquí
    $mail->Port       = 587; // agregar puerto SMTP aquí

    // Opciones para entornos locales (evita errores SSL en desarrollo)
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Correo del ciudadano
    $mail->addAddress($resulta['correo']);  

    $mail->setFrom('TU CORREO', 'Municipalidad Distrital de Chilca');  //NOTA: Cambiar por su correo
    $mail->Subject = 'Licencia de Funcionamiento Entregada';
    $mail->isHTML(true);

    $mail->Body = '
        Estimado vecino,<br><br>
        La Gerencia de Desarrollo Económico y Turismo de la Municipalidad Distrital de Chilca otorga la Licencia de Funcionamiento con los detalles indicados.<br><br>

        Con la presente queda en la obligación de cumplir las siguientes disposiciones:<br>
        • Reglamento de Aplicación de Sanciones Administrativas (RASA)<br>
        • Cuadro Único de Infracciones (CUIS)<br>
        • Ordenanza Municipal N° 388-MDCH/CM<br>
        • Otros<br><br>

        <b>EXHIBIR EN UN LUGAR VISIBLE</b>
    ';
    $mail->AltBody = 'Su licencia está adjunta al correo.';

    // ADJUNTAR PDF
    $mail->addAttachment($ruta_pdf);

    $mail->send();

} catch (Exception $e) {
    error_log("ERROR EMAIL: " . $e->getMessage());
}
}

