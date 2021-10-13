<?php
	session_start();
		include "../conexion.php";

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scriptpresent.php"; ?>
	<title>Lista de productos</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>Lista de Productos</li>
			</ul>
		</div>
	</div>

	<section id="container">

		<h1><i class="fas fa-cube"></i> Lista de Productos</h1>

		<?php if ($_SESSION['rol'] == 1 || $_SESSION ['rol'] ==2) {?>
		<a href="registro_producto.php" class="btn_new"><i class="fas fa-plus"></i> Crear Producto</a>

		<a href="art.php" class="btn_new"><i class="fas fa-plus"></i> Actualizar Producto Masivo</a>
		<?php } ?>

		<form action="buscar_producto.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>Cod</th>
				<th>EAN</th>
				<th>Descripcion</th>
				<th>Precio</th>
				<th>Cantidad</th>
				<th>Proveedor</th>

				<th>Categoria</th>
				<th>Foto</th>
				<?php if ($_SESSION['rol'] == 1 || $_SESSION ['rol'] ==2) {?>
		    	<th>Acciones</th>
		    	<?php } 
		    	?>
			</tr>
		<?php
			//Paginador
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM producto WHERE estatus = 1 ");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 20;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection,"SELECT p.codproducto,p.ean,p.descripcion,p.precio,p.existencia,pr.proveedor,sub.subcategoria,p.foto
				 FROM producto p
				 INNER JOIN proveedor pr
				 ON p.proveedor = pr.codproveedor
				 INNER JOIN subcategoria sub
				 ON  p.idsub  = sub.idsub
				 WHERE p.estatus = 1 ORDER BY p.descripcion DESC LIMIT $desde,$por_pagina");

				mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					if($data['foto'] != 'img_producto.png' ){
						$foto = 'img/uploads/'.$data['foto'];
					}else {
						$foto ='img/'.$data['foto'];
					}
								?>
				<tr class="row<?php echo $data["codproducto"]; ?>">
					<td><?php echo $data["codproducto"]; ?></td>
					<td><?php echo $data["ean"]; ?></td>
					<td><?php echo $data["descripcion"]; ?></td>
					<td class="celPrecio"><?php echo number_format($data["precio"]); ?></td>
					<td class="celExistencia"><?php echo number_format($data["existencia"]); ?></td>
					<td><?php echo $data["proveedor"]; ?></td>
					<td><?php echo $data["subcategoria"]; ?></td>
					<td class="img_producto" id="img-contenedor"><img src="<?php echo $foto; ?>"  alt="<?php echo $data["descripcion"]; ?>"></td>


					<?php if ($_SESSION['rol'] == 1 || $_SESSION ['rol'] ==2) {?>
						<td>
							<!--<a class="link_add add_product"  href="#" product="<?php echo $data["codproducto"]; ?>" ><i class="fas fa-plus"></i> Agregar</a>
							|-->
							<a class="link_edit" href="editar_producto.php?id=<?php echo $data["codproducto"]; ?>"><i class="far fa-edit"></i> Editar</a>
							<!--|
							<a class="link_delete del_product" href="#" product="<?php echo $data["codproducto"]; ?>"><i class="far fa-trash-alt"></i> Eliminar</a>-->

						</td>
					<?php } ?>
				</tr>
				<?php
				}
			}
		 ?>
		</table>
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


	</section>
	<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
