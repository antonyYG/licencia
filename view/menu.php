<?php
// Solución definitiva:
// Solo llama a session_start() si el estado de la sesión no es ACTIVO.
// Esto evita el error "session already active".
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si la variable de sesión 'nombres' existe antes de usarla
$userName = isset($_SESSION['nombres']) ? $_SESSION['nombres'] : 'Invitado';

?>
<head>
  <!-- Usa los enlaces CSS que necesitas -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<!-- SideBar -->
<section class="full-box cover dashboard-sideBar" style="background-color: rgba(0, 84, 58, 0.5);">
  <div class="full-box dashboard-sideBar-bg btn-menu-dashboard"></div>
  <div class="full-box dashboard-sideBar-ct" style="background-color: rgba(0, 84, 58, 0.5);">
    <!--SideBar Title -->
    <div class="full-box text-uppercase text-center text-titles dashboard-sideBar-title">
      LICENCIA F. <i class="zmdi zmdi-close btn-menu-dashboard visible-xs"></i>
    </div>
    <!-- SideBar User info -->
    <div class="full-box dashboard-sideBar-UserInfo" style="background-color: rgba(0, 84, 58, 0.5);">
      <figure class="full-box">
        <!-- Asegúrate de que esta ruta de imagen sea correcta -->
        <img src="../public/assets/img/avatar.jpg" alt="UserIcon">
        <!-- Muestra el nombre usando la variable verificada -->
        <figcaption class="text-center text-titles"><?php echo $userName; ?></figcaption>
      </figure>
      <ul class="full-box list-unstyled text-center">
        <li>
          <!-- Asumiendo que btn-exit-system maneja el cierre de sesión -->
          <a href="#!" class="btn-exit-system">
            <i class="zmdi zmdi-power"></i>
          </a>
        </li>
        <li>
          <a href="#!" class="btn-modal-help">
            <i class="zmdi zmdi-help-outline"></i>
          </a>
        </li>
      </ul>
    </div>
    <!-- SideBar Menu -->
    <ul class="list-unstyled full-box dashboard-sideBar-Menu" style="background-color: rgba(0, 84, 58, 0.5);">
      <li>
        <a href="home.php">
          <i class="fas fa-cogs"></i> Inicio
        </a>
      </li>
      <li>
        <a href="tramite.php">
          <i class="fas fa-copy"></i> Tramite
        </a>
      </li>
      <li>
        <a href="giros.php">
          <i class="fas fa-tags"></i> Giros
        </a>
      </li>
      <li>
        <a href="usuario.php">
          <i class="fas fa-users"></i> Usuario
        </a>
      </li>
      <li>
        <a href="tienda.php">
          <i class="fas fa-store"></i> Tienda
        </a>
      </li>

      <li>
        <a href="consultalicencia.php">
          <i class="fas fa-search"></i> Consulta de Licencia
        </a>
      </li>
      <li>
        <a href="Estadisticas.php">
          <i class="fas fa-chart-pie"></i> Estadisticas Graficas
        </a>
      </li>
      <li>
        <a href="mapamiembros.php">
          <i class="fas fa-map"></i> Mapa ubicacional de miembros
        </a>
      </li>
    </ul>
  </div>
</section>
