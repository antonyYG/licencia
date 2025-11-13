$(document).ready(function(){
    listar();
    $("#guardar").click(function(){
        var giro=$("#nombregiro").val();
        if (giro.length==0) {
            toastr.info("Ingresar el dato respectivo","Giro");
        }else{
            // Validación frontend: duplicados case-insensitive
            var existe = false;
            if (table) {
                var datos = table.rows().data();
                for (var i = 0; i < datos.length; i++) {
                    if ((datos[i].nombregiro || '').toLowerCase().trim() === giro.toLowerCase().trim()) {
                        existe = true; break;
                    }
                }
            }
            if (existe) {
                toastr.warning('El giro ya existe en el sistema');
                return;
            }
            $.ajax({
            "url":"../controller/giro.php?boton=insertar",
            "method":"post",
            "data":{nombregiro:giro}
            }).done(function(rsp){
                if (rsp=='1') {
                    toastr.success("Se registro exitosamente","Giro");
                    limpiar();
                    table.ajax.reload();
                }else if (rsp==='dup'){
                    toastr.error("El giro ya existe en el sistema","Giro");
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
            }else if (rsp==='dup'){
                toastr.error("El giro ya existe en el sistema","Giro");
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
            {"defaultContent":'<button type="button" class="btn btn-primary btn-raised editar btn-sm" aria-label="Editar giro"><i class="zmdi zmdi-edit"></i> Editar</button>'},
            {"defaultContent":'<button type="button" class="btn btn-danger btn-raised eliminar btn-sm" aria-label="Eliminar giro"><i class="zmdi zmdi-delete"></i> Eliminar</button>'}
        ],
        "columnDefs": [
            { targets: 1, responsivePriority: 1 },
            { targets: 2, responsivePriority: 1 }
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

    $(tbody).on("click", ".eliminar", function(){
        var data=table.row($(this).parents("tr")).data();
        var id = data.idgiro;
        var ok = confirm('¿Está seguro de eliminar este giro?');
        if (!ok) return;
        $.ajax({
            "url":"../controller/giro.php?boton=eliminar",
            "method":"post",
            "data":{idgiro:id}
        }).done(function(rsp){
            if (rsp=='1') {
                toastr.success("Giro eliminado correctamente","Giro");
                table.ajax.reload();
            }else{
                toastr.error("No se pudo eliminar el giro","Giro");
            }
        });
    });
}

function limpiar(){
	giro=$("#nombregiro").val('');
}
