<?php
require_once __DIR__ . '/controllers/barril.controller.php';
require_once __DIR__ . '/controllers/lugar.controller.php';
require_once __DIR__ . '/controllers/variedad.controller.php';
require_once __DIR__ . '/models/venta.model.php';

$ventaModel = new Venta();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['barriles']) && !empty($_POST['id_lugar'])) {

        $barrilesSeleccionados = $_POST['barriles'];
        $nuevoLugar = $_POST['id_lugar'];

        $controller = new BarrilController();
        $controllerLugar = new lugarController();
        $controllerVariedad = new variedadController();

        $nombreLugar = $controllerLugar->getNombreLugarById($nuevoLugar);

        foreach ($barrilesSeleccionados as $id_barril) {
            // 1. Cambiar lugar del barril
            $controller->cambiarLugarBarril($id_barril, $nuevoLugar);

            // 2. Registrar fecha de venta en barriles
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fechaVenta = date("Y-m-d H:i:s");
            $controller->registrarFechaVenta($id_barril, $fechaVenta);

            // 3. Obtener datos del barril
            $barril = $controller->getBarrilById($id_barril);

            if ($nombreLugar !== "CAMARA" && $nombreLugar !== "ZONA_VACIOS") {
                // 4. Obtener precio por litro de la variedad
                $precioPorLitro = $controllerVariedad->getPrecioPorLitro($barril->id_variedad);

                // 5. Calcular precio total
                $precioTotal = $barril->litros * $precioPorLitro;

                // 6. Registrar la venta en la tabla ventas
                $ventaModel->registrarVenta(
                    $barril->codigo,
                    $barril->litros,
                    $nuevoLugar,
                    $barril->id_variedad,
                    $precioTotal
                );
            }
        }

        // Redirección según tipo de lugar
        if ($nombreLugar !== "CAMARA" && $nombreLugar !== "ZONA_VACIOS") {
            header("Location: listar_ventas.php?mensaje=exito_venta");
        } else {
            header("Location: listar_barrilescamara.php?mensaje=exito_lugar");
        }
        exit();
    } else {
        header("Location: listar_barrilescamara.php?mensaje=error_lugar");
        exit();
    }
}
?>

