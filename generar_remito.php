<?php
require_once __DIR__ . '/controllers/barril.controller.php';
require_once __DIR__ . '/controllers/variedad.controller.php';
require('./pdf/fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha_inicio'], $_POST['fecha_fin'])) {
    $cliente = $_POST['cliente'] ?? '';
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image('./img/logo.jpg', 10, 10, 40);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Remito de Entrega', 0, 1, 'C');
    $pdf->Ln(28);

    $pdf->SetFont('Arial', '', 12);
    $controller = new BarrilController();
    $controllerVariedad = new variedadController();

    if ($cliente === 'sin_cliente') {
        $pdf->Cell(100, 10, 'Cliente: Ventas sin cliente', 0, 1);
        $cliente = ''; // Para que la consulta considere los barriles sin cliente
    } elseif (!empty($cliente)) {
        $lugar = $controller->getNombre($cliente);
        $pdf->Cell(100, 10, 'Cliente: ' . htmlspecialchars($lugar), 0, 1);
    } else {
        $pdf->Cell(100, 10, 'Cliente: Todos', 0, 1);
    }

   // Mostrar fechas según lo ingresado
    if (!empty($fechaInicio) && !empty($fechaFin)) {
        $pdf->Cell(100, 10, 'Fecha Desde: ' . htmlspecialchars($fechaInicio), 0, 1);
        $pdf->Cell(100, 10, 'Fecha Hasta: ' . htmlspecialchars($fechaFin), 0, 1);
    } elseif (!empty($fechaInicio)) {
        $pdf->Cell(100, 10, 'Fecha: ' . htmlspecialchars($fechaInicio), 0, 1);
    } elseif (!empty($fechaFin)) {
        $pdf->Cell(100, 10, 'Fecha: ' . htmlspecialchars($fechaFin), 0, 1);
    }

    // Obtener los barriles por cliente y rango de fechas
    $barriles = $controller->getBarrilesPorClienteOFecha($cliente, $fechaInicio, $fechaFin);

    $pdf->Cell(30, 10, 'Codigo Barril', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Variedad', 1, 0, 'C'); 
    $pdf->Cell(30, 10, 'Litros', 1, 0, 'C');
    $pdf->Cell(45, 10, 'Precio x Litro', 1, 0, 'C');
    $pdf->Cell(45, 10, 'Precio Total', 1, 1, 'C');
    
    
    

    $pdf->SetFont('Arial', '', 10);
    $totalGeneral = 0;

    foreach ($barriles as $barril) {
        $precioTotal = $barril->litros * $barril->precio_x_litro;
        $totalGeneral += $precioTotal;

        $pdf->Cell(30, 10, $barril->codigo, 1, 0, 'C');
        $variedadNombre = $controllerVariedad->getNombreVariedad($barril->id_variedad);
        $pdf->Cell(40, 10, $variedadNombre, 1, 0, 'C');
        $pdf->Cell(30, 10, $barril->litros . 'L', 1, 0, 'C');
        $pdf->Cell(45, 10, '$' . number_format($barril->precio_x_litro, 2), 1, 0, 'C');
        $pdf->Cell(45, 10, '$' . number_format($precioTotal, 2), 1, 1, 'C');
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(145, 10, 'PRECIO TOTAL (sin IVA)', 1, 0, 'R');
    $pdf->Cell(45, 10, '$' . number_format($totalGeneral, 2), 1, 1, 'C');


    if (ob_get_length()) ob_end_clean();
    $pdf->Output('D', 'remito_' . ($cliente ?: 'todos') . '_' . $fechaInicio . '_' . $fechaFin . '.pdf');
    exit;
}
