<?php
	session_start();
		include "../conexion.php";

 ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<?php include "includes/scriptpresent.php";?>
		<title>Nueva Venta</title>
	</head>
	<body>

		<?php include "includes/header.php"; ?>

		<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="javascript:window.history.back();"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Segir comprando</a> <i>/</i></li>
				<li>Mi carrito</li>
			</ul>
		</div>
	</div>

<selection id="container">
		<div class="title_page">
				<h1><i class="fas fa-cube"></i>Nueva Venta </h1>
		</div>
		<div class="datos_cliente">
			<div class="action_cliente">
				<h4>Datos Cliente</h4>
					<?php if ($_SESSION['rol'] == 1 || $_SESSION ['rol'] ==2) {?>
					<a href="#" class="btn_new btn_new_cliente"><i class="fas fa-plus"></i> Nuevo Cliente </a>
				<?php } ?>
					<a href="#" class="btn_new btn_cambio_direccion"><i class="fas fa-plus"></i> Cambiar Direccion</a>
			</div>
			

			<h3 style="color: red;" > <i class="far fa-newspaper"> </i> Por favor confirmar su numero de Cedula*</h3>


			<form  name="for_new_cliente_venta" id="for_new_cliente_venta" class="datos">
			<input type="hidden" name="action" value="addCliente">
			<input type="hidden" name="idcliente" value="idcliente"  id="idcliente" required>
				<div class="wd30">
					<label for="nit">CC</label>
					<input type="text" name="nit_cliente" id="nit_cliente" placeholder="C.C." >
				</div>
				<div class="wd30">
					<label for="nombre">Nombre</label>
					<input type="text" name="nom_cliente" id="nom_cliente"  disabled required 
					value="<?php echo $_SESSION['nombre'] ; ?>">
			  	</div>
				<div class="wd30">
					<label for="telefono">Telefono</label>
					<input type="number" name="tel_cliente" id="tel_cliente" disabled required 
					value="<?php echo $_SESSION['telefono'] ; ?>">
				</div>
				<div class="wd100">
					<label for="direccion">Dirección</label>
					<input type="text" name="dir_cliente" id="dir_cliente"  disabled required 
					value="<?php echo $_SESSION['direccion'];?>">
				</div >

				<div id="div_registro_cliente" class="wd100">
					<button type="submit" class="btn_save" ><i class="far fa-save fa-lg"></i>Guardar </button>
				</div>				
				
				<div id="div_cambio_direccion" class="wd100">
					<button type="submit" class="btn_save" ><i class="far fa-save fa-lg"></i>Cambiar Direccion </button>
				</div>
				<h5 style="color: blue;" > <i class="far fa-newspaper"> </i>Nota: Tan pronto cambies la dirección volver a confirmar su numero de CC.*</h5>
			</form>





	</div>

		<div class="datos_venta">
				<h4>Datos Venta</h4>
				<div class="datos">
					<div class="wd50">
						<label> Vendedor</label>
						<p><?php echo $_SESSION['nombre'] ; ?> </p>
					</div>
					<div class="wd50">
						<label>Acciones</label>
						<div id="acciones_venta">
							<a href="#" class="btn_ok textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i>Anular</a>
							<a href="#" class="btn_new textcenter" id="btn_facturar_venta" style="display: none;"><i class="far fa-edit"></i>Comprar</a>
						</div>
					</div>
				</div>
			</div>
			<table class="tbl_venta">
				<thead>
					<tr>
						<th width="100px">Código</th>
						<th>Descripción</th>
						<th>Existencia</th>
						<th width="100px">Cantidad</th>
						<th class="textright">Precio</th>
						<th class="textright">Precio Total</th>
					  	<th>Acción</th>
					</tr>
					<tr>
						<td> <input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
						<td id="txt_descripcion">-</td>
						<td id="txt_existencia">-</td>
						<td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled> </td>
						<td id="txt_precio" class="textright">0.00</td>
						<td id="txt_precio_total" class="textright">0.00</td>
						<td> <a href="#" class="link_add" id="add_product_venta"><i class="fas fa-plus"></i>Agregar</a></td>
					</tr>
					<tr>
						<th>Código</th>
						<th colspan="2">Descripción</th>
						<th >Cantidad</th>
						<th class="textright">Precio</th>
						<th class="textright">Precio Total</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody id="detalle_venta">
					
					<!-- Contenido formulario desde ajax.php -->
				</tbody>
				<tfoot id="detalle_totales">
					<!-- Contenido formulario desde ajax.php -->
				</tfoot>
</table>
</selection>
<br><br>

		<?php include "includes/footerplantilla.php"; ?>

		<script type="text/javascript">
			$(document).ready(function()
			{
				var usuarioid = '<?php echo $_SESSION['idUser'] ?>';
				serchForDetalle(usuarioid);
			});
		</script>

	</body>
</html>
