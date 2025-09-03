<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Control de Stock</title>
    <link rel="icon" type="image/png" href="./img/logo.png">
    <link rel="stylesheet" href="/Proyecto Cerveceria/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container-fluid section-fondo">

<?php
    require_once __DIR__ . '/controllers/variedad.controller.php';
    require_once __DIR__ . '/controllers/lata.controller.php';
    require_once __DIR__ . '/controllers/lugar.controller.php';
    require_once __DIR__ .'/views/header.php';
    require_once __DIR__ . '/views/nav.php';


    $controllerVariedades = new variedadController();
    $variedades = $controllerVariedades->getAllVariedades();

    $lugarController = new lugarController();
    $lugares = $lugarController->getAllLugares();

    $lataController = new LataController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_variedad = $_POST['id_variedad'];
        $cantidad = $_POST['cantidad'];
        $lataController->cargarLatas($id_variedad, $cantidad);
        header("Location: cargar.php?mensaje=exito");
        exit;
    }

    $latas = $lataController->listarLatas();
?>

<div class="container py-4">

    <h1 class="text-center mb-4 venta">📦 Cargar Latas</h1>

    <!-- Mensaje de éxito -->
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'exito'): ?>
        <div class="alert alert-success text-center">
            ✅ Latas cargadas correctamente
        </div>
    <?php endif; ?>

    <!-- Formulario de carga -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Nueva Carga</h5>
            <form action="" method="POST">

            <div class="mb-3">
            <label for="variedad" class="form-label">Variedad</label>
            <select name="id_variedad" id="variedad" class="form-select" required>
                <option value="">-- Seleccionar Variedad --</option>
                <?php 
                $excluir = ['VACIO', 'LEVA KVEIK', 'LEVA S05']; // nombres a excluir
                foreach ($variedades as $v): 
                    if (in_array($v->nombre, $excluir)) continue; // saltar estas opciones
                ?>
                    <option value="<?= $v->id_variedad ?>"><?= htmlspecialchars($v->nombre) ?></option>
                <?php endforeach; ?>
            </select>
        </div>


                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad de Latas</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
                </div>

                <button type="submit" class="btn btn-success w-100">➕ Cargar</button>
            </form>
        </div>
    </div>

    <!-- Tabla de stock -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">📊 Stock Actual</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Variedad</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latas as $lata): ?>
                            <tr>
                                <td><?= htmlspecialchars($lata['variedad']) ?></td>
                                <td><?= $lata['cantidad'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($latas)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted">No hay stock registrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
