<?php 
session_start();

	require_once "../model/Usuario.php";
	$use=new Usuario();
	$user=$use->contaruser();
	$lice=$use->contarlicencia();
	$tien=$use->contartienda();
	$giro=$use->contargiro();
	$zona=$use->contarzonas();
	
?>
<!DOCTYPE html>
<html lang="es">
    <meta charset="UTF-8">

<head>
	<?php require_once "head.php"; ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

	<style type="text/css">

	.full-box.tile-title {
		background-color: #00543A;
		box-shadow: 0 0 20px white; /* Cambia el valor para ajustar la sombra */
	}

	.full-box.tile-icon i {
		color: #00543A;
		
	}

	.full-box.tile-number {
		border-color: #00543A;
		color: #00543A;
	}

    .full-box.tile:hover {
		background-color: rgba(0, 56, 37, 0.5);
	}

	.full-box.tile:hover .tile-title {
		background-color: rgba(0, 56, 37, 0.5);
	}

	.full-box.tile:hover .tile-icon i {
		color: rgba(0, 56, 37, 0.5);
	}

	.full-box.tile:hover .tile-number {
		border-color: rgba(0, 56, 37, 0.5);
		color: rgba(0, 56, 37, 0.5);
	}
	
  .notification-badge {
    position: absolute;

    border-radius: 50%;
    color: white;
    font-size: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .has-notifications {
    color: red;
  }
</style>

</head>
<body>
	<?php require_once "menu.php"; ?>

	<!-- Content page-->
	<section class="full-box dashboard-contentPage">
		<!-- NavBar -->
			<nav class="full-box dashboard-Navbar" >
			   <li id="btn-notificaciones" style="float: right;">
  <span class="fa-stack fa-lg">
    <i class="fas fa-bell fa-stack-1x"></i>
    <span class="notification-badge" id="notification-badge"></span>
  </span>
</li>
		<ul class="full-box list-unstyled text-right">
			<li class="pull-left">
				<a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
			</li>
		</ul>
	</nav>
		<!-- Content page -->
<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">Panel de control</h1>
  </div>
</div>

		<div class="full-box text-center" style="padding: 30px 10px;">
<a href="usuario.php">
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Usuarios
		</div>
		<div class="full-box tile-icon text-center">
			<i class="fas fa-users"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $user; ?></p>
			<small>Registros</small>
		</div>
	</article>
</a>

			<a href="tramite.php">
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Licencias
		</div>
		<div class="full-box tile-icon text-center">
			<i class="fas fa-copy"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $lice; ?></p>
			<small>Registros</small>
		</div>
	</article>
</a>

		<a href="tienda.php">
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Tiendas
		</div>
		<div class="full-box tile-icon text-center">
			<i class="fas fa-store"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $tien; ?></p>
			<small>Registros</small>
		</div>
	</article>
</a>


<a href="giros.php">
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Giros
		</div>
		<div class="full-box tile-icon text-center">
			<i class="fas fa-tags"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $giro; ?></p>
			<small>Registros</small>
		</div>
	</article>
</a>





			<a href="Estadisticas.php">
	        <article class="full-box tile">
	       	<div class="full-box tile-title text-center text-titles text-uppercase">
			Estadisticas Graficas
		</div>
		<div class="full-box tile-icon text-center" style="text-align: center;">
			<i class="fas fa-chart-pie"></i>
		</div>
		<div class="full-box tile-number text-titles">
					<p class="full-box"><?php echo $zona; ?></p>
					<small>Graficos</small>
				</div>
	</article>
</a>

<a href="mapamiembros.php">
	        <article class="full-box tile">
	       	<div class="full-box tile-title text-center text-titles text-uppercase">
			Mapa
		</div>
		<div class="full-box tile-icon text-center" style="text-align: center;">
			<i class="fas fa-map"></i>
		</div>
		<div class="full-box tile-number text-titles">
					<p class="full-box"><?php echo $lice; ?></p>
					<small>Registros</small>
				</div>
	</article>
</a>

<a href="consultalicencia.php">
	        <article class="full-box tile">
	       	<div class="full-box tile-title text-center text-titles text-uppercase">
			Consultar licencias
		</div>
		<div class="full-box tile-icon text-center" style="text-align: center;">
			<i class="fas fa-search"></i>
		</div>
		<div class="full-box tile-number text-titles">
					<p class="full-box"><?php echo $lice; ?></p>
					<small>Cantidad de licencias</small>
				</div>
	</article>
</a>


		</div>
	</section>


	<!-- Dialog help -->
	<div class="modal fade" tabindex="-1" role="dialog" id="Dialog-Help">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    	<h4 class="modal-title">Ayuda</h4>
			    </div>
			    <div class="modal-body">
			        <p>
			        	Consulte el manual de usuario, o cierre sesión se existe algun tipo de error.
			        </p>
			    </div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-primary btn-raised" data-dismiss="modal"><i class="zmdi zmdi-thumb-up"></i> Ok</button>
		      	</div>
		    </div>
	  	</div>
	</div>
	<?php
$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_de_datos = 'licencia3';

$conexion = mysqli_connect($host, $usuario, $contraseña, $base_de_datos);

if (!$conexion) {
    die('Error al conectar a la base de datos: ' . mysqli_connect_error());
}


$consulta = "SELECT COUNT(*) as total_licencias FROM licencia";
$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die('Error al realizar la consulta: ' . mysqli_error($conexion));
}

$fila = mysqli_fetch_assoc($resultado);
$total_licencias = $fila['total_licencias'];

mysqli_free_result($resultado);

$consulta1 = "SELECT COUNT(*) as total_tienda FROM tienda";
$resultado1 = mysqli_query($conexion, $consulta1);

if (!$resultado1) {
    die('Error al realizar la consulta: ' . mysqli_error($conexion));
}

$fila1 = mysqli_fetch_assoc($resultado1);
$total_tiendas = $fila1['total_tienda'];

mysqli_free_result($resultado1);

echo '<script>
  document.getElementById("btn-notificaciones").addEventListener("click", function() {
    // Notificación de licencias
    Toastify({
      text: "El sistema tiene ' .$total_licencias. ' licencias",
      duration: 3000,
      gravity: "top",
      positionLeft: false,
      backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
      className: "info",
    }).showToast();

    // Notificación adicional
    Toastify({
      text: "El sistema tiene '. $total_tiendas .' tiendas",
      duration: 3000,
      gravity: "top",
      positionLeft: false,
      backgroundColor: "linear-gradient(to right, #ff7f50, #ff4500)",
      className: "info",
    }).showToast();
  });
</script>';

?>

	<?php require_once "script.php"; ?>
<script>
  // Obtener el número de notificaciones pendientes (ejemplo: 2)
  var numeroNotificacionesPendientes = 2;

  // Actualizar el valor del punto rojo de notificación
  var badge = document.getElementById("notification-badge");
  var icon = document.querySelector("#btn-notificaciones i");
  
  if (numeroNotificacionesPendientes > 0) {
    badge.style.display = "block";
    badge.innerText = numeroNotificacionesPendientes;
    icon.classList.add("has-notifications");
  } else {
    badge.style.display = "none";
    icon.classList.remove("has-notifications");
  }
</script>




</body>
</html>