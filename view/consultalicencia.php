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
			  <h1 class="text-titles">Consulta de Licencias</h1>
			</div>
		</div>
		<div class="full-box text-center" style="padding: 30px 10px;">
		<div class="col-md-12">
			<div class="row">
			<div class="col-md-7" style="">    
				<video id="preview" width="80%"></video>
             </div>
             <div class="col-md-4"> 
             	<form>
             		<input type="text" name="idtramite" id="idtramite" class="form-control">
             		<button type="button" class="btn btn-success btn-raised" id="btnescan"><i class="zmdi zmdi-search-for"></i>	</button>
             	</form>   
				
				<button type="button" class="btn btn-warning btn-raised" onclick="prender()"><i class="zmdi zmdi-desktop-windows"></i></button>
             </div>
             <div class="col-md-4">
             	<label style="color: blue;font-size: 30px;" id="hola"></label>
             </div>
             <center>
             <div class="col-md-12"> 
             	<h1 align="center" style="color: red;">Licencia de Funcionamiento</h1>   
				<div class="table-responsive">
					<table align="center" class="table table-striped table-bordered" style="width:60%" id="expediente" border="1">
				    </table>
				</div>
             </div>
         </center>   
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
	<script type="text/javascript" src="../public/instascan/js/instascan.min.js"></script>
	<script type="text/javascript" src="script/consultalicenciaweb.js"></script>
	<script type="text/javascript" src="../public/toastr/js/toastr.min.js"></script>
</body>
</html>




