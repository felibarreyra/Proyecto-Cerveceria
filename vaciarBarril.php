<?php require_once __DIR__ . '/controllers/barril.controller.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["codigo"])) {
    $codigo = $_POST["codigo"];
    $controller = new BarrilController();
    $model = new Barril();

    $barril = $controller->getBarrilBycodigo($codigo); // Ahora sí devuelve el barril correctamente

    if ($barril && $barril->estado === 'VACIO') {
        header("Location: ./actualizarbarril.php?mensaje=ya_vacio&codigo=" . $codigo);
        exit();
    }

    $resultado = $model->vaciarBarril($codigo);

    if ($resultado) {
        header("Location: ./actualizarbarril.php?mensaje=vaciar_ok&codigo=" . $codigo);
    } else {
        header("Location: ./actualizarbarril.php?mensaje=vaciar_error&codigo=" . $codigo);
    }
    exit();
}

