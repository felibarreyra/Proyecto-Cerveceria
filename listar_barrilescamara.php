<?php require_once __DIR__ . '/controllers/barril.controller.php';?>
<?php require_once __DIR__ . '/controllers/variedad.controller.php';?>
<?php require_once __DIR__ . '/controllers/lugar.controller.php';?>
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
        <section class="camara">

        <?php $controller= new BarrilController();
        $barriles=$controller->getBarriles();?>

        <?php $controllerVariedades= new variedadController();
        $variedades=$controllerVariedades->getAllVariedades();?>

        <?php $controllerLugar= new lugarController();
        $lugares=$controllerLugar->getAllLugares();?>

    <?php 
        // Capturamos los valores de los filtros, si están disponibles en los parámetros GET
        $variedad = isset($_GET['variedad']) ? $_GET['variedad'] : ''; // Filtro por variedad
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : ''; // Filtro por código (si lo tienes)
        $litros = isset($_GET['litros']) ? $_GET['litros'] : ''; // Filtro por litros (si lo tienes)

            // Llamamos al controlador y pasamos los filtros obtenidos
        $barriles = $controller->getBarrilesFiltradosCamara($variedad, $codigo, $litros);
         ?>

        <section class="contenedor-lista">
        <h2 class="venta">Stock en Camara</h2>
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
                $totalLitros += $barril->litros;  // Acceder a los litros del barril como propiedad de objeto
            }
            
        ?>


<form action="listar_barrilescamara.php" method="GET" class="formulario-filtros">
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


    <button type="submit" class="btn-filtrar">Filtrar</button>
</form>


       

    <div class="lista-barriles">
        <?php if (!empty($barriles)): ?>
            <?php foreach ($barriles as $barril):
                $estado = $controller->getEstadoByCodigo($barril->codigo);?>
                <div class="barril-card">
                    <h3><?= htmlspecialchars($barril->codigo) ?></h3>
                    <p><b>VARIEDAD :</b> <?= htmlspecialchars($barril->variedad) ?></p>
                    <p><b>LITROS :</b> <?= htmlspecialchars($barril->litros) ?>L</p>
                    <p><b>ESTADO:</b> 
                    <span class="estado <?= strtolower($estado) ?>">
                        <?= htmlspecialchars($estado) ?>
                     </span>
                </p>
                    <form action="eliminar.php" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este barril?');">
                        <input type="hidden" name="id_barril" value="<?= htmlspecialchars($barril->id_barril) ?>">
                        <button type="submit" class="btn-borrar">🗑️ Borrar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="mensaje-vacio">No hay barriles registrados.</p>
        <?php endif; ?>
    </div>
 </section>
 <form action="generar_estadistica.php" method="POST" class="form-remito">
    <label for="input-fecha-inicio">Fecha de Inicio:</label>
    <input type="date" name="fecha_inicio" id="input-fecha-inicio" required class="campo-fecha">

    <label for="input-fecha-fin">Fecha de Fin:</label>
    <input type="date" name="fecha_fin" id="input-fecha-fin" required class="campo-fecha">

    <button type="submit" class="btn-generar">Generar Estadística</button>
</form>

</section>
        <!-- contenedor --> 
        <?php include './views/footer.php'; ?>

</body>
</html>
