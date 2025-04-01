<?php
session_start(); // Asegúrate de que la sesión esté iniciada
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

    <section class="agregar">

    <!-- contenedor del formulario -->
    <?php
    // Instanciar el controlador
    require_once './controllers/variedad.controller.php';
    $controllerVariedad = new variedadController();

    // Obtener todas las variedades
    $variedades = $controllerVariedad->getAllVariedades();
    require_once './controllers/lugar.controller.php';
    $controllerLugares = new lugarController();

    $lugares = $controllerLugares->getAllLugares();

    // Incluir la vista del formulario
    include './views/formagregar.php';
    ?>
</section>
    <?php include './views/footer.php'; ?>

</body>
</html>
