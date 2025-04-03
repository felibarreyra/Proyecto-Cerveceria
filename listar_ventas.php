<?php require_once __DIR__ . '/controllers/barril.controller.php'; ?>
<?php require_once __DIR__ . '/controllers/variedad.controller.php'; ?>
<?php require_once __DIR__ . '/controllers/lugar.controller.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="./img/logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de control de stock y ventas para cervecería.">
    <title>Sistema de Control de Stock</title>
    
    <!-- Estilos -->
    <link rel="stylesheet" href="styles.css"> <!-- Asegurar que la ruta sea correcta -->

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include './views/header.php'; ?>
    
    <!-- Barra de navegación --> 
    <?php include './views/nav.php'; ?>

    <?php
    $controller = new BarrilController();
    $barriles = $controller->getBarriles();
    
    $controllerVariedades = new variedadController();
    $variedades = $controllerVariedades->getAllVariedades();
    
    $controllerLugar = new lugarController();
    $lugares = $controllerLugar->getAllLugares();

    // Capturamos los valores de los filtros, si están disponibles en los parámetros GET
    $variedad = isset($_GET['variedad']) ? $_GET['variedad'] : ''; // Filtro por variedad
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : ''; // Filtro por código
    $litros = isset($_GET['litros']) ? $_GET['litros'] : ''; // Filtro por litros
    $lugar = isset($_GET['lugar']) ? $_GET['lugar'] : ''; // Filtro por lugar
    
    // Llamamos al controlador y pasamos los filtros obtenidos
    $barriles = $controller->getBarrilesFiltradosVentas($variedad, $codigo, $litros, $lugar);
    ?>
    <section class="ventas-container">

    <section class="contenedor-lista-ventas">
        <h2 class="venta">Ventas</h2>
        <?php
        if (isset($_GET['mensaje'])) {
            $mensaje = $_GET['mensaje'];
            if ($mensaje == 'exito') {
                echo '<p style="color: green;">Barril agregado exitosamente.</p>';
            } elseif ($mensaje == 'error') {
                echo '<p style="color: red;">Hubo un error al agregar el barril.</p>';
            } elseif ($mensaje == 'dato_incompleto') {
                echo '<p style="color: orange;">Faltan datos. Por favor, completa todos los campos.</p>';
            }
        }
        ?>
        
        <?php
        // Contar barriles filtrados
        $totalBarriles = count($barriles); // $barriles es el array con los barriles filtrados
        // Calcular la cantidad total de litros
        $totalLitros = 0;
        foreach ($barriles as $barril) {
            $estado = $controller->getEstadoByCodigo($barril->codigo); // Acceder al estado del barril 
             $totalLitros += $barril->litros;
        }
        ?>
        <form action="./listar_ventas.php" method="GET" class="formulario-filtros">
            <h3>Filtrar Barriles <span class="cantidad-barriles">(Cantidad: <?= $totalBarriles ?>)</span><span class="cantidad-litros">(Litros Totales: <?= $totalLitros ?>L)</span></h3>

                    <div class="campo-filtro">
            <label for="variedad">Variedad:</label>
            <select name="variedad" id="variedad">
                <option value="">--Seleccionar--</option>
                <?php 
                // Filtrar las variedades para no mostrar la que tenga nombre "VACIO"
                $variedadesFiltradas = array_filter($variedades, function($variedad) {
                    return $variedad->nombre != 'VACIO';  // Verificar que el nombre no sea "VACIO"
                });
                
                foreach ($variedadesFiltradas as $variedad): ?>
                    <option value="<?= $variedad->id_variedad ?>" <?= isset($_GET['variedad']) && $_GET['variedad'] == $variedad->id_variedad ? 'selected' : '' ?>>
                        <?= htmlspecialchars($variedad->nombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


            <div class="campo-filtro">
                <label for="litros">Litros:</label>
                <select name="litros" id="litros">
                    <option value="">--Seleccionar--</option>
                    <option value="20" <?= isset($_GET['litros']) && $_GET['litros'] == '20' ? 'selected' : '' ?>>20L</option>
                    <option value="30" <?= isset($_GET['litros']) && $_GET['litros'] == '30' ? 'selected' : '' ?>>30L</option>
                    <option value="50" <?= isset($_GET['litros']) && $_GET['litros'] == '50' ? 'selected' : '' ?>>50L</option>
                </select>
            </div>

            <div class="campo-filtro">
                <label for="lugar">Lugar:</label>
                <select name="lugar" id="lugar">
                    <option value="">--Seleccionar--</option>
                    <?php foreach ($lugares as $lugar): ?>
                        <?php if ($lugar->nombre != 'CAMARA' && $lugar->nombre != 'ZONA_VACIOS'  ): ?>  <!-- Excluir lugar 'Cámara' -->
                            <option value="<?= $lugar->id_lugar ?>" <?= isset($_GET['lugar']) && $_GET['lugar'] == $lugar->id_lugar ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lugar->nombre) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-filtrar">Filtrar</button>
        </form>

        <div class="lista-barriles">
            <?php if (!empty($barriles)): ?>
                <?php foreach ($barriles as $barril): ?>
                    <div class="barril-card">
                        <h3><?= htmlspecialchars($barril->codigo) ?></h3>
                        <p><b>VARIEDAD :</b> <?= htmlspecialchars($barril->variedad) ?></p>
                        <p><b>LITROS :</b> <?= htmlspecialchars($barril->litros) ?>L</p>
                    <!-- Aquí agregamos la clase correspondiente según el estado -->
                    <p><b>ESTADO:</b> 
                     <span class="estado <?= strtolower($estado) ?>">
                    <?= htmlspecialchars($estado) ?>
                        </span>
                    </p>

                        <p><b>LUGAR:</b> <?= htmlspecialchars($barril->lugar) ?></p>
                        <?php $fecha_venta = $controller->getFechaByCodigo($barril->codigo);?>
                        <b>FECHA DE VENTA:</b> <?= htmlspecialchars($fecha_venta) ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="mensaje-vacio">No hay barriles registrados.</p>
            <?php endif; ?>
        </div>
        <!-- Aquí está el botón para redirigir a la página de "Generar Remito" -->


    </section>
    <form action="generar_remito.php" method="POST" class="form-remito">
    <label for="select-cliente">Cliente:</label>
    <select name="cliente" id="select-cliente" class="campo-select">
        <option value="">--Seleccionar Cliente--</option>
        <option value="sin_cliente">Ventas sin cliente</option>
        <?php foreach ($lugares as $lugar): ?>
            <?php if ($lugar->nombre != 'CAMARA' && $lugar->nombre != 'ZONA_VACIOS'): ?>
                <option value="<?= $lugar->id_lugar ?>"><?= htmlspecialchars($lugar->nombre) ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>

    <label for="input-fecha-inicio">Fecha de Inicio:</label>
    <input type="date" name="fecha_inicio" id="input-fecha-inicio" required class="campo-fecha">

    <div id="fecha-fin-container" style="display: none;">
        <label for="input-fecha-fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" id="input-fecha-fin" class="campo-fecha">
    </div>

    <button type="submit" class="btn-generar">Generar Remito</button>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let selectCliente = document.getElementById("select-cliente");
        let fechaFinContainer = document.getElementById("fecha-fin-container");
        let fechaFin = document.getElementById("input-fecha-fin");

        function actualizarVisibilidadFechaFin() {
            if (selectCliente.value === "sin_cliente") {
                fechaFinContainer.style.display = "block";
                fechaFin.required = true;
            } else {
                fechaFinContainer.style.display = "none";
                fechaFin.required = false;
            }
        }

        // Ocultar el campo al cargar la página
        actualizarVisibilidadFechaFin();

        // Agregar evento para cambiar visibilidad al seleccionar cliente
        selectCliente.addEventListener("change", actualizarVisibilidadFechaFin);
    });
</script>



</section>

    <!-- contenedor --> 
    <?php include './views/footer.php'; ?>

</body>
</html>
