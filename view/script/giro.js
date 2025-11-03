$(document).ready(function(){
	listar();
	$("#guardar").click(function(){
    var giro = $("#nombregiro").val().trim();

    if (giro.length == 0) {
        toastr.info("Ingresar el dato respectivo", "Giro");
        return;
    }

    $.ajax({
        url: "../controller/giro.php?boton=insertar",
        method: "post",
        data: { nombregiro: giro }
    }).done(function(rsp) {
        console.log("Respuesta servidor:", rsp); // <-- útil para debug
        if (rsp === "1") {
            toastr.success("Se registró exitosamente", "Giro");
            limpiar();
            table.ajax.reload();
        } else if (rsp === "existe") {
            toastr.warning("Ya existe un giro con ese nombre", "Giro duplicado");
        } else {
            toastr.error("No se pudo registrar", "Giro");
        }
    }).fail(function(xhr) {
        console.error("Error AJAX:", xhr.responseText);
        toastr.error("Error en el servidor", "Giro");
    });
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
			{"defaultContent":'<button type="button" class="btn btn-primary btn-raised editar btn-sm"><i class="zmdi zmdi-edit"></i></button>'}
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
	obtener_datos("#giro tbody", table);
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
