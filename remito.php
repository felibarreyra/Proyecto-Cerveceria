<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Control de Stock</title>
    <link rel="icon" type="image/png" href="./img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="text-white section-fondo">
<?php
require_once __DIR__ . '/controllers/lugar.controller.php';
require_once __DIR__ . '/controllers/variedad.controller.php';
require_once __DIR__ . '/controllers/barril.controller.php';
?>
<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>
<?php
$controllerLugar = new lugarController();
$controllerVariedad = new variedadController();
$controllerBarril = new BarrilController();
$lugares = $controllerLugar->getAllLugares();
$variedades = $controllerVariedad->getAllVariedades();
$fecha = $_GET['fecha'] ?? '';
$variedad = $_GET['variedad'] ?? '';
$litros = $_GET['litros'] ?? '';
$lugar = $_GET['lugar'] ?? '';

?>
    <!-- Generar Remito -->
    <div class="container mt-4 text-white">
        <h2 class="text-center text-info mb-4 venta">🧾 GENERAR REMITO</h2>
    <div class="container mt-5 d-flex justify-content-center contenedor-remito">
        <div class="col-md-5">
            <div class="card shadow-sm bg-dark text-white rounded">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-center">Generar Remito</h5>
                    <form action="generar_remito.php" method="POST">
                        <!-- Hidden inputs para pasar filtros -->
                        <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
                        <input type="hidden" name="variedad" value="<?= htmlspecialchars($variedad) ?>">
                        <input type="hidden" name="litros" value="<?= htmlspecialchars($litros) ?>">
                        <input type="hidden" name="lugar" value="<?= htmlspecialchars($lugar) ?>">

                        <!-- Cliente -->
                        <div class="mb-3">
                            <label for="select-cliente" class="form-label">Cliente</label>
                            <select name="cliente" id="select-cliente" class="form-select">
                                <option value="">--Seleccionar Cliente--</option>
                                <option value="sin_cliente">Ventas sin cliente</option>
                                <?php foreach ($lugares as $l): ?>
                                    <?php if (!in_array($l->nombre, ['CAMARA', 'ZONA_VACIOS'])): ?>
                                        <option value="<?= $l->id_lugar ?>"> <?= htmlspecialchars($l->nombre) ?> </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Fechas -->
                        <div class="mb-3">
                            <label for="input-fecha-inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="input-fecha-inicio" required class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="input-fecha-fin" class="form-label">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" id="input-fecha-fin" class="form-control">
                        </div>

                        <!-- Botones -->
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-danger w-100 fw-bold d-flex justify-content-center align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Remito Automático
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="remito_manual.php" class="btn btn-secondary w-100 fw-bold d-flex justify-content-center align-items-center">
                                    ✍️ Remito Manual
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php include './views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
