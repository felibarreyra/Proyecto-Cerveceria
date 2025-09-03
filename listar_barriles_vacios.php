<?php
require_once __DIR__ . '/controllers/barril.controller.php';
$controller = new BarrilController();

$litrosFiltro = isset($_GET['litros']) ? $_GET['litros'] : null;

$barrilesVacios = $controller->getBarrilesVacios('VACIO', $litrosFiltro);

$totalBarriles = count($barrilesVacios);
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Estilos propios -->
<link rel="stylesheet" href="styles.css">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="text-white section-fondo">

<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>

<section class="py-5">
    <div class="container">

        <h2 class="text-center text-info mb-4 venta">Barriles Vacíos <span class="badge bg-secondary"><?= $totalBarriles ?></span></h2>

        <!-- Formulario de filtrado -->
        <form method="GET" class="row g-3 mb-4 justify-content-center">
            <div class="col-auto">
                <label for="litros" class="form-label">Filtrar por capacidad:</label>
                <select name="litros" id="litros" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="20" <?= ($litrosFiltro == "20") ? "selected" : "" ?>>20L</option>
                    <option value="30" <?= ($litrosFiltro == "30") ? "selected" : "" ?>>30L</option>
                    <option value="50" <?= ($litrosFiltro == "50") ? "selected" : "" ?>>50L</option>
                </select>
            </div>
        </form>

        <?php if (!empty($barrilesVacios)): ?>
        <table class="table table-dark table-hover table-bordered text-center align-middle">
            <thead class="table-secondary text-dark">
                <tr>
                    <th>Código</th>
                    <th>Litros</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barrilesVacios as $barril): ?>
                <tr>
                    <td><?= htmlspecialchars($barril->codigo) ?></td>
                    <td><?= $barril->litros ?>L</td>
                    <td>
                        <span class="badge <?= strtolower($barril->estado) === 'lleno' ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= htmlspecialchars($barril->estado) ?>
                        </span>
                    </td>
                    <td>
                        <form action="eliminar.php" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este barril?');">
                            <input type="hidden" name="id_barril" value="<?= htmlspecialchars($barril->id_barril) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Borrar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="alert alert-warning text-center mt-4">
                No hay barriles vacíos registrados para esta capacidad.
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include './views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
