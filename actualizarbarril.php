<?php 
require_once __DIR__ . '/controllers/barril.controller.php'; 
require_once __DIR__ . '/controllers/variedad.controller.php'; 
require_once __DIR__ . '/controllers/lugar.controller.php'; 
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

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Estilos propios -->
<link rel="stylesheet" href="styles.css">

<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="text-white section-fondo">

<!-- Header -->
<?php include './views/header.php'; ?>

<!-- Navbar -->
<?php include './views/nav.php'; ?>

<section class="container py-5 custom-height">

    <?php
    $controllerVariedades = new variedadController();
    $variedades = $controllerVariedades->getAllVariedades();

    $controllerLugar = new lugarController();
    $lugares = $controllerLugar->getAllLugares();

    $barril = null;
    $lugar = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_barril'])) {
        $codigo = $_POST['codigo'];
        $controller = new BarrilController();
        $barril = $controller->getBarrilBycodigo($codigo);
        if ($barril) {
            $lugar = $controllerLugar->getNombreLugarById($barril->id_lugar);
        }
    }
    ?>

    <!-- Mensajes de sistema -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mb-4">
        <?php if ($_GET['mensaje'] === 'ya_vacio'): ?>
            <div class="alert alert-warning">El barril ya estaba vacío.</div>
        <?php elseif ($_GET['mensaje'] === 'vaciar_ok'): ?>
            <div class="alert alert-success">El barril se vació correctamente.</div>
        <?php elseif ($_GET['mensaje'] === 'vaciar_error'): ?>
            <div class="alert alert-danger">Hubo un error al intentar vaciar el barril.</div>
        <?php elseif ($_GET['mensaje'] === 'modificado'): ?>
            <div class="alert alert-success">Modificaste el barril con código <?= htmlspecialchars($_GET['codigo']) ?>.</div>
        <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Título -->
    <h2 class="text-center mb-4"><?= $barril ? "Barril Código: " . htmlspecialchars($barril->codigo) : "Modificar Barril" ?></h2>

    <form method="POST" class="row g-3 justify-content-center mb-5">
    <div class="col-md-6">
        <input type="text" name="codigo" class="form-control form-control-lg" placeholder="Código del Barril" required>
    </div>
    <div class="col-md-2">
    <button type="submit" name="buscar_barril" class="btn btn-primary btn-custom w-100">Buscar</button>

</div>

</form>

    <?php if ($barril): ?>

        <!-- Tabla con información del barril -->
        <div class="table-responsive mb-4">
            <table class="table table-dark table-bordered text-center">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>Variedad</th>
                        <th>Lugar</th>
                        <th>Litros</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($controllerVariedades->getNombreVariedad($barril->id_variedad)) ?></td>
                        <td><?= htmlspecialchars($lugar) ?></td>
                        <td><?= htmlspecialchars($barril->litros) ?>L</td>
                        <td><?= htmlspecialchars($barril->estado) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Vaciar o llenar barril -->
        <div class="text-center mb-4">
            <?php if (strtoupper($barril->estado) === 'VACIO'): ?>
                <h5 class="text-warning">Llenar Barril</h5>
            <?php else: ?>
                <form action="vaciarBarril.php" method="POST">
                    <input type="hidden" name="codigo" value="<?= htmlspecialchars($barril->codigo) ?>">
                    <button type="submit" class="btn btn-danger">Vaciar Barril 🗑️</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Formulario de edición si está en ZONA_VACIOS -->
        <?php if ($lugar === 'ZONA_VACIOS'): ?>
        <div class="card bg-dark border-info p-4 mb-5">
            <form action="actualizar.php" method="POST" class="row g-3">
                <input type="hidden" name="codigo" value="<?= htmlspecialchars($barril->codigo) ?>">

                <div class="col-md-3">
                    <label for="variedad" class="form-label">Variedad</label>
                    <select name="id_variedad" id="variedad" class="form-select">
                        <option value="">--Seleccionar--</option>
                        <?php foreach ($variedades as $variedad): ?>
                            <?php if ($variedad->id_variedad != 4): ?>
                                <option value="<?= $variedad->id_variedad ?>" <?= ($barril->id_variedad == $variedad->id_variedad) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($variedad->nombre) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="litros" class="form-label">Litros</label>
                    <select name="litros" id="litros" class="form-select">
                        <option value="<?= $barril->litros ?>" selected><?= $barril->litros ?>L</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">--Seleccionar--</option>
                        <option value="LLENO" <?= ($barril->estado == 'LLENO') ? 'selected' : '' ?>>LLENO</option>
                        <option value="EN USO" <?= ($barril->estado == 'EN USO') ? 'selected' : '' ?>>EN USO</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="lugar" class="form-label">Lugar</label>
                    <select name="id_lugar" id="lugar" class="form-select">
                        <?php foreach ($lugares as $lugarObj): ?>
                            <?php if ($lugarObj->nombre === 'CAMARA'): ?>
                                <option value="<?= $lugarObj->id_lugar ?>" <?= ($barril->id_lugar == $lugarObj->id_lugar) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lugarObj->nombre) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success mt-3">Guardar Cambios 💾</button>
                </div>
            </form>
        </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Solo se puede llenar el barril si está en <strong>LA ZONA DE VACIOS</strong>. Actualmente está en <strong><?= htmlspecialchars($lugar) ?></strong>.
            </div>
        <?php endif; ?>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-danger text-center">No se encontró el barril con ese código.</div>
    <?php endif; ?>

</section>

<!-- Footer -->
<?php include './views/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
