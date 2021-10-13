 <?php
	session_start();

	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nit']) ||empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
      		$nit = $_POST['nit'];
			$nombre = $_POST['nombre'];
			$telefono  = $_POST['telefono'];
			$direccion = $_POST['direccion'];
			$usuario_id = $_SESSION['idUser'];
			$result    = 0;

			if (is_numeric($nit)) {
				$query = mysqli_query($conection,"SELECT * FROM cliente WHERE nit = '$nit'");
				$result = mysqli_fetch_array($query);
			}
			if($result > 0 ){
				$alert='<p class="msg_error">El numero de NIT ya existe.</p>';
			}else {
				$query_insert = mysqli_query($conection,"INSERT INTO cliente(nit,nombre,telefono,direccion,usuario_id)
																	VALUES('$nit','$nombre','$telefono','$direccion','$usuario_id')");

		if($query_insert){
			$alert='<p class="msg_save">Cliente guardado correctamente.</p>';
			}else{
				$alert='<p class="msg_error">Error al guardar el cliente.</p>';
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
	<title>Registro Clientes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>Registro Cliente</li>
			</ul>
		</div>
	</div>
	<section id="container">

		<div class="form_register">
			<br>
			<h1><i class="fas fa-plus"></i> Registro Cliente</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="nit">ID</label>
				<input type="number" name="nit" id="nit" placeholder="numero de NIT">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
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
