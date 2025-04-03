<?php
require_once __DIR__ . '/controllers/barril.controller.php';
require_once __DIR__ . '/controllers/lugar.controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['barriles']) && !empty($_POST['id_lugar'])) {
        $barrilesSeleccionados = $_POST['barriles'];
        $nuevoLugar = $_POST['id_lugar'];

        $controller = new BarrilController();
        $controllerLugar = new lugarController();

        $nombreLugar = $controllerLugar->getNombreLugarById($nuevoLugar);

        foreach ($barrilesSeleccionados as $id_barril) {
            $controller->cambiarLugarBarril($id_barril, $nuevoLugar);
        }

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
