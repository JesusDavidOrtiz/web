<?php
	session_start();
		include "../conexion.php";

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scriptpresent.php"; ?>
	<title>Lista de Ventas</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="ventas.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Atras</a> <i>/</i></li>
				<li>Detalle Factura</li>

			</ul>
		</div>
	</div><br>


<div class="container">
	<section id="container">
		<h1> <i class="far fa-newspaper"> </i> Detalle Ventas</h1><br><br><br>
		
		<table>
			<tr>
				<th>No.</th>
				<th>Fecha / Hora</th>
				<th>Ean</th>
				<th>Descripcion</th>
				<th>Cantidad</th>
				<th>Precio Venta</th>
				<th>Foto</th>
				
				
			</tr>
			
		<?php
			
			$query = mysqli_query($conection,"SELECT f.nofactura,
													   f.fecha,
													   f.estatus,
												       pr.ean,
												       pr.descripcion,
												       u.cantidad,
												       u.precio_venta,
												       f.totalfactura,
												       pr.foto
															FROM factura f
													INNER JOIN detallefactura u
													ON f.nofactura = u.nofactura
													INNER JOIN producto pr
												 ON u.codproducto = pr.codproducto
													WHERE f.nofactura =".$_GET['id']);

			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					if ($data["estatus"] == 1 && $data['foto'] != 'img_producto.png' ) {
						$estado = '<span class="pagada"> Pagada</span>';
						$foto = 'img/uploads/'.$data['foto'];
					}else{
						$estado = '<span class="anulada"> Anulada</span>';
						$foto ='img/'.$data['foto'];
					}

				?>
				<tr id="row_<?php echo $data["nofactura"]; ?>">
					<td><?php echo $data["nofactura"]; ?></a> </td>
					<td><?php echo $data["fecha"]; ?></td>
					<td><?php echo $data["ean"]; ?></td>
					<td><?php echo $data["descripcion"]; ?></td>
					<td><?php echo $data["cantidad"]; ?></td>
					<td><?php echo $data["precio_venta"]; ?></td>
					<td class="img_producto" id="img-contenedor"><img src="<?php echo $foto; ?>"  alt="<?php echo $data["descripcion"]; ?>"></td>
					
					

				<tr>
					<th colspan="6" class="textrihgt">Total Factura</th>
					<td class="textrihgt totalfactura"><span>$</span><?php echo $data["totalfactura"]; ?></td>
				</tr>
				</tr>
		<?php
				}

			}
		 ?>



		</table><br><br><br>
				
		

	</section>
</div>
	<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
