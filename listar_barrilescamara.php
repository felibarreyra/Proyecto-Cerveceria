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
<title>Sistema de Control de Stock</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Estilos propios -->
<link rel="stylesheet" href="styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="text-white bg-dark">

<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>

<?php
$controller = new BarrilController();
$controllerVariedades = new variedadController();
$controllerLugar = new lugarController();

$variedades = $controllerVariedades->getAllVariedades();
$lugares = $controllerLugar->getAllLugares();

$variedad = $_GET['variedad'] ?? '';
$litros = $_GET['litros'] ?? '';
$estado = $_GET['estado'] ?? '';

$barriles = $controller->getBarrilesFiltradosCamara($variedad, '', $litros, $estado);

$totalBarriles = count($barriles);
$totalLitros = array_sum(array_map(fn($b) => $b->litros, $barriles));
?>

<section class="py-5 section-fondo" style="min-height: 100vh;">
<div class="container-fluid py-4">

<h2 class="mb-4 text-center text-info venta">📦 Stock en Cámara</h2>

<!-- Formulario de filtros -->
<form action="" method="GET" class="mb-4 bg-dark p-3 rounded shadow-sm">
    <div class="row g-3 align-items-end">
        <div class="col-md-3 col-sm-6">
            <label for="variedad" class="form-label">Variedad</label>
            <select name="variedad" id="variedad" class="form-select form-select-sm">
                <option value="">--Seleccionar--</option>
                <?php foreach ($variedades as $v):
                    if ($v->nombre != 'VACIO'):
                        $sel = $variedad == $v->id_variedad ? 'selected' : '';
                ?>
                    <option value="<?= $v->id_variedad ?>" <?= $sel ?>><?= htmlspecialchars($v->nombre) ?></option>
                <?php endif; endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 col-sm-6">
            <label for="litros" class="form-label">Litros</label>
            <select name="litros" id="litros" class="form-select form-select-sm">
                <option value="">--Seleccionar--</option>
                <?php foreach ([20,30,50] as $l):
                    $sel = $litros == $l ? 'selected' : '';
                ?>
                    <option value="<?= $l ?>" <?= $sel ?>><?= $l ?>L</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 col-sm-6">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select form-select-sm">
                <option value="">--Seleccionar--</option>
                <?php foreach (['LLENO','EN USO'] as $e):
                    $sel = $estado == $e ? 'selected' : '';
                ?>
                    <option value="<?= $e ?>" <?= $sel ?>><?= $e ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-auto col-sm-6">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
        </div>
        <div class="col-md text-end fw-bold text-white">
            Cantidad: <?= $totalBarriles ?> | Litros Totales: <?= $totalLitros ?>L
        </div>
    </div>
</form>

<!-- Tabla de barriles con scroll -->
<form action="cambiar_lugar.php" method="POST" class="bg-dark p-3 rounded shadow-sm">
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-dark table-hover table-bordered align-middle text-center">
            <thead class="table-secondary text-dark sticky-top">
                <tr>
                    <th>Código</th>
                    <th>Litros</th>
                    <th>Variedad</th>
                    <th>Estado</th>
                    <th>✔</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($barriles)): ?>
                    <?php foreach ($barriles as $barril): 
                        $esExcluido = in_array(strtoupper($barril->variedad), ['LEVA KVEIK','LEVA S05']);
                        $estadoEmoji = (strtolower($barril->estado) === 'lleno') 
                            ? '🟢 Lleno' 
                            : '🟡 En uso';
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($barril->codigo) ?></td>
                            <td><?= $barril->litros ?>L</td>
                            <td><?= htmlspecialchars($barril->variedad) ?></td>
                            <td><?= $estadoEmoji ?></td>
                            <td>
                                <?php if ($esExcluido): ?>
                                    -
                                <?php elseif (strtolower($barril->estado) === 'lleno'): ?>
                                    <input type="checkbox" name="barriles[]" value="<?= $barril->id_barril ?>">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-warning">No hay barriles registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Formulario de venta/cambio de lugar -->
    <div class="row mt-4 align-items-end">
        <div class="col-md-4 col-sm-6">
            <label for="lugar" class="form-label">Lugar</label>
            <select name="id_lugar" id="lugar" class="form-select form-select-sm">
                <option value="">--Seleccionar--</option>
                <?php foreach ($lugares as $lugar):
                    if (!in_array($lugar->nombre, ['CAMARA','ZONA_VACIOS'])): ?>
                    <option value="<?= $lugar->id_lugar ?>"><?= htmlspecialchars($lugar->nombre) ?></option>
                <?php endif; endforeach; ?>
            </select>
        </div>
        <div class="col-md-auto col-sm-6">
            <button type="submit" class="btn btn-success btn-sm w-100">Vender</button>
        </div>
    </div>
</form>

<!-- Botón generar estadística -->
<div class="row mt-4">
    <div class="col-md-3 col-sm-6">
        <form action="generar_estadistica.php" method="POST">
            <button type="submit" class="btn btn-secondary btn-lg w-100">Generar Estadística de Cámara</button>
        </form>
    </div>
</div>

</div>
</section>

<?php include './views/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
