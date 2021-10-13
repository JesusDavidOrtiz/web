<?php
	session_start();
	require 'PHPMailer/PHPMailerAutoload.php';
		require "../conexion.php";

		//print_r($_POST); exit;

		if (!empty($_POST)) {

			//Extraer datos del Producto
			if ($_POST['action'] == 'infoProducto')
			 {
				$producto_id = $_POST['producto'];

				$query = mysqli_query($conection,"SELECT codproducto,descripcion,existencia,precio  FROM producto WHERE codproducto = $producto_id and estatus = 1 ");

				mysqli_close($conection);

				$result = mysqli_num_rows($query);
			   if($result > 0){
				$data = mysqli_fetch_assoc($query);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
				exit;
				}
				echo "error";
				exit;
			}


			//Agregar productos a entrada
			if ($_POST['action'] == 'addProduct')
			 {
			 	if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])) 
			 	{

			 	$cantidad    = $_POST['cantidad'];
			 	$precio      = $_POST['precio'];
			 	$producto_id = $_POST['producto_id'];
			 	$usuario_id  = $_SESSION['idUser'];

			 	$query_insert = mysqli_query($conection,"INSERT INTO entradas(codproducto,
			 																	cantidad,
			 																	precio,
			 																	usuario_id)
			 																	VALUES($producto_id,
			 																		   $cantidad,
			 																		   $precio,
			 																		   $usuario_id)");

			 	if ($query_insert) {
			 		$query_upd = mysqli_query($conection,"CALL actualizar_precio_producto($cantidad,$precio,$producto_id);");
			 		$result_pro = mysqli_num_rows($query_upd);
			 		if ($result_pro >0) {
			 			$data = mysqli_fetch_assoc($query_upd);
			 			$data['producto_id'] = $producto_id;
			 			echo json_encode($data,JSON_UNESCAPED_UNICODE);
			 			exit;			 			
			 		}
			 	}else{
			 		echo "error";
			 	}
			 	mysqli_close($conection);

			 	}else{
			 		echo "error";
			 	}
			 	exit;
			 }

			 //Buscar datos del cliente
			 if ($_POST['action'] == 'searchCliente'){
			 	echo "Buscar Cliente";
			 	exit;
			 }


			//Eliminar datos del Producto
			if ($_POST['action'] == 'delProduct')
			{
			 	if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
			 		echo "error";
			 	}else{
					$idproducto = $_POST['producto_id'];

					$query_delete = mysqli_query($conection,"UPDATE producto SET estatus = 0 WHERE codproducto = $idproducto");
					mysqli_close($conection);

					if($query_delete){
						header("location: lista_productos.php");
					}else{
						echo "Error al eliminar";
					}
				}
				echo "error";
				exit;
			}

			//Buscar Cliente
			if ($_POST['action'] == 'SearchCliente') 
			{
				if (!empty($_POST['cliente'])) {

					$nit = $_POST['cliente'];
					$query = mysqli_query($conection,"SELECT * FROM cliente WHERE nit like '$nit' and estatus = 1 ");
					mysqli_close($conection);
					$result = mysqli_num_rows($query);

					$data = '';

					if($result > 0){
						$data = mysqli_fetch_assoc($query);
					}else{
						$data = 0;
					}
					echo json_encode($data, JSON_UNESCAPED_UNICODE);
					}
					exit;
			}

			//registro cliente desde ventas
		    if ($_POST['action'] == 'addCliente')
		    {
			 	$nit = $_POST['nit_cliente'];
				$nombre = $_POST['nom_cliente'];
				$telefono  = $_POST['tel_cliente'];
				$direccion = $_POST['dir_cliente'];
				$usuario_id = $_SESSION['idUser'];

			$query = mysqli_query($conection,"SELECT * FROM cliente WHERE nit = '$nit' ");
			$result = mysqli_fetch_array($query);

			if($result > 0){
				$query_update = mysqli_query($conection,"UPDATE cliente
														SET direccion = '$direccion'
													   where nit = $nit");

				if($query_update){
			 		$codCliente = mysqli_insert_id($conection);
				 	$msg1 = $codCliente;
				}else{
					$msg1='error';
				}
				mysqli_close($conection);
				echo $msg1;
				exit;

			}else{

				$query_insert = mysqli_query($conection,"INSERT INTO cliente(nit,nombre,telefono,direccion,usuario_id)
																	VALUES('$nit','$nombre','$telefono','$direccion','$usuario_id')");


				if($query_insert){
			 		$codCliente = mysqli_insert_id($conection);
				 	$msg = $codCliente;
				}else{
					$msg='error';
				}
				mysqli_close($conection);
				echo $msg;
				exit;
			}


			}

			//Agregar Producto detalle
			if ($_POST['action'] == 'addProductoDetalle')
			{ 		
				if (empty($_POST['producto']) || empty($_POST['cantidad']))
				{
					echo "error";
				}else{
					$codproducto = $_POST['producto'];
					$cantidad	 = $_POST['cantidad'];
					$token		 = md5($_SESSION['idUser']);

					$query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
					$result_iva = mysqli_num_rows($query_iva);

					$query_detalle_tem = mysqli_query($conection, "CALL add_detalle_tem($codproducto,$cantidad,'$token')");
					$result = mysqli_num_rows($query_detalle_tem);

					$detalle_tabla = '';
					$detalleTotales = '';
					$sub_total     = 0;
					$iva           = 0;
					$total         = 0;
					$arrayData     = array();

					if ($result > 0)
					{
						if ($result_iva > 0)
						{
							$info_iva = mysqli_fetch_assoc($query_iva);
							$iva = $info_iva['iva'];
						}
						while ($data = mysqli_fetch_assoc($query_detalle_tem))
						{
							$precioTotal = round($data['cantidad'] * $data['precio_venta'],2);
							$sub_total   = round($sub_total + $precioTotal, 2);
							$total       = round($total + $precioTotal, 2);

							$detalle_tabla .='<tr>
												<td>'.$data['codproducto'].'</td>
												<td colspan="2">'.$data['descripcion'].'</td>
												<td class="textcenter">'.$data['cantidad'].'</td>
												<td class="textright">'.$data['precio_venta'].'</td>
												<td class="textright">'.$precioTotal.'</td>
												<td class="">
												<a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="far fa-trash-alt"></i></a>
													</td>
												</tr>';
						}

						$impuesto = round($sub_total * ($iva / 100), 2);
						$tl_sniva = round($sub_total - $impuesto, 2);
						$totaldtl    = round($tl_sniva + $impuesto, 2);

						$detalleTotales	.= '<tr>
												<td colspan="5" class="textright">SUBTOTAL</td>
												<td class="textcenter">'.$tl_sniva.'</td>
											</tr>
											<tr>
												<td colspan="5" class="textright">IVA('.$iva.')</td>
												<td class="textcenter">'.$impuesto.'</td>
											</tr>
											<tr>
												<td colspan="5" class="textright">Total</td>
												<td class="textcenter"> '.$totaldtl.' </td>
											</tr>';

						$arrayData['detalle'] = $detalle_tabla;
						$arrayData['totales'] = $detalleTotales;

						echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);

					}else{
						echo'error';
					}
					mysqli_close($conection);

				}
				exit;
			}

			//Anular Venta
			if ($_POST['action'] == 'anularVenta'){
				$token = md5($_SESSION['idUser']);
				$query_del = mysqli_query($conection, "DELETE FROM detalle_temp where token_user = '$token'");
				mysql_close($conection);
				if ($query_del) {
					echo "Ok";
				}else{
					echo "error";
				}
				exit;
 
			}

			//Extrae detalle tem
			if ($_POST['action'] == 'serchForDetalle')
			{ 		
				if (empty($_POST['user']))
				{
					echo "error";
				}else{
					
					$token		 = md5($_SESSION['idUser']);

					$query = mysqli_query($conection, "SELECT tmp.correlativo,
															  tmp.token_user,
															  tmp.cantidad,
															  tmp.precio_venta,
															  p.codproducto,
															  p.descripcion,
															  p.ean
														 FROM detalle_temp tmp 
												   INNER JOIN producto p 
												           ON tmp.codproducto = p.codproducto
        												WHERE tmp.token_user = '$token'");

					$result = mysqli_num_rows($query);

					$query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
					$result_iva = mysqli_num_rows($query_iva);

					
					

					$detalle_tabla = '';
					$detalleTotales = '';
					$sub_total     = 0;
					$iva           = 0;
					$total         = 0;
					$arrayData     = array();

					if ($result > 0)
					{
						if ($result_iva > 0)
						{
							$info_iva = mysqli_fetch_assoc($query_iva);
							$iva = $info_iva['iva'];
						}
						while ($data = mysqli_fetch_assoc($query))
						{
							$precioTotal = round($data['cantidad'] * $data['precio_venta'],2);
							$sub_total   = round($sub_total + $precioTotal, 2);
							$total       = round($total + $precioTotal, 2);

							$detalle_tabla .='<tr>
												<td>'.$data['codproducto'].'</td>
												<td colspan="2">'.$data['descripcion'].'</td>
												<td class="textcenter">'.$data['cantidad'].'</td>
												<td class="textright">'.$data['precio_venta'].'</td>
												<td class="textright">'.$precioTotal.'</td>
												<td class="">
												<a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="far fa-trash-alt"></i></a>
													</td>
												</tr>';
						}

						$impuesto = round($sub_total * ($iva / 100), 2);
						$tl_sniva = round($sub_total - $impuesto, 2);
						$totaldtl    = round($tl_sniva + $impuesto, 2);

						$detalleTotales	.= '<tr>
												<td colspan="5" class="textright">SUBTOTAL</td>
												<td class="textcenter">'.$tl_sniva.'</td>
											</tr>
											<tr>
												<td colspan="5" class="textright">IVA('.$iva.')</td>
												<td class="textcenter">'.$impuesto.'</td>
											</tr>
											<tr>
												<td colspan="5" class="textright">Total</td>
												<td class="textcenter"> '.$totaldtl.' </td>
											</tr>';

						$arrayData['detalle'] = $detalle_tabla;
						$arrayData['totales'] = $detalleTotales;

						echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);

					}else{
						echo'error';
					}
					mysqli_close($conection);

				}
				exit;
			}

			//eliminar producto detalle
			if ($_POST['action'] == 'delproductodetalle')
			{ 		
				if (empty($_POST['id_detalle']))
				{
					echo "error";
				}else{

					$id_detalle  = $_POST['id_detalle'];
					$token		 = md5($_SESSION['idUser']);

					$query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
					$result_iva = mysqli_num_rows($query_iva);

					$query_detalle_tem = mysqli_query($conection, "CALL del_detalle_tem($id_detalle,'$token')");
					$result = mysqli_num_rows($query_detalle_tem);
					

					$detalle_tabla = '';
					$detalleTotales = '';
					$sub_total     = 0;
					$iva           = 0;
					$total         = 0;
					$arrayData     = array();

					if ($result > 0)
					{
						if ($result_iva > 0)
						{
							$info_iva = mysqli_fetch_assoc($query_iva);
							$iva = $info_iva['iva'];
						}
						while ($data = mysqli_fetch_assoc($query_detalle_tem))
						{
							$precioTotal = round($data['cantidad'] * $data['precio_venta'],2);
							$sub_total   = round($sub_total + $precioTotal, 2);
							$total       = round($total + $precioTotal, 2);

							$detalle_tabla .='<tr>
												<td>'.$data['codproducto'].'</td>
												<td colspan="2">'.$data['descripcion'].'</td>
												<td class="textcenter">'.$data['cantidad'].'</td>
												<td class="textright">'.$data['precio_venta'].'</td>
												<td class="textright">'.$precioTotal.'</td>
												<td class="">
												<a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="far fa-trash-alt"></i></a>
													</td>
												</tr>';
						}

						$impuesto = round($sub_total * ($iva / 100), 2);
						$tl_sniva = round($sub_total - $impuesto, 2);
						$totaldtl = round($tl_sniva + $impuesto, 2);

						$detalleTotales	.= '<tr>
												<td colspan="5" class="textright">SUBTOTAL</td>
												<td class="textcenter">'.$tl_sniva.'</td>
											</tr>
											<tr>
												<td colspan="5" class="textright">IVA('.$iva.')</td>
												<td class="textcenter">'.$impuesto.'</td>
											</tr>
											<tr>
												<td colspan="5" class="textright">Total</td>
												<td class="textcenter"> '.$totaldtl.' </td>
											</tr>';

						$arrayData['detalle'] = $detalle_tabla;
						$arrayData['totales'] = $detalleTotales;

						echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);

					}else{
						echo'error';
					}
					mysqli_close($conection);

				}
				exit;
			}

			//Info Factura
			if ($_POST['action'] == 'infofactura')
			 {
				if (!empty($_POST['nofactura'])) {
					
					$nofactura = $_POST['nofactura'];
					$query_nofactura = mysqli_query($conection, "SELECT * FROM factura WHERE nofactura = '$nofactura' 
																		     AND estatus = 1");

					mysqli_close($conection);

					$result = mysqli_num_rows($query_nofactura);

					if ($result > 0) {

					$data = mysqli_fetch_assoc($query_nofactura);
					echo json_encode($data,JSON_UNESCAPED_UNICODE);
						exit;
					}

				}
				echo "error";
				exit;
			}



			//Generar Venta
			if ($_POST['action'] == 'procesarVenta')
			{	

				if (empty($_POST['codcliente']))
				{
					$codCliente = 1;
				}else{
					$codcliente = $_POST['codcliente'];
				}
				
				$token		 = md5($_SESSION['idUser']);
				$usuario     = $_SESSION['idUser'];
				$correo     = $_SESSION['email'];
				$nombre    = $_SESSION['nombre'];

				$query = mysqli_query($conection,"SELECT * FROM detalle_temp WHERE token_user = '$token' ");
				$result = mysqli_num_rows($query);

				if ($result > 0) 
				{	
						$precio = mysqli_query($conection,"SELECT precio_venta FROM detalle_temp WHERE token_user = '$token' ");
						$precio = mysqli_fetch_assoc($precio);

						
						$query_correo = mysqli_query($conection,"SELECT * FROM configuracion");
						$result_correo = mysqli_fetch_assoc($query_correo);
						
						$mail = new PHPMailer();
						$mail->isSMTP();
						$mail->SMTPAuth = true;
						$mail->SMTPSecure = 'tls';//Modificar
						$mail->Host = $result_correo['host'];//Modificar
						$mail->Port = $result_correo['puerto'];//Modificar
						$mail->Username = $result_correo['email_emisor']; //Modificar
						$mail->Password = $result_correo['password']; //Modificar
						
						$mail->setFrom($result_correo['email_emisor'], 'Factura de Compra');//Modificar	
						
						//$mail->addAttachment('factura/generaFactura.php?cl=$codcliente','factura.PDF');

						$mail->addAddress($correo, $correo);//Modificar

						
						$mail->Subject = $result_correo['asunto'];//Modificar
						$mail->Body = '<H2 style="color: blue; text-aling:center;">Hola, '. $nombre.'</H2>
								   <H3>Gracias por utilizar los servicios de nuestra plataforma MERK2. los siguientes son los datos de tu transacci&oacuten:</H3><br>

								   <p> Estado de la Transacci&oacuten: Solicitada</p>
								   <P> Empresa: '.$result_correo['nombre'].'</P>
								   <P> Valor de la Transacci&oacuten: '.$precio['precio_venta'].'</P>'; //Modificar
						$mail->IsHTML(true);	

						if($mail->send()){
							$query_procesar = mysqli_query($conection, "CALL procesar_venta($usuario,$codcliente,'$token')");
							$result_detalle = mysqli_num_rows($query_procesar);	

							if ($result_detalle > 0) {
							   
								$data = mysqli_fetch_assoc($query_procesar);
								echo json_encode($data, JSON_UNESCAPED_UNICODE);



							}else{
								echo 'ERROR';
							}
						}else {
							echo 'Error al enviar el correo';
						}
				}else{
					echo "error";
				}
				mysqli_close($conection);
				exit;
			}

		


		
			
			//Anular Factura
			if ($_POST['action'] == 'anularFactura'){
				if (!empty($_POST['nofactura'])) {
					$nofactura = $_POST['nofactura'];

					$query_anular = mysqli_query($conection,"CALL anular_factura ($nofactura)");
					mysqli_close($conection);
					$result = mysqli_num_rows($query_anular);
					if ($result > 0) {
						$data = mysqli_fetch_assoc($query_anular);
						echo json_encode($data,JSON_UNESCAPED_UNICODE);
						exit;
					}
				}
				echo "error";
				exit;
			}

		}

	exit;

 ?>





