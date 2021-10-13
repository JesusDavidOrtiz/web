		<nav>
			<ul>
				<li><a href="index.php"><i class="fas fa-home"></i>Inicio</a></li>

			<?php
				if($_SESSION['rol'] == 1){

			 ?>
				<li class="principal">
					<a href="#"><i class="fas fa-users"></i>Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fas fa-user-plus"></i>Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fas fa-users"></i>Lista de Usuarios</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><i class="fas fa-cubes"></i>Productos</a>
					<ul>
						<li><a href="registro_producto.php"><i class="fas fa-plus"></i> Nuevo Producto</a></li>
						<li><a href="lista_productos.php"><i class="fas fa-cube"></i> Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"> <i class="fas fa-users"></i> Clientes</a>
					<ul>
						<li><a href="registro_cliente.php"><i class="fas fa-plus"></i> Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php"><i class="fas fa-users"></i> Lista de Clientes</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><img src="img/Carritoicon.png" style="width: 50px" margin="-0px">Ventas</a>
					<ul>
						<li><a href="nueva_venta.php"><i class="fas fa-plus"></i> Nueva Venta</a></li>
						<li><a href="Ventas.php"><i class="far fa-newspaper"> </i> Ventas</a></li>
					</ul>
				</li>
			<?php } ?>

			<?php
				if($_SESSION['rol'] == 3){
			 ?>
				<li class="CambioContraseña">
					<a href="#">Usuario</a>
					<ul>
						<li><a href="lista_usuario_contraseña.php">Cambio de Contraseña</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Ventas</a>
					<ul>
						<li><a href="nueva_venta.php">Nueva Venta</a></li>
						<li><a href="Ventas.php">Ventas</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><i class="fas fa-cubes"></i>Productos</a>
					<ul>
						<li><a href="lista_productos.php"><i class="fas fa-cube"></i>Lista de Productos</a></li>
					</ul>
				</li>
			<?php } ?>


				<!--
				<li class="principal">
					<a href="#">Proveedores</a>
					<ul>
						<li><a href="#">Nuevo Proveedor</a></li>
						<li><a href="#">Lista de Proveedores</a></li>
					</ul>
				</li>

				<li class="principal">
					<a href="#">Productos</a>
					<ul>
						<li><a href="#">Nuevo Producto</a></li>
						<li><a href="#">Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Pedidos</a>
					<ul>
						<li><a href="Productos.php">Pedidos</a></li>
						<li><a href="#">Facturas</a></li>
					</ul>
				</li>-->

			</ul>
		</nav>
