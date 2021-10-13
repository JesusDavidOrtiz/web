
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
  	<?php include "includes/producto.php"; ?>
<title>Contigo</title>
<link rel="stylesheet" type="text/css" href="css/Carrito.css">


</head>
<body>
  <?php include "includes/header.php"; ?>
<div class="container" align='center'>
<h3 align="center">Contigo</h3>

<!-- Proceso consulta de articulos  -->
<?php
$query = "SELECT * FROM productos ORDER BY id ASC";
$result = mysqli_query($connect, $query);
if(mysqli_num_rows($result) > 0)
{
while($row = mysqli_fetch_array($result))
{
?>
<div class="col-md-4">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?action=add&id=<?php echo $row["id"]; ?>">
<div class="thumbnail">
<img src="<?php echo $row["image"]; ?>" class="img-responsive" width="100px"/>
<div class="caption">
<h4 class="text-info text-center"><?php $row["name"]; ?></h4>
<h4 class="text-danger text-center"> <?php $row["price"]; ?></h4>
<input type="text" name="quantity" class="form-control" value="0" />
<p class='text-center'>
<input type="submit" name="add_to_cart" class="btn btn-success " value="Agregar al Carrito" /></p>
<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
</div>
</div>
</form>
</div>
<?php
}
}
?>



<!-- Proceso de Carrito de compras  -->
<div style="clear:both"></div>

<h3>Detalle de la orden</h3>
<div class="table-responsive">
<table class="table table-bordered">
<tr>
<th width="40%">Descripción</th>
<th width="10%" class='text-center'>Cantidad</th>
<th width="20%" class='text-right'>Precio</th>
<th width="15%" class='text-right'>Total</th>
<th width="5%"></th>
</tr>
<?php
if(!empty($_SESSION["shopping_cart"]))
{
$total = 0;
foreach($_SESSION["shopping_cart"] as $keys => $values)
{
?>
<tr>
<td><?php echo $values["item_name"]; ?></td>
<td class='text-center'><?php echo $values["item_quantity"]; ?></td>
<td class='text-right'>$ <?php echo $values["item_price"]; ?></td>
<td class='text-right'>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
<td><a href="Productos.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Eliminar</span></a></td>
</tr>

<?php

$total = $total + ($values["item_quantity"] * $values["item_price"]);
}
?>
<tr>
<td colspan="3" align="right">Total</td>
<td align="right">$ <?php echo number_format($total, 2); ?></td>

<td>
  <input type="submit" name="" class="btn btn-success " value="Guardar" /></p>
</td>
</tr>
<?php
}
?></table>
</div>
</div>
</body>
</html>
