<?php
	session_start();
	if($_SESSION['rol'] != 1)
	{
		header("location: ");
	}

	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave'])||empty($_POST['direccion']) || empty($_POST['rol']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{

			$cedula = $_POST['cedula'];
			$nombre = $_POST['nombre'];
			$email  = $_POST['correo'];
			$user   = $_POST['usuario'];
			$clave  = md5($_POST['clave']);
			$direccion  = $_POST['direccion'];
			$telefono  = $_POST['telefono'];
			$rol    = $_POST['rol'];


			$query = mysqli_query($conection,"SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ");
			$result = mysqli_fetch_array($query);

			if($result > 0){
				$alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
			}else{

				$query_insert = mysqli_query($conection,"INSERT INTO usuario(cedula,nombre,correo,usuario,clave,direccion,telefono,rol)
																	VALUES('$cedula','$nombre','$email','$user','$clave','$direccion','$telefono','$rol')");
				if($query_insert){
					$alert='<p class="msg_save">Usuario creado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al crear el usuario.</p>';
				}
			}
		}
	}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<?php include "includes/scriptpresent.php"; ?>
 <title>Contigo</title>

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

			<h1><i class="fas fa-user-plus"></i>Registro usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="cedula">Nombre</label>
				<input type="text" name="cedula" id="cedula" placeholder="Cedula">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
				<label for="correo">Correo electrónico</label>
				<input type="email" name="correo" id="correo" placeholder="Correo electrónico">
				<label for="usuario">Usuario</label>
				<input type="text" name="usuario" id="usuario" placeholder="Usuario">
				<label for="clave">Clave</label>
				<input type="password" name="clave" id="clave" placeholder="Clave de acceso">
				<label for="direccion">Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Direccion Completa">
				<label for="telefono">Telefono</label>
				<input type="text" name="telefono" id="telefono" placeholder="Telefono">
				<label for="rol">Tipo Usuario</label>

				<?php

					$query_rol = mysqli_query($conection,"SELECT * FROM rol");
					mysqli_close($conection);
					$result_rol = mysqli_num_rows($query_rol);

				 ?>

				<select name="rol" id="rol">
					<?php
						if($result_rol > 0)
						{
							while ($rol = mysqli_fetch_array($query_rol)) {
					?>
							<option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"] ?></option>
					<?php
								# code...
							}

						}
					 ?>
				</select>
				<input type="submit" value="Crear usuario" class="btn_save">


			</form>


		</div>


	</section>
	<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
