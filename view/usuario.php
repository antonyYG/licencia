<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require_once "head.php"; ?>
	<link rel="stylesheet" type="text/css" href="../public/toastr/css/toastr.css">
	<link rel="stylesheet" type="text/css" href="../public/fontawesome/css/all.min.css">
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
			  <h1 class="text-titles">Administrar Usuarios</h1>
			</div>
		</div>
		<div class="full-box text-center" style="padding: 30px 10px;">
		<div class="col-md-12">
			<div class="row">
			<div class="col-md-11">    
					<button type="button" class="btn btn-warning pull pull-right btn-raised btn-sm" data-toggle="modal" data-target="#modalinsertarUser">Agregar Usuario</button> 
				
             </div>
         </div>

			<div class="panel-body">
				<div class="table-responsive">
					<table id="tablatiendausuario" class="table table-striped table-bordered" style="width:100%">
				        <thead>
				            <tr>
				                <th>Nombres</th>
												<th>Apellido Paterno</th>
												<th>Apellido Materno</th>
												<th>Dni</th>
												<th style="color: green;">Actualizar</th>
												<th style="color: purple;">Condicion</th>
				            </tr>
				        </thead>
				        <tbody>
				        </tbody>
				    </table>
				    
				    
				</div>
		<!-- <div class="col-md-12">
	<table id="tablaEditarTipoUsuario" class="table table-striped table-bordered" style="width:100%">
		<thead>
			<tr>
				<th>Dni</th>
				<th>Tipo de Usuario</th>
				<th>Acción</th>
			</tr>
		</thead>
		<tbody>
		
		</tbody>
	</table>
</div> -->

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
			    	<h4 class="modal-title">Help</h4>
			    </div>
			    <div class="modal-body">
			        <p>
			        	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt beatae esse velit ipsa sunt incidunt aut voluptas, nihil reiciendis maiores eaque hic vitae saepe voluptatibus. Ratione veritatis a unde autem!
			        </p>
			    </div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-primary btn-raised" data-dismiss="modal"><i class="zmdi zmdi-thumb-up"></i> Ok</button>
		      	</div>
		    </div>
	  	</div>
	</div>
	<?php require_once "script.php"; ?>
<script type="text/javascript">
/*$(document).ready(function() {
    // Obtener los datos de los usuarios y mostrarlos en la tabla
    $.ajax({
        url: 'obtener_usuarios.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var usuarios = response.usuario;
                var tbody = $('#tablaEditarTipoUsuario tbody');

                usuarios.forEach(function(usuario) {
                    var fila = '<tr>';
                    fila += '<td>' + usuario.dni + '</td>';
                    fila += '<td>';
                    fila += '<select class="form-control" name="tipoUsuario">';
                    fila += '<option value="administrador"' + (usuario.tipo_usuario === 'administrador' ? ' selected' : '') + '>Administrador</option>';
                    fila += '<option value="usuario"' + (usuario.tipo_usuario === 'usuario' ? ' selected' : '') + '>Usuario</option>';
                    fila += '</select>';
                    fila += '</td>';
                    fila += '<td>';
                    fila += '<button class="btn btn-primary">Guardar</button>';
                    fila += '</td>';
                    fila += '<input type="hidden" name="idpersona" value="' + usuario.idpersona + '">';
                    fila += '</tr>';

                    tbody.append(fila);
                });
            } else {
                toastr.error('Error al obtener los usuarios');
            }
        },
        error: function() {
            toastr.error('Error al obtener los usuarios');
        }
    });

    // Enviar los datos al servidor para guardar el tipo de usuario
    $('#tablaEditarTipoUsuario').on('click', 'button', function() {
        var fila = $(this).closest('tr');
        var idpersona = fila.find('input[name="idpersona"]').val();
        var tipoUsuario = fila.find('select[name="tipoUsuario"]').val();

        $.ajax({
            url: 'guardar_tipo_usuario.php',
            type: 'POST',
            dataType: 'json',
            data: {
                idpersona: idpersona,
                tipoUsuario: tipoUsuario
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error al guardar el tipo de usuario');
            }
        });
    });
});
*/
</script>

		<script type="text/javascript" src="script/usuario.js"></script>
		<script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
		<script type="text/javascript" src="script/validacion.js"></script>
		
</body>
</html>

<!-- insertar -->
<div class="modal fade" id="modalinsertarUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: green;">
        <h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Agregar Usuario</h3>
      </div>
      <div class="modal-body">
        
      		<form id="formuser">
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Nombres</label>
			      <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Ingresar Nombres" onkeypress="return soloLetras(event)">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputPassword4" style="color: black;">Apellido Paterno</label>
			      <input type="text" class="form-control" id="apellidop" name="apellidop" placeholder="Ingresar el Apellido Paterno" onkeypress="return soloLetras(event)">
			    </div>
			  </div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Apellido Materno</label>
			      <input type="text" class="form-control" id="apellidom" name="apellidom" placeholder="Ingresar Apellido Materno" onkeypress="return soloLetras(event)">
			    </div>	
			  </div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Direccion</label>
			       <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingresar Direccion">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputPassword4" style="color: black;">Dni</label>
			      <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingresar dni" maxlength="8" onkeypress="return numeros(event)">
			    </div>
			  </div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Correo</label>
			       <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingresar Correo">
			    </div>
			  </div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Contraseña</label>
			      <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingresar la Contraseña">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Repite Contraseña</label>
			      <input type="password" class="form-control" id="repitecontrasena" name="repitecontrasena" placeholder="Ingresar Nuevamente la Contraseña">
			    </div>
			    <div class="form-group col-md-6">
					<label style="color: black;">Rol</label>
					<select name="rol" id="rol" class="form-control">
						<option value="Administrador">Administrador</option>
						<option value="Usuario">Usuario</option>
					</select>
				</div>
			  </div>
			  <hr width="100%">
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-raised" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-raised" id="usuarioregistrar">Guardar</button>
      </div>
	</form>
   </div>
    </div>
  </div>
</div>

<!-- editar -->
<div class="modal fade" id="modaleditarUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: purple;">
        <h3 class="modal-title" id="exampleModalLabel" style="color: white; text-align: center;">Editar Usuario</h3>
      </div>
      <div class="modal-body">
        
      		<form id="formusereidit">
			  <div class="form-row">
			    <div class="form-group col-md-6">
			    	<input type="hidden" name="idusuario" id="idusuario">
			      <label for="inputEmail4" style="color: black;">Nombres</label>
			      <input type="text" class="form-control" id="nombresedit" name="nombres" placeholder="Ingresar Nombres" onkeypress="return soloLetras(event)">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputPassword4" style="color: black;">Apellido Paterno</label>
			      <input type="text" class="form-control" id="apellidopedit" name="apellidop" placeholder="Ingresar el Apellido Paterno" onkeypress="return soloLetras(event)">
			    </div>
			  </div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Apellido Materno</label>
			      <input type="text" class="form-control" id="apellidomedit" name="apellidom" placeholder="Ingresar Apellido Materno" onkeypress="return soloLetras(event)">
			    </div>	
			  </div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Direccion</label>
			       <input type="text" class="form-control" id="direccionedit" name="direccion" placeholder="Ingresar Direccion">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputPassword4" style="color: black;">Dni</label>
			      <input type="text" class="form-control" id="dniedit" name="dni" placeholder="Ingresar dni" maxlength="8" onkeypress="return numeros(event)">
			    </div>
			  </div>
			  <div class="form-row">
					<div class="form-group col-md-6">
						<label for="correoedit" style="color: black;">Correo electrónico</label>
						<input type="email" class="form-control" id="correoedit" name="correo" placeholder="Ingresar correo">
					</div>
					<div class="form-group col-md-6">
						<label for="roledit" style="color: black;">Rol</label>
						<select class="form-control" id="roledit" name="rol">
						<option value="">Seleccione</option>
						<option value="Administrador">Administrador</option>
						<option value="Usuario">Usuario</option>
						</select>
					</div>
				</div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Contraseña</label>
			      <input type="password" class="form-control" id="contrasenaedit" name="contrasena" placeholder="Ingresar la Contraseña">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputEmail4" style="color: black;">Repite Contraseña</label>
			      <input type="password" class="form-control" id="repitecontrasenaedit" name="repitecontrasena" placeholder="Ingresar Nuevamente la Contraseña">
			    </div>
			  </div>
			  <hr width="100%">
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-raised" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-raised" id="btnedita">Actualizar</button>
      </div>
	</form>
   </div>
    </div>
  </div>
</div>

<!---activo---->
<<div class="modal fade" id="modalactivoEspe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: green;">
        <h4 class="modal-title" id="exampleModalLabel" align="center" style="color: white;">ESTADO DEL USUARIO</h4>
      </div>
      <div class="modal-body">
      	
      	<div class="col-md-4" style="text-align: center;">
           <img src="../files/img/user.jpg" style="width: 120px;height: 120px">
        </div>
        <p style="color: red;">¿Esta seguro de cambiar el estado del usuario?</p>
            <p>Recuerda que si habilitas a este usuario, este <br>podra ingresar al sistema hasta que su estado <br> vuelva a ser el de Inactivo</p>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-raised btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-raised btn-sm" id="habilitarespe">Cambiar</button>
        <input type="hidden" name="idusuario" id="idactivo">
      </div>
    </div>
  </div>
</div>

<!---inactivo---->
<div class="modal fade" id="modalinactivoEspe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: red;">
        <h4 class="modal-title" id="exampleModalLabel" align="center" style="color: white;">ESTADO DEL USUARIO</h4>
      </div>
      <div class="modal-body">
         
      	<div class="col-md-4" style="text-align: center;">
           <img src="../files/img/user.jpg" style="width: 120px;height: 120px">
        </div>
        <p style="color: red;">¿Esta seguro de cambiar el estado del usuario?</p>
            <p>Recuerda que si deshabilitas a este usuario, este no <br>podra ingresar al sistema hasta que su estado vuelva a <br> ser el de Activo</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-raised btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-raised btn-sm" id="deshabilitarespe">Cambiar</button>
        <input type="hidden" name="idusuario" id="idinactivo">
      </div>
    </div>
  </div>
</div>

