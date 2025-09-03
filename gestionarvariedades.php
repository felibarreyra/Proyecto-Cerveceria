<?php
require_once __DIR__ . '/controllers/variedad.controller.php';
$controllerVariedad = new variedadController();

// Manejo de agregar variedad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_variedad'])) {
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    if (!empty($nombre) && $precio > 0) {
        $controllerVariedad->agregarVariedad($nombre, $precio);
        header("Location: gestionarVariedades.php?mensaje=agregado");
        exit;
    } else {
        $error = "Nombre y precio deben ser válidos.";
    }
}

// Manejo de modificar precio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar_precio'])) {
    $id_variedad = $_POST['id_variedad'];
    $precio = floatval($_POST['precio']);
    if ($precio > 0) {
        $controllerVariedad->modificarPrecio($id_variedad, $precio);
        header("Location: gestionarVariedades.php?mensaje=modificado");
        exit;
    } else {
        $error = "El precio debe ser mayor a 0.";
    }
}

// Manejo de eliminar variedad
if (isset($_GET['eliminar'])) {
    $id_variedad = $_GET['eliminar'];
    $controllerVariedad->eliminarVariedad($id_variedad);
    header("Location: gestionarVariedades.php?mensaje=eliminado");
    exit;
}

// Obtener todas las variedades
$variedades = $controllerVariedad->getAllVariedades();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="./img/logo.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Control de Stock</title>
<!-- Estilos propios -->
<link rel="stylesheet" href="styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="section-fondo text-white">

<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>

<div class="container py-5">
    <h2 class="text-center text-info mb-4 venta">Gestión de Variedades</h2>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success">
            <?php 
                if ($_GET['mensaje'] == 'agregado') echo "Variedad agregada correctamente.";
                if ($_GET['mensaje'] == 'modificado') echo "Precio modificado correctamente.";
                if ($_GET['mensaje'] == 'eliminado') echo "Variedad eliminada correctamente.";
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulario para agregar variedad -->
    <div class="card bg-secondary mb-4">
        <div class="card-body">
            <form action="" method="POST" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre de la variedad" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="precio" class="form-control" placeholder="Precio x litro" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="agregar_variedad" class="btn btn-primary w-100 btn-custom">Agregar Variedad</button>
                </div>
            </form>
        </div>
    </div>

<!-- Tabla de variedades -->
<div class="table-responsive">
    <table class="table table-dark table-hover table-bordered text-center align-middle">
        <thead class="table-secondary text-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio x Litro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $variedadesFiltradas = array_filter($variedades, function($v) {
                return strtoupper($v->nombre) !== 'VACIO';
            });
            ?>
            
            <?php if (!empty($variedadesFiltradas)): ?>
                <?php foreach ($variedadesFiltradas as $variedad): ?>
                    <tr>
                        <td><?= htmlspecialchars($variedad->id_variedad) ?></td>
                        <td><?= htmlspecialchars($variedad->nombre) ?></td>
                        <td>
                            <form action="" method="POST" class="d-flex justify-content-center align-items-center">
                                <input type="hidden" name="id_variedad" value="<?= $variedad->id_variedad ?>">
                                <input type="number" name="precio" class="form-control form-control-sm me-2" 
                                       value="<?= htmlspecialchars($variedad->precio_x_litro) ?>" step="1" style="width:100px;" required>
                                <button type="submit" name="modificar_precio" class="btn btn-var btn-warning btn-sm">Modificar</button>
                            </form>
                        </td>
                        <td>
                            <a href="?eliminar=<?= $variedad->id_variedad ?>" class="btn btn-var btn-danger btn-sm"
                               onclick="return confirm('¿Seguro que quieres eliminar esta variedad?');">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-warning">No hay variedades registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
