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
		if(empty($_POST['ean']) ||  empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
			$ean       = $_POST['ean'];
			$proveedor = $_POST['proveedor'];
			$producto  = $_POST['producto'];
			$precio    = $_POST['precio'];
			$cantidad  = $_POST['cantidad'];
			$usuario_id  = $_SESSION['idUser'];

			$foto = $_FILES['foto'];
			$nombre_foto = $foto ['name'];
			$type				 = $foto ['type'];
			$url_temp		 = $foto ['tmp_name'];

			$imgproducto = 'img_producto.png';

			if($nombre_foto != '')
			{
				$destino = 'img/uploads/';
				$img_nombre ='img_'.md5(date('d-m-Y H:m:s'));
				$imgproducto = $img_nombre.'.jpg';
				$src = $destino.$imgproducto;
			}

				$query_insert = mysqli_query($conection,"INSERT INTO producto
					(ean,descripcion,proveedor,precio,existencia,usuario_id,foto)
																	VALUES('$ean','$producto','$proveedor','$precio','$cantidad','$usuario_id','$imgproducto')");

				if($query_insert){

					if($nombre_foto != ''){
						move_uploaded_file($url_temp,$src);
					}
					$alert='<p class="msg_save">Producto guardado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al guardar el producto.</p>';
				}
			}
		}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<div class="form_register">
			<h1><i class="fas fa-cubes"></i>Registro Producto</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post" enctype="multipart/form-data">

				<label for="proveedor">Proveedor</label>
				<?php
				$query_proveedor = mysqli_query($conection,"SELECT codproveedor,proveedor from proveedor where estatus =1 ORDER BY proveedor ASC;");
				 $result_proveedor = mysqli_num_rows($query_proveedor);
				 mysqli_close($conection);
				 ?>
				<select class="" name="proveedor" id="proveedor">
					<?php
					if($result_proveedor >0){
						while ($proveedor = mysqli_fetch_array($query_proveedor)) {

							?>
           <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>

							<?php
						}

					}
					 ?>

				</select>
				<label for="ean">Ean</label>
				<input type="text" name="ean" id="ean" placeholder="Nombre del Producto">
				<label for="producto">Producto</label>
				<input type="text" name="producto" id="producto" placeholder="Nombre del Producto">
				<label for="precio">Precio</label>
				<input type="number" name="precio" id="precio" placeholder="Precio del producto">
				<label for="cantidad">Cantidad</label>
				<input type="number" name="cantidad" id="cantidad" placeholder="cantidad Producto">

				<div class="photo">
	      <label for="foto">Foto</label>
        <div class="prevPhoto">
        <span class="delPhoto notBlock">X</span>
        <label for="foto"></label>
        </div>
        <div class="upimg">
        <input type="file" name="foto" id="foto">
        </div>
        <div id="form_alert"></div>
			</div>

				<input type="submit" value="Guardar producto" class="btn_save">
			</form>
				</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
