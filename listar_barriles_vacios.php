<?php
// Incluir el controlador necesario
require_once __DIR__ . '/controllers/barril.controller.php';
$controller = new BarrilController();

// Obtener todos los barriles que están vacíos
$barrilesVacios = $controller->getBarrilesPorEstado('VACIO');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de barriles</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de que la ruta sea correcta -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>

    <?php include './views/header.php'; ?>

    <!-- Barra de navegación --> 
    <?php include './views/nav.php'; ?>
<section class="vacios">

    <section class="contenedor-lista">
        <h2 class="barriles_vacios">Barriles Vacíos</h2>

        <?php
        // Mensaje de éxito o error si existe
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
                    <div class="barril-card">
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
                <p class="mensaje-vacio">No hay barriles vacíos registrados.</p>
            <?php endif; ?>
        </div>
    </section>
</section>

    <?php include './views/footer.php'; ?>

</body>
</html>
