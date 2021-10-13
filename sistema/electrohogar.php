<?php

	session_start();

	include "../conexion.php";

	 $salida = "";


//Paginador
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM menu m
				 INNER JOIN categorias ct
				 ON m.idmenu = ct.idmenu
				 INNER JOIN subcategoria sub
				 ON  ct.idcat  = sub.idcat
                 INNER JOIN producto p
				 ON  sub.idsub  = p.idsub
                 INNER JOIN proveedor pr
				 ON p.proveedor = pr.codproveedor
				 WHERE p.estatus = 1 
                 and m.idmenu = 5      
               	 and p.existencia >0    
                 and p.foto != 'img_producto.png'            
				 ORDER BY p.descripcion DESC");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 48;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);



	$consulta_product = mysqli_query($conection,"SELECT p.codproducto,p.ean,p.descripcion,p.precio,p.existencia,pr.proveedor,sub.subcategoria,p.foto,p.idboton,p.Promocion,m.menu
				 FROM menu m
				 INNER JOIN categorias ct
				 ON m.idmenu = ct.idmenu
				 INNER JOIN subcategoria sub
				 ON  ct.idcat  = sub.idcat
                 INNER JOIN producto p
				 ON  sub.idsub  = p.idsub
                 INNER JOIN proveedor pr
				 ON p.proveedor = pr.codproveedor
				 WHERE p.estatus = 1 
                 and m.idmenu = 5     
                 and p.existencia >0    
                 and p.foto != 'img_producto.png'              
				 ORDER BY p.descripcion DESC 
				 LIMIT $desde,$por_pagina");


	

 if (isset($_POST['consulta'])) {
    	$q = real_escape_string($_POST['consulta']);
    	$consulta_product = mysqli_query($conection,"SELECT p.codproducto,p.ean,p.descripcion,p.precio,p.existencia,pr.proveedor,sub.subcategoria,p.foto,p.idboton,p.Promocion,m.menu
				 FROM menu m
				 INNER JOIN categorias ct
				 ON m.idmenu = ct.idmenu
				 INNER JOIN subcategoria sub
				 ON  ct.idcat  = sub.idcat
                 INNER JOIN producto p
				 ON  sub.idsub  = p.idsub
                 INNER JOIN proveedor pr
				 ON p.proveedor = pr.codproveedor
				 WHERE p.codproducto LIKE '%$q%' OR p.ean LIKE '%$q%' OR p.descripcion LIKE '%$q%' OR p.precio LIKE '%$q%' OR p.existencia LIKE '$q' OR pr.proveedor LIKE '%$q%' OR sub.subcategoria LIKE '%$q%'
				 ORDER BY p.codproducto DESC 
				 LIMIT $desde,$por_pagina");
    }

    $result_p = mysqli_num_rows($consulta_product);
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
	<!-- banner 
	<div class="banner banner1">
		<div class="container">
			<h2>Great Offers on <span>Mobiles</span> Flat <i>35% Discount</i></h2>
		</div>
	</div>-->
	<!-- breadcrumbs -->
	<div class="banner banner1">
		<div class="container">
			<!--<h2>GRANDES OFERTAS SOLO<span>PARA TI </span></h2>-->
		</div>
	</div>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>ELECTROHOGAR</li>
			</ul>
		</div>
	</div>



	<!-- //breadcrumbs -->
	<!-- mobiles -->
	<div class="mobiles">
		<div class="container">


			<div class="w3ls_mobiles_grids">
					<!--<div class="w3ls_mobiles_grid_right_grid2">
						<section class="webdesigntuts-workshop">
							<form action="Busqueda_electrohogar.php" method="get" >		    
								<input type="search" name="busqueda" id="busqueda" placeholder="Descripción">		    	
								<button> <i class="fas fa-search"></i></button>
							</form>
						</section>						
						<div class="clearfix"> </div>
					</div>-->
					
				<?php include "includes/filtrocatelectrohogar.php"; ?>

				
				<div class="col-md-8 w3ls_mobiles_grid_right">

					<!--<div class="w3ls_mobiles_grid_right_grid2">
						<div class="w3ls_mobiles_grid_right_grid2_right">
							<select name="select_item" class="select_item">
								<form action="Busqueda_electrohogar.php" method="get" >
									<option selected="selected">Seleccion Por Defecto</option>
									<option value="p.precio DESC">Precio: Mayor a Menor</option>
									<option value="p.precio ASC">Precio: Menor a Mayor</option>
								</form>
							</select>
						</div>	

						<div class="clearfix"> </div>
					</div>-->

					<div class="clearfix"> </div>
					<div class="w3ls_mobiles_grid_right_grid3">
						<section class="products-list">
							<?php
							if($result_p > 0){
								while ($Product = mysqli_fetch_array($consulta_product)) {
									if($Product['foto'] != 'img_producto.png' ){
										$foto = 'img/uploads/'.$Product['foto'];
									}else {
									$foto ='img/'.$Product['foto'];
									}

									?>
									
									
									<div class="product-item" category="<?php echo $Product["subcategoria"]; ?>">
							
								<div class="agile_ecommerce_tab_left mobiles_grid">
									<div class="hs-wrapper hs-wrapper2" >

										<img src="<?php echo $foto; ?>" alt=" " class="img-responsive" />
										<img src="<?php echo $foto; ?>" alt=" " class="img-responsive" />
										<img src="<?php echo $foto; ?>" alt=" " class="img-responsive" />
										<img src="<?php echo $foto; ?>" alt=" " class="img-responsive" />
										<img src="<?php echo $foto; ?>" alt=" " class="img-responsive" />
								        <div class="w3_hs_bottom w3_hs_bottom_sub1">
										    <ul>
												<li>
												<a href="single.php?id=<?php echo $Product['codproducto'];?>" ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span><br> Ver datelle</a>
												</li>
											</ul>
										</div>
									</div>
									
									<h5><a href="single.php?id=<?php echo $Product['codproducto'];?>"><?php echo $Product['descripcion']; ?></a></h5>
									
									<div class="simpleCart_shelfItem">
										<p><i class="item_price">$<?php echo number_format($Product['precio']); ?></i></p>
		<form action="#" method="post">
			<input type="hidden" name="cmd" value="_cart" />
			<input type="hidden" name="txt_cod_producto" id="txt_cod_producto" value="<?php echo $Product['codproducto']; ?>"/>
			<input type="hidden" name="add" value="1" id="txt_cant_producto"/>
			<input type="hidden" name="w3ls_item" id="txt_existencia" value="<?php echo $Product['existencia']; ?>" />
			<input type="hidden" name="w3ls_item" id="txt_descripcion" value="<?php echo $Product['descripcion']; ?>" />
			<input type="hidden" name="amount" id="txt_precio" value="<?php echo $Product['precio']; ?>"/>			
			<!--<button type="submit" class="w3ls-cart" id="add_product_ventas" >Agregar</button>-->
		</form>
									</div>

										<?php
										if($Product['Promocion'] != ''){
											?>
									<div class="mobiles_grid_pos">
										<h6><?php echo $Product['Promocion']; ?></h6>
									</div>
											<?php
									}
									?>
								</div>
							
						</div>	
									<?php
										}
									}
								
									?>
						<div class="clearfix"> </div>

						
						</section>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<div class="paginador">
			<ul>
			<?php
				if($pagina != 1)
				{
			 ?>
				<li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>
			<?php
				}
				for ($i=1; $i <= $total_paginas; $i++) {
					# code...
					if($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';
					}else{
						echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
					}
				}

				if($pagina != $total_paginas)
				{
			 ?>
				<li><a href="?pagina=<?php echo $pagina + 1; ?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?> ">>|</a></li>
			<?php } ?>
			</ul>
		</div>

	</div>
	


<?php include "includes/footerplantilla.php"; ?>

<script type="text/javascript">
			$(document).ready(function()
			{
				var usuarioid = '<?php echo $_SESSION['idUser'] ?>';
				serchForDetalle(usuarioid);

			});
		</script>
</body>
</html>
