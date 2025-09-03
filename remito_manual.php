<?php
require_once __DIR__ . '/controllers/variedad.controller.php';
require_once __DIR__ . '/controllers/lugar.controller.php';

$controllerVariedad = new variedadController();
$controllerLugar = new lugarController();

$variedades = $controllerVariedad->getAllVariedades();
$lugares = $controllerLugar->getAllLugares();

// Mantener valores seleccionados
$clienteSeleccionado = $_POST['cliente'] ?? '';
$fechaInicio = $_POST['fecha_inicio'] ?? '';
$fechaFin    = $_POST['fecha_fin'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Generar Remito Manual</title>
<link rel="icon" type="image/png" href="./img/logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="styles.css">
</head>
<body class="section-fondo text-white">
<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>

<div class="container py-4">
    <h2 class="mb-4 text-center text-info">📄 Generar Remito Manual</h2>

    <form action="generar_remito_manual.php" method="POST" class="form-section shadow-sm p-4 bg-dark rounded">

        <div class="row g-3 align-items-end mb-4">
            <div class="col-md-5">
                <label for="cliente" class="form-label">Cliente</label>
                <select name="cliente" id="cliente" class="form-select">
                    <option value="">--Seleccionar Cliente--</option>
                    <option value="sin_cliente" <?= ($clienteSeleccionado === 'sin_cliente') ? 'selected' : '' ?>>Ventas sin cliente</option>
                    <?php foreach($lugares as $l): ?>
                        <?php if (!in_array(strtoupper($l->nombre), ['ZONA_VACIOS', 'CAMARA'])): ?>
                            <option value="<?= $l->id_lugar ?>" <?= ($l->id_lugar == $clienteSeleccionado) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l->nombre) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= htmlspecialchars($fechaInicio) ?>" required>
            </div>

            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= htmlspecialchars($fechaFin) ?>">
            </div>
        </div>

        <h5 class="text-warning mb-3">Precios por litro (opcional)</h5>
        <div class="row g-3 mb-4">
            <?php foreach($variedades as $v): ?>
                <?php if (!in_array(strtoupper($v->nombre), ['VACIO', 'LEVA KVEIK', 'LEVA S05'])): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <label class="form-label small"><?= htmlspecialchars($v->nombre) ?></label>
                        <input type="number" step="0.01" class="form-control" name="precios[<?= $v->id_variedad ?>]" placeholder="<?= $v->precio_x_litro ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary px-4 fw-bold">Generar PDF</button>
        </div>
    </form>
</div>

<?php include './views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
