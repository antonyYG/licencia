<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php require_once "head.php"; ?>
	<link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
		integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
		crossorigin=""/>
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
                                    <!-- Nueva columna para mostrar el DNI -->
                                    <th>DNI</th>
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
	<!-- Leaflet JS -->
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
		integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
		crossorigin=""></script>
	<script type="text/javascript" src="script/tienda.js"></script>
	<script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
	<script type="text/javascript" src="script/validacion.js"></script>
</body>

</html>

<!-- insertar -->
<div class="modal fade" id="modalinsertar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header" style="background: orange;">
				<h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Agregar Tienda</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-info">
					<strong>Instrucciones:</strong> Haz clic en el mapa para seleccionar la ubicación de tu tienda. Las coordenadas y dirección se llenarán automáticamente.
				</div>
				
				<!-- Mapa Leaflet -->
				<div id="map" style="height: 300px; margin-bottom: 15px; border: 1px solid #ccc;"></div>
				
                <form id="formtienda">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputEmail4" style="color: black;">N° Ruc</label>
                            <input type="text" class="form-control" id="ruc" name="ruc" placeholder="Ingresar Ruc" onkeypress="return numeros(event)" maxlength="11">
                        </div>
                        <!-- Nuevo campo DNI, colocado al lado de RUC -->
                        <div class="form-group col-md-4">
                            <label for="inputDni" style="color: black;">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingresar DNI" onkeypress="return numeros(event)" maxlength="8">
                        </div>
                        <div class="form-group col-md-4">
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
						<div class="form-group col-md-12">
							<label for="inputPassword4" style="color: black;">Dirección (se completa automáticamente)</label>
							<input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="La dirección se completará automáticamente al seleccionar en el mapa" readonly>
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
                        <label for="inputLatitud" style="color: black;">Latitud</label>
                        <input type="text" class="form-control" id="latitud" name="latitud" placeholder="Haz clic en el mapa" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputLongitud" style="color: black;">Longitud</label>
                        <input type="text" class="form-control" id="longitud" name="longitud" placeholder="Haz clic en el mapa" readonly>
                    </div>
                   <div class="form-group col-md-6">
                    <label for="inputzona" style="color: black;">Zona</label>
                    <select class="form-control" id="zona" name="zona">
                        <option value="">Selecciona una zona</option>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: purple;">
                <h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Editar Tienda</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Instrucciones:</strong> Haz clic en el mapa para actualizar la ubicación de tu tienda. Las coordenadas y dirección se actualizarán automáticamente.
                </div>
                
                <!-- Mapa Leaflet para edición -->
                <div id="map-edit" style="height: 300px; margin-bottom: 15px; border: 1px solid #ccc;"></div>
        
                <form id="formtiendaedita">
                    <div class="form-row">
                        <input type="hidden" name="idtienda" id="idtienda">
                        <div class="form-group col-md-4">
                            <label for="inputEmail4" style="color: black;">N° Ruc</label>
                            <input type="text" class="form-control" id="rucedit" name="ruc" placeholder="Ingresar Ruc" onkeypress="return numeros(event)" maxlength="11">
                        </div>
                        <!-- Nuevo campo DNI en edición -->
                        <div class="form-group col-md-4">
                            <label for="inputDniEdit" style="color: black;">DNI</label>
                            <input type="text" class="form-control" id="dniedit" name="dni" placeholder="Ingresar DNI" onkeypress="return numeros(event)" maxlength="8">
                        </div>
                        <div class="form-group col-md-4">
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
                        <div class="form-group col-md-12">
                            <label for="inputPassword4" style="color: black;">Ubicación de Tienda (se completa automáticamente)</label>
                            <input type="text" class="form-control" id="ubicacionedit" name="ubicacion" placeholder="La dirección se completará automáticamente al seleccionar en el mapa" readonly>
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
                            <input type="text" class="form-control" id="latitudedit" name="latitud" placeholder="Haz clic en el mapa" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputLongitud" style="color: black;">Longitud</label>
                            <input type="text" class="form-control" id="longitudedit" name="longitud" placeholder="Haz clic en el mapa" readonly>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputzona" style="color: black;">Zona</label>
                        <select class="form-control" id="zonaedit" name="zona">
                            <option value="">Selecciona una zona</option>
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

<script>
// Variables globales para los mapas y marcadores
let map, mapEdit;
let marker, markerEdit;

// Función para obtener dirección desde coordenadas (geocodificación inversa)
function obtenerDireccion(lat, lng, callback) {
    // Usar el servicio de Nominatim (OpenStreetMap) para geocodificación inversa
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                callback(data.display_name);
            } else {
                callback('Dirección no encontrada');
            }
        })
        .catch(error => {
            console.error('Error al obtener dirección:', error);
            callback('Error al obtener dirección');
        });
}

// Inicializar mapa cuando se abre el modal de inserción
function abrirmodal() {
    $('#modalinsertar').modal('show');
    
    // Inicializar el mapa después de que el modal se muestre completamente
    setTimeout(function() {
        if (!map) {
            initMap();
        } else {
            map.invalidateSize();
        }
    }, 500);
}

// Inicializar mapa principal
function initMap() {
    // Coordenadas de Chupaca, Perú como ubicación por defecto
    const defaultLat = -12.0667;
    const defaultLng = -75.2833;
    
    // Crear el mapa
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    // Agregar capa de tiles de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Agregar marcador inicial
    marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);
    
    // Función para actualizar coordenadas y dirección
    function actualizarUbicacion(lat, lng) {
        // Actualizar campos de coordenadas (con más decimales para precisión)
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
        
        // Obtener y actualizar dirección
        obtenerDireccion(lat, lng, function(direccion) {
            document.getElementById('ubicacion').value = direccion;
        });
    }
    
    // Actualizar al hacer clic en el mapa
    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        
        // Actualizar posición del marcador
        marker.setLatLng([lat, lng]);
        
        // Actualizar campos
        actualizarUbicacion(lat, lng);
    });
    
    // Actualizar al arrastrar el marcador
    marker.on('dragend', function(e) {
        const { lat, lng } = marker.getLatLng();
        actualizarUbicacion(lat, lng);
    });
    
    // Establecer valores iniciales en los campos
    actualizarUbicacion(defaultLat, defaultLng);
}

// Inicializar mapa de edición cuando se abre el modal
$('#modaleditar').on('shown.bs.modal', function() {
    // Obtener valores actuales de latitud y longitud desde los campos del formulario
    let currentLat = document.getElementById('latitudedit').value;
    let currentLng = document.getElementById('longitudedit').value;
    
    // Si no hay valores o son inválidos, usar Chupaca como predeterminado
    if (!currentLat || !currentLng || isNaN(parseFloat(currentLat)) || isNaN(parseFloat(currentLng))) {
        currentLat = -12.0667;
        currentLng = -75.2833;
    } else {
        currentLat = parseFloat(currentLat);
        currentLng = parseFloat(currentLng);
    }
    
    // Crear o actualizar el mapa de edición
    if (!mapEdit) {
        mapEdit = L.map('map-edit').setView([currentLat, currentLng], 13);
        
        // Agregar capa de tiles de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapEdit);
        
        // Agregar marcador inicial
        markerEdit = L.marker([currentLat, currentLng], {
            draggable: true
        }).addTo(mapEdit);
        
        // Función para actualizar coordenadas y dirección en edición
        function actualizarUbicacionEdit(lat, lng) {
            // Actualizar campos de coordenadas
            document.getElementById('latitudedit').value = lat;
            document.getElementById('longitudedit').value = lng;
            
            // Obtener y actualizar dirección
            obtenerDireccion(lat, lng, function(direccion) {
                document.getElementById('ubicacionedit').value = direccion;
            });
        }
        
        // Actualizar al hacer clic en el mapa
        mapEdit.on('click', function(e) {
            const { lat, lng } = e.latlng;
            
            // Actualizar posición del marcador
            markerEdit.setLatLng([lat, lng]);
            
            // Actualizar campos
            actualizarUbicacionEdit(lat, lng);
        });
        
        // Actualizar al arrastrar el marcador
        markerEdit.on('dragend', function(e) {
            const { lat, lng } = markerEdit.getLatLng();
            actualizarUbicacionEdit(lat, lng);
        });
        
        // Actualizar dirección inicial
        actualizarUbicacionEdit(currentLat, currentLng);
        
    } else {
        // Actualizar posición del marcador y vista del mapa
        markerEdit.setLatLng([currentLat, currentLng]);
        mapEdit.setView([currentLat, currentLng], 13);
        mapEdit.invalidateSize();
        
        // Actualizar dirección
        obtenerDireccion(currentLat, currentLng, function(direccion) {
            document.getElementById('ubicacionedit').value = direccion;
        });
    }
});

// Limpiar mapas cuando se cierran los modales
$('#modalinsertar').on('hidden.bs.modal', function() {
    if (map) {
        map.remove();
        map = null;
        marker = null;
    }
});

$('#modaleditar').on('hidden.bs.modal', function() {
    if (mapEdit) {
        mapEdit.remove();
        mapEdit = null;
        markerEdit = null;
    }
});
</script>