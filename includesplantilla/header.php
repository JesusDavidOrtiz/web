<?php
	include "conexion.php";
	include 'sistema/includes/conexionmenu.php';
	include "includesplantilla/menu.php";

?>


	<script>
		$('#myModal88').modal('show');
	</script>
  <?php
  $menus = new MenuDinamic();
  ?>
	<div class="header" id="home1">

		<div class="container">

			<div class="w3l_login">
				<a href="#" data-toggle="modal" data-target="#myModal88"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
			</div>

			<div class="w3l_logo">
 				<h1><img href="index.php" src="sistema/images/CORBETA.png" style="width: 150px"/></h1>
			</div>


			<div class="search">
 			 	<input class="search_box" type="checkbox" id="search_box"/>
 			 	<label class="icon-search" for="search_box"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></label>
 			 		<div class="search_form">
 				 		<form action="#" method="post">
 					 		<input type="text" name="Search" placeholder="Search...">
 							 <input type="submit" value="Send">
 				 		</form>
 					 </div>
 		 	</div>

			<div class="cart cart box_1">
 			 	<button class="w3view-cart" type="submit" name="submit" value=""><a href="index.php" ><h5>Mi carrito</h5></a><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>		
 			 	<div class="optionsBar">
	 			 	<p>Bogotá, <?php echo fechaC(); ?>   </p>
	 		 	</div>
 		 	</div>

		</div>
	</div>

	<div class="navigation">

		<div class="container">
			<nav class="navbar navbar-default">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header nav_2">
					<button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
						<span class="sr-only">Navegacion</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
 				 	<ul class="nav navbar-nav">
						<?php foreach ($menus->getMenu() as $m): ?>
						<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $m['menu']; ?><b class="caret"></b></a>
 						 	<ul class="dropdown-menu multi-column columns-3">
 							 	<div class="row">
 							 		<?php foreach ($menus->getCategoria($m['idmenu']) as $s): ?>
 								<div class="col-sm-3">
 									<ul class="multi-column-dropdown">
 										<h6><?php echo $s['categoria']; ?></h6>
 										 	<?php foreach ($menus->getSubCat($s['idcat']) as $sub): ?>
 										 	 	<li><a href="sistema/mercado.php"><?php echo $sub['subcategoria']; ?></a></li>
 										 	<?php endforeach; ?>
 									</ul>
 								</div>	
 							 		<?php endforeach; ?>
 								</div>
 						 	</ul>
 					 	</li>
 					 <?php endforeach; ?>
	 				</ul>
 				</div>
			</nav>
		</div>
	</div>
<!--<div class="social-bar">
    <a href="https://es-la.facebook.com/Contigored/" class="icon icon-facebook"  target="_blank"><img style="width: 20px" src="sistema/img/facebook.png"> </a>
    <a href="https://www.instagram.com/redcontigo/" class="icon icon-instagram" target="_blank"><img style="width: 20px" src="sistema/img/instagram.png"></a>
  </div>-->