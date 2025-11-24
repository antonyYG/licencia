$(document).ready(function(){
	listartramite();
	mostrartramite();
	listartramiteedit();
	listargiross();
	listargirossComer();

	// Generar Exp. N° automáticamente si está vacío (formato: EXP-YYYY-YYYYMMDDHHmmss)
	(function autoGenExpediente(){
		var $exp = $("#expediente");
		if ($exp.length && !$exp.val()){
			var now = new Date();
			var yyyy = now.getFullYear();
			var pad = n => (""+n).padStart(2,"0");
			var stamp = `${yyyy}${pad(now.getMonth()+1)}${pad(now.getDate())}${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}`;
			$exp.val(`EXP-${yyyy}-${stamp}`);
		}
	})();

	// Helpers de concatenación y validación
    // Sufijos solo se aplican en el reporte; aquí validamos números
	function onlyDigits(str){ return str.replace(/\D+/g,''); }
	function isDigits(str){ return /^\d+$/.test(str); }
	function isAfter(dateA, dateB){
		var a = new Date(dateA);
		var b = new Date(dateB);
		return a instanceof Date && !isNaN(a) && b instanceof Date && !isNaN(b) && a.getTime() > b.getTime();
	}

    // Enforce numérico en campos correspondientes
    $("#recibotes").on("input", function(){ this.value = onlyDigits(this.value); });
    $("#numresolucion").on("input", function(){ this.value = onlyDigits(this.value); });
    $("#numresolucion_itse").on("input", function(){ this.value = onlyDigits(this.value); });

	// Comportamiento de Tipo de Licencia: Indeterminada vs Temporal
	$("#tipolicencia").on("change", function(){
		var tipo = $(this).val();
		var $vig = $("#vigencia");
		if (tipo === "1"){
			$vig.val("");
			$vig.prop("disabled", true);
		}else{
			$vig.prop("disabled", false);
		}
	});

	$("#btn_registrar").click(function(e){
	e.preventDefault();
	// NOTA: serializaré después de preparar los valores finales
	var nombrecomercial = $("#nombrecomercial").val();
	var giro = $("#giro").val();
	var recibotes = $("#recibotes").val();
	var vigencia = $("#vigencia").val();
	var fechingreso = $("#fechingreso").val();
	var fechexpedicion = $("#fechexpedicion").val();
	var expediente = $("#expediente").val();
	var numresolucion = $("#numresolucion").val();
	var idtienda = $("#idtiendass").val();
	var numdocval = $("#numdoc").val();
	var numresolucion_itse = $("#numresolucion_itse").val(); // Nuevo campo agregado
	var expedicion_itse = $("#expedicionitse").val(); // Nuevo campo agregado
	var vigencia_itse = $("#vigenciaitse").val(); // Nuevo campo agregado
	var tipolicencia = document.getElementById('tipolicencia');

    // Validaciones estrictas: solo dígitos
    if (recibotes && !isDigits(recibotes)) { return toastr.error("N° recibo Tesorería debe ser numérico","Licencia"); }
    if (numresolucion && !isDigits(numresolucion)) { return toastr.error("N° resolución debe ser numérico","Licencia"); }
    if (numresolucion_itse && !isDigits(numresolucion_itse)) { return toastr.error("N° resolución ITSE debe ser numérico","Licencia"); }

	// Validación de fechas ITSE: vigencia posterior a expedición
	if (expedicion_itse && vigencia_itse && !isAfter(vigencia_itse, expedicion_itse)){
		return toastr.error("Vigencia ITSE debe ser posterior a Expedición ITSE","Licencia");
	}

	// Reglas de tipo de licencia
	var tipoLicVal = tipolicencia.value;
    if (tipoLicVal === "2"){
        // Temporal: vigencia requerida
        if (!vigencia || vigencia.length === 0){
            return toastr.info("Vigencia es requerida para licencia Temporal","Licencia");
        }
    }

	// Validaciones adicionales obligatorias
	if (!idtienda || idtienda.length === 0){
		return toastr.info("Seleccione una tienda para el tramitante","Licencia");
	}
	if (!numdocval || numdocval.length === 0){
		return toastr.info("El N° Doc debe generarse al elegir tipo de licencia","Licencia");
	}
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
        // Serializar datos exactamente como se ingresan
        var datos = $("#form_parttramite").serialize();

		$.ajax({
				"url": "../controller/registrotramite.php?boton=insertar",
				"method": "post",
				"data": datos
			})
			.done(function(data) {
				var raw = (typeof data === 'string') ? data : (data && data.toString ? data.toString() : '');
				var resp = (raw || '').trim();
				if (resp === "1") {
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
					var msg = "No se pudo registrar";
					var idx = resp.indexOf('0|');
					if (idx >= 0) {
						msg = resp.substring(idx + 2).trim() || msg;
					} else if (resp && resp !== '0') {
						// Si el servidor retornó un texto distinto a 1/0, mostrarlo directamente
						msg = resp;
					}
					console.error('Registro error:', resp);
					toastr.error(msg, "Licencia");
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown){
				console.error('AJAX fail:', textStatus, errorThrown, jqXHR && jqXHR.responseText);
				toastr.error('Error de red: ' + textStatus, 'Licencia');
			});
    }
});

$("#btn_editar").click(function() {
    // Validación estricta en edición
    var resolEdit = $("#numresolucionedit").val();
    var resolITSEEdit = $("#numresolucion_itse").val();
    var reciboEdit = $("#recibotesedit").val();
    if (reciboEdit && !isDigits(reciboEdit)) { return toastr.error("N° recibo Tesorería debe ser numérico","Licencia"); }
    if (resolEdit && !isDigits(resolEdit)) { return toastr.error("N° resolución debe ser numérico","Licencia"); }
    if (resolITSEEdit && !isDigits(resolITSEEdit)) { return toastr.error("N° resolución ITSE debe ser numérico","Licencia"); }

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
			var msg = "No se pudo actualizar";
			if (typeof data === 'string' && data.indexOf('0|') === 0) {
				msg = data.substring(2);
			}
			toastr.error(msg, "Licencia");
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
