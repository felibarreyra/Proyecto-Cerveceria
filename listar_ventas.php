<?php
require_once __DIR__ . '/controllers/variedad.controller.php';
require_once __DIR__ . '/controllers/lugar.controller.php';
require_once __DIR__ . '/models/venta.model.php';

$controllerVariedades = new variedadController();
$controllerLugar = new lugarController();
$ventaModel = new Venta();

// Obtener todas las variedades y lugares
$variedades = $controllerVariedades->getAllVariedades();
$lugares = $controllerLugar->getAllLugares();

// Filtros desde GET
$fechaInicio = $_GET['fecha_inicio'] ?? null;
$fechaFin = $_GET['fecha_fin'] ?? null;
$variedad = $_GET['variedad'] ?? null;
$litros = $_GET['litros'] ?? null;
$lugar = $_GET['lugar'] ?? null;

// Si solo se pasó una fecha, usarla como inicio y fin
if ($fechaInicio && !$fechaFin) $fechaFin = $fechaInicio;

// Obtener ventas filtradas
$ventas = $ventaModel->obtenerVentasConFiltros($fechaInicio, $fechaFin, $variedad, $litros, $lugar);

// Totales
$totalVentas = count($ventas);
$totalLitros = array_sum(array_map(fn($v) => $v->litros, $ventas));
$totalDinero = array_sum(array_map(fn($v) => $v->precio_total, $ventas));

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Ventas</title>
    <link rel="icon" type="image/png" href="./img/logo.png">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="container-fluid section-fondo text-white">

<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>

<div class="container mt-4">
    <h2 class="text-info text-center mb-4 venta">📊 VENTAS</h2>

   <!-- Filtros -->
<div class="card bg-dark text-white mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3 text-info">
            <i class="bi bi-funnel-fill me-2"></i> Filtros
        </h5>
        <form action="" method="GET">
            <div class="row g-3 row-cols-1 row-cols-md-3 row-cols-lg-6">
                <!-- Fecha Inicio -->
                <div class="col">
                    <label for="fecha_inicio" class="form-label">
                        <i class="bi bi-calendar-event me-1"></i> Inicio
                    </label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" 
                           value="<?= htmlspecialchars($fechaInicio) ?>" 
                           class="form-control">
                </div>

                <!-- Fecha Fin -->
                <div class="col">
                    <label for="fecha_fin" class="form-label">
                        <i class="bi bi-calendar2-check me-1"></i> Fin
                    </label>
                    <input type="date" name="fecha_fin" id="fecha_fin" 
                           value="<?= htmlspecialchars($fechaFin) ?>" 
                           class="form-control">
                </div>

                <!-- Variedad -->
                <div class="col">
                    <label for="variedad" class="form-label">
                        <i class="bi bi-beer me-1"></i> Variedad
                    </label>
                    <select name="variedad" id="variedad" class="form-select">
                        <option value="">--Todas--</option>
                        <?php foreach ($variedades as $v): ?>
                            <?php if (!in_array($v->nombre, ['VACIO','LEVA KVEIK','LEVA S05'])): ?>
                                <option value="<?= $v->id_variedad ?>" 
                                    <?= ($variedad == $v->id_variedad) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v->nombre) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Litros -->
                <div class="col">
                    <label for="litros" class="form-label">
                        <i class="bi bi-droplet-half me-1"></i> Litros
                    </label>
                    <select name="litros" id="litros" class="form-select">
                        <option value="">--Todos--</option>
                        <option value="20" <?= ($litros == '20') ? 'selected' : '' ?>>20L</option>
                        <option value="30" <?= ($litros == '30') ? 'selected' : '' ?>>30L</option>
                        <option value="50" <?= ($litros == '50') ? 'selected' : '' ?>>50L</option>
                    </select>
                </div>

                <!-- Cliente -->
                <div class="col">
                    <label for="lugar" class="form-label">
                        <i class="bi bi-person-circle me-1"></i> Cliente
                    </label>
                    <select name="lugar" id="lugar" class="form-select">
                        <option value="">--Todos--</option>
                        <?php foreach ($lugares as $l): ?>
                            <?php if (!in_array($l->nombre,['CAMARA','ZONA_VACIOS'])): ?>
                                <option value="<?= $l->id_lugar ?>" <?= ($lugar == $l->id_lugar) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($l->nombre) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Botones -->
                <div class="col d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                    <a href="listar_ventas.php" class="btn btn-outline-light w-100">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

   
   <!-- Tabla -->
<div class="table-responsive" style="max-height:600px; overflow:auto;">
    <table class="table table-dark table-bordered table-hover align-middle text-center">
        <thead class="table-secondary text-dark sticky-top">
            <tr>
                <th>Cliente</th>
                <th>Variedad</th>
                <th>Litros</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($ventas): ?>
                <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v->lugar) ?></td>
                        <td><?= htmlspecialchars($v->variedad) ?></td>
                        <td><?= $v->litros ?>L</td>
                        <td><?= date('d/m/Y H:i', strtotime($v->fecha_venta)) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-warning">⚠️ No hay ventas con los filtros aplicados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


    <!-- Generar Remito -->
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
