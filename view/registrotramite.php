<?php 	
	session_start();
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require_once "head.php"; ?>
	<link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
	<link rel="icon" href="1.png">
</head>
<body>
	<?php 
	require_once "../config/conexion.php";
	
	$con=new conexion();
	$conexion=$con->conectar();
		$consulta = mysqli_query($conexion, "SELECT exp_num FROM licencia order by exp_num DESC limit 1");
		$dato = mysqli_fetch_array($consulta);
		$numero = isset($dato["exp_num"])? $dato["exp_num"]: "";
		if($numero == "") {
			$numero = "00000"; 
		}

		$codigo_mostrar = str_pad(($numero+1), 5, "0", STR_PAD_LEFT);
	 ?>

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
		  <h1 class="text-titles" style="color: blue">Nuevo Registro de Trámite</h1>
		</div>
		
	</div>
	<div class="full-box text-center" style="padding: 30px 10px;">
		<div class="col-md-12">
			<div id="ocultarForm">
				<form id="form_parttramite">
					<h3 style="text-align: left;color: red;">DATOS DEL TRAMITANTE</h3>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip02" style="color: black;">Nombres</label>
							<button type="button" class="btn btn-info btn-raised btn-sm" data-toggle="modal" data-target="#modallistar"><i class="zmdi zmdi-account"></i></button>
							<input type="text" id="nombresLi" name="nombresLi" class="form-control">
							<input type="hidden" id="idtiendass" name="nombres" class="form-control" readonly="readonly">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip02" style="color: black;">Apellido Paterno</label>
							<input type="text" class="form-control" id="apellidopli" name="apellidopli">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip02" style="color: black;">Apellido Materno</label>
							<input type="text" class="form-control" id="apellidomli" name="apellidomli">
						</div>
					</div>
					<hr width="100%" style="opacity: .1">
					<h3 style="text-align: left;color: red;">DATOS DE LA LICENCIA</h3>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip01" style="color: black">Exp. N°</label>
							<input type="text" class="form-control" id="expediente" name="expediente" placeholder="Ingresar número" readonly="readonly" value="<?php echo $codigo_mostrar; ?>">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip02" style="color: black">Número de RUC</label>
							<input type="text" id="numruc" name="numruc" class="form-control">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip02" style="color: black">Establecimiento Ubicado en</label>
							<input type="text" class="form-control" id="ubicacion" name="ubicacion">
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Giro o Comercio</label>
							<button type="button" class="btn btn-warning btn-raised btn-sm" data-toggle="modal" data-target="#modallistarcomercio"><i class="zmdi zmdi-account"></i></button>
							<input type="text" name="nombregiros" id="idgirocomer" class="form-control" readonly="readonly">
							<input type="hidden" name="giro" id="giro" class="form-control" readonly="readonly">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Nombre Comercial</label>
							<input type="text" id="nombrecomercial" name="nombrecomercial" class="form-control" placeholder="Ingresar nombre comercial" onkeypress="return soloLetras(event)">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip04" style="color: black">Área Comercial</label>
							<input type="text" class="form-control" id="arealocal" name="arealocal" readonly="readonly">
						</div>
					</div>
					<hr width="100%" style="opacity: .1">
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip05" style="color: black">N° Recibo Tesorería</label>
							<input type="text" class="form-control" id="recibotes" name="recibotes" placeholder="Ingresar N° de recibo" onkeypress="return numeros(event)">
						</div>

					</div>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Tipo de Anuncio</label>
							<input type="text" class="form-control" id="fechexpedicion" name="fechexpedicion" placeholder="Ingresar Tipo de Anuncio">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip04" style="color: black">N° de Resolución</label>
							<input type="text" class="form-control" id="numresolucion" name="numresolucion" placeholder="Ingresar N° de Resolución" onkeypress="return numeros(event)" maxlength="6">
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip04" style="color: black">Tipo de Licencia</label>
							<select name="tipolicencia" id="tipolicencia" class="form-control" onchange="handleLicenciaTipo(this)">
								<option value="">Seleccionar Tipo</option>
								<option value="1">Indeterminada</option>
								<option value="2">Temporal</option>
							</select>
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Fecha de Expedición</label>
							<input type="date" class="form-control" id="fechingreso" name="fechingreso">
						</div>
							<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Vigencia</label>
							<input type="date" class="form-control" id="vigencia" name="vigencia">
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">N° Doc</label>
							<input type="text" class="form-control" id="numdoc" name="numdoc" readonly="readonly">
						</div>
					</div>
					<hr width="100%" style="opacity: .1">
					<h3 style="text-align: left;color: red;">DATOS DEL ITSE</h3>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Número Resolución ITSE</label>
							<input type="text" class="form-control" id="numresolucion_itse" name="numresolucion_itse" placeholder="Ingresar N° de Resolución ITSE" onkeypress="return numeros(event)" maxlength="6">
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Expedición ITSE</label>
							<input type="date" class="form-control" id="expedicionitse" name="expedicionitse" onchange="calcularVigencia()">
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationTooltip03" style="color: black">Vigencia ITSE</label>
							<input type="date" class="form-control" id="vigenciaitse" name="vigenciaitse" readonly>
						</div>
					</div>
					
					<hr width="100%" style="opacity: .1">
					<button class="btn btn-success btn-raised" type="button" id="btn_registrar">Registrar</button>
					<a href="tramite.php"><button type="button" class="btn btn-danger btn-raised">Salir</button></a>
				</form>
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
    var vigenciaField = document.getElementById("vigencia");
    if (selectedValue === "1") {
      vigenciaField.value = "0001-01-01"; // Establecer una fecha válida pero arbitraria
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
<div class="modal fade" id="modallistar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
<div class="col-md-11">
    <a href="tienda.php" target="_blank" class="btn btn-warning pull-right btn-raised btn-sm" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Ir a Agregar Tienda</a>
</div>


      <div class="modal-header" style="background: purple;">
        <h3 class="modal-title" id="exampleModalLabel" style="text-align: center;color: white;">Administrar Tienda</h3>
      </div>
      <div class="modal-body">
		<div class="table-responsive">
			<table id="datatabletienda" class="table table-striped table-bordered" style="width:100%">
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
<div class="modal fade" id="modallistarcomercio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: orange;">
        <h3 class="modal-title" id="exampleModalLabel" style="text-align: center;color: white;">Administrar Giros</h3>
      </div>
      <div class="modal-body">
		<div class="table-responsive">
			<table id="datatablegiros" class="table table-striped table-bordered" style="width:100%">
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