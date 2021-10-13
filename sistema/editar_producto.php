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
		if(empty($_POST['ean']) ||  empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove']) ||  empty($_POST['subcategoria']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
			$codproducto = $_POST['id'];
			$ean       	 = $_POST['ean'];
			$proveedor 	 = $_POST['proveedor'];
			$subcategoria 	 = $_POST['subcategoria'];
			$producto  	 = $_POST['producto'];
			$precio    	 = $_POST['precio'];
			$imgproducto = $_POST['foto_actual'];
			$imgRemove   = $_POST['foto_remove'];


			$foto 		 = $_FILES['foto'];
			$nombre_foto = $foto ['name'];
			$type		 = $foto ['type'];
			$url_temp	 = $foto ['tmp_name'];

			$upd = '';

			if($nombre_foto != '')
			{
				$destino = 'img/uploads/';
				$img_nombre ='img_'.$ean.md5(date('D-M-Y H:m:s'));
				$imgproducto = $img_nombre.'.jpg';
				$src = $destino.$imgproducto;
			}else{
				if ($_POST['foto_actual'] != $_POST['foto_remove']) {
					$imgproducto = 'img_producto.png';
				}
			}

			$query_insert = mysqli_query($conection,"UPDATE producto
														SET  ean = $ean,
															 descripcion = '$producto',
															 proveedor = $proveedor,
															 precio = $precio,
															 foto = '$imgproducto',
															 idsub = $subcategoria
													   where codproducto = $codproducto
													   and ean = $ean ");

			if($query_insert){
					if (($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove']))
					{
						unlink('img/uploads/'.$_POST['foto_actual']);
					}

				if($nombre_foto != ''){
						move_uploaded_file($url_temp,$src);
				}
					$alert='<p class="msg_save">Producto actualizado correctamente.</p>';
					}else{
					$alert='<p class="msg_error">Error al actualizar el producto.</p>';
					}
			}
	}

//validar producto

	if (empty($_REQUEST['id'])) {
		header("location: lista_productos.php");
	}else{

		$id_producto = $_REQUEST['id'];
		if (!is_numeric($id_producto)) {
			header("location: lista_productos.php");
		}

		$query_producto = mysqli_query($conection,"SELECT p.codproducto,p.ean,p.descripcion, p.precio,p.foto,pr.codproveedor,pr.proveedor, sub.subcategoria,sub.idsub
									 from producto p
									 INNER JOIN proveedor pr
									 ON p.proveedor = pr.codproveedor
									 INNER JOIN subcategoria sub
									 ON p.idsub = sub.idsub
									 where p.codproducto = $id_producto AND p.estatus =1");
		$result_producto= mysqli_num_rows($query_producto);


		$foto ='';
		$classRemove = 'notBlock';


		if($result_producto >0){

			$data_producto = mysqli_fetch_assoc($query_producto);

			if ($data_producto['foto'] != 'img_producto.png') {
				$classRemove = '';
				$foto = '<img id="img" src="img/uploads/'.$data_producto['foto'].' alt="Producto">';
			}

			//print_r($data_producto);

		}else{
			header("location: lista_productos.php");
		}
	}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scriptpresent.php"; ?>
	<title>Actualizar Producto</title>
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
			<h1><i class="fas fa-cubes"></i>Actualizar Producto</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>



			<form action="" method="post" enctype="multipart/form-data">

			<input type="hidden" name="id" value="<?php echo $data_producto['codproducto']; ?>">
			<input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
			<input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">

				<label for="proveedor">Proveedor</label>

				<?php
				$query_proveedor = mysqli_query($conection,"SELECT codproveedor,proveedor from proveedor where estatus =1 ORDER BY proveedor ASC;");
				 $result_proveedor = mysqli_num_rows($query_proveedor);


				 $query_sub = mysqli_query($conection,"SELECT idsub,subcategoria from subcategoria where estatus = 1 ORDER BY subcategoria ASC;");
				 $result_sub = mysqli_num_rows($query_sub);


				 mysqli_close($conection);
				 ?>
				<select name="proveedor" id="proveedor" class="notItemOne">
					<option value="<?php echo $data_producto['codproveedor'] ?>" selected><?php echo $data_producto['proveedor'] ?>
					</option>
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
				<input type="text" name="ean" id="ean" placeholder="Nombre del Producto" value="<?php echo $data_producto['ean']; ?>">
				<label for="producto">Producto</label>
				<input type="text" name="producto" id="producto" placeholder="Nombre del Producto" value="<?php echo $data_producto['descripcion']; ?>">
				<label for="precio">Precio</label>
				<input type="number" name="precio" id="precio" placeholder="Precio del producto"value="<?php echo $data_producto['precio']; ?>" >

				<label for="subcategoria">Categoria</label>

				<?php
				
				 
				 ?>
				<select name="subcategoria" id="subcategoria" class="notItemOne">
					<option value="<?php echo $data_producto['idsub'] ?>" selected><?php echo $data_producto['subcategoria'] ?>
					</option>
					<?php
					if($result_sub >0){
						while ($sub = mysqli_fetch_array($query_sub)) {

							?>
           <option value="<?php echo $sub['idsub']; ?>"><?php echo $sub['subcategoria']; ?></option>

							<?php
						}

					}
					 ?>

				</select>




				<div class="photo">
				    <label for="foto">Foto</label>
			        <div class="prevPhoto">
			        <span class="delPhoto <?php echo $classRemove;?>">X</span>
			        <label for="foto"></label>
			        <?php echo $foto; ?>
			        </div>
			        <div class="upimg">
			        <input type="file" name="foto" id="foto">
			        </div>
			        <div id="form_alert"></div>
				</div>

				<input type="submit" value="Actualizar producto" class="btn_save">
			</form>
		</div>


	</section>
	<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
