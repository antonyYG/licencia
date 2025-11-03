$(document).ready(function(){
	listartramite();
	mostrartramite();
	listartramiteedit();
	listargiross();
	listargirossComer();

	$("#btn_registrar").click(function(e){
	e.preventDefault();
	var datos = $("#form_parttramite").serialize();
	var nombrecomercial = $("#nombrecomercial").val();
	var giro = $("#giro").val();
	var recibotes = $("#recibotes").val();
	var vigencia = $("#vigencia").val();
	var fechingreso = $("#fechingreso").val();
	var fechexpedicion = $("#fechexpedicion").val();
	var expediente = $("#expediente").val();
	var numresolucion = $("#numresolucion").val();
	var numresolucion_itse = $("#numresolucion_itse").val(); // Nuevo campo agregado
	var expedicion_itse = $("#expedicionitse").val(); // Nuevo campo agregado
	var vigencia_itse = $("#vigenciaitse").val(); // Nuevo campo agregado
	var tipolicencia = document.getElementById('tipolicencia');
	if (
		nombrecomercial.length == 0 ||
		giro.length == 0 ||
		recibotes.length == 0 ||
		fechingreso.length == 0 ||
		fechexpedicion.length == 0 ||
		expediente.length == 0 ||
		numresolucion.length == 0 ||
		numresolucion_itse.length == 0 || 
		expedicion_itse.length == 0 || 
		vigencia_itse.length == 0 
	) {
		toastr.info("Ingresar los datos respectivos","Licencia");
	} else if (tipolicencia.value == 0 || tipolicencia.value == "") {
		toastr.info("Seleccionar el tipo de Licencia","Licencia");
	} else {
		$.ajax({
				"url": "../controller/registrotramite.php?boton=insertar",
				"method": "post",
				"data": datos
			})
			.done(function(data) {
				if (data == "1") {
					swal({
						title: 'Se registro exitosamente!',
						text: "¿Se esta generando su Tramite de Licencia",
						type: 'success',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Generar Licencia',
						allowOutsideClick: false
					}).then(function() {
						window.open("../public/pdf/tramitelicencia.php?idtramite=" + expediente, '_blank');
						location.href = "tramite.php";
					});
					$("#form_parttramite").trigger('reset');
				} else {
					toastr.error("No se pudo registrar","Licencia");
				}
			});
	}
});

$("#btn_editar").click(function() {
	var datos = $("#form_parttramiteedit").serialize();
	$.ajax({
		"url": "../controller/registrotramite.php?boton=editar",
		"method": "post",
		"data": datos
	})
	.done(function(data) {
		if (data == "1") {
			toastr.success("Se actualizó correctamente", "Licencia");
			setTimeout('location.href="tramite.php"', 500);
		} else {
			toastr.error("No se pudo actualizar", "Licencia");
		}
	});
});


	$("#tipolicencia").on("change", function(e){
		e.preventDefault();
		var tipo=$("#tipolicencia").val();
		if (tipo==0 || tipo=="") {
			$("#numdoc").val('');
		}else{
			$.post('../controller/registrotramite.php?boton=seleccion',{tipo:tipo}, function(data){
			data=JSON.parse(data);
			$("#numdoc").val(data.tipoli);
		});
	   }	
	});

});	



function listartramite(){
	var tabla=$("#datatabletienda").DataTable({
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/registrotramite.php?boton=listartienda",
			"method":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"numruc"},
			{"data":"nombres_per"},
			{"data":"apellidop_per"},
			{"data":"apellidom_per"},
			{"data":'selec'}
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
	obtener_datotienda("#datatabletienda tbody", tabla);
}

var obtener_datotienda=function(tbody, tabla){
	$(tbody).on("click", ".seleccion", function(){
		var data=tabla.row($(this).parents("tr")).data();
		$("#nombresLi").val(data.nombres_per);
		$("#apellidopli").val(data.apellidop_per);
		$("#apellidomli").val(data.apellidom_per);
		$("#numruc").val(data.numruc);
		$("#comercial").val(data.nombregiro);
		$("#ubicacion").val(data.ubic_tienda);
		$("#arealocal").val(data.area_tienda);
		$("#idtiendass").val(data.idtienda);
		$("#modallistar").modal("hide");
	});
}

//*edicion*/
function listartramiteedit(){
	var tabla=$("#datatabletiendaedit").DataTable({
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/registrotramite.php?boton=listartiendaedit",
			"method":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"numruc"},
			{"data":"nombres_per"},
			{"data":"apellidop_per"},
			{"data":"apellidom_per"},
			{"data":'selecedit'}
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
	obtener_datotiendaedit("#datatabletiendaedit tbody", tabla);
}

var obtener_datotiendaedit=function(tbody, tabla){
	$(tbody).on("click", ".seleccionedit", function(){
		var data=tabla.row($(this).parents("tr")).data();
		$("#nombresLiedit").val(data.nombres_per);
		$("#apellidopliedit").val(data.apellidop_per);
		$("#apellidomliedit").val(data.apellidom_per);
		$("#numrucedit").val(data.numruc);
		$("#ubicacionedit").val(data.ubic_tienda);
		$("#arealocaledit").val(data.area_tienda);
		$("#idtiendassedit").val(data.idtienda);
		$("#modallistaredit").modal("hide");
	});
}

function mostrartramite(){
	var licencia=$("#licenciatable").DataTable({
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
}

//*comercio*/
function listargiross(){
	var tabla=$("#datatablegiros").DataTable({
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/registrotramite.php?boton=listarcomercio",
			"method":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"nombregiro"},
			{"data":'botonseleccion'}
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
	obtener_giros("#datatablegiros tbody", tabla);
}

var obtener_giros=function(tbody, tabla){
	$(tbody).on("click", ".selecciongiro", function(){
		var data=tabla.row($(this).parents("tr")).data();
		$("#giro").val(data.idgiro);
		$("#idgirocomer").val(data.nombregiro);
		$("#modallistarcomercio").modal("hide");
	});
}

//*comercio*/
function listargirossComer(){
	var tabla=$("#datatablegiroscom").DataTable({
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/registrotramite.php?boton=listarcomercioedit",
			"method":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"nombregiro"},
			{"data":'botonseleccion'}
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
	obtener_girosedit("#datatablegiroscom tbody", tabla);
}

var obtener_girosedit=function(tbody, tabla){
	$(tbody).on("click", ".selecciongiroedit", function(){
		var data=tabla.row($(this).parents("tr")).data();
		$("#comercialedit").val(data.idgiro);
		$("#idgirocomerci").val(data.nombregiro);
		$("#modallistarcomerciogiro").modal("hide");
	});
}

function imprimir(url, ancho, alto) {
                var posicion_x;
                var posicion_y;
                posicion_x = (screen.width / 2) - (ancho / 2);
                posicion_y = (screen.height / 2) - (alto / 2);
                window.open(url, "documento", "width=" + ancho + ",height=" + alto + ",menubar=0,toolbar=0,directories=0,scrollbars=no,resizable=no,left=" + posicion_x + ",top=" + posicion_y + "");
 }
