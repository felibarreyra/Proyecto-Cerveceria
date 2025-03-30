<?php
session_start(); // Asegúrate de que la sesión esté iniciada
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de barriles</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de que la ruta sea correcta -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include './views/header.php'; ?>

    <!-- Barra de navegación --> 
    <?php include './views/nav.php'; ?>


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

    <?php include './views/footer.php'; ?>

</body>
</html>
