<?php

	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
 ?>

<div class="modal">
		<div class="bodyModal">
			<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefaul(); sendDataProduct();">

			<h1><i class="fas fa-cubes"></i><br> Agregar Producto</h1>
			<h2 class="nameProducto">Monitor</h2>
			<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto"><br>
			<input type="text" name="precio" id="txtPrecio" placeholder="Precio del Producto">
			<input type="hidden" name="producto_id" id="producto_id" required>
			<input type="hidden" name="action" value="addProduct" required>
			<div class="alert alertAddProduct"> </div>
			<button type="submit" class="btn_new"><i class="fas fa-plus"></i>Agregar </button>
			<a href="#" class="btn_ok closeModal" onclick="coloseModal();"><i class="fas fa-ban"></i>Cerrar </a>
			</form>
		</div>

		<?php include "header.php"; ?>
	</div>
