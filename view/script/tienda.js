$(document).ready(function(){
	$("#registrar").click(function(){
    var datos = $("#formtienda").serialize();
    var ruc = $("#ruc").val();
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
    } else if (latitud.length == 0 || longitud.length == 0) {
        toastr.info("Ingresar la latitud y longitud", "Tienda");
    } else {
        $.ajax({
            "url": "../controller/tienda.php?boton=insertar",
            "method": "post",
            "data": datos
        }).done(function(rsp){
            if (rsp == "1") {
                toastr.success("Se registr®Æ exitosamente", "Tienda");
                limpiar();
                tabla.ajax.reload();
            } else {
                toastr.error("No se pudo registrar", "Tienda");
            }
        });
    }
});


	$(document).on('click','.actualizar', function(){
		$("#modaleditar").modal({backdrop:'static', keyboard:false});
		$("#modaleditar").modal("show");
		var id=$(this).data("id");
				$.ajax({
					"url":"../controller/tienda.php?boton=mostrartienda",
					"method":"post",
					"data":{idtienda:id},
					"dataType":"json"
				}).done(function(rsp){
					$("#idtienda").val(rsp.idtienda);
					$("#rucedit").val(rsp.ruc);
					$("#nombresedit").val(rsp.nombres);
					$("#apellidopedit").val(rsp.apellidop);
					$("#apellidomedit").val(rsp.apellidom);
					$("#ubicacionedit").val(rsp.ubicacion);
					$("#areaedit").val(rsp.area);
					$("#latitudedit").val(rsp.latitud);
					$("#longitudedit").val(rsp.longitud);
					$("#zonaedit").val(rsp.zona);
					$("#celularedit").val(rsp.celular);
				});
	});

	$("#edita").click(function(){
		var datos=$("#formtiendaedita").serialize();
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
        "iDisplayLength":4,//Paginaci√≥n
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/tienda.php?boton=lista",
			"method":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"numruc"},
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