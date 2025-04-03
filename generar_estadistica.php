<?php require_once __DIR__ . '/controllers/barril.controller.php';
require_once __DIR__ . '/controllers/variedad.controller.php';
require('./pdf/fpdf/fpdf.php');

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();

// Agregar imagen
$pdf->Image('./img/logo.jpg', 10, 10, 40);

// Título del documento
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(200, 10, 'Estadistica de Camara', 0, 1, 'C');
$pdf->Ln(28);

// Obtener los barriles en cámara actualmente
$barrilController = new BarrilController();
$variedadController = new variedadController();
$barriles = $barrilController->obtenerBarrilesEnCamaraActuales();

// Encabezado de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Variedad', 1, 0, 'C');
$pdf->Cell(40, 10, 'Tipo de Barril', 1, 0, 'C');
$pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
$pdf->Cell(30, 10, 'Total Litros', 1, 1, 'C');

// Contenido de la tabla
$pdf->SetFont('Arial', '', 10);
$totalBarriles = 0;
$totalLitros = 0;

foreach ($barriles as $barril) {
    $variedadNombre = $variedadController->getNombreVariedad($barril->id_variedad);
    $totalLitrosFila = $barril->litros * $barril->cantidad;

    $pdf->Cell(50, 10, $variedadNombre, 1, 0, 'C');
    $pdf->Cell(40, 10, $barril->litros . 'L', 1, 0, 'C');
    $pdf->Cell(30, 10, $barril->cantidad, 1, 0, 'C');
    $pdf->Cell(30, 10, $totalLitrosFila . 'L', 1, 1, 'C');

    $totalBarriles += $barril->cantidad;
    $totalLitros += $totalLitrosFila;
}

// Total general
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'TOTAL BARRILES', 1, 0, 'R');
$pdf->Cell(30, 10, $totalBarriles, 1, 0, 'C');
$pdf->Cell(30, 10, $totalLitros . 'L', 1, 1, 'C');

// Limpiar buffer y generar PDF
if (ob_get_length()) ob_end_clean();
$pdf->Output('D', 'estadisticas_camara_actual.pdf');
exit;
?>
