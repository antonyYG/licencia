$(document).ready(function(){
	$("#registrar").click(function(){
    var datos = $("#formtienda").serialize();
    var ruc = $("#ruc").val();
    // Nuevo: obtener DNI del formulario
    var dni = $("#dni").val();
    var nombres = $("#nombres").val();
    var apellidop = $("#apellidop").val();
    var apellidom = $("#apellidom").val();
    var ubicacion = $("#ubicacion").val();
    var area = $("#area").val();
    var latitud = $("#latitud").val();
    var longitud = $("#longitud").val();
    var zona = $("#zona").val();
    var celular = $("#celular").val();

    if (ruc.length == 0 || nombres.length == 0 || apellidop.length == 0 || apellidom.length == 0) {
        toastr.info("Ingresar los datos respectivos", "Tienda");
    } else {
        // Asegurar que las coordenadas tengan decimales
        if(latitud && longitud) {
            // Convertir a números para asegurar formato decimal
            datos += "&latitud=" + parseFloat(latitud) + "&longitud=" + parseFloat(longitud);
        }
        
        // Enviamos también el DNI serializado en el formulario
        $.ajax({
            "url": "../controller/tienda.php?boton=insertar",
            "method": "post",
            "data": datos
        }).done(function(rsp){
            if (rsp == "1") {
                toastr.success("Se registró exitosamente", "Tienda");
                limpiar();
                tabla.ajax.reload();
            } else {
                toastr.error("No se pudo registrar", "Tienda");
            }
        });
    }
});

	$(document).on('click', '.actualizar', function() {
    const id = $(this).data("id");
    console.log("ID obtenido:", id);
    
    if (!id) {
        toastr.error("No se pudo obtener el ID de la tienda", "Error");
        return;
    }

    $("#modaleditar").modal({ backdrop: 'static', keyboard: false });
    $("#modaleditar").modal("show");

    $.ajax({
        url: "../controller/tienda.php?boton=mostrartienda",
        method: "post",
        data: { idtienda: id },
        dataType: "json",
        success: function(rsp) {
            console.log("Respuesta del backend:", rsp);

            // Mostrar el ID en la consola o en un campo del modal
            $("#idtienda").val(rsp.idtienda); // si tienes un input hidden para el ID
            $("#tituloModal").text("Editando tienda ID: " + rsp.idtienda); // si quieres mostrarlo en el título

            // Y llenar los demás campos
            $("#rucedit").val(rsp.ruc);
            $("#nombresedit").val(rsp.nombres);
            $("#apellidopedit").val(rsp.apellidop);
            $("#apellidomedit").val(rsp.apellidom);
            $("#ubicacionedit").val(rsp.ubicacion);
            $("#areaedit").val(rsp.area);
            // Asegurar que las coordenadas tengan decimales al cargar
            $("#latitudedit").val(parseFloat(rsp.latitud));
            $("#longitudedit").val(parseFloat(rsp.longitud));
            $("#zonaedit").val(rsp.zona);
            $("#celularedit").val(rsp.celular);
            // Nuevo: completar el DNI en la edición
            $("#dniedit").val(rsp.dni);
        },
        error: function(xhr) {
            console.error("Error AJAX:", xhr.responseText);
            toastr.error("Error al obtener datos de la tienda", "Tienda");
        }
    });
});

	$("#edita").click(function(){
		var datos=$("#formtiendaedita").serialize();
        // Asegurar que las coordenadas tengan decimales en la edición
        var latitud = $("#latitudedit").val();
        var longitud = $("#longitudedit").val();
        if(latitud && longitud) {
            datos += "&latitud=" + parseFloat(latitud) + "&longitud=" + parseFloat(longitud);
        }
        
			$.ajax({
					"url":"../controller/tienda.php?boton=editar",
					"method":"post",
					"data":datos
				}).done(function(rsp){
					if (rsp=="1") {
						toastr.success("Se actualizo exitosamente", "Tienda");
						$("#modaleditar").modal("hide");
						tabla.ajax.reload();
					}else{
						toastr.error("No se pudo actualizar", "Tienda");
					}
				});
			});

});

var tabla;

function init(){
	listartienda();
}

function listartienda(){
	tabla=$("#tablatienda").DataTable({
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/tienda.php?boton=lista",
			"method":"post",
			"dataType":"json"
		},
        "columns":[
            {"data":"numruc"},
            // Nueva columna para mostrar el DNI
            {"data":"dni"},
            {"data":"nombres_per"},
            {"data":"apellidop_per"},
            {"data":"apellidom_per"},
        
            {"data":"edita"}
            
            
        ],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
}

function abrirmodal(){
	$("#modalinsertar").modal({backdrop:'static', keyboard:false});
	$("#modalinsertar").modal("show");
}


function limpiar(){
    $("#ruc").val('');
    // Limpiar también DNI
    $("#dni").val('');
    $("#nombres").val('');
    $("#apellidop").val('');
    $("#apellidom").val('');
    $("#ubicacion").val('');
    $("#area").val('');
	$("#latitud").val('');
	$("#longitud").val('');
	$("#zona").val('');
	$("#celular").val('');
}


init();