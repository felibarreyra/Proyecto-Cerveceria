<?php
require_once __DIR__ . '/controllers/lugar.controller.php';
$controllerLugar = new lugarController();

// Manejo de agregar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = trim($_POST['nombre']);
    if (!empty($nombre)) {
        $controllerLugar->agregarLugar($nombre);
        header("Location: gestionarCliente.php?mensaje=agregado");
        exit;
    } else {
        $error = "El nombre no puede estar vacío.";
    }
}

// Manejo de eliminar cliente
if (isset($_GET['eliminar'])) {
    $id_lugar = $_GET['eliminar'];
    $controllerLugar->eliminarLugar($id_lugar); // Debes tener este método en tu controller
    header("Location: gestionarCliente.php?mensaje=eliminado");
    exit;
}

// Obtener todos los clientes
$clientes = $controllerLugar->getAllLugares();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="./img/logo.png">
<title>Sistema de Control de Stock</title>
<!-- Estilos propios -->
<link rel="stylesheet" href="styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="text-white section-fondo">

<?php include './views/header.php'; ?>
<?php include './views/nav.php'; ?>

<div class="container py-5">
    <h2 class="text-center text-info mb-4 venta">Gestión de Clientes</h2>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success">
            <?php 
                if ($_GET['mensaje'] == 'agregado') echo "Cliente agregado correctamente.";
                if ($_GET['mensaje'] == 'eliminado') echo "Cliente eliminado correctamente.";
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulario para agregar cliente -->
    <div class="card bg-secondary mb-4">
        <div class="card-body">
            <form action="" method="POST" class="row g-2 align-items-center">
                <div class="col-md-9">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del cliente" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="agregar_cliente" class="btn btn-primary w-100 btn-custom">Agregar Cliente</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="table-responsive">
        <table class="table table-dark table-hover table-bordered text-center align-middle">
            <thead class="table-secondary text-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $clientesFiltrados = array_filter($clientes, function($c) {
                return strtoupper($c->nombre) !== 'ZONA_VACIOS';
            });
            ?>
            
            <?php if (!empty($clientesFiltrados)): ?>
                    <?php foreach ($clientesFiltrados as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente->id_lugar) ?></td>
                            <td><?= htmlspecialchars($cliente->nombre) ?></td>
                            <td>
                                <a href="?eliminar=<?= $cliente->id_lugar ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Seguro que quieres eliminar este cliente?');">
                                   Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-warning">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
