<?php
session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require_once "head.php"; ?>
	<link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
	<style>
    /* Estilos personalizados */
    body {
        background-color: #f2f2f2;
    }

    .dashboard-contentPage {
        background-color: #ffffff;
        color: #333333;
    }

    .dashboard-Navbar {
        background-color: #ffffff;
    }

    .dashboard-Navbar ul li a {
        color: #333333;
    }

    .page-header {
        border-bottom: 1px solid #cccccc;
    }

    .btn-menu-dashboard {
        color: #333333;
    }

    .btn-menu-dashboard:hover {
        color: #ffffff;
    }

    .btn-warning {
        background-color: #00543A;
        border-color: #00543A;
    }

    .btn-warning:hover {
        background-color: #003524;
        border-color: #003524;
        color: #ffffff;
    }

    .table {
        color: #333333;
    }

    .btn-primary {
        background-color: #00543A;
        border-color: #00543A;
    }

    .btn-primary:hover {
        background-color: #003524;
        border-color: #003524;
        color: #ffffff;
    }

    .btn-light {
        background-color: #ffffff;
        border-color: #ffffff;
    }

    .btn-light:hover {
        background-color: #f2f2f2;
        color: #333333;
    }

    span {
        padding: 4px;
    }

    span[style*="background: #27AE60"] {
        background-color: #27AE60;
    }

    span[style*="background: red"] {
        background-color: red;
    }

    span[style*="background: #FF8000"] {
        background-color: #FF8000;
    }

    span[style*="background: brown"] {
        background-color: brown;
    }
    
.verde {
        color: green;
        background-color: rgba(0, 128, 0, 0.1); /* Fondo verde claro */
    }
    
    .amarillo {
        color: orange;
        background-color: rgba(255, 255, 0, 0.1); /* Fondo amarillo claro */
    }
    
    .rojo {
        color: red;
        background-color: rgba(255, 0, 0, 0.1); /* Fondo rojo claro */
    }
    
    .resaltado {
        font-weight: bold; /* Texto en negrita */
    }
</style>


</head>
<body style="background-color: #f2f2f2;">


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
			<h1 class="text-titles">Administrar Tramite</h1>
		</div>
	</div>
	<div class="full-box text-center" style="padding: 30px 10px;">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-11">  
					<a href="registrotramite.php"><button type="button" class="btn btn-warning pull pull-right btn-raised btn-sm" data-toggle="modal" data-target="#exampleModal">
						Agregar Tramite
					</button></a>  
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="licenciatable" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>EXP</th>
								<th>N° RUC o DNI</th>
								<th>NOMBRE COMERCIAL</th>
								<th>FECHA DE EXPEDICIÓN</th>
								<th>VIGENCIA DE LICENCIA</th>
								<th>TIPO DE ANUNCIO</th>
								<th style="color: green;">ACTUALIZAR</th>
								<th style="color: purple;">GENERAR LICENCIA</th>
								<th style="color: brown;">TIPO DE LICENCIA</th>
								<th style="color: brown;">ESTADO DE LICENCIA</th>
								<th>TIEMPO RESTANTE ITSE</th>
								<th style="color: brown;">ESTADO ITSE</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								require_once "../model/RegistroTramite.php";
								$trami = new RegistroTramite();
								$lice = $trami->listartramite();
								foreach ($lice as $row) {
									$vigencia_lic = ($row['vigencia_lic'] === "0001-01-01" ? "Indeterminado" : $row['vigencia_lic']);
        					        $expedicion_itse = new DateTime($row['expedicionITSE']);
                                    $vigencia_itse = new DateTime($row['vigenciaITSE']);
                                    $hoy = new DateTime();
                                    $intervalo = $hoy->diff($vigencia_itse);
                                    $tiempo_restante_itse = $intervalo->format("%r%a");
                                    
                                    // Asignar clases CSS según la cantidad de días restantes
                                    $color_clase = '';
                                    if ($tiempo_restante_itse >= 730) {
                                        $color_clase = 'verde';  // Color verde para más de 30 días restantes
                                    } elseif ($tiempo_restante_itse >= 350) {
                                        $color_clase = 'amarillo';  // Color amarillo para más de 15 días restantes
                                    } else {
                                        $color_clase = 'rojo';  // Color rojo para 15 días o menos restantes
                                    }
                                    
                                    // Agregar la clase "resaltado" para resaltar el texto
                                    $color_clase .= ' resaltado';



							?>
									<tr>
										<td><?php echo $row['exp_num']; ?></td>
										<td><?php echo $row['numruc']; ?></td>
										<td><?php echo $row['nombre_comercial']; ?></td>
										<td><?php echo $row['fecha_ingreso']; ?></td>
										<td><?php echo $vigencia_lic; ?></td>
										<td><?php echo $row['fecha_expedicion']; ?></td>
										<td>
											<?php if ($row['condicion'] == '1') { ?>
												<a href="editartramite.php?idtramite=<?php echo $row['idlicencia']; ?>"><button type="button" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-edit"></i></button></a>
											<?php } else { ?>
												<a href="editartramite.php?idtramite=<?php echo $row['idlicencia']; ?>"><button type="button" class="btn btn-primary btn-raised btn-sm">s<i class="zmdi zmdi-edit"></i></button></a>
											<?php } ?>
										</td>
										<td>
											<?php if ($row['condicion'] == '1') { ?>
												<button type="button" class="btn btn-light btn-raised btn-sm" onclick="imprimir('../public/pdf/tramitelicencia.php?idtramite=<?php echo $row['exp_num']; ?>','1200', '500')"><i class="zmdi zmdi-print"></i></button>
											<?php } else { ?>
												<button type="button" class="btn btn-light btn-raised btn-sm" onclick="imprimir('../public/pdf/tramitelicencia.php?idtramite=<?php echo $row['exp_num']; ?>','1200', '500')"><i class="zmdi zmdi-print"></i></button>
											<?php } ?>
										</td>
										<td>
											<?php if ($row['tipo_lic'] == "1") { ?>
												<span style="color: white; text-align: center;width: 50px;background: #FF8000;border-radius: 6px;margin: 50px auto;padding: 2px;">Indeterminado</span>
											<?php } else { ?>
												<span style="color: white; text-align: center;width: 50px;background: red;border-radius: 6px;margin: 50px auto;padding: 2px;">Temporal</span>
											<?php } ?>
										</td>
										<td>
											<?php if ($row['condicion'] == "1") { ?>
												<span style="color: white; text-align: center;width: 50px;background: #27AE60;border-radius: 6px;margin: 50px auto;padding: 2px;">Activo</span>
											<?php } else { ?>
												<span style="color: white; text-align: center;width: 50px;background: red;border-radius: 6px;margin: 50px auto;padding: 2px;">Anulada</span>
											<?php } ?>
										</td>
										<td><span class="<?php echo $color_clase; ?>"><?php echo $tiempo_restante_itse; ?> días</span></td>


										<td>
											<?php if ($row['EstadoITSE'] == "1") { ?>
												<span style="color: white; text-align: center;width: 50px;background: #27AE60;border-radius: 6px;margin: 50px auto;padding: 2px;">ACTIVO</span>
											<?php } else { ?>
												<span style="color: white; text-align: center;width: 50px;background: red;border-radius: 6px;margin: 50px auto;padding: 2px;">INACTIVO</span>
											<?php } ?>
										</td>
									</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
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


