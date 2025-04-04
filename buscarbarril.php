<?php
require_once './controllers/barril.controller.php';
require_once './controllers/lugar.controller.php';

$barrilController = new BarrilController();
$lugarController = new LugarController();

$barril = null;
$nombreLugar = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_barril'])) {
    $codigo = $_POST['codigo'];
    $barril = $barrilController->getBarrilBycodigo($codigo);

    if ($barril) {
        $nombreLugar = $lugarController->getNombreLugarById($barril->id_lugar);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <link rel="icon" type="image/png" href="./img/logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de control de stock y ventas para cervecería.">
    <title>Sistema de Control de Stock</title>
    
    <!-- Estilos -->
    <link rel="stylesheet" href="styles.css"> <!-- Asegurar que la ruta sea correcta -->

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>
<section class="section-buscar-barril">


    <form method="POST" class="form-buscar-barril">
        <label for="codigo">Ingrese Código del Barril:</label>
        <input type="text" name="codigo" id="codigo" required>
        <button type="submit" name="buscar_barril" class="btn-modificar-buscar">Buscar</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="resultado-barril">
        <?php if ($barril): 
            if ($barril->litros == 20) {
                $imagenBarril = './img/barril20.jpg';
            } elseif ($barril->litros == 30) {
                $imagenBarril = './img/barril.png';
            } elseif ($barril->litros == 50) {
                $imagenBarril = './img/barril50.jpg';
            }
        ?>
        <div class="contenido-resultado">
            <div class="texto-barril">
                <p><strong>Código:</strong> <?= htmlspecialchars($barril->codigo) ?></p>
                <p><strong>Capacidad:</strong> <?= htmlspecialchars($barril->litros) ?> L</p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($barril->estado) ?></p>
                <p><strong>Ubicación:</strong> <?= htmlspecialchars($nombreLugar) ?></p>
            </div>
            <div class="imagen-barril">
                <img src="<?= $imagenBarril ?>" alt="Barril <?= htmlspecialchars($barril->litros) ?>L" class="imageen-barril">
            </div>
        </div>
        <?php else: ?>
            <p class="no-encontrado">No se encontró un barril con ese código.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

</section>
<?php include './views/footer.php'; ?>
</body>
</html>

