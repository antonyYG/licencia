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
			  <h1 class="text-titles">Administrar Giros</h1>
			</div>
		</div>
		<div class="full-box text-center" style="padding: 30px 10px;">
		<div class="col-md-12">
			<div class="row">
			<div class="col-md-11">  
					<button type="button" class="btn btn-warning pull pull-right btn-raised btn-sm" data-toggle="modal" data-target="#exampleModal">
					  Agregar Giro
					</button>    
				
             </div>
         </div>

			<div class="panel-body">
				<div class="table-responsive">
					<table id="giro" class="table table-striped table-bordered" style="width:100%">
				        <thead>
				            <tr>
				                <th>Giro</th>
				                <th style="color: green;">Actualizar</th>
				                <th style="color: red;">Eliminar</th>
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
	<script type="text/javascript" src="script/giro.js"></script>
	<script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
	<script type="text/javascript" src="script/validacion.js"></script>
</body>
</html>

<!-- agregar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: green;">
        <h3 class="modal-title" id="exampleModalLabel" style="text-align: center; color: white;">Agregar Giro</h3>
      </div>
      <div class="modal-body">
        <form>
		  <div class="form-group">
		    <label for="exampleInputEmail1">Giro</label>
		    <input type="text" class="form-control" id="nombregiro" name="nombregiro" aria-describedby="giro" placeholder="Ingresar Giro" onkeypress="return soloLetras(event)">
		  </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-raised" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-raised" id="guardar">Guardar</button>
      </div>
      </form>
     </div> 
    </div>
  </div>
</div>

<!-- editar -->
<div class="modal fade" id="modaledita" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: green;">
        <h3 class="modal-title" id="exampleModalLabel" style="text-align: center; color: white;">Editar Giro</h3>
      </div>
      <div class="modal-body">
        <form>
		  <div class="form-group">
		  	<input type="hidden" name="idgiro" id="idgiroedit">
		    <label for="exampleInputEmail1">Giro</label>
		    <input type="text" class="form-control" id="nombregiroedit" name="nombregiro" aria-describedby="giro" placeholder="Ingresar Giro">
		  </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-raised" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-raised" id="editar">Editar</button>
      </div>
      </form>
     </div>
    </div>
  </div>
</div>

<!-- eliminar -->
<div class="modal fade" id="modaleliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: red;">
        <h4 class="modal-title" id="exampleModalLabel" align="center" style="color: white;">ELIMINAR GIRO</h4>
      </div>
      <div class="modal-body">

      	<div class="col-md-4" style="text-align: center;">
           <img src="../files/img/tramite.png" style="width: 120px;height: 120px">
        </div>
        <p style="color: red;">¿Esta seguro de eliminar este giro?</p>
            <p>Recuerda que si eliminas este giro, se perderá permanentemente</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-raised btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-raised btn-sm" id="eliminargiro">Eliminar</button>
        <input type="hidden" name="idgiro" id="ideliminar">
      </div>
    </div>
  </div>
</div>
