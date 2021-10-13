<?php
include "../conexion.php";
session_start();

$fileContacts = $_FILES['fileContacts']; 
$fileContacts = file_get_contents($fileContacts['tmp_name']); 

$fileContacts = explode("\n", $fileContacts);
$fileContacts = array_filter($fileContacts); 

// preparar contactos (convertirlos en array)
foreach ($fileContacts as $contact) 
{
	$contactList[] = explode(",", $contact);
}
// Actualizar Produtos
foreach ($contactList as $contactUpdate) 
{
	$usuario_id  = $_SESSION['idUser'];

	if ($contactUpdate > 0 ) {

		$query_producto = mysqli_query($conection,"SELECT * from producto
									 where ean in ('{$contactUpdate[1]}' )");
		
		$result_producto= mysqli_num_rows($query_producto);

		if($result_producto > 0){

			$query_insert = mysqli_query($conection,"UPDATE producto
														SET  ean = '{$contactUpdate[1]}',
															 descripcion = '{$contactUpdate[2]}',
															 proveedor = '{$contactUpdate[3]}',
															 precio = '{$contactUpdate[4]}',
															 existencia = '{$contactUpdate[5]}',
															 usuario_id =  '{$usuario_id}',
															 estatus = '{$contactUpdate[8]}',
															 idsub =  '{$contactUpdate[9]}',
															 foto = '{$contactUpdate[10]}',
															 idboton = '{$contactUpdate[11]}',
															 Promocion = '{$contactUpdate[12]}'
													   where ean in ('{$contactUpdate[1]}') ");
		}else {

	$query_insert = mysqli_query($conection,"INSERT INTO producto
						(ean,descripcion,proveedor,precio,existencia,usuario_id,estatus,idsub,foto,idboton,Promocion)
						 VALUES

						 ('{$contactUpdate[1]}', 
						  '{$contactUpdate[2]}',
						  '{$contactUpdate[3]}',
						  '{$contactUpdate[4]}',
						  '{$contactUpdate[5]}',						  
						  '{$usuario_id}',
						  '{$contactUpdate[8]}',
						  '{$contactUpdate[9]}',
						  '{$contactUpdate[10]}',
						  '{$contactUpdate[11]}',
						  '{$contactUpdate[12]}'
						   )"); 
		}


		
	}else{
		$alert='<p class="msg_error">Error al actualizar el producto.</p>';
	}

}

// insertar Productos

/*
foreach ($contactList as $contactData) 
{
	$usuario_id  = $_SESSION['idUser'];

	$query_insert = mysqli_query($conection,"INSERT INTO producto
						(ean,descripcion,proveedor,precio,existencia,usuario_id,estatus,idsub,foto,idboton,Promocion)
						 VALUES

						 ('{$contactData[1]}', 
						  '{$contactData[2]}',
						  '{$contactData[3]}',
						  '{$contactData[4]}',
						  '{$contactData[5]}',						  
						  '{$usuario_id}',
						  '{$contactData[8]}',
						  '{$contactData[9]}',
						  '{$contactData[10]}',
						  '{$contactData[11]}',
						  '{$contactData[12]}'
						   )"); 
}*/


?>