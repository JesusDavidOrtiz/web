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
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>Lista de Ventas</li>
			</ul>
		</div>
	</div>
	<section id="container">

		<h1> <i class="far fa-newspaper"> </i> Lista de Ventas</h1>
		<a href="nueva_venta.php" class="btn_new"><i class="fas fa-plus"> </i> Nueva venta</a>

		

		<form action="buscar_ventas.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="No Factura">
			<input type="submit" value="Buscar" class="btn_search">
		</form>
		<div>
			<h5>
				<form action="buscar_ventas.php" method="get" class="form_search_date">
					<label>De: </label>
					<input type="date" name="fecha_de" required="">
					<label>A: </label>
					<input type="date" name="fecha_a" required="">
					<button type="submit" class="btn_new"><i class="fas fa-search"></i> </button>
				</form>
			</h5>
		</div>



		<table>
			<tr>
				<th>No.</th>
				<th>Fecha / Hora</th>
				<th>Cliente</th>
				<th>Vendedor</th>
				<th><?php

					$query_estado = mysqli_query($conection,"SELECT * FROM estados");

					$result_estado = mysqli_num_rows($query_estado);

				 ?>

				<select name="estado" id="estado">
					<option value="#">Estado</option>
					<?php
						if($result_estado > 0)
						{
							while ($estado = mysqli_fetch_array($query_estado)) {
					?>
							<option value="<?php echo $estado["idestado"]; ?>"><?php echo $estado["descripcion"] ?></option>
					<?php
								# code...
							}

						}
					 ?>
				</select></th>
				<th>Pedido</th>
				<th class="textrihgt">Total Factura</th>
				<th class="textrihgt">Acciones</th>
			</tr>
		<?php
			//Paginador
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM factura WHERE estatus != 10 ");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 20;

			$user = $_SESSION['idUser'];

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection,"SELECT f.nofactura,
													 f.fecha,
													 f.totalfactura,
													 f.codcliente,
													 f.estatus,
													 f.noPedido,
													 u.nombre as vendedor,
													 cl.nombre as cliente
											    FROM factura f
										   INNER JOIN usuario u
										   ON f.usuario = u.idusuario
										   INNER JOIN cliente cl
										   ON f.codcliente = cl.idcliente
										   WHERE f.estatus != 10
										   ORDER BY f.fecha DESC LIMIT $desde, $por_pagina");

			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					if ($data["estatus"] == 1) {
						$estado = '<span class="Solicitada"> Solicitada</span>';
					}
					if ($data["estatus"] == 2) {
						$estado = '<span class="Digitado"> Digitado</span>';
					}
					if ($data["estatus"] == 3) {
						$estado = '<span class="Reservado"> Reservado</span>';
					}

					if ($data["estatus"] == 4){
						$estado = '<span class="anulada"> Anulada</span>';
					}
				?>
				<tr id="row_<?php echo $data["nofactura"]; ?>">
					<td><a href="Detalle_venta.php?id=<?php echo $data['nofactura'];?>"><?php echo $data["nofactura"]; ?></a> </td>
					<td><?php echo $data["fecha"]; ?></td>
					<td><?php echo $data["cliente"]; ?></td>
					<td><?php echo $data["vendedor"]; ?></td>
					<td><?php echo $estado; ?></td>
					<td><?php echo $data["noPedido"]; ?></td>
					<td class="textrihgt totalfactura"><span>$</span><?php echo number_format($data["totalfactura"]); ?></td>





					<td>
						<div class="div_acciones">
							<div>
								<button class="btn_view view_factura" type="button" cl="<?php echo $data["codcliente"] ?>" f="<?php echo $data["nofactura"] ?>"><i class="fas fa-eye"></i> VER</button>
							</div>	
									
							
							<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ){
								 if($data["estatus"] != 4)
							{

							 ?>

							<!--<div class="div_factura">
								<button class="btn_anular anular_factura" data-toggle="modal" data-target="#myModal<?php echo $data["nofactura"]; ?>" fac="<?php echo $data["nofactura"]; ?>"><i class="fas fa-ban"></i> </button>
							</div>-->
							<a href="actualizar_estado.php?id=<?php echo $data["nofactura"]; ?>" class="btn_new" cl="<?php echo $data["codcliente"] ?>" fac="<?php echo $data["nofactura"] ?>">Actualizar</a>
							

							<?php }else{  ?>
								<div class="div_factura">
									<button type="button" class="btn_anular inactive" ><i class="fas fa-ban"></i> </button>
								</div>
							<?php }
							}
						 	?>
						</div>
					</td>
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
