<?php
 include "includes/filtroelectronica.php"; 
  $menus = new FiltroCat();
  
  ?>

<div class="col-md-4 w3ls_mobiles_grid_left">
	<div class="w3ls_mobiles_grid_left_grid">
		<h3 ><a href="#" class="category_item panel panel-default" category="all">Categories</a></h3>
			<div class="w3ls_mobiles_grid_left_grid_sub">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php foreach ($menus->getCategoria() as $c): ?>
			    <div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title asd">
							<a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $sub['idsub']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $sub['idsub']; ?>">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i><?php echo $c['categoria']; ?>
							</a>
						</h4>
					</div>
					<div id="collapse<?php echo $sub['idsub'];?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body panel_text">
							<ul>
								<?php foreach ($menus->getSubCat($c['idcat']) as $sub): ?>
								<div class="store-wrapper">
									<div class="category_list">
										<li><a href="#" class="category_item" category="<?php echo $sub['subcategoria']; ?>"><?php echo $sub['subcategoria']; ?></a> </li>
									</div>
								</div>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>	
				<?php endforeach; ?>						 
				</div>
			</div>
	</div>	
		
</div>


			