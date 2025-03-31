<?php
require_once __DIR__ . '/controllers/barril.controller.php';
require('./pdf/fpdf/fpdf.php');

// Verificar si se enviaron los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente'], $_POST['fecha'])) {
    $cliente = $_POST['cliente'];
    $fecha = $_POST['fecha'];

    // Crear el PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Agregar imagen (X, Y, Ancho, Alto)
    $pdf->Image('./img/logo.jpg', 10, 10, 40); // Imagen en la esquina superior izquierda

    // Título del remito
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Remito de Entrega', 0, 1, 'C');
    
    // Espacio para evitar que la imagen tape el contenido
    $pdf->Ln(28); // Mueve el contenido hacia abajo

    // Información del cliente y fecha
    $pdf->SetFont('Arial', '', 12);
    $controller = new BarrilController();
    $lugar = $controller->getNombre($cliente); // Obtener nombre del cliente
    
    $pdf->Cell(100, 10, 'Cliente: ' . htmlspecialchars($lugar), 0, 1);
    $pdf->Cell(100, 10, 'Fecha: ' . htmlspecialchars($fecha), 0, 1);
    $pdf->Ln(8); // Espaciado

    // Obtener los barriles
    $barriles = $controller->getBarrilesPorClienteYFecha($cliente, $fecha);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Codigo Barril', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Litros', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Precio x Litro', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Precio Total', 1, 1, 'C'); // Salto de línea

    // Contenido de la tabla
    $pdf->SetFont('Arial', '', 10);
    $totalGeneral = 0;

    foreach ($barriles as $barril) {
        $precioTotal = $barril->litros * $barril->precio_x_litro;
        $totalGeneral += $precioTotal;

        $pdf->Cell(40, 10, $barril->codigo, 1, 0, 'C');
        $pdf->Cell(40, 10, $barril->litros . 'L', 1, 0, 'C');
        $pdf->Cell(60, 10, '$' . number_format($barril->precio_x_litro, 2), 1, 0, 'C');
        $pdf->Cell(50, 10, '$' . number_format($precioTotal, 2), 1, 1, 'C');
    }

    // Total general
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(140, 10, 'TOTAL GENERAL', 1, 0, 'R');
    $pdf->Cell(50, 10, '$' . number_format($totalGeneral, 2), 1, 1, 'C');

    // Limpiar buffer de salida y generar PDF
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('D', 'remito_' . $cliente . '_' . $fecha . '.pdf');
    exit;
} else {
    die('Acceso no permitido.');
}
