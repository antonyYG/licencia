$(document).ready(function(){
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
				if (rsp=="3") {
					toastr.success("Bienvenido usuario","LICENCIA");
					setTimeout("location.href='view/home.php'", 500);
				}else if (rsp=="4"){
					toastr.error("Usted ha sido bloqueado por el Administrador!", "LICENCIA");
				}else if (rsp=="5") {
					toastr.warning("la contraseña es incorrecta, vuelva a ingresar!", "LICENCIA");
				}else if (rsp=="6") {
					toastr.warning("el dni es incorrecto","LICENCIA");
				}
			});
		}
	});
});