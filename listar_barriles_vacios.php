<?php
// Incluir el controlador necesario
require_once __DIR__ . '/controllers/barril.controller.php';
$controller = new BarrilController();

// Obtener el filtro de litros si está definido
$litrosFiltro = isset($_GET['litros']) ? $_GET['litros'] : null;

// Obtener barriles vacíos filtrados por capacidad
$barrilesVacios = $controller->getBarrilesVacios('VACIO', $litrosFiltro);

// Contar la cantidad de barriles filtrados
$totalBarriles = count($barrilesVacios);
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

    <section class="vacios">
        <section class="contenedor-lista">
            <h2 class="venta">Barriles Vacíos (<?= $totalBarriles ?>)</h2>

            <!-- Formulario de filtrado -->
            <form method="GET" action="">
                <label for="litros">Filtrar por capacidad:</label>
                <select name="litros" id="litros" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="20" <?= ($litrosFiltro == "20") ? "selected" : "" ?>>20L</option>
                    <option value="30" <?= ($litrosFiltro == "30") ? "selected" : "" ?>>30L</option>
                    <option value="50" <?= ($litrosFiltro == "50") ? "selected" : "" ?>>50L</option>
                </select>
            </form>

            <?php
            if (isset($_GET['mensaje'])) {
                $mensaje = $_GET['mensaje'];
                if ($mensaje == 'exito') {
                    echo '<p style="color: green;">Acción realizada exitosamente.</p>';
                } elseif ($mensaje == 'error') {
                    echo '<p style="color: red;">Hubo un error al realizar la acción.</p>';
                } elseif ($mensaje == 'dato_incompleto') {
                    echo '<p style="color: orange;">Faltan datos. Por favor, completa todos los campos.</p>';
                }
            }
            ?>

<div class="lista-barriles">
    <?php if (!empty($barrilesVacios)): ?>
        <?php foreach ($barrilesVacios as $barril): ?>
            <?php
                // Lógica para seleccionar la imagen correcta
                if ($barril->litros == 20) {
                    $imagenBarril = './img/barril20.jpg';
                } elseif ($barril->litros == 30) {
                    $imagenBarril = './img/barril.png';
                } elseif ($barril->litros == 50) {
                    $imagenBarril = './img/barril50.jpg';
                } 
            ?>
            <div class="barril-card-vacios">
                <img src="<?= $imagenBarril ?>" alt="Barril <?= htmlspecialchars($barril->litros) ?>L" class="img-barril">
                <h3><?= htmlspecialchars($barril->codigo) ?></h3>
                <p><b>LITROS:</b> <?= htmlspecialchars($barril->litros) ?>L</p>
                <p><b>ESTADO:</b> <span class="estado vacio"><?= htmlspecialchars($barril->estado) ?></span></p>
                <form action="eliminar.php" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este barril?');">
                    <input type="hidden" name="id_barril" value="<?= htmlspecialchars($barril->id_barril) ?>">
                    <button type="submit" class="btn-borrar">🗑️ Borrar</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="mensaje-vacio">No hay barriles vacíos registrados para esta capacidad.</p>
    <?php endif; ?>
</div>

        </section>
    </section>

    <?php include './views/footer.php'; ?>

</body>
</html>
