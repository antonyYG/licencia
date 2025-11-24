$(document).ready(function(){
	// Helper to handle lock responses like '7|45'
	function handleLockResponse(rsp) {
		if (rsp.indexOf && rsp.indexOf('7|') === 0) {
			var parts = rsp.split('|');
			var sec = parseInt(parts[1], 10) || 0;
			toastr.error('Usuario bloqueado temporalmente. Intenta de nuevo en ' + sec + ' segundos.', 'LICENCIA');
			$('#formlogin :input').prop('disabled', true);
			var countdown = setInterval(function(){
				sec--;
				if (sec <= 0) {
					clearInterval(countdown);
					$('#formlogin :input').prop('disabled', false);
					toastr.info('Ya puedes intentar ingresar de nuevo.', 'LICENCIA');
				}
			}, 1000);
			return true;
		}
		return false;
	}

	// Al cargar la página, consultar al servidor si hay bloqueo activo
	$.ajax({
		url: 'controller/usuario.php?boton=check_lock',
		method: 'get'
	}).done(function(rsp){
		handleLockResponse(rsp);
	});

	$("#formlogin").submit(function(e){
		e.preventDefault();
		var dni=$("#dni").val();
		var contrasena=$("#contrasena").val();
		if (dni.length==0 || contrasena.length==0) {
			toastr.info("Ingresar los datos respectivos","LICENCIA");
		}else if (dni.length!=8) {
			toastr.warning("El dni tiene que tener 8 dijitos","LICENCIA");
		}else{
			$.ajax({
			"url":"controller/usuario.php?boton=login",
			"method":"post",
			"data":{dni:dni, contrasena:contrasena}
			}).done(function(rsp){
				if (rsp.indexOf && rsp.indexOf('7|') === 0) {
					// Bloqueo temporal, formato: 7|seconds
					var parts = rsp.split('|');
					var sec = parseInt(parts[1], 10) || 0;
					toastr.error('Usuario bloqueado temporalmente. Intenta de nuevo en ' + sec + ' segundos.', 'LICENCIA');
					// opcional: deshabilitar form
					$('#formlogin :input').prop('disabled', true);
					// contador simple para reactivar (opcional)
					var countdown = setInterval(function(){
						sec--;
						if (sec <= 0) {
							clearInterval(countdown);
							$('#formlogin :input').prop('disabled', false);
							toastr.info('Ya puedes intentar ingresar de nuevo.', 'LICENCIA');
						}
					}, 1000);
				} else if (rsp.indexOf && rsp.indexOf('5|') === 0) {
					// Contraseña incorrecta con intentos restantes: 5|left
					var left = parseInt(rsp.split('|')[1], 10) || 0;
					toastr.warning('Contraseña incorrecta. Intentos restantes: ' + left, 'LICENCIA');
				} else if (rsp=="3") {
					toastr.success("Bienvenido usuario","LICENCIA");
					setTimeout("location.href='view/home.php'", 500);
				} else if (rsp=="4"){
					toastr.error("Usted ha sido bloqueado por el Administrador!", "LICENCIA");
				} else {
					// fallback
					toastr.error('Respuesta inesperada del servidor: ' + rsp, 'LICENCIA');
				}
			});
		}
	});
});