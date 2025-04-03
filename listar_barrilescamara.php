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
    <meta name="description" content="Sistema de control de stock y ventas para cervecería.">
    <title>Sistema de Control de Stock</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include './views/header.php'; ?>
    <?php include './views/nav.php'; ?>

    <section class="camara">
        <?php
        $controller = new BarrilController();
        $barriles = $controller->getBarriles();

        $controllerVariedades = new variedadController();
        $variedades = $controllerVariedades->getAllVariedades();

        $controllerLugar = new lugarController();
        $lugares = $controllerLugar->getAllLugares();

        $variedad = $_GET['variedad'] ?? '';
        $codigo = $_GET['codigo'] ?? '';
        $litros = $_GET['litros'] ?? '';

        $barriles = $controller->getBarrilesFiltradosCamara($variedad, $codigo, $litros);
        ?>
         <section class="contenedor-lista">
        <h2 class="venta">Stock en Cámara</h2>

        <?php
        if (isset($_GET['mensaje'])) {
            if ($_GET['mensaje'] == 'exito_lugar') {
                echo '<p style="color: green;">Barril cambiado de lugar exitosamente.</p>';
            } elseif ($_GET['mensaje'] == 'error_lugar') {
                echo '<p style="color: red;">Hubo un error al cambiar el barril de lugar.</p>';
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


<form action="cambiar_lugar.php" method="POST">
        <div class="lista-barriles">
            <?php if (!empty($barriles)): ?>  <!-- Verifica que haya barriles antes de iterar -->
                <?php foreach ($barriles as $barril): ?>
                    <?php 
                        // Convertimos el estado a minúsculas para que coincida con las clases CSS
                        $estadoClase = strtolower(str_replace(" ", "-", $barril->estado)); 

                        
                        // Solo permitir seleccionar barriles que estén llenos
                        $esLleno = strtolower($barril->estado) === 'lleno'; 
                    ?>
                    <div class="barril-card estado-<?= $estadoClase ?>">
                        <?php if ($esLleno): ?>  
                            <input type="checkbox" name="barriles[]" value="<?= $barril->id_barril ?>" class="checkbox-barril">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($barril->codigo) ?></h3>
                        <p><b>VARIEDAD :</b> <?= htmlspecialchars($barril->variedad) ?></p>
                        <p><b>LITROS :</b> <?= htmlspecialchars($barril->litros) ?>L</p>
                        <p><b>ESTADO:</b> 
                        <span class="estado <?= $estadoClase ?>">
                        <?= htmlspecialchars($barril->estado) ?>
                            </span>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>  
                <p class="mensaje-vacio">No hay barriles registrados.</p>
            <?php endif; ?>
        </div>


            <label for="lugar">Lugar:</label>
            <select name="id_lugar" id="lugar">
                <option value="">--Seleccionar--</option>
                <?php foreach ($lugares as $lugar): ?>
                    <?php if ($lugar->nombre != 'CAMARA' && $lugar->nombre != 'ZONA_VACIOS'  ): ?>
                        <option value="<?= $lugar->id_lugar ?>"><?= htmlspecialchars($lugar->nombre) ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-generar">Vender</button>
        </form>
    </section>
    <form action="generar_estadistica.php" method="POST" class="form-remito">
    <button type="submit" class="btn-generar">Generar Estadística de Cámara</button>
</form>

    </section>

        <?php include './views/footer.php'; ?>

</body>
</html>
