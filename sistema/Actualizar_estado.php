<?php
	session_start();
	if($_SESSION['rol'] != 1)
	{
		header("location: ./");
	}
	require 'PHPMailer/PHPMailerAutoload.php';
	include "../conexion.php";

	//validar producto

	if (empty($_REQUEST['id'])) {
		header("location: ventas.php");
	}else{

		$id_venta = $_REQUEST['id'];
		if (!is_numeric($id_venta)) {
			header("location: ventas.php");
		}

		$query_producto = mysqli_query($conection,"SELECT *,us.nombre,cl.nombre as cliente, 
															cl.correo as correo, es.descripcion, f.noPedido,f.comentario
												    from factura f 
												    INNER JOIN usuario us
													ON f.usuario = us.idusuario 
													INNER JOIN cliente cl
													ON f.codcliente = cl.idcliente 
													INNER JOIN estados es
													ON f.estatus = es.idestado 
													where f.nofactura = $id_venta");
		$result_producto= mysqli_num_rows($query_producto);


		if($result_producto >0){

			$data_producto = mysqli_fetch_assoc($query_producto);

			//print_r($data_producto);

		}else{
			header("location: ventas.php");
		}
	}


	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['estado']) || empty($_POST['Pedido']) || empty($_POST['comentario']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
			$noFactura = $_POST['no_factura'];
			$estado    = $_POST['estado'];
			$Pedido	   = $_POST['Pedido'];
			$comentario	 = $_POST['comentario'];
			$correo    = $_SESSION['email'];
			$nombre    = $_SESSION['nombre'];

					

			$query_insert = mysqli_query($conection,"UPDATE factura
														SET  estatus = $estado,
															 noPedido = '$Pedido',
															 comentario = '$comentario'
													   where nofactura = $id_venta");

			if($query_insert > 0){



					$query = mysqli_query($conection,"SELECT * FROM configuracion");
					$result = mysqli_fetch_assoc($query);

					if ($result >0) {
						$query_producto = mysqli_query($conection,"SELECT *,us.nombre,cl.nombre as cliente, 
															cl.correo as correo, es.descripcion, f.noPedido,
															 f.comentario
												    from factura f 
												    INNER JOIN usuario us
													ON f.usuario = us.idusuario 
													INNER JOIN cliente cl
													ON f.codcliente = cl.idcliente 
													INNER JOIN estados es
													ON f.estatus = es.idestado 
													where f.nofactura = $id_venta");
						$result_producto= mysqli_num_rows($query_producto);


						if($result_producto >0){

							$data_producto = mysqli_fetch_assoc($query_producto);

						}else{
							header("location: ventas.php");
						}


					}
					
					$mail = new PHPMailer();
					$mail->isSMTP();
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = 'tls';//Modificar
					$mail->Host = $result['host'];//Modificar
					$mail->Port = $result['puerto'];//Modificar
					$mail->Username = $result['email_emisor']; //Modificar
					$mail->Password = $result['password']; //Modificar
					
					$mail->setFrom($result['email_emisor'], 'Factura de Compra');//Modificar	
					
					//$mail->addAttachment('factura_19.pdf','factura.PDF');

					$mail->addAddress($data_producto['correo'], $data_producto['correo']);//Modificar

					
					$mail->Subject = $result['asunto'];//Modificar
					$mail->Body = '<H2 style="color: blue; text-aling:center;">Hola, '. $data_producto['nombre'].'</H2>
								   <H3>Gracias por utilizar los servicios de nuestra plataforma MERK2. los siguientes son los datos de tu transacci&oacuten:</H3><br>

								   <p> Estado de la Transacci&oacuten: '.$data_producto['descripcion'].'</p>
								   <P> Pedido: '.$Pedido.'</P>
								   <P> Empresa: '.$result['nombre'].'</P>
								   <P> Valor de la Transacci&oacuten: '.number_format($data_producto['totalfactura']);.'</P>
								   <P> Comentario: '.$data_producto['comentario'].'</P>'; //Modificar
					$mail->IsHTML(true);
					
					if($mail->send()){
						$alert='<p class="msg_save">Estado actualizado correctamente.</p>';
							
					
					}else{
					$alert='<p class="msg_error">Error al actualizar el estado.</p>';
					}
			}else{
				$alert='<p class="msg_error">Error al actualizar el estado.</p>';
			}
			
		

			

		}
	}




 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scriptpresent.php"; ?>
	<title>Actualizar Estado</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="ventas.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Atras</a> <i>/</i></li>
				<li>Actualizar Estado</li>
			</ul>
		</div>
	</div>
	<section id="container">

		<div class="form_register">
			<h1><i class="fas fa-cubes"></i>Actualizar Estado</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>



			<form action="" method="post" enctype="multipart/form-data">

			<input type="hidden" name="no_factura" id="no_factura" value="<?php echo $data_producto['nofactura']; ?>">
			<input type="hidden" name="correo" value="<?php echo $data_producto['correo']; ?>">
			

				<label for="estado">Nuevo estado*</label>
				<?php

					$query_estado = mysqli_query($conection,"SELECT * FROM estados");
					mysqli_close($conection);
					$result_estado = mysqli_num_rows($query_estado);

				 ?>

				<select name="estado" id="estado">
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
				</select>
				<label for="Pedido">No. Pedido*</label>
				<input type="text" name="Pedido" id="Pedido" placeholder="No. Pedido" value="<?php echo $data_producto['noPedido']; ?>" >

				<label for="comentario">Comentario*</label>
				<input type="text" name="comentario" id="comentario" placeholder="Motivo de anulación" value="<?php echo $data_producto['comentario']; ?>" >

				<label for="vendedor">Vendedor</label>
				<input type="text" name="vendedor" id="vendedor" placeholder="Nombre del Producto" value="<?php echo $data_producto['nombre']; ?>" disabled>

				<label for="Cliente">Cliente</label>
				<input type="text" name="Cliente" id="Cliente" placeholder="Nombre del Producto" value="<?php echo $data_producto['cliente']; ?>" disabled>
				<label for="precio">Total factura $</label>
				<input type="number" name="precio" id="precio" placeholder="Precio del producto" value="<?php echo $data_producto['totalfactura']; ?>" disabled>
				
				<input type="submit" value="Actualizar producto" class="btn_save">
			</form>
		</div>


	</section>
	<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
