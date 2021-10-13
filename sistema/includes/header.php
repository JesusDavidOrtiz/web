<?php
include "../conexion.php";
include 'conexionmenu.php';
include "menu.php";

	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

?>

  <script>
 	 $('#myModal88').modal('show');
  </script>

  <?php
  $menus = new MenuDinamic();
  ?>



  <div class="header" id="home1">

 	 <div class="container">


 		 <div class="w3l_logo">
 			 <h1><img href="index.php" src="images/DESJDWEB.jpeg" style="width: 150px"></img>
 		 </h1>


 		 </div>


 		 <div class="cart cart box_1">
 			 <button class="w3view-cart" type="submit" name="submit" value=""><a href="nueva_venta.php" >Mi carrito</a><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>


 			 <div class="optionsBar">
	 			 <p>Bogotá, <?php echo fechaC(); ?>   </p>
	 		 <!--	<span>|</span>-->
	 			 <a href="editar_usuario_contraseña.php"><span class="user">  <?php echo $_SESSION['user'].'-'.$_SESSION['rol']; ?></span><img class="photouser" src="img/user.png" alt="Usuario"></a>


 		 	</div>
 		 </div>
 		 <div class="search">
 			 <div class="w3ls_mobiles_grid_right_grid2">
						<section class="webdesigntuts-workshop">
							<form action="Busqueda.php" method="get" >
								<input type="search" name="busqueda" id="busqueda" placeholder="Descripción">
								<button> <i class="fas fa-search"></i></button>
							</form>
						</section>
						<div class="clearfix"> </div>
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
 							 	<?php

			 					if($m['idmenu'] == 1  ){

			 			 		?>
 							 		<h2>ADMINISTRACION</h2><br>

 							 	<?php } ?>
 						 			<?php foreach ($menus->getCategoria($m['idmenu']) as $s): ?>
 								 <div class="col-sm-3">
 									 <ul class="multi-column-dropdown">
 										 <h6><?php echo $s['categoria']; ?></h6>
 										 	<?php foreach ($menus->getSubCat($s['idcat']) as $sub): ?>
 										 	 	<li><a href="<?php echo $sub['enlace']; ?>"><?php echo $sub['subcategoria']; ?></a></li>
 										 	<?php endforeach; ?>
 									 </ul><br>
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



	<div class="social-bar">
    <a href="salir.php" class="icon icon-facebook"  target="_blank"><img style="width: 32px" src="img/salir.png" alt="Salir del sistema" title="Salir"> </a>
  </div>
