<!-- newsletter
<div class="newsletter">
	<div class="container">
		<div class="col-md-6 w3agile_newsletter_left">
			<h3>BOLETIN INFORMATIVO</h3>
			<p>...</p>
		</div>
		<div class="col-md-6 w3agile_newsletter_right">
			<form action="#" method="post">
				<input type="email" name="Email" placeholder="Email" required="">
				<input type="submit" value="" />
			</form>
		</div>
		<div class="clearfix"> </div>
	</div>
</div>
 /newsletter -->
<!-- <footer> -->
<div class="footer">
		<div class="container">
			<div class="w3_footer_grids">
				<div class="col-md-3 w3_footer_grid">
					<h3>Contacto</h3>

					<ul class="address">
						<li><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>Cra 7B # 18-12<span>Funza Cundinamarca</span></li>
						<li><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i><a href="mailto:info@example.com">ortizarevalojesusdavid432@gmail.com</a></li>
						<li><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i>+3183107697</li>
					</ul>
				</div>
				<!--<div class="col-md-3 w3_footer_grid">
					<h3>HORARIO DE ATENCIÓN</h3>
					<ul class="info">
						<li><a href="about.php">Sobre nosotros</a></li>
						<li><a href="mail.php">Contáctenos</a></li>
						<li><a href="codes.php">Códigos cortos</a></li>
						<li><a href="faq.php">Preguntas frecuentes</a></li>
						<li><a href="products.php">productos especiales</a></li>
					</ul>
				</div>-->
				<div class="col-md-3 w3_footer_grid">
					<h3>Categoria</h3>
					<ul class="info">
						<li><a href="mercado.php">Mercado</a></li>
						<li><a href="Electrohogar.php">Electrohogar</a></li>
						<li><a href="electronica.php">Electronica</a></li>
						<!--<li><a href="products1.php">Usables</a></li>
						<li><a href="products2.php">Cocina</a></li>-->
					</ul>
				</div>
				<div class="col-md-3 w3_footer_grid">
					<h3>Perfil</h3>
					<ul class="info">
						<li><a href="index.php">Inicio</a></li>
						<!--<li><a href="Instructivo.php">Especiales de hoy</a></li>-->
					</ul>
					<!--<h4>Síguenos</h4>
					<div class="agileits_social_button">
						<ul>
							<li><a href="https://www.facebook.com/Corbeta.sa/?pnref=lhc" class="facebook"> </a></li>
							<li><a href="https://www.instagram.com/corbeta.sa/" class="instagram"> </a></li>
							<li><a href="#" class="youtube"> </a></li>
							<li><a href="#" class="pinterest"> </a></li>
						</ul>
					</div>-->
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<div class="footer-copy">
			<div class="footer-copy1">
				<div class="footer-copy-pos">
					<a href="#home1" class="scroll"><img src="images/arrow.png" alt=" " class="img-responsive" /></a>
				</div>
			</div>

		</div>
	<div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Jesus David Ortiz 2021</span>
          </div>
        </div>
	</div>

	<script src="js/minicart.js"></script>
	<script>
        w3ls.render();

        w3ls.cart.on('w3sb_checkout', function (evt) {

        	var items, len, i;

        	if (this.subtotal() > 0) {
        		items = this.items();

        		for (i = 0, len = items.length; i < len; i++) {
        		}
        	}
        });
    </script>
