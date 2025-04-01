<?php
require_once __DIR__ . '/controllers/barril.controller.php';
require_once __DIR__ . '/controllers/variedad.controller.php';
require('./pdf/fpdf/fpdf.php');

// Verificar si se enviaron los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha_inicio'], $_POST['fecha_fin'])) {
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];

    // Crear el PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Agregar imagen (X, Y, Ancho, Alto)
    $pdf->Image('./img/logo.jpg', 10, 10, 40);
    
    // Título del documento
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Estadisticas por Fecha', 0, 1, 'C');
    $pdf->Ln(28);

    // Información de fechas
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(100, 10, 'Fecha de Inicio: ' . htmlspecialchars($fechaInicio), 0, 1);
    $pdf->Cell(100, 10, 'Fecha de Fin: ' . htmlspecialchars($fechaFin), 0, 1);
    $pdf->Ln(8);

    // Obtener los barriles en cámara entre las fechas seleccionadas
    $barrilController = new BarrilController();
    $barriles = $barrilController->obtenerBarrilesEnCamaraPorFechas($fechaInicio, $fechaFin);
    
    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Fecha', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Variedad', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Tipo de Barril', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Total Litros', 1, 1, 'C');

    // Contenido de la tabla
    $pdf->SetFont('Arial', '', 10);
    $totalBarriles = 0;
    $totalLitros = 0;

    $variedadController = new VariedadController();
    
    // Agrupar barriles por variedad y tipo
    $barrilesAgrupados = [];
    foreach ($barriles as $barril) {
        $clave = $barril->id_variedad . '_' . $barril->litros;
        if (!isset($barrilesAgrupados[$clave])) {
            $barrilesAgrupados[$clave] = [
                'variedad' => $variedadController->getNombreVariedad($barril->id_variedad),
                'litros' => $barril->litros,
                'cantidad' => 0,
                'fecha' => $barril->fecha_venta
            ];
        }
        $barrilesAgrupados[$clave]['cantidad']++;
    }

    // Ahora imprimimos la tabla con los datos correctos
    foreach ($barrilesAgrupados as $datos) {
        $totalLitrosFila = $datos['litros'] * $datos['cantidad'];
        
        $pdf->Cell(40, 10, $datos['fecha'], 1, 0, 'C');
        $pdf->Cell(50, 10, $datos['variedad'], 1, 0, 'C');
        $pdf->Cell(40, 10, $datos['litros'] . 'L', 1, 0, 'C');
        $pdf->Cell(30, 10, $datos['cantidad'], 1, 0, 'C');
        $pdf->Cell(30, 10, $totalLitrosFila . 'L', 1, 1, 'C');

        $totalBarriles += $datos['cantidad'];
        $totalLitros += $totalLitrosFila;
    }

    // Total general
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(130, 10, 'TOTAL BARRILES', 1, 0, 'R');
    $pdf->Cell(30, 10, $totalBarriles, 1, 0, 'C');
    $pdf->Cell(30, 10, $totalLitros . 'L', 1, 1, 'C');

    // Limpiar buffer y generar PDF
    if (ob_get_length()) ob_end_clean();
    $pdf->Output('D', 'estadisticas_barriles_' . $fechaInicio . '_' . $fechaFin . '.pdf');
    exit;
} else {
    die('Acceso no permitido.');
}
?>
