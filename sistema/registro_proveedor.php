 <?php
	session_start();

	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['contacto']) ||empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
      		$contacto = $_POST['contacto'];
			$nombre = $_POST['nombre'];
			$telefono  = $_POST['telefono'];
			$direccion = $_POST['direccion'];
			$usuario_id = $_SESSION['idUser'];
			$result    = 0;

			if (!is_numeric($contacto)) {
				$query = mysqli_query($conection,"SELECT * FROM Proveedor WHERE proveedor = '$nombre'");
				$result = mysqli_fetch_array($query);
			}
			if($result > 0 ){
				$alert='<p class="msg_error">El proveedor ya existe.</p>';
			}else {
				$query_insert = mysqli_query($conection,"INSERT INTO proveedor(proveedor,contacto,telefono,direccion,usuario_id)
																	VALUES('$nombre','$contacto','$telefono','$direccion','$usuario_id')");

		if($query_insert){
			$alert='<p class="msg_save">Proveedor guardado correctamente.</p>';
			}else{
				$alert='<p class="msg_error">Error al guardar el Proveedor.</p>';
				}
			}
		}
			mysqli_close($conection);
	}



 ?>

<!DOCTYPE html>
<html lang="en">
<head>

	<?php include "includes/scriptpresent.php"; ?>
	<title>Registro Proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>Registro Proveedor</li>
			</ul>
		</div>
	</div>
	<section id="container">

		<div class="form_register">
			<br>
			<h1><i class="fas fa-plus"></i> Registro Proveedor</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="nombre">Proveedor</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
				<label for="contacto">Nombre Contacto</label>
				<input type="text" name="contacto" id="contacto" placeholder="Corbeta">
				<label for="telefono">Telefono</label>
				<input type="number" name="telefono" id="telefono" placeholder="Telefono">
				<label for="direccion">Dirección</label>
				<input type="text" name="direccion" id="direccion" placeholder="Direccion">

				<input type="submit" value="Guardar Cliente" class="btn_save">

			</form>


		</div>


	</section>
	<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
