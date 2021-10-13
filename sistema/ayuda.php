<?php 
session_start();
?>

<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scriptpresent.php"; ?>
<title>Contigo</title>
<?php include "includes/header.php"; ?>

</head>
<body>
	
	<!-- breadcrumbs -->
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
				<li>Como Comprar</li>
			</ul>
		</div>
	</div>
	<!-- //breadcrumbs -->
	<!-- mobiles -->
<br><div id="portapdf">
    <object data="Cómo_realizo_mi_compra.pdf" type="application/pdf" width="100%" height="100%"></object>
</div>
<br>


<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
