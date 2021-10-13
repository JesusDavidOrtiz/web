<?php


$alert = '';
session_start();
if(!empty($_SESSION['active']))
{
	header('location: sistema/');
}else{

	if(!empty($_POST))
	{
		if(empty($_POST['usuario']) || empty($_POST['clave']))
		{
			$alert = 'Ingrese su usuario y su calve';
		}else{

			require_once "conexion.php";

			$user = mysqli_real_escape_string($conection,$_POST['usuario']);
			$pass = md5(mysqli_real_escape_string($conection,$_POST['clave']));

			$query = mysqli_query($conection,"SELECT * FROM usuario WHERE usuario= '$user' AND clave = '$pass'");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			if($result > 0)
			{
				$data = mysqli_fetch_array($query);
				$_SESSION['active'] = true;
				$_SESSION['idUser'] = $data['idusuario'];
				$_SESSION['cedula'] = $data['cedula'];
				$_SESSION['nombre'] = $data['nombre'];
				$_SESSION['email']  = $data['correo'];
				$_SESSION['user']   = $data['usuario'];
				$_SESSION['telefono']   = $data['telefono'];
				$_SESSION['direccion']   = $data['direccion'];
				$_SESSION['rol']    = $data['rol'];

				header('location: sistema/ ');
			}else{
				$alert = 'El usuario o la clave son incorrectos';
				session_destroy();
			}
		}
	}
}
include "conexion.php";

if(!empty($_POST))
	{
		
		if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave'])||empty($_POST['direccion']) || empty($_POST['rol']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{

			$cedula = $_POST['cedula'];
			$nombre = $_POST['nombre'];
			$email  = $_POST['correo'];
			$user   = $_POST['usuario'];
			$clave  = md5($_POST['clave']);
			$direccion  = $_POST['direccion'];
			$telefono  = $_POST['telefono'];
			$rol    = $_POST['rol'];


			$query = mysqli_query($conection,"SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ");
			$result = mysqli_fetch_array($query);

			if($result > 0){
				$alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
			}else{

				$query_insert = mysqli_query($conection,"INSERT INTO usuario(cedula,nombre,correo,usuario,clave,direccion,telefono,rol)
																	VALUES('$cedula','$nombre','$email','$user','$clave','$direccion','$telefono','$rol')");
				if($query_insert){
					$alert='<p class="msg_save">Usuario creado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al crear el usuario.</p>';
				}
			}
		}
	}

 ?>
 <div class="modal fade" id="myModal88" tabindex="-1" role="dialog" aria-labelledby="myModal88"

		aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;</button>
					<h4 class="modal-title" id="myModalLabel">¡No espere, inicie sesión ahora!</h4>
				</div>
				<div class="modal-body modal-body-sub">
					<div class="row">
						<div class="col-md-8 modal_body_left modal_body_left1" style="border-right: 1px dotted #C2C2C2;padding-right:3em;">
							<div class="sap_tabs">
								<div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
									<ul>
										<li class="resp-tab-item" aria-controls="tab_item-0"><span>INICIAR SESION</span></li>
										<li class="resp-tab-item" aria-controls="tab_item-1"><span>Crear Cuenta</span></li>
									</ul>
									<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
										<div class="facts">
											<div class="register">
												<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
												<form action="" method="post">
													<input name="usuario" placeholder="Usuario" type="text" maxlength="15" required="">
													<input name="clave" placeholder="Contraseña" type="password" required="">

													<div class="sign-up">
														<input type="submit" value="INGRESAR"/>
													</div>
												</form>
											</div>
										</div>
									</div>
									<div class="tab-2 resp-tab-content" aria-labelledby="tab_item-1">
										<div class="facts">
											<div class="register">
												<section id="container">

		<div class="form_register">
			<br>

			<h1>Registro usuario</h1>
			<hr>
			

			<form action="" method="post">
				
				<input type="text" name="cedula" id="cedula" placeholder="Cedula">
				
				<input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
				
				<input type="email" name="correo" id="correo" placeholder="Correo electrónico">
				
				<input type="text" name="usuario" id="usuario" placeholder="Usuario">
				
				<input type="password" name="clave" id="clave" placeholder="Clave de acceso">
				
				<input type="text" name="direccion" id="direccion" placeholder="Direccion Completa">
				
				<input type="text" name="telefono" id="telefono" placeholder="Telefono">
				

				<select name="rol" id="rol">
					
							<option value="3">Cliente</option>
				</select>
				<input type="submit" value="Crear usuario" class="btn_save">


			</form>


		</div>


	</section>
											</div>
										</div>
									</div>
								</div>
							</div>
							<script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
							<script type="text/javascript">
								$(document).ready(function () {
									$('#horizontalTab').easyResponsiveTabs({
										type: 'default',
										width: 'auto',
										fit: true
									});
								});
							</script>
							<div id="OR" class="hidden-xs">O</div>
						</div>
						<div class="col-md-4 modal_body_right modal_body_right1">
							<h1><img href="index.php" src="sistema/images/CORBETA.png" style="width: 250px"/></h1>
							<!--<div class="row text-center sign-with">
								<div class="col-md-12">
									<h3 class="other-nw">INICIA SESION CON</h3>
								</div>
								<div class="col-md-12">
									<ul class="social">
										<li class="social_facebook"><a href="#" class="entypo-facebook"></a></li>
										<li class="social_dribbble"><a href="#" class="entypo-dribbble"></a></li>
										<li class="social_twitter"><a href="#" class="entypo-twitter"></a></li>
										<li class="social_behance"><a href="#" class="entypo-behance"></a></li>
									</ul>
								</div>
							</div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
