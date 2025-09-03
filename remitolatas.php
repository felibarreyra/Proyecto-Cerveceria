<?php
require_once __DIR__ . '/controllers/lata.controller.php';
require_once __DIR__ . '/controllers/lugar.controller.php';
require_once __DIR__ . '/controllers/variedad.controller.php';
require('./pdf/fpdf/fpdf.php');

$lataController = new LataController();
$lugarController = new lugarController();
$variedadController = new variedadController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_lugar'], $_GET['fecha'])) {
    $id_lugar = $_GET['id_lugar'];
    $fecha = $_GET['fecha'];

    // Traigo cliente
    $cliente = $lugarController->getNombreLugarById($id_lugar);


    // Traigo las ventas de esa fecha y cliente
    $ventas = $lataController->getVentasPorClienteYFecha($id_lugar, $fecha);

    // Armar PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image('./img/logo.jpg', 10, 10, 40);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Remito de Entrega - Latas', 0, 1, 'C');
    $pdf->Ln(28);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(100, 10, 'Cliente: ' . htmlspecialchars($cliente), 0, 1);
    $pdf->Cell(100, 10, 'Fecha: ' . htmlspecialchars($fecha), 0, 1);
    $pdf->Ln(5);

    // Encabezados tabla
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(70, 10, 'Variedad', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(45, 10, 'Precio Unitario', 1, 0, 'C');
    $pdf->Cell(45, 10, 'Subtotal', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    $totalGeneral = 0;

    foreach ($ventas as $venta) {
        $subtotal = $venta['cantidad'] * $venta['precio_unitario'];
        $totalGeneral += $subtotal;

        $pdf->Cell(70, 10, htmlspecialchars($venta['variedad']), 1, 0, 'C');
        $pdf->Cell(30, 10, $venta['cantidad'], 1, 0, 'C');
        $pdf->Cell(45, 10, '$' . number_format($venta['precio_unitario'], 2), 1, 0, 'C');
        $pdf->Cell(45, 10, '$' . number_format($subtotal, 2), 1, 1, 'C');
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(145, 10, 'PRECIO TOTAL (sin IVA)', 1, 0, 'R');
    $pdf->Cell(45, 10, '$' . number_format($totalGeneral, 2), 1, 1, 'C');

    if (ob_get_length()) ob_end_clean();
    $pdf->Output('D', 'LATAS - ' . $cliente . '_' . $fecha . '.pdf');
    exit;
} else {
    die("❌ Parámetros inválidos para generar el remito.");
}
