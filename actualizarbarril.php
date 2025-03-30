<?php 
require_once __DIR__ . '/controllers/barril.controller.php'; 
require_once __DIR__ . '/controllers/variedad.controller.php'; 
require_once __DIR__ . '/controllers/lugar.controller.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de barriles</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <?php include './views/header.php'; ?>

    <!-- Barra de navegación -->
    <?php include './views/nav.php'; ?>

    <?php
    $controllerVariedades = new variedadController();
    $variedades = $controllerVariedades->getAllVariedades();

    $controllerLugar = new lugarController();
    $lugares = $controllerLugar->getAllLugares();

    $barril = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_barril'])) {
        $codigo = $_POST['codigo'];
        $controller = new BarrilController();
        $barril = $controller->getBarrilBycodigo($codigo);
    }
    ?>

    <section class="contenedor-modificar">
        <h2 class="stock">Modificar Barril</h2>

        <!-- Buscar barril por código -->
        <form method="POST"class="form-buscar-barril">
            <label for="codigo">Ingrese Código del Barril:</label>
            <input type="text" name="codigo" id="codigo" required>
            <button type="submit" name="buscar_barril">Buscar</button>
        </form>

        <!-- Formulario de edición (solo si se encontró el barril) -->
        <?php if ($barril): ?>
            <div id="formulario-edicion">
                <h3>Editando Barril Código: <?= htmlspecialchars($barril->codigo) ?></h3>
                <form action="actualizar.php" method="POST">
                    <input type="hidden" name="codigo" value="<?= htmlspecialchars($barril->codigo) ?>">

                    <label for="variedad">Variedad:</label>
                    <select name="id_variedad" id="variedad">
                        <?php foreach ($variedades as $variedad): ?>
                            <option value="<?= $variedad->id_variedad ?>" <?= $variedad->id_variedad == $barril->id_variedad ? 'selected' : '' ?>>
                                <?= htmlspecialchars($variedad->nombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="litros">Litros:</label>
                    <select name="litros" id="litros">
                        <option value="20" <?= $barril->litros == 20 ? 'selected' : '' ?>>20L</option>
                        <option value="30" <?= $barril->litros == 30 ? 'selected' : '' ?>>30L</option>
                        <option value="50" <?= $barril->litros == 50 ? 'selected' : '' ?>>50L</option>
                    </select>

                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado">
                        <option value="LLENO" <?= $barril->estado == 'LLENO' ? 'selected' : '' ?>>LLENO</option>
                        <option value="MITAD" <?= $barril->estado == 'EN USO' ? 'selected' : '' ?>>EN USO</option>
                        <option value="VACIO" <?= $barril->estado == 'VACIO' ? 'selected' : '' ?>>VACIO</option>
                    </select>

                    <label for="lugar">Lugar:</label>
                    <select name="id_lugar" id="lugar">
                        <?php foreach ($lugares as $lugar): ?>
                            <option value="<?= $lugar->id_lugar ?>" <?= $lugar->id_lugar == $barril->id_lugar ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lugar->nombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                </form>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="mensaje-error">No se encontró el barril con ese código.</p>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <?php include './views/footer.php'; ?>

</body>
</html>
