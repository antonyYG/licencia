<?php 
	session_start();
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require_once "head.php"; ?>
	<link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
</head>
<body>
	<?php require_once "menu.php"; ?>

	<!-- Content page-->
	<section class="full-box dashboard-contentPage">
		<!-- NavBar -->
		<nav class="full-box dashboard-Navbar">
			<ul class="full-box list-unstyled text-right">
				<li class="pull-left">
					<a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
				</li>
			</ul>
		</nav>
		<!-- Content page -->
		<div class="container-fluid">
			<div class="page-header">
			  <h1 class="text-titles" style="color: blue">Editar Registro de Tramite</h1>
			</div>

		</div>
		<div class="full-box text-center" style="padding: 30px 10px;">
			<div class="col-md-12">
			<div id="ocultarForm">
				<?php 	
					require_once "../model/RegistroTramite.php";
					$Tramite=new RegistroTramite();
					$idtramite=$_GET['idtramite'];
					$Registro=$Tramite->ListTramite($idtramite);
					
					foreach ($Registro as $row) {
				 ?>
			<form id="form_parttramiteedit">
				<h3 style="text-align: left;color: red;">DATOS DEL TRAMITANTE</h3>
	        <div class="form-row">
	          <div class="col-md-4 mb-3">
	            <label for="validationTooltip02" style="color: black;">Nombres</label>
	            <button type="button" class="btn btn-info btn-raised btn-sm" data-toggle="modal" data-target="#modallistaredit"><i class="zmdi zmdi-account"></i></button>
	            <input type="text" id="nombresLiedit" name="nombresLi" class="form-control" readonly="readonly" value="<?php echo $row['nombres_per']; ?>">
	            <input type="hidden" id="idtiendassedit" name="nombres" class="form-control" readonly="readonly" value="<?php echo $row['idtienda']; ?>">
	          </div>
	          <div class="col-md-4 mb-3">
	            <label for="validationTooltip02" style="color: black;">Apellido Paterno</label>
	            <input type="text" class="form-control" id="apellidopliedit" name="apellidopli" readonly="readonly" value="<?php echo $row['apellidop_per']; ?>">
	          </div>
	          <div class="col-md-4 mb-3">
	            <label for="validationTooltip02" style="color: black;">Apelldio Materno</label>
	            <input type="text" class="form-control" id="apellidomliedit" name="apellidomli" readonly="readonly" value="<?php echo $row['apellidom_per']; ?>">
	          </div>
	        </div>
        	<hr width="100%" style="opacity: .1">
        	<h3 style="text-align: left;color: red;">DATOS DE LA LICENCIA</h3>
				<div class="form-row">
					<div class="col-md-4 mb-3">
						<label for="validationTooltip01" style="color: black">Exp. N°</label>
						<input type="text" class="form-control" id="expedienteedit" name="expediente" placeholder="Ingresar numero" readonly="readonly" value="<?php echo $row['exp_num']; ?>" onkeypress="return numeros(event)" >
					</div>
					<div class="col-md-4 mb-3">
						<input type="hidden" name="idtramite" id="idtramite" value="<?php echo $row['idlicencia']; ?>">
						<label for="validationTooltip02" style="color: black">Numero de Ruc</label>
						<input type="text" id="numrucedit" name="numruc" class="form-control" readonly="readonly" value="<?php echo $row['numruc']; ?>"> 
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip02" style="color: black">Establecimiento Ubicado en</label>
						<input type="text" class="form-control" id="ubicacionedit" name="ubicacion" readonly="readonly" value="<?php echo $row['ubic_tienda'] ?>">
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip03" style="color: black">Giro o Comercio</label>
						<button type="button" class="btn btn-warning btn-raised btn-sm" data-toggle="modal" data-target="#modallistarcomerciogiro"><i class="zmdi zmdi-account"></i></button>
						<input type="text" name="nombregiros" id="idgirocomerci" class="form-control" readonly="readonly" value="<?php echo $row['nombregiro']; ?>">
						<input type="hidden" name="giro" id="comercialedit" class="form-control" readonly="readonly" value="<?php echo $row['idgiro']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4 mb-3">
						<label for="validationTooltip03" style="color: black">Nombre Comercial</label>
						<input type="text" id="nombrecomercialedit" name="nombrecomercial" class="form-control" placeholder="Ingresar nombre comercial" value="<?php echo $row['nombre_comercial']; ?>" onkeypress="return soloLetras(event)">
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip04" style="color: black">Area Comercial</label>
						<input type="text" class="form-control" id="arealocaledit" name="arealocal" readonly="readonly" value="<?php echo $row['area_tienda']; ?>">
					</div>
				</div>
				<hr width="100%" style="opacity: .1">
				<div class="form-row">
					<div class="col-md-4 mb-3">
						<label for="validationTooltip05" style="color: black">N° Recibo Tesoreria</label>
						<input type="text" class="form-control" id="recibotesedit" name="recibotes" placeholder="Ingresar N° de recibo" value="<?php echo $row['numrecibo_tesoreria']; ?>" onkeypress="return numeros(event)">
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip03" style="color: black">Vigencia</label>
						<input type="date" class="form-control" id="vigenciaedit" name="vigencia" value="<?php echo $row['vigencia_lic']; ?>">
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip03" style="color: black">Fecha de Expedicion</label>
						<input type="date" class="form-control" id="fechingresoedit" name="fechingreso" value="<?php echo $row['fecha_ingreso']; ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4 mb-3">
						<label for="validationTooltip03" style="color: black">Tipo de Anuncio</label>
						<input type="text" class="form-control" id="fechexpedicionedit" name="fechexpedicion" value="<?php echo $row['fecha_expedicion']; ?>">
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip04" style="color: black">N° de Resolucion</label>
						<input type="text" class="form-control" id="numresolucionedit" name="numresolucion" placeholder="Ingresar N° de Resolucion" value="<?php echo $row['num_resolucion']; ?>" onkeypress="return numeros(event)" maxlength="6">
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip04" style="color: black">Estado de licencia</label>
						<select name="estado" id="estado" class="form-control">
								<option value="1" <?php echo $row['condicion']=="1"? "selected": "" ?>>Activo</option>	
								<option value="0" <?php echo $row['condicion']=="0"? "selected": "" ?>>Inactivo</option>
						</select>
					</div>
				<div class="col-md-4 mb-3">
  <label for="validationTooltip04" style="color: black">Tipo de Licencia</label>
  <select name="tipolicencia" id="tipolicencia" class="form-control" onchange="handleLicenciaTipo(this)" >
    <option value="1" <?php echo $row['tipo_lic'] == "1" ? "selected" : "" ?>>Indeterminada</option>
    <option value="2" <?php echo $row['tipo_lic'] == "2" ? "selected" : "" ?>>Temporal</option>
  </select>
</div>
					<div class="col-md-4 mb-3">
						<label for="validationTooltip04" style="color: black">N° de Doc</label>
						<input type="text" class="form-control" id="numdoc" name="numdoc" placeholder="Ingresar el Numero de doc" value="<?php echo $row['num_tipolic']; ?>" onkeypress="return numeros(event)" maxlength="6" readonly="readonly">
					</div>
				</div>
				<hr width="100%" style="opacity: .1">
            <h3 style="text-align: left;color: red;">DATOS DEL ITSE</h3>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label for="validationTooltip03" style="color: black">Número Resolución ITSE</label>
                    <input type="text" class="form-control" id="numresolucion_itse" name="numresolucion_itse" placeholder="Ingresar N° de Resolución ITSE" value="<?php echo $row['NumResITSE']; ?>" onkeypress="return numeros(event)" maxlength="6">
                </div>
                <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label for="validationTooltip03" style="color: black">Estado ITSE</label>
                    <select name="estado_itse" id="estado_itse" class="form-control">
							<option value="1" <?php echo $row['EstadoITSE']=="1"? "selected":"" ?> >ACTIVA</option>
							<option value="0" <?php echo $row['EstadoITSE']=="0"? "selected":"" ?> >INACTIVA</option>
						</select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="validationTooltip03" style="color: black">Expedición ITSE</label>
                <input type="date" class="form-control" id="expedicionitse" name="expedicionitse" value="<?php echo $row['expedicionITSE']; ?>" onchange="calcularVigencia()">
            </div>
            </div>
            
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="validationTooltip03" style="color: black">Vigencia ITSE</label>
                <input type="date" class="form-control" id="vigenciaitse" name="vigenciaitse" value="<?php echo $row['vigenciaITSE']; ?>" readonly>
            </div>
            </div>
            
            
				<hr width="100%" style="opacity: .1">
			<button class="btn btn-success btn-raised" type="button" id="btn_editar">Actualizar</button>
			<a href="tramite.php"><button type="button" class="btn btn-danger btn-raised">Salir</button></a>
		</form>
		<?php } ?>
	   </div>
	</div>
   </div>
</section>


	<!-- Dialog help -->
	<div class="modal fade" tabindex="-1" role="dialog" id="Dialog-Help">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    	<h4 class="modal-title">Ayuda</h4>
			    </div>
			    <div class="modal-body">
			        <p>
			        	Consulte el manual de usuario, o cierre sesión se existe algun tipo de error.
			        </p>
			    </div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-primary btn-raised" data-dismiss="modal"><i class="zmdi zmdi-thumb-up"></i> Ok</button>
		      	</div>
		    </div>
	  	</div>
	</div>
	<?php require_once "script.php"; ?>
	<script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
	<script type="text/javascript" src="script/registrotramite.js"></script>
	<script type="text/javascript" src="script/validacion.js"></script>
<script>
  function handleLicenciaTipo(selectElement) {
    var selectedValue = selectElement.value;
    var vigenciaField = document.getElementById("vigenciaedit");
    var valorAnterior = vigenciaField.value;

    if (selectedValue === "1" || valorAnterior === "indeterminado") {
      vigenciaField.value = "0001-01-01";
      vigenciaField.setAttribute("readonly", "readonly");
    } else {
      vigenciaField.value = "";
      vigenciaField.removeAttribute("readonly");
    }
  }

  // Obtener el elemento del tipo de licencia y asignarle el evento onChange
  var tipoLicencia = document.getElementById("tipolicencia");
  tipoLicencia.addEventListener("change", function() {
    handleLicenciaTipo(this);
  });

  window.addEventListener('DOMContentLoaded', function() {
    var vigenciaField = document.getElementById("vigenciaedit");
    var valorAnterior = vigenciaField.value;

    if (valorAnterior === "indeterminado") {
      vigenciaField.setAttribute("readonly", "readonly");
    }
  });
  
   function calcularVigencia() {
  var expedicionField = document.getElementById("expedicionitse");
  var vigenciaField = document.getElementById("vigenciaitse");

  // Obtener la fecha de expedición ingresada por el usuario
  var expedicionValue = expedicionField.value;

  if (expedicionValue) {
    // Convertir la fecha de expedición a un objeto Date
    var expedicionDate = new Date(expedicionValue);

    // Calcular la fecha de vigencia sumando 2 años a la fecha de expedición
    var vigenciaDate = new Date(expedicionDate);
    vigenciaDate.setFullYear(vigenciaDate.getFullYear() + 2);

    // Formatear la fecha de vigencia como YYYY-MM-DD
    var vigenciaFormatted = vigenciaDate.toISOString().slice(0, 10);

    // Establecer la fecha de vigencia en el campo correspondiente
    vigenciaField.value = vigenciaFormatted;
  }
}

</script>


</body>
</html>


<!-- lista -->
<div class="modal fade" id="modallistaredit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: purple;">
        <h3 class="modal-title" id="exampleModalLabel" style="text-align: center;color: white;">Administrar Tiendas</h3>
      </div>
      <div class="modal-body">
		<div class="table-responsive">
			<table id="datatabletiendaedit" class="table table-striped table-bordered" style="width:100%">
				<thead>
				    <tr>
				    <th>N° Ruc</th>
				    <th>Nombres</th>
				    <th>Apellido Paterno</th>
				    <th>Apellido Materno</th>
				    <th style="color: purple;">Seleccionar</th>
				    </tr>
				</thead>
			<tbody>
			</tbody>
			</table>
		</div>
      </div>
    </div>
  </div>
</div>

<!-- lista comercio-->
<div class="modal fade" id="modallistarcomerciogiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: orange;">
        <h3 class="modal-title" id="exampleModalLabel" style="text-align: center;color: white;">Administrar Giros</h3>
      </div>
      <div class="modal-body">
		<div class="table-responsive">
			<table id="datatablegiroscom" class="table table-striped table-bordered" style="width:100%">
				<thead>
				    <tr>
				    <th>Giro</th>
				    <th style="color: purple;">Seleccionar</th>
				    </tr>
				</thead>
			<tbody>
			</tbody>
			</table>
		</div>
      </div>
    </div>
  </div>
</div>