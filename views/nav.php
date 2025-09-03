<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="actualizarbarril.php">LLENAR/VACIAR BARRIL</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="listar_barrilescamara.php">STOCK EN CAMARA</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="gestionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-cart-fill me-1"></i> VENTAS
          </a>
          <ul class="dropdown-menu" aria-labelledby="gestionDropdown">
            <li><a class="dropdown-item" href="listar_ventas.php">Estadistica</a></li>
            <li><a class="dropdown-item" href="">Stock Clientes</a></li>
            <li><a class="dropdown-item" href="remito.php">Remito</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="listar_barriles_vacios.php">ZONA VACIOS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="buscarbarril.php">BUSCAR BARRIL</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="gestionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-gear-fill me-1"></i> GESTION
          </a>
          <ul class="dropdown-menu" aria-labelledby="gestionDropdown">
            <li><a class="dropdown-item" href="gestionarcliente.php">Clientes</a></li>
            <li><a class="dropdown-item" href="gestionarvariedades.php">Variedades</a></li>
            <li><a class="dropdown-item" href="agregar.php">Agregar Barril</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="gestionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-gear-fill me-1"></i> LATAS
          </a>
          <ul class="dropdown-menu" aria-labelledby="gestionDropdown">
            <li><a class="dropdown-item" href="cargar.php">Cargar</a></li>
            <li><a class="dropdown-item" href="venderlatas.php">Vender</a></li>
          </ul>
        </li>
        
      </ul>
    </div>
  </div>
</nav>

