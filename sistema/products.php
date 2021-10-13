<?php
	session_start();
	include "../conexion.php";

	$consulta = mysqli_query($conection,"SELECT * FROM categorias WHERE estatus = 1 ");
	$result = mysqli_num_rows($consulta);
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

	<!-- //navigation -->
	<!-- banner -->
	<div class="banner banner1">
		<div class="container">
			<h2>Great Offers on <span>Mobiles</span> Flat <i>35% Discount</i></h2>
		</div>
	</div>
	<!-- breadcrumbs -->
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a> <i>/</i></li>
				<li>Products</li>
			</ul>
		</div>
	</div>
	<!-- //breadcrumbs -->
	<!-- mobiles -->
	<div class="mobiles">
		<div class="container">
			<div class="w3ls_mobiles_grids">
				<div class="col-md-4 w3ls_mobiles_grid_left">
					<div class="w3ls_mobiles_grid_left_grid">
						<h3>Categories</h3>
						<div class="w3ls_mobiles_grid_left_grid_sub">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							  <div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingOne">
								  <h4 class="panel-title asd">
									<a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i>Los recién llegados
									</a>
								  </h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
								  <div class="panel-body panel_text">
									<ul>
										<div class="store-wrapper">
										<div class="category_list">
										    <li><a href="#" class="category_item" category="all">Todo</a></li>
											<li><a href="#" class="category_item" category="ordenadores">Ordenadores</a></li>
											<li><a href="#" class="category_item" category="laptops">Laptops</a></li>
											<li><a href="#" class="category_item" category="smartphones">Smartphones</a></li>
											<li><a href="#" class="category_item" category="monitores">Monitores</a></li>
											<li><a href="#" class="category_item" category="audifonos">Audifonos</a></li>
										</div>
										

									<!--	<?php
										while ($fila = mysqli_fetch_array($consulta))
										{
											echo '<li><a href="products.php">'.$fila ['categoria'].'</a></li>';
										}
										?>-->

										
									</ul>
								  </div>
								</div>
							  </div>
							  <div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingTwo">
								  <h4 class="panel-title asd">
									<a class="pa_italic collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i>Accessories
									</a>
								  </h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								   <div class="panel-body panel_text">
									<ul>
										<li><a href="products2.php">Grinder</a></li>
										<li><a href="products2.php">Heater</a></li>
										<li><a href="products2.php">Kid's Toys</a></li>
										<li><a href="products2.php">Filters</a></li>
										<li><a href="products2.php">AC</a></li>
									</ul>
								  </div>
								</div>
							  </div>
							</div>
							<ul class="panel_bottom">
								<li><a href="products.php">Summer Store</a></li>
								<li><a href="products.php">Featured Brands</a></li>
								<li><a href="products.php">Today's Deals</a></li>
								<li><a href="products.php">Latest Watches</a></li>
							</ul>
						</div>
					</div>
					<div class="w3ls_mobiles_grid_left_grid">
						<h3>Color</h3>
						<div class="w3ls_mobiles_grid_left_grid_sub">
							<div class="ecommerce_color">
								<ul>
									<li><a href="#"><i></i> Red(5)</a></li>
									<li><a href="#"><i></i> Brown(2)</a></li>
									<li><a href="#"><i></i> Yellow(3)</a></li>
									<li><a href="#"><i></i> Violet(6)</a></li>
									<li><a href="#"><i></i> Orange(2)</a></li>
									<li><a href="#"><i></i> Blue(1)</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="w3ls_mobiles_grid_left_grid">
						<h3>Price</h3>
						<div class="w3ls_mobiles_grid_left_grid_sub">
							<div class="ecommerce_color ecommerce_size">
								<ul>
									<li><a href="#">Below $ 100</a></li>
									<li><a href="#">$ 100-500</a></li>
									<li><a href="#">$ 1k-10k</a></li>
									<li><a href="#">$ 10k-20k</a></li>
									<li><a href="#">$ Above 20k</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8 w3ls_mobiles_grid_right">
					<div class="col-md-6 w3ls_mobiles_grid_right_left">
						<div class="w3ls_mobiles_grid_right_grid1">
							<img src="images/46.jpg" alt=" " class="img-responsive" />
							<div class="w3ls_mobiles_grid_right_grid1_pos1">
								<h3>Smart Phones<span>Up To</span> 15% Discount</h3>
							</div>
						</div>
					</div>
					<div class="col-md-6 w3ls_mobiles_grid_right_left">
						<div class="w3ls_mobiles_grid_right_grid1">
							<img src="images/47.jpg" alt=" " class="img-responsive" />
							<div class="w3ls_mobiles_grid_right_grid1_pos">
								<h3>Top 10 Latest<span>Mobile </span>& Accessories</h3>
							</div>
						</div>
					</div>
					<div class="clearfix"> </div>

					<div class="w3ls_mobiles_grid_right_grid2">
						<div class="w3ls_mobiles_grid_right_grid2_left">
							<h3>Showing Results: 0-1</h3>
						</div>
						<div class="w3ls_mobiles_grid_right_grid2_right">
							<select name="select_item" class="select_item">
								<option selected="selected">Default sorting</option>
								<option>Sort by popularity</option>
								<option>Sort by average rating</option>
								<option>Sort by newness</option>
								<option>Sort by price: low to high</option>
								<option>Sort by price: high to low</option>
							</select>
						</div>
						<div class="clearfix"> </div>
					</div>
					<div class="w3ls_mobiles_grid_right_grid3">
						<section class="products-list">
							<div class="product-item" category="laptops">
							
								<div class="agile_ecommerce_tab_left mobiles_grid">
									<div class="hs-wrapper hs-wrapper2" >
										<img src="images/laptop_hp.jpg" alt=" " class="img-responsive" />
										<img src="images/laptop_hp.jpg" alt=" " class="img-responsive" />
										<img src="images/laptop_hp.jpg" alt=" " class="img-responsive" />
										<img src="images/laptop_hp.jpg" alt=" " class="img-responsive" />
										<img src="images/laptop_hp.jpg" alt=" " class="img-responsive" />
								        <div class="w3_hs_bottom w3_hs_bottom_sub1">
										    <ul>
												<li>
												<a href="#" data-toggle="modal" data-target="#myModal9"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
												</li>
											</ul>
										</div>
									</div>
									<h5><a href="single.php">Laptop Hp</a></h5>
									<div class="simpleCart_shelfItem">
										<p><span>$250</span> <i class="item_price">$245</i></p>
											<form action="#" method="post">
												<input type="hidden" name="cmd" value="_cart" />
													<input type="hidden" name="add" value="1" />
														<input type="hidden" name="w3ls_item" value="Smart Phone" />
															<input type="hidden" name="amount" value="245.00"/>
																<button type="submit" class="w3ls-cart">Add to cart</button>
											</form>
									</div>
									<div class="mobiles_grid_pos">
										<h6>New</h6>
									</div>
								</div>
							
						</div>	
												
												
											




											<div class="product-item" category="laptops">
												<img src="images/laptop_toshiba.jpg" alt="" >
												<a href="#">Laptop Toshiba</a>
											</div>
											<div class="product-item" category="smartphones">
												<img src="images/samsung_galaxy.jpg" alt="" >
												<a href="#">Samsung Galaxy</a>
											</div>
											<div class="product-item" category="smartphones">
												<img src="images/iphone.jpg" alt="" >
												<a href="#">Iphone 7</a>
											</div>
											<div class="product-item" category="ordenadores">
												<img src="images/pc_hp.jpg" alt="" >
												<a href="#">PC Hp</a>
											</div>
											<div class="product-item" category="ordenadores">
												<img src="images/pc_lenovo.jpg" alt="" >
												<a href="#">PC Lenovo</a>
											</div>
											<div class="product-item" category="monitores">
												<img src="images/monitor_asus.jpg" alt="" >
												<a href="#">Monitor Asus</a>
											</div>
											<div class="product-item" category="audifonos">
												<img src="images/jbl.jpg" alt="" >
												<a href="#">Audifonos JBL</a>
											</div>
										
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles" >
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2" category="audifonos">
									<img src="images/jbl.jpg" alt=" " class="img-responsive" />
									<img src="images/jbl.jpg" alt=" " class="img-responsive" />
									<img src="images/jbl.jpg" alt=" " class="img-responsive" />
									<img src="images/jbl.jpg" alt=" " class="img-responsive" />
									<img src="images/jbl.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal9"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Smart Phone</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$250</span> <i class="item_price">$245</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Smart Phone" />
										<input type="hidden" name="amount" value="245.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
								<div class="mobiles_grid_pos">
									<h6>New</h6>
								</div>
							</div>
						</div>
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles" category="monitores">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2" >
									<img src="images/p1.jpg" alt=" " class="img-responsive" />
									<img src="images/p2.jpg" alt=" " class="img-responsive" />
									<img src="images/p3.jpg" alt=" " class="img-responsive" />
									<img src="images/p4.jpg" alt=" " class="img-responsive" />
									<img src="images/p1.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal4"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Audio-speaker</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$50</span> <i class="item_price">$45</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Audio-speaker" />
										<input type="hidden" name="amount" value="45.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
							</div>
						</div>
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles" category="monitores">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2" >
									<img src="images/30.jpg" alt=" " class="img-responsive" />
									<img src="images/28.jpg" alt=" " class="img-responsive" />
									<img src="images/26.jpg" alt=" " class="img-responsive" />
									<img src="images/27.jpg" alt=" " class="img-responsive" />
									<img src="images/29.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal9"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Red Mobile</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$80</span> <i class="item_price">$65</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Red Mobile" />
										<input type="hidden" name="amount" value="65.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
							</div>
						</div>
						<div class="clearfix"> </div>
						</section>
					</div><!--
					<div class="w3ls_mobiles_grid_right_grid3">
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2">
									<img src="images/p4.jpg" alt=" " class="img-responsive" />
									<img src="images/p1.jpg" alt=" " class="img-responsive" />
									<img src="images/p3.jpg" alt=" " class="img-responsive" />
									<img src="images/p2.jpg" alt=" " class="img-responsive" />
									<img src="images/p5.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal4"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Headphone</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$250</span> <i class="item_price">$245</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Headphone"/>
										<input type="hidden" name="amount" value="245.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
							</div>
						</div>
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2">
									<img src="images/p5.jpg" alt=" " class="img-responsive" />
									<img src="images/p1.jpg" alt=" " class="img-responsive" />
									<img src="images/p2.jpg" alt=" " class="img-responsive" />
									<img src="images/p4.jpg" alt=" " class="img-responsive" />
									<img src="images/p3.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal4"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Wireless Speaker</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$100</span> <i class="item_price">$86</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Wireless Speaker" />
										<input type="hidden" name="amount" value="86.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
							</div>
						</div>
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2">
									<img src="images/p6.jpg" alt=" " class="img-responsive" />
									<img src="images/26.jpg" alt=" " class="img-responsive" />
									<img src="images/31.jpg" alt=" " class="img-responsive" />
									<img src="images/22.jpg" alt=" " class="img-responsive" />
									<img src="images/p6.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Asus Tablet</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$450</span> <i class="item_price">$425</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Asus Tablet" />
										<input type="hidden" name="amount" value="425.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
								<div class="mobiles_grid_pos">
									<h6>New</h6>
								</div>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
					<div class="w3ls_mobiles_grid_right_grid3">
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2">
									<img src="images/p3.jpg" alt=" " class="img-responsive" />
									<img src="images/p5.jpg" alt=" " class="img-responsive" />
									<img src="images/p4.jpg" alt=" " class="img-responsive" />
									<img src="images/p2.jpg" alt=" " class="img-responsive" />
									<img src="images/p1.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal4"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Music MP3 Player</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$60</span> <i class="item_price">$58</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Ultra MP3 Player" />
										<input type="hidden" name="amount" value="58.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
								<div class="mobiles_grid_pos">
									<h6>New</h6>
								</div>
							</div>
						</div>
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2">
									<img src="images/31.jpg" alt=" " class="img-responsive" />
									<img src="images/30.jpg" alt=" " class="img-responsive" />
									<img src="images/p6.jpg" alt=" " class="img-responsive" />
									<img src="images/26.jpg" alt=" " class="img-responsive" />
									<img src="images/29.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Golden Tablet</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$650</span> <i class="item_price">$550</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Golden Tablet" />
										<input type="hidden" name="amount" value="550.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
							</div>
						</div>
						<div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_mobiles">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper2">
									<img src="images/35.jpg" alt=" " class="img-responsive" />
									<img src="images/30.jpg" alt=" " class="img-responsive" />
									<img src="images/27.jpg" alt=" " class="img-responsive" />
									<img src="images/28.jpg" alt=" " class="img-responsive" />
									<img src="images/29.jpg" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom w3_hs_bottom_sub1">
										<ul>
											<li>
												<a href="#" data-toggle="modal" data-target="#myModal9"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
											</li>
										</ul>
									</div>
								</div>
								<h5><a href="single.php">Wireless Phone</a></h5>
								<div class="simpleCart_shelfItem">
									<p><span>$250</span> <i class="item_price">$210</i></p>
									<form action="#" method="post">
										<input type="hidden" name="cmd" value="_cart" />
										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="w3ls_item" value="Wireless Phone" />
										<input type="hidden" name="amount" value="210.00"/>
										<button type="submit" class="w3ls-cart">Add to cart</button>
									</form>
								</div>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>-->
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<div class="modal video-modal fade" id="myModal9" tabindex="-1" role="dialog" aria-labelledby="myModal9">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<section>
					<div class="modal-body">
						<div class="col-md-5 modal_body_left">
							<img src="images/27.jpg" alt=" " class="img-responsive" />
						</div>
						<div class="col-md-7 modal_body_right">
							<h4>Latest Smart Phone </h4>
							<p>Ut enim ad minim veniam, quis nostrud
								exercitation ullamco laboris nisi ut aliquip ex ea
								commodo consequat.Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore
								eu fugiat nulla pariatur. Excepteur sint occaecat
								cupidatat non proident, sunt in culpa qui officia
								deserunt mollit anim id est laborum.</p>
							<div class="rating">
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive" />
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="modal_body_right_cart simpleCart_shelfItem">
								<p><span>$250</span> <i class="item_price">$245</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart" />
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="w3ls_item" value="Smart Phone" />
									<input type="hidden" name="amount" value="245.00"/>
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
							<h5>Color</h5>
							<div class="color-quality">
								<ul>
									<li><a href="#"><span></span></a></li>
									<li><a href="#" class="brown"><span></span></a></li>
									<li><a href="#" class="purple"><span></span></a></li>
									<li><a href="#" class="gray"><span></span></a></li>
								</ul>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="modal video-modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModal6">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<section>
					<div class="modal-body">
						<div class="col-md-5 modal_body_left">
							<img src="images/34.jpg" alt=" " class="img-responsive" />
						</div>
						<div class="col-md-7 modal_body_right">
							<h4>Musical Kids Toy</h4>
							<p>Ut enim ad minim veniam, quis nostrud
								exercitation ullamco laboris nisi ut aliquip ex ea
								commodo consequat.Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore
								eu fugiat nulla pariatur. Excepteur sint occaecat
								cupidatat non proident, sunt in culpa qui officia
								deserunt mollit anim id est laborum.</p>
							<div class="rating">
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive" />
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="modal_body_right_cart simpleCart_shelfItem">
								<p><span>$150</span> <i class="item_price">$100</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart">
									<input type="hidden" name="add" value="1">
									<input type="hidden" name="w3ls_item" value="Kids Toy">
									<input type="hidden" name="amount" value="100.00">
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
							<h5>Color</h5>
							<div class="color-quality">
								<ul>
									<li><a href="#"><span></span></a></li>
									<li><a href="#" class="brown"><span></span></a></li>
									<li><a href="#" class="purple"><span></span></a></li>
									<li><a href="#" class="gray"><span></span></a></li>
								</ul>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="modal video-modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModal5">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<section>
					<div class="modal-body">
						<div class="col-md-5 modal_body_left">
							<img src="images/36.jpg" alt=" " class="img-responsive">
						</div>
						<div class="col-md-7 modal_body_right">
							<h4>Dry Vacuum Cleaner</h4>
							<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
								commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
								cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
							<div class="rating">
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive">
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="modal_body_right_cart simpleCart_shelfItem">
								<p><span>$960</span> <i class="item_price">$920</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart">
									<input type="hidden" name="add" value="1">
									<input type="hidden" name="w3ls_item" value="Vacuum Cleaner">
									<input type="hidden" name="amount" value="920.00">
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
							<h5>Color</h5>
							<div class="color-quality">
								<ul>
									<li><a href="#"><span></span></a></li>
									<li><a href="#" class="brown"><span></span></a></li>
									<li><a href="#" class="purple"><span></span></a></li>
									<li><a href="#" class="gray"><span></span></a></li>
								</ul>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="modal video-modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModal4">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<section>
					<div class="modal-body">
						<div class="col-md-5 modal_body_left">
							<img src="images/p3.jpg" alt=" " class="img-responsive" />
						</div>
						<div class="col-md-7 modal_body_right">
							<h4>Music MP3 Player </h4>
							<p>Ut enim ad minim veniam, quis nostrud
								exercitation ullamco laboris nisi ut aliquip ex ea
								commodo consequat.Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore
								eu fugiat nulla pariatur. Excepteur sint occaecat
								cupidatat non proident, sunt in culpa qui officia
								deserunt mollit anim id est laborum.</p>
							<div class="rating">
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive" />
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="modal_body_right_cart simpleCart_shelfItem">
								<p><span>$60</span> <i class="item_price">$58</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart" />
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="w3ls_item" value="MP3 Player" />
									<input type="hidden" name="amount" value="58.00"/>
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
							<h5>Color</h5>
							<div class="color-quality">
								<ul>
									<li><a href="#"><span></span></a></li>
									<li><a href="#" class="brown"><span></span></a></li>
									<li><a href="#" class="purple"><span></span></a></li>
									<li><a href="#" class="gray"><span></span></a></li>
								</ul>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="modal video-modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModal3">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<section>
					<div class="modal-body">
						<div class="col-md-5 modal_body_left">
							<img src="images/38.jpg" alt=" " class="img-responsive">
						</div>
						<div class="col-md-7 modal_body_right">
							<h4>Kitchen &amp; Dining Accessories</h4>
							<p>Ut enim ad minim veniam, quis nostrud
								exercitation ullamco laboris nisi ut aliquip ex ea
								commodo consequat.Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore
								eu fugiat nulla pariatur. Excepteur sint occaecat
								cupidatat non proident, sunt in culpa qui officia
								deserunt mollit anim id est laborum.</p>
							<div class="rating">
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive">
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive">
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="modal_body_right_cart simpleCart_shelfItem">
								<p><span>$650</span> <i class="item_price">$645</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart">
									<input type="hidden" name="add" value="1">
									<input type="hidden" name="w3ls_item" value="Microwave Oven">
									<input type="hidden" name="amount" value="645.00">
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
							<h5>Color</h5>
							<div class="color-quality">
								<ul>
									<li><a href="#"><span></span></a></li>
									<li><a href="#" class="brown"><span></span></a></li>
									<li><a href="#" class="purple"><span></span></a></li>
									<li><a href="#" class="gray"><span></span></a></li>
								</ul>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="modal video-modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModal2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<section>
					<div class="modal-body">
						<div class="col-md-5 modal_body_left">
							<img src="images/p6.jpg" alt=" " class="img-responsive" />
						</div>
						<div class="col-md-7 modal_body_right">
							<h4>Latest Asus ZenPad Tablet</h4>
							<p>Ut enim ad minim veniam, quis nostrud
								exercitation ullamco laboris nisi ut aliquip ex ea
								commodo consequat.Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore
								eu fugiat nulla pariatur. Excepteur sint occaecat
								cupidatat non proident, sunt in culpa qui officia
								deserunt mollit anim id est laborum.</p>
							<div class="rating">
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star-.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive" />
								</div>
								<div class="rating-left">
									<img src="images/star.png" alt=" " class="img-responsive" />
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="modal_body_right_cart  simpleCart_shelfItem">
								<p><span>$45</span> <i class="item_price">$425</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart" />
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="w3ls_item" value="Latest Tablet" />
									<input type="hidden" name="amount" value="425.00" />
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
							<h5>Color</h5>
							<div class="color-quality">
								<ul>
									<li><a href="#"><span></span></a></li>
									<li><a href="#" class="brown"><span></span></a></li>
									<li><a href="#" class="purple"><span></span></a></li>
									<li><a href="#" class="gray"><span></span></a></li>
								</ul>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<!-- Related Products -->
	<div class="w3l_related_products">
		<div class="container">
			<h3>Related Products</h3>
			<ul id="flexiselDemo2">
				<li>
					<div class="w3l_related_products_grid">
						<div class="agile_ecommerce_tab_left mobiles_grid">
							<div class="hs-wrapper hs-wrapper3">
								<img src="images/34.jpg" alt=" " class="img-responsive" />
								<img src="images/35.jpg" alt=" " class="img-responsive" />
								<img src="images/27.jpg" alt=" " class="img-responsive" />
								<img src="images/28.jpg" alt=" " class="img-responsive" />
								<img src="images/37.jpg" alt=" " class="img-responsive" />
								<div class="w3_hs_bottom">
									<div class="flex_ecommerce">
										<a href="#" data-toggle="modal" data-target="#myModal6"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
									</div>
								</div>
							</div>
							<h5><a href="single.php">Kid's Toy</a></h5>
							<div class="simpleCart_shelfItem">
								<p class="flexisel_ecommerce_cart"><span>$150</span> <i class="item_price">$100</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart">
									<input type="hidden" name="add" value="1">
									<input type="hidden" name="w3ls_item" value="Kid's Toy">
									<input type="hidden" name="amount" value="100.00">
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="w3l_related_products_grid">
						<div class="agile_ecommerce_tab_left mobiles_grid">
							<div class="hs-wrapper hs-wrapper3">
								<img src="images/36.jpg" alt=" " class="img-responsive" />
								<img src="images/32.jpg" alt=" " class="img-responsive" />
								<img src="images/33.jpg" alt=" " class="img-responsive" />
								<img src="images/32.jpg" alt=" " class="img-responsive" />
								<img src="images/36.jpg" alt=" " class="img-responsive" />
								<div class="w3_hs_bottom">
									<div class="flex_ecommerce">
										<a href="#" data-toggle="modal" data-target="#myModal5"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
									</div>
								</div>
							</div>
							<h5><a href="single.php">Vacuum Cleaner</a></h5>
							<div class="simpleCart_shelfItem">
								<p class="flexisel_ecommerce_cart"><span>$960</span> <i class="item_price">$920</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart" />
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="w3ls_item" value="Vacuum Cleaner" />
									<input type="hidden" name="amount" value="920.00"/>
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="w3l_related_products_grid">
						<div class="agile_ecommerce_tab_left mobiles_grid">
							<div class="hs-wrapper hs-wrapper3">
								<img src="images/38.jpg" alt=" " class="img-responsive" />
								<img src="images/37.jpg" alt=" " class="img-responsive" />
								<img src="images/27.jpg" alt=" " class="img-responsive" />
								<img src="images/28.jpg" alt=" " class="img-responsive" />
								<img src="images/37.jpg" alt=" " class="img-responsive" />
								<div class="w3_hs_bottom">
									<div class="flex_ecommerce">
										<a href="#" data-toggle="modal" data-target="#myModal3"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
									</div>
								</div>
							</div>
							<h5><a href="single.php">Microwave Oven</a></h5>
							<div class="simpleCart_shelfItem">
								<p class="flexisel_ecommerce_cart"><span>$650</span> <i class="item_price">$645</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart" />
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="w3ls_item" value="Microwave Oven" />
									<input type="hidden" name="amount" value="645.00"/>
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="w3l_related_products_grid">
						<div class="agile_ecommerce_tab_left mobiles_grid">
							<div class="hs-wrapper hs-wrapper3">
								<img src="images/p3.jpg" alt=" " class="img-responsive" />
								<img src="images/p5.jpg" alt=" " class="img-responsive" />
								<img src="images/p4.jpg" alt=" " class="img-responsive" />
								<img src="images/p2.jpg" alt=" " class="img-responsive" />
								<img src="images/p1.jpg" alt=" " class="img-responsive" />
								<div class="w3_hs_bottom">
									<div class="flex_ecommerce">
										<a href="#" data-toggle="modal" data-target="#myModal4"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
									</div>
								</div>
							</div>
							<h5><a href="single.php">Music MP3 Player</a></h5>
							<div class="simpleCart_shelfItem">
								<p><span>$60</span> <i class="item_price">$58</i></p>
								<form action="#" method="post">
									<input type="hidden" name="cmd" value="_cart" />
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="w3ls_item" value="Ultra MP3 Player" />
									<input type="hidden" name="amount" value="58.00"/>
									<button type="submit" class="w3ls-cart">Add to cart</button>
								</form>
							</div>
							<div class="mobiles_grid_pos">
								<h6>New</h6>
							</div>
						</div>
					</div>
				</li>
			</ul>


		</div>
	</div>
	<!-- //Related Products -->

<?php include "includes/footerplantilla.php"; ?>
</body>
</html>
