<!DOCTYPE html>
<html lang="es">
<head>
	<title>Licencia</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="public/css/main.css">
	<link rel="stylesheet" type="text/css" href="public/toastr/css/toastr.css">
	<style type="text/css">
		.img{
			background-size: 100% 100%;
			background-attachment: fixed;
		}
		#formlogin {
		background-color: rgba(0, 84, 58, 0.7);
		border: none;
		box-shadow: 0 0 20px #0a0b0bff; /* Cambia el valor para ajustar la sombra */
	}
.btn-custom {
		background-color: #00543A !important;
		color: white;
		border-color: #00543A !important;
	}

	.btn-custom:hover,
	.btn-custom:focus {
		background-color: #003825 !important;
		border-color: #003825 !important;
	}

	</style>
</head>
<body class="cover img" style="background-image: url(files/img/Nivel-finalizado.png);">
	<form id="formlogin" autocomplete="off" class="full-box logInForm">
		<p class="text-center text-muted"><i class="zmdi zmdi-account-circle zmdi-hc-5x"></i></p>
		<p class="text-center text-dark text-uppercase" style="color: white;">Inicia sesión con tu cuenta</p>

		<div class="form-group label-floating">
		  <label class="control-label" for="UserEmail" style="color: white;">Dni</label>
		  <input class="form-control" id="dni" name="dni" type="text" maxlength="8" style="color: white;" onkeypress="return numeros(event)">
		  <p class="help-block" style="color: white;">Escribe tú Dni</p>
		</div>
		<div class="form-group label-floating">
		  <label class="control-label" for="UserPass" style="color: white;">Contraseña</label>
		  <input class="form-control" id="contrasena" name="contrasena" type="password" style="color: white;">
		  <p class="help-block" style="color: white;">Escribe tú contraseña</p>
		</div>
		<div class="form-group text-center">
			<input type="submit" value="Iniciar sesión" class="btn btn-raised btn-custom" style="color: white;">
		</div>
	</form>
	<!--====== Scripts -->
	<script src="public/js/jquery-3.1.1.min.js"></script>
	<script src="public/js/bootstrap.min.js"></script>
	<script src="public/js/material.min.js"></script>
	<script src="public/js/ripples.min.js"></script>
	<script src="public/js/sweetalert2.min.js"></script>
	<script src="public/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="public/js/main.js"></script>
	<script type="text/javascript" src="view/script/login.js"></script>
	<script type="text/javascript" src="public/toastr/js/toastr.min.js"></script>
	<script type="text/javascript" src="view/script/validacion.js"></script>
	<script>
		$.material.init();
	</script>
</body>
</html>