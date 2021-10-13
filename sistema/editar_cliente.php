<?php

	session_start();
	if($_SESSION['rol'] != 1)
	{
		header("location: ./");
	}

	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{

			$idCliente = $_POST['id'];
			$nit       = $_POST['nit'];
			$nombre    = $_POST['nombre'];
			$telefono  = $_POST['Telefono'];
			$direccion = $_POST['direccion'];

			$result = 0;

			if (is_numeric($nit) and $nit !=0) {
				$query = mysqli_query($conection,"SELECT * FROM cliente WHERE (nit !='$nit' and idcliente !='$idCliente')");
				$result = mysqli_fetch_array($query);
				$result = count($result);
}


			if($result > 0){
				$alert='<p class="msg_error">El NIT ya existe, Registre otro</p>';
			}else{
				if ($nit== ''){
						$nit=0;
				}
					$sql_update = mysqli_query($conection,"UPDATE cliente
															SET nit = '$nit', nombre='$nombre',telefono='$telefono',direccion='$direccion'
															WHERE idcliente= $idCliente ");

				if($sql_update){
					$alert='<p class="msg_save">Cliente actualizado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al actualizar el Cliente.</p>';
				}

			}

		}

	}

	//Mostrar Datos
	if(empty($_REQUEST['id']))
	{
		header('Location: lista_clientes.php');
		mysqli_close($conection);
	}
	$idcliente = $_REQUEST['id'];

	$sql= mysqli_query($conection,"SELECT *	FROM cliente WHERE idcliente= $idcliente ");
	mysqli_close($conection);
	$result_sql = mysqli_num_rows($sql);

	if($result_sql == 0){
		header('Location: lista_clientes.php');
	}else{

		while ($data = mysqli_fetch_array($sql)) {
			# code...
			$idcliente  = $data['idcliente'];
			$nit        = $data['nit'];
			$nombre     = $data['nombre'];
			$telefono   = $data['telefono'];
			$direccion  = $data['direccion'];


		}
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scriptpresent.php"; ?>
	<title>Actualizar Cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>Actualizar</li>
			</ul>
		</div>
	</div>
	<section id="container">

		<div class="form_register">
			<h1>Actualizar Cliente</h1>
			<hr>
	<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<imput type="hidden" name="id" value="<?php echo $idcliente; ?>">
				<label for="nit">ID</label>
				<input type="number" name="nit" id="nit" placeholder="numero de NIT" value="<?php echo $nit; ?>" >
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="<?php echo $nombre; ?>">
				<label for="telefono">Telefono</label>
				<input type="number" name="Telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono; ?>">
				<label for="direccion">Dirección</label>
				<input type="text" name="direccion" id="direccion" placeholder="Direccion" value="<?php echo $direccion; ?>">

				<input type="submit" value="Guardar Cliente" class="btn_save">

			</form>


		</div>


	</section>
	<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
