<?php
	session_start();
	/*if($_SESSION['rol'] != 1)
	{
		header("location: ./");
	}*/
	include "../conexion.php";

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de usuarios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>Lista de usuarios</li>
			</ul>
		</div>
	</div>
	<section id="container">

		<h1>Detalle usuario</h1>

<!--
		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="submit" value="Buscar" class="btn_search">
		</form>-->

		<table>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Correo</th>
				<th>Usuario</th>
				<th>Direccion</th>
				<th>Telefono</th>
				<th>Acciones</th>
			</tr>

		<?php
			//Paginador
			$usuario_id = $_SESSION['user'];
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM usuario WHERE estatus = 1 ");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 10;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}



			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);


$query = mysqli_query($conection,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol,u.telefono,u.direccion FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE usuario = '$usuario_id'");
			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {

			?>
				<tr>
					<td><?php echo $data["idusuario"]; ?></td>
					<td><?php echo $data["nombre"]; ?></td>
					<td><?php echo $data["correo"]; ?></td>
					<td><?php echo $data["usuario"]; ?></td>
					<td><?php echo $data['direccion'] ?></td>
					<td><?php echo $data['telefono'] ?></td>
					<td>
						<a class="link_edit" href="editar_usuario_contraseña.php?id=<?php echo $data["idusuario"]; ?>">Editar</a>

					</td>
				</tr>

		<?php
				}

			}
		 ?>

		</table>



	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
