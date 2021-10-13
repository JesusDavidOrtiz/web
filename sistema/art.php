<?php 
session_start();
?>
<html lang="es">
	<head> 
        <?php include "includes/scriptpresent.php"; ?>
		<title>Crear Articulos</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		

        <!-- Latest compiled and minified CSS -->
     

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

	</head>
	<body>
        <?php include "includes/header.php"; ?>
		<div class="breadcrumb_dress">
            <div class="container">
                <ul>
                    <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a> <i>/</i></li>
                    <li>Actualizar Producto Masivo</li>
                </ul>
            </div>
        </div><br>
        <h1><i class="fas fa-cubes"></i> Actualizar Producto Masivo</h1>
        <br>

        <form action="files.php" method="post" enctype="multipart/form-data" id="filesForm" >
            <div class="col-md-4 offset-md-4">
                <input class="form-control" type="file" name="fileContacts" >
                <button type="button" onclick="uploadContacts()" class="btn btn-primary form-control" >Cargar</button>
            </div>
        </form>

</body>
</html>

<script type="text/javascript">

    function uploadContacts()
    {

        var Form = new FormData($('#filesForm')[0]);
        $.ajax({

            url: "import.php",
            type: "post",
            data : Form,
            processData: false,
            contentType: false,
            success: function(data)
            {
                alert('Registros Agregados!');
            }
        });
    }

</script>