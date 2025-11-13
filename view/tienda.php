<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php require_once "head.php"; ?>
	<link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">

	<!-- LeafletJS CSS (OpenStreetMap) -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />


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
                                    <th>DNI</th>
                                    <th>Nombres</th>
                                    <th>Apellido Paterno</th>
                                    <th>Apellido Materno</th>
                                    <th style="color: green;">Editar</th>
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
	<!-- LeafletJS JS (OpenStreetMap) - debe cargarse ANTES de tienda.js -->
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
	<script type="text/javascript" src="script/tienda.js"></script>
	<script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
	<script type="text/javascript" src="script/validacion.js"></script>
<!-- Modales ubicados más abajo para mantener estructura HTML válida -->

<!-- insertar -->
<div class="modal fade" id="modalinsertar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
            <div class="modal-header" style="background: orange;">
                <h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Agregar Tienda</h3>
            </div>
			<div class="modal-body">

				<form id="formtienda">
					<!-- Mapa interactivo: Chilca (inicio del formulario) -->
	<div class="form-row">
		<div class="form-group col-md-12" role="region" aria-label="Mapa interactivo de Chilca">
			<label style="color: black;">Mapa interactivo (Chilca - Huancayo, Junín)</label>
			<div id="map-insert" style="width: 100%; height: 320px; border: 1px solid #ddd; border-radius: 4px;"></div>
			<small style="display:block; margin-top:6px; color:#555;">Haz clic en el mapa para autocompletar Latitud, Longitud y Dirección.</small>
			<div id="map-insert-error" style="color:#b00020; margin-top:6px; display:none;">No se pudo cargar el mapa. Verifica tu conexión.</div>
		</div>
	</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputEmail4" style="color: black;">N° Ruc</label>
							<input type="text" class="form-control" id="ruc" name="ruc" placeholder="Ingresar Ruc" onkeypress="return numeros(event)" maxlength="12">
						</div>
						<div class="form-group col-md-6">
							<label for="inputDni" style="color: black;">Dni</label>
							<input type="text" class="form-control" id="dni" name="dni" placeholder="Ingresar Dni" maxlength="8" onkeypress="return numeros(event)">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputNombres" style="color: black;">Nombres</label>
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
<div class="modal fade" id="modaleditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        	<div class="modal-dialog" role="document">
        		<div class="modal-content">
        <div class="modal-header" style="background: purple;">
            <h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Editar Tienda</h3>
        </div>
        			<div class="modal-body">
        
				<form id="formtiendaedita">
            <!-- Mapa interactivo: Chilca (inicio del formulario) -->
            <div class="form-row">
                <div class="form-group col-md-12" role="region" aria-label="Mapa interactivo de Chilca">
                    <label style="color: black;">Mapa interactivo (Chilca - Huancayo, Junín)</label>
                    <div id="map-edit" style="width: 100%; height: 320px; border: 1px solid #ddd; border-radius: 4px;"></div>
                    <small style="display:block; margin-top:6px; color:#555;">Haz clic en el mapa para autocompletar Latitud, Longitud y Dirección.</small>
					<div id="map-edit-error" style="color:#b00020; margin-top:6px; display:none;">No se pudo cargar el mapa. Verifica tu conexión.</div>
                </div>
            </div>
						 <div class="form-row">
                <input type="hidden" name="idtienda" id="idtienda">
                <div class="form-group col-md-6">
                    <label for="inputEmail4" style="color: black;">N° Ruc</label>
                    <input type="text" class="form-control" id="rucedit" name="ruc" placeholder="Ingresar Ruc" onkeypress="return numeros(event)">
                </div>
            
                <div class="form-group col-md-6">
                    <label for="inputDniEdit" style="color: black;">Dni</label>
                    <input type="text" class="form-control" id="dniedit" name="dni" placeholder="Ingresar Dni" maxlength="8" onkeypress="return numeros(event)">
                </div>
            </div>
            <div class="form-row">
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

<style>
  /* Responsivo: reduce altura del mapa en pantallas pequeñas */
  @media (max-width: 576px) {
    #map-insert, #map-edit { height: 240px; }
  }
</style>

</body>

</html>


