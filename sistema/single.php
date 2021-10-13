<?php
	session_start();
	include "../conexion.php";
		
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scriptpresent.php"; ?>

<title>Contigo</title>
<?php include "includes/header.php"; ?>

</head>
<body>
	<!-- //navigation -->
	<!-- banner -->
	<div class="banner banner10">
		<div class="container">
			<h2>Detalle</h2>
		</div>
	</div>
	<!-- //banner -->
	<!-- breadcrumbs -->
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<!--<pre lang="html4strict" lineno="1"><a href="javascript:window.history.back();">« Volver atrás</a></pre>-->
				<li><a href="javascript:window.history.back();"><span class="glyphicon glyphicon-home" ></span> Atras</a> <i>/</i></li><li>Detalle <i>/</i><a href="nueva_venta.php" >Mi carrito</a></li>
			</ul>

		</div>
	</div>
	<!-- //breadcrumbs -->
	<!-- single -->
	<div class="single">	
		<div class="container">
			<div class="col-md-4 single-left">




<?php
		
	$consulta_product = mysqli_query($conection,"SELECT * from producto where codproducto=".$_GET['id'])or die(mysqli_error());
	$result_p = mysqli_num_rows($consulta_product);

		while ($f=mysqli_fetch_array($consulta_product)) {		
						
							
									if($f['foto'] != 'img_producto.png' ){
										$foto = 'img/uploads/'.$f['foto'];
									}else {
									$foto ='img/'.$f['foto'];
									}

						?>
				<div class="flexslider">
					<ul class="slides">
						<li data-thumb="<?php echo $foto; ?>">
							<div class="thumb-image"> <img src="<?php echo $foto; ?>" data-imagezoom="true" class="img-responsive" alt=""> </div>
						</li>
						<li data-thumb="<?php echo $foto; ?>">
							 <div class="thumb-image"> <img src="<?php echo $foto; ?>" data-imagezoom="true" class="img-responsive" alt=""> </div>
						</li>
						<li data-thumb="<?php echo $foto; ?>">
						   <div class="thumb-image"> <img src="<?php echo $foto; ?>" data-imagezoom="true" class="img-responsive" alt=""> </div>
						</li>
						
					</ul>

				</div>

				<!-- flexslider -->
					<script defer src="js/jquery.flexslider.js"></script>
					<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
					<script>
					// Can also be used with $(document).ready()
					$(window).load(function() {
					  $('.flexslider').flexslider({
						animation: "slide",
						controlNav: "thumbnails"
					  });
					});
					</script>
				<!-- flexslider -->
				<!-- zooming-effect -->
					<script src="js/imagezoom.js"></script>
				<!-- //zooming-effect -->
			</div>
			<div class="col-md-8 single-right">
				<!--<h3><?php echo $f['descripcion']; ?></h3>-->
				<h3><i><?php echo $f['ean']; ?></i></h3>
				<div class="rating1">
					<span class="starRating">
						
						<input id="rating4" type="radio" name="rating" value="4">
						<label for="rating4">4</label>
						<input id="rating3" type="radio" name="rating" value="3" checked>
						<label for="rating3">3</label>
						<input id="rating2" type="radio" name="rating" value="2">
						<label for="rating2">2</label>
						<input id="rating1" type="radio" name="rating" value="1">
						<label for="rating1">1</label>
					</span>
				</div>
				<table class="tbl_venta">
					<thead>
						<tr>
							<!--<th class="textcenter">Precio UN</th>-->

							<td id="txt_precio" class="textleft" value="<?php echo $f['precio']; ?>" style="font-size: 2.8125rem; color: #F35331;"><span itemprop="priceCurrency" content="COP">$</span> <?php echo number_format($f['precio']); ?></td>
						</tr>
						<tr>
							<!--<th class="textcenter" >Código</th>-->
							<td> <input type="hidden" name="txt_cod_producto" id="txt_cod_producto" value="<?php echo $f['codproducto'];?>" disabled class="textleft" /></td>
						</tr>
						<tr>
							<!--<th class="textcenter">Descripción</th>-->
							<td id="txt_descripcion" class="textleft"><?php echo $f['descripcion']; ?></td>
						</tr>
						<tr>
							<!--<th class="textcenter">Existencia</th>-->
							<td id="txt_existencia" class="textleft" ><p class="availability in-stock">Disponibilidad: <span>En existencia*</span></p><?php echo $f['existencia']; ?>

								<!--<div class="other-plus">
	                             <p><img src="https://media.aws.alkosto.com/media/ALKOSTO/alkosto-rwd/envio_gratis.png" alt="Envío Gratis">&nbsp;&nbsp;Envío Gratis</p> 
	                        	</div>-->
							</td>
						</tr>
						<tr>
						<!--<th class="textcenter" >Cantidad</th> -->                     
           					<td ><input type="text" class="textleft" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" /> </td>
						</tr>

						<tr>
						  	<!--<th class="textcenter">Acción</th>-->
						<td><button type="submit" name="submit" value=""><a href="#" class="link_add" id="add_product_venta">Agregar al carrito</a><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button></td>
						</tr>
						
						
					</thead>
				</table>

				

				<!--<div class="description">
					<h5><i><?php echo $f['ean']; ?></i></h5>
					<p><?php echo $f['descripcion']; ?></p>
				</div>
				<div class="simpleCart_shelfItem">
						<p><i class="item_price">$<?php echo $f['precio']; ?></i></p>
					<form action="#" method="post">
						<input type="hidden" name="cmd" value="_cart" />
						<input type="hidden" name="txt_cod_producto" id="txt_cod_producto" value="<?php echo $f['codproducto'];?>"/>
						<label >Cantidad</label>
						<input type="number" style="border: 0px; width:50px; background-color: #def;" name="add" value="1" min="1" id="txt_cant_producto" />
						<input type="hidden" name="w3ls_item" id="txt_existencia" value="<?php echo $f['existencia']; ?>" disabled />
						<input type="hidden" name="w3ls_item" id="txt_descripcion" value="<?php echo $f['descripcion']; ?>" />
						<input type="hidden" name="amount" id="txt_precio" value="<?php echo $f['precio']; ?>"/><br>
						
						<button type="submit" class="w3ls-cart" id="add_product_ventas" onclick='alert("Articulo agregado al carrito de compras.")'>Agregar a carrito</button>
						
					</form>

				</div>-->
	<?php
		}
	?>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>

	<!-- Related Products -->
	 <?php include "includes/Marcado_top.php"; ?>	
 <?php include "includes/Marcado_top_Aseo.php"; ?>
	<!-- //Related Products -->


<?php include "includes/footerplantilla.php"; ?>


</body>
</html>
