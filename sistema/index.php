<?php
	session_start();
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<?php include "includes/scriptpresent.php"; ?>
 
 <style media="screen">
 	li{
 		width: calc(100% / <?php echo $result; ?>)
 	}
 </style>
 </head>
 <body>

 	<?php include "includes/header.php"; ?>


 	
 	<!-- //navigation -->
 	<!-- banner -->
 	<div class="banner banner1">
		<div class="container">
			<!--<h2>GRANDES OFERTAS SOLO<span>PARA TI </span></h2>-->
		</div>
	</div>

 	<div class="banner-bottom1">
 		<div class="agileinfo_banner_bottom1_grids">
 			<div class="col-md-7 agileinfo_banner_bottom1_grid_left">
 				<h3>Especiales productos de electronica para ti<!--<span>20% <i>Discount</i></span>--></h3>
 				<a href="electronica.php">COMPRAR</a>
 			</div>
 			<!--<div class="col-md-5 agileinfo_banner_bottom1_grid_right">
 				<h4>OFERTA ESPECIAL</h4>
 				<div class="timer_wrap">
 					<div id="counter"> </div>
 				</div>
 				<script src="js/jquery.countdown.js"></script>
 				<script src="js/script.js"></script>
 			</div>-->
 			<div class="clearfix"> </div>
 		</div>
 	</div>


 <?php include "includes/Marcado_top.php"; ?>	
 <?php include "includes/Marcado_top_Aseo.php"; ?>

 <?php include "includes/footerplantilla.php"; ?>
 </body>
 </html>
