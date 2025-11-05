$(document).ready(function(){
	listar();
	$("#guardar").click(function(){
		var giro=$("#nombregiro").val();
		if (giro.length==0) {
			toastr.info("Ingresar el dato respectivo","Giro");
		}else{
			$.ajax({
			"url":"../controller/giro.php?boton=insertar",
			"method":"post",
			"data":{nombregiro:giro}
			}).done(function(rsp){
				if (rsp=='1') {
					toastr.success("Se registro exitosamente","Giro");
					limpiar();
					table.ajax.reload();
					$("#exampleModal").modal("hide");
				}else{
					toastr.error("No se pudo registrar","Giro");
				}
			});
		}
		
	});

	$("#editar").click(function(){
		var idgiro=$("#idgiroedit").val();
		var giro=$("#nombregiroedit").val();
		$.ajax({
			"url":"../controller/giro.php?boton=editar",
			"method":"post",
			"data":{nombregiro:giro, idgiro:idgiro}
		}).done(function(rsp){
			if (rsp=='1') {
				toastr.success("Se actualizo correctamente","Giro");
				table.ajax.reload();
				$("#modaledita").modal("hide");
			}else{
				toastr.error("no se pudo registrar","Giro");
			}
		});
	});

});
var table;

function listar(){
	table=$("#giro").DataTable({	
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url": "../controller/giro.php?boton=listar",
			"type":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"nombregiro"},
			{"defaultContent":'<button type="button" class="btn btn-primary btn-raised editar btn-sm"><i class="zmdi zmdi-edit"></i></button>'},
			{"defaultContent":'<button type="button" class="btn btn-danger btn-raised eliminar btn-sm"><i class="zmdi zmdi-delete"></i></button>'}
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
	obtener_datos("#giro tbody", table);
	obtener_datos_eliminar("#giro tbody", table);
}

var obtener_datos=function(tbody, table){
	$(tbody).on("click", ".editar", function(){
		var data=table.row($(this).parents("tr")).data();
		$("#nombregiroedit").val(data.nombregiro);
		$("#idgiroedit").val(data.idgiro);
		$("#modaledita").modal({backdrop:'static', keyboard:false});
		$("#modaledita").modal("show");
	});
}

function limpiar(){
	giro=$("#nombregiro").val('');
}

var obtener_datos_eliminar=function(tbody, table){
	$(tbody).on("click", ".eliminar", function(){
		var data=table.row($(this).parents("tr")).data();
		$("#ideliminar").val(data.idgiro);
		$("#modaleliminar").modal({backdrop:'static', keyboard:false});
		$("#modaleliminar").modal("show");
	});
}

$(document).on("click", "#eliminargiro", function(){
	var idgiro=$("#ideliminar").val();
	$.post("../controller/giro.php?boton=eliminar", {idgiro:idgiro}, function(rsp){
		if (rsp=="1") {
			toastr.success("El giro se elimino correctamente","Giro");
			$("#modaleliminar").modal("hide");
			table.ajax.reload();
		}else{
			toastr.error("No se pudo eliminar","Giro");
		}
	});
});
