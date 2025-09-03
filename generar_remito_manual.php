<?php
require_once __DIR__ . '/controllers/variedad.controller.php';
require_once __DIR__ . '/controllers/lugar.controller.php';
require_once __DIR__ . '/models/venta.model.php';
require('./pdf/fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cliente = $_POST['cliente'] ?? '';
    $fechaInicio = $_POST['fecha_inicio'] ?? '';
    $fechaFin = $_POST['fecha_fin'] ?? '';
    $preciosManuales = $_POST['precios'] ?? [];

    $venta = new Venta();
    $controllerVariedad = new variedadController();
    $controllerLugar = new lugarController();

    // Cliente
    if ($cliente === 'sin_cliente') {
        $clienteFiltro = null;
        $nombreCliente = 'Ventas sin cliente';
    } elseif (!empty($cliente)) {
        $clienteFiltro = $cliente;
        $nombreCliente = $controllerLugar->getNombreLugarById($cliente);
    } else {
        $clienteFiltro = null;
        $nombreCliente = 'Todos';
    }

    // Fechas
    if (empty($fechaInicio)) $fechaInicio = date('Y-m-d');
    if (empty($fechaFin)) $fechaFin = $fechaInicio;

    // Obtener ventas filtradas
    $barriles = $venta->obtenerVentasConFiltros($fechaInicio, $fechaFin, null, null, $clienteFiltro);

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Logo
    $pdf->Image('./img/logo.jpg', 10, 10, 40);
    // Mover cursor debajo del logo
    $pdf->SetY(55); // Esto mueve el inicio de los textos al borde inferior del logo

    // Título del remito
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Remito de Entrega', 0, 1, 'C');
    $pdf->Ln(5); // Espacio extra

    // Datos cliente y fecha
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Cliente: ' . htmlspecialchars($nombreCliente), 0, 1);

    if ($fechaInicio === $fechaFin) {
        $pdf->Cell(0, 10, 'Fecha: ' . htmlspecialchars($fechaInicio), 0, 1);
    } else {
        $pdf->Cell(0, 10, 'Fecha Desde: ' . htmlspecialchars($fechaInicio) . '  Hasta: ' . htmlspecialchars($fechaFin), 0, 1);
    }
    $pdf->Ln(5);

    // Tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 10, 'Codigo', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Variedad', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Litros', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Precio x Litro', 1, 0, 'C');
    $pdf->Cell(45, 10, 'Precio Total', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    $totalGeneral = 0;

    foreach ($barriles as $barril) {
        $variedadNombre = $controllerVariedad->getNombreVariedad($barril->id_variedad);

        $precioLitro = isset($preciosManuales[$barril->id_variedad]) && $preciosManuales[$barril->id_variedad] > 0
            ? $preciosManuales[$barril->id_variedad]
            : ($barril->precio_total / max(1, $barril->litros));

        $precioTotal = $barril->litros * $precioLitro;
        $totalGeneral += $precioTotal;

        $pdf->Cell(30, 10, $barril->codigo_barril ?? '-', 1, 0, 'C');
        $pdf->Cell(50, 10, $variedadNombre, 1, 0, 'C');
        $pdf->Cell(25, 10, $barril->litros . 'L', 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($precioLitro, 2, ',', '.'), 1, 0, 'C');
        $pdf->Cell(45, 10, '$' . number_format($precioTotal, 2, ',', '.'), 1, 1, 'C');
    }

    // Total general
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(145, 10, 'PRECIO TOTAL (sin IVA)', 1, 0, 'R');
    $pdf->Cell(45, 10, '$' . number_format($totalGeneral, 2, ',', '.'), 1, 1, 'C');

    // Salida PDF
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('D', 'REMITO_' . ($clienteFiltro ?: 'todos') . '_' . $fechaInicio . '_' . $fechaFin . '.pdf');
    exit;
}
