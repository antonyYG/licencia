<?php session_start(); ?>
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
				<h1 class="text-titles">Administrar Tienda</h1>
			</div>
		</div>
		<div class="full-box text-center" style="padding: 30px 10px;">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-11">
						<button type="button" class="btn btn-warning pull pull-right btn-raised btn-sm" data-toggle="modal" onclick="abrirmodal()">Agregar Tienda</button>

					</div>
				</div>

				<div class="panel-body">
					<div class="table-responsive">
						<table id="tablatienda" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th>N° Ruc</th>
									<th>Nombres</th>
									<th>Apellido Paterno</th>
									<th>Apellido Materno</th>
									<th style="color: green;">Actualizar</th>
								</tr>
							</thead>
							<tbody>
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
	<script type="text/javascript" src="script/tienda.js"></script>
	<script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
	<script type="text/javascript" src="script/validacion.js"></script>
</body>

</html>

<!-- insertar -->
<div class="modal fade" id="modalinsertar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" style="background: orange;">
				<h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Agregar Tienda</h3>
				<a href="https://www.google.com/maps/place/Chupaca" target="_blank" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Ir a Chupaca en Google Maps</a>
			</div>
			<div class="modal-body">

				<form id="formtienda">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputEmail4" style="color: black;">N° Ruc</label>
							<input type="text" class="form-control" id="ruc" name="ruc" placeholder="Ingresar Ruc" onkeypress="return numeros(event)" maxlength="12">
						</div>
						<div class="form-group col-md-6">
							<label for="inputPassword4" style="color: black;">Nombres</label>
							<input type="text" class="form-control" id="nombres" name="nombres" placeholder="Ingresar Nombres" onkeypress="return soloLetras(event)">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputEmail4" style="color: black;">Apellido Paterno</label>
							<input type="text" class="form-control" id="apellidop" name="apellidop" placeholder="Ingresar Apellido Paterno" onkeypress="return soloLetras(event)">
						</div>
						<div class="form-group col-md-6">
							<label for="inputPassword4" style="color: black;">Apellido Materno</label>
							<input type="text" class="form-control" id="apellidom" name="apellidom" placeholder="Ingresar Apellido Materno" onkeypress="return soloLetras(event)">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputPassword4" style="color: black;">Direccion</label>
							<input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ingresar Ubicacion de tienda" >
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputEmail4" style="color: black;">Area Comercial</label>
							<input type="text" class="form-control" id="area" name="area" placeholder="Ingresar el Area">
						</div>
					</div>
					<div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputLatitud" style="color: black;">Latitud (12)</label>
                        <input type="text" class="form-control" id="latitud" name="latitud" placeholder="Ingresar Latitud " >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputLongitud" style="color: black;">Longitud (75)</label>
                        <input type="text" class="form-control" id="longitud" name="longitud" placeholder="Ingresar Longitud ">
                    </div>
                   <div class="form-group col-md-6">
                    <label for="inputzona" style="color: black;">Zona</label>
                    <select class="form-control" id="zona" name="zona">
                        <option>Selecciona una zona</option>
                        <?php
                        // Conexión a la base de datos
                        require_once "../config/conexion.php";
                        
                        $con = new conexion();
                        $conexion = $con->conectar();
                
                        // Verificar la conexión
                        if (!$conexion) {
                            die("Error de conexión: " . mysqli_connect_error());
                        }
                
                        // Consulta a la base de datos
                        $consulta = "SELECT id_zona, nombre_zona FROM zonas";
                        $resultado = mysqli_query($conexion, $consulta);
                
                        // Generar las opciones del menú desplegable
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            echo '<option value="' . $fila['id_zona'] . '">' . $fila['nombre_zona'] . '</option>';
                        }
                
                        // Cerrar la conexión a la base de datos
                        mysqli_close($conexion);
                        ?>
                    </select>
                </div>


                </div>
    
                <div class="form-row">
    <div class="form-group col-md-6">
        <label for="inputcelular" style="color: black;">Celular</label>
        <input type="text" class="form-control" id="celular" name="celular" placeholder="Ingresar Celular" maxlength="9">
    </div>
</div>

					<hr width="100%">
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-raised" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-raised" id="registrar">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- editar -->
<div class="modal fade" id="modaleditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        	<div class="modal-dialog" role="document">
        		<div class="modal-content">
        			<div class="modal-header" style="background: purple;">
            <h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Editar Tienda</h3>
            <a href="https://www.google.com/maps/place/Chupaca" target="_blank" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Ir a Chupaca en Google Maps</a>
        </div>
        			<div class="modal-body">
        
        				<form id="formtiendaedita">
        					 <div class="form-row">
                <input type="hidden" name="idtienda" id="idtienda">
                <div class="form-group col-md-6">
                    <label for="inputEmail4" style="color: black;">N° Ruc</label>
                    <input type="text" class="form-control" id="rucedit" name="ruc" placeholder="Ingresar Ruc" onkeypress="return numeros(event)">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4" style="color: black;">Nombres</label>
                    <input type="text" class="form-control" id="nombresedit" name="nombres" placeholder="Ingresar Nombres" onkeypress="return soloLetras(event)">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4" style="color: black;">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellidopedit" name="apellidop" placeholder="Ingresar Apellido Paterno" onkeypress="return soloLetras(event)">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4" style="color: black;">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellidomedit" name="apellidom" placeholder="Ingresar Apellido Materno" onkeypress="return soloLetras(event)">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputPassword4" style="color: black;">Ubicación de Tienda</label>
                    <input type="text" class="form-control" id="ubicacionedit" name="ubicacion" placeholder="Ingresar Ubicación de tienda">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4" style="color: black;">Area del Local</label>
                    <input type="text" class="form-control" id="areaedit" name="area" placeholder="Ingresar el Área">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputLatitud" style="color: black;">Latitud</label>
                    <input type="text" class="form-control" id="latitudedit" name="latitud" placeholder="Ingresar Latitud">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputLongitud" style="color: black;">Longitud</label>
                    <input type="text" class="form-control" id="longitudedit" name="longitud" placeholder="Ingresar Longitud">
                </div>
            </div>
           <div class="form-group col-md-6">
    <label for="inputzona" style="color: black;">Zona</label>
    <select class="form-control" id="zonaedit" name="zona">
        <option>Selecciona una zona</option>
        <?php
        // Conexión a la base de datos
        require_once "../config/conexion.php";
        
        $con = new conexion();
        $conexion = $con->conectar();

        // Verificar la conexión
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        // Consulta a la base de datos
        $consulta = "SELECT id_zona, nombre_zona FROM zonas";
        $resultado = mysqli_query($conexion, $consulta);

        // Generar las opciones del menú desplegable
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo '<option value="' . $fila['id_zona'] . '">' . $fila['nombre_zona'] . '</option>';
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
        ?>
    </select>
</div>


    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputcelular" style="color: black;">Celular</label>
            <input type="text" class="form-control" id="celularedit" name="celular" placeholder="Ingresar Celular">
        </div>
    </div>
					<hr width="100%">
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-raised" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-raised" id="edita">Actualizar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


