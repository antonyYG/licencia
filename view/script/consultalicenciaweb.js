$(document).ready(function(){

	$("#btnescan").click(function(){
		var idtramite=$("#idtramite").val();
		if (idtramite.length==0) {
			toastr.info("Escaner el numero de expediente en la licencia","Licencia");
		}else{
			$.ajax({
			"url":"../controller/consultalicencia.php?boton=buscar",
			"method":"post",
			"data":{idtramite:idtramite}
			}).done(function(r){
				$("#expediente").html(r);
			});
		}	
	});
		
});


let scanner=new Instascan.Scanner({video:document.getElementById('preview')});
		scanner.addListener('scan', function (content) {
			var data=content.split(":");
			var data = data[2].replace('Numero de Ruc','');
        	document.getElementById('idtramite').value=data;
      	});
      	function prender(){
		Instascan.Camera.getCameras().then(function(cameras){
			if (cameras.length > 0) {
				scanner.start(cameras[0]);
			}else{
				console.error('No cameras found.');
				}
		}).catch(function(e){
			console.error(e);
		});
	}