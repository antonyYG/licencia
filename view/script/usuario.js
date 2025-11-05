$(document).ready(function(){
	listar();
	$("#usuarioregistrar").click(function () {
    var nombres = $("#nombres").val(),
        apellidop = $("#apellidop").val(),
        apellidom = $("#apellidom").val(),
        dni = $("#dni").val(),
        direccion = $("#direccion").val(),
        correo = $("#correo").val(),
        contrasena = $("#contrasena").val(),
        repitecontrasena = $("#repitecontrasena").val(),
        rol = $("#rol").val();

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (
        nombres.length == 0 ||
        apellidop.length == 0 ||
        apellidom.length == 0 ||
        dni.length == 0 ||
        direccion.length == 0 ||
        correo.length == 0 ||
        contrasena.length == 0 ||
        repitecontrasena.length == 0 ||
        rol.length == 0
    ) {
        toastr.info("Ingresar los datos respectivos", "Usuario");
    } else if (dni.length != 8) {
        toastr.warning("El DNI tiene que tener 8 dígitos", "Usuario");
    } else if (!emailRegex.test(correo)) {
        toastr.warning("Ingrese un correo electrónico válido", "Usuario");
    } else if (contrasena != repitecontrasena) {
        toastr.warning("La contraseña no coincide", "Usuario");
    } else {
        $.post(
            "../controller/usuario.php?boton=insertar",
            {
                nombres: nombres,
                apellidop: apellidop,
                apellidom: apellidom,
                dni: dni,
                direccion: direccion,
                correo: correo,
                contrasena: contrasena,
                repitecontrasena: repitecontrasena,
                rol: rol,
            },
            function (rsp) {
				if (rsp === "1") {
					toastr.success("Se registró exitosamente", "Usuario");
					$("#modalinsertarUser").modal("hide");
					limpiar();
					table.ajax.reload();
				} else if (rsp === "correo_duplicado") {
					toastr.warning("El correo ya está registrado", "Usuario");
				} else {
					toastr.error("No se pudo registrar", "Usuario");
				}
			}
        );
    }
});

	$(document).on("click", ".actua", function() {
		$("#modaleditarUser").modal("show");
		var idusuario = $(this).data("id");
		$.ajax({
			url: "../controller/usuario.php?boton=motrarpersona",
			method: "post",
			data: { idusuario: idusuario },
			dataType: "json"
		}).done(function(data) {
			$("#idusuario").val(data.idusuario);
			$("#nombresedit").val(data.nombres);
			$("#apellidopedit").val(data.apellidop);
			$("#apellidomedit").val(data.apellidom);
			$("#dniedit").val(data.dni);
			$("#direccionedit").val(data.direccion);
			$("#correoedit").val(data.correo); 
			$("#roledit").val(data.rol);      
		});
	});

	$("#btnedita").click(function() {
    var idusuario = $("#idusuario").val(),
        nombres = $("#nombresedit").val(),
        apellidop = $("#apellidopedit").val(),
        apellidom = $("#apellidomedit").val(),
        dni = $("#dniedit").val(),
        direccion = $("#direccionedit").val(),
        correo = $("#correoedit").val(),
        rol = $("#roledit").val(),     
        contrasena = $("#contrasenaedit").val(),
        repitecontrasena = $("#repitecontrasenaedit").val();

    if (dni.length != 8) {
        toastr.warning("El DNI debe tener 8 dígitos", "Usuario");
    } else if (contrasena != repitecontrasena) {
        toastr.warning("Las contraseñas no coinciden", "Usuario");
    } else {
			$.post("../controller/usuario.php?boton=editar", {
				idusuario: idusuario,
				nombres: nombres,
				apellidop: apellidop,
				apellidom: apellidom,
				dni: dni,
				direccion: direccion,
				correo: correo, 
				rol: rol,   
				contrasena: contrasena,
				repitecontrasena: repitecontrasena
			}, function(rsp) {
				if (rsp == "1") {
					toastr.success("Se actualizó exitosamente", "Usuario");
					$("#modaleditarUser").modal("hide");
					table.ajax.reload();
				} else if (rsp == "correo_duplicado") {
					toastr.warning("El correo ya está registrado en otro usuario", "Usuario");
				} else {
					toastr.error("No se pudo actualizar", "Usuario");
				}
			});
		}
	});

	$(document).on("click", "#deshabilitarespe", function(){
		var idusuario=$("#idinactivo").val();
		$.post("../controller/usuario.php?boton=inactivo", {idusuario:idusuario}, function(rsp){
			if (rsp=="1") {
				toastr.success("El usuario esta deshabilitado","Usuario");
				$("#modalinactivoEspe").modal("hide");
				table.ajax.reload();
			}else{
				toastr.error("no se pudo deshabilitado","Usuario");
			}
		});
	});

	$(document).on("click", "#habilitarespe", function(){
		var idusuario=$("#idactivo").val();
		$.post("../controller/usuario.php?boton=activo", {idusuario:idusuario}, function(rsp){
			if (rsp=="1") {
				toastr.success("El usuario esta habilitado","Usuario");
				$("#modalactivoEspe").modal("hide");
				table.ajax.reload();
			}else{
				toastr.error("no se pudo habilitar","Usuario");
			}
		});
	});

	


});
var table;

function listar(){
	 table=$("#tablatiendausuario").DataTable({
	 	"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/usuario.php?boton=listar",
			"method":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"nombres"},
			{"data":"apellidop"},
			{"data":"apellidom"},
			{"data":"dni"},
			{"data":"edit"},
			{"data":"condicion"}
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
	obtener_datoinactivo("#tablatiendausuario tbody", table);
	obtener_datosactivo("#tablatiendausuario tbody", table);
}

var obtener_datoinactivo=function(tbody, table){
	$(tbody).on("click", ".inactivo", function(){
		var data=table.row($(this).parents("tr")).data();
		$("#idinactivo").val(data.idpersona);
		$("#modalinactivoEspe").modal({backdrop:'static', keyboard:false});
		$("#modalinactivoEspe").modal("show");
	});
}

var obtener_datosactivo=function(tbody, table){
	$(tbody).on("click", ".activo", function(){
		var data=table.row($(this).parents("tr")).data();
		$("#idactivo").val(data.idpersona);
		$("#modalactivoEspe").modal({backdrop:'static', keyboard:false});
		$("#modalactivoEspe").modal("show");
	});
}

function limpiar(){
	$("#nombres").val('');
	$("#apellidop").val('');
	$("#apellidom").val('');
	$("#dni").val('');
	$("#direccion").val('');
	$("#contrasena").val('');
	$("#repitecontrasena").val('');
}





