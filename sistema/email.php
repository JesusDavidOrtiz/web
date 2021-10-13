<?php
	require 'PHPMailer/PHPMailerAutoload.php';
	require '../conexion.php';

	$query = mysqli_query($conection,"SELECT * FROM configuracion");
	$result = mysqli_fetch_assoc($query);
	
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';//Modificar
	$mail->Host = $result['host'];//Modificar
	$mail->Port = $result['puerto'];//Modificar
	$mail->Username = $result['email_emisor']; //Modificar
	$mail->Password = $result['password']; //Modificar
	
	$mail->setFrom($result['email_emisor'], 'Factura de Compra');//Modificar	
	
	$mail->addAttachment('factura_19.pdf','factura.PDF');

	$mail->addAddress('davinchi89@hotmail.com', 'davinchi89@hotmail.com');//Modificar

	
	$mail->Subject = $result['asunto'];//Modificar
	$mail->Body = $result['cuerpo']; //Modificar
	$mail->IsHTML(true);
	
	if($mail->send()){
		echo 'Enviado';
		} else {
		echo 'Error';
	}
?>