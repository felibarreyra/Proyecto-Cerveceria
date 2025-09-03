<?php
require_once __DIR__ . '/controllers/lata.controller.php';
require_once __DIR__ . '/controllers/variedad.controller.php';
require_once __DIR__ . '/controllers/lugar.controller.php';

$lataController = new LataController();
$variedadController = new variedadController();
$lugarController = new lugarController();

$variedades = $variedadController->getAllVariedades();
$lugares = $lugarController->getAllLugares();
$latas = $lataController->listarLatas();

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === "venta") {
    $id_variedad = $_POST['id_variedad'];
    $cantidad = intval($_POST['cantidad']);
    $id_lugar = $_POST['id_lugar'];
    $precio_unitario = floatval($_POST['precio_unitario']);

    // Verificar stock disponible
    $stock = $lataController->getByVariedad($id_variedad);
    if (!$stock || $cantidad > $stock['cantidad']) {
        $mensaje = "❌ No hay suficiente stock disponible.";
    } else {
        // Descontar del stock
        $lataController->descontarStock($id_variedad, $cantidad);

        // Registrar venta
        $lataController->registrarVenta($id_variedad, $id_lugar, $cantidad, $precio_unitario);

        // Redirigir para evitar reenvío de formulario
        header("Location: venderlatas.php?mensaje=ok");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Sistema de Control de Stock</title>
  <link rel="icon" type="image/png" href="./img/logo.png">
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="container-fluid section-fondo">
<?php
    require_once __DIR__ .'/views/header.php';
    require_once __DIR__ . '/views/nav.php';?>

<div class="container mt-4 section-fondo">
  <h2 class="venta">🍺 Vender Latas</h2>

  <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok'): ?>
      <div class="alert alert-success">✅ Venta registrada con éxito</div>
  <?php elseif ($mensaje): ?>
      <div class="alert alert-danger"><?= $mensaje ?></div>
  <?php endif; ?>

  <!-- Formulario de Venta -->
  <form method="POST" class="bg-white p-4 rounded shadow-sm mb-4">
      <input type="hidden" name="accion" value="venta">

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
          <label for="cantidad" class="form-label">Cantidad</label>
          <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
      </div>

      <div class="mb-3">
          <label for="lugar" class="form-label">Cliente</label>
          <select name="id_lugar" id="lugar" class="form-select" required>
              <option value="">-- Seleccionar --</option>
              <?php foreach ($lugares as $l): 
                if ($l->nombre !== "CAMARA" && $l->nombre !== "ZONA_VACIOS"): ?>
                  <option value="<?= $l->id_lugar ?>"><?= htmlspecialchars($l->nombre) ?></option>
              <?php endif; endforeach; ?>
          </select>
      </div>

      <div class="mb-3">
          <label for="precio_unitario" class="form-label">Precio por lata</label>
          <input type="number" step="1" name="precio_unitario" id="precio_unitario" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success">💸 Registrar Venta</button>
  </form>

  <!-- Formulario de Remito -->
  <h3 class="venta mt-5">🧾 Generar Remito</h3>
  <form action="remitolatas.php" method="GET" class="bg-white p-4 rounded shadow-sm mb-4">
      <div class="mb-3">
          <label for="remito_cliente" class="form-label">Cliente</label>
          <select name="id_lugar" id="remito_cliente" class="form-select" required>
              <option value="">-- Seleccionar --</option>
              <?php foreach ($lugares as $l): 
                if ($l->nombre !== "CAMARA" && $l->nombre !== "ZONA_VACIOS"): ?>
                <option value="<?= $l->id_lugar ?>"><?= htmlspecialchars($l->nombre) ?></option>
              <?php endif; endforeach; ?>
          </select>
      </div>

      <div class="mb-3">
          <label for="remito_fecha" class="form-label">Fecha</label>
          <input type="date" name="fecha" id="remito_fecha" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">📑 Generar Remito</button>
  </form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
