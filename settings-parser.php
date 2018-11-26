<?php 
function settings_parser () { ?>
<?php $img = plugins_url('/images/delete-value.jpg' , __FILE__); 
      $placeholder = 'Введите класс или идентификатор';?>
     <?php if(isset($_POST) && !empty($_POST['goods-field']) && !empty($_POST['name-goods-field']) && !empty($_POST['price-goods-field']) && !empty($_POST['desc-goods-field']) && !empty($_POST['img-goods-field'])): ?>
		<?php wp_redirect(admin_url('admin.php?page=settings-page&update=true'));?>
		<?php (saveDataOption($_POST)); ?>
		<?php endif;?>
	<?php if($_GET['update'] == true): ?>
	<div class="col-md-4 margin-btn">
		<div class="alert alert-success notice is-dismissible" role="alert">
			<strong>Данные сохранены</strong>
		</div>
	</div>
	<?php endif; ?>
	<div class="container">
		<div class="col-md-4">
			<h2><?php echo get_admin_page_title();?></h2>
		</div>
		<form method="post" id="options-parser">
			<div class="col-md-4 margin-btn">
				<label for="goods-field">Блок в котором находяться все товары</label>
				<input type="text" name="goods-field" value="<?php echo get_option('goods-field'); ?>" id="goods-field" class="form-control" placeholder="<?php echo $placeholder; ?>">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="name-goods-field">Блок с названием продукта</label>
				<input type="text" name="name-goods-field" value="<?php echo get_option('name-goods-field'); ?>" id="name-goods-field" class="form-control" placeholder="<?php echo $placeholder; ?>">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="price-goods-field">Блок с ценой</label>
				<input type="text" name="price-goods-field" value="<?php echo get_option('price-goods-field'); ?>" id="price-goods-field" class="form-control" placeholder="<?php echo $placeholder; ?>">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="desc-goods-field">Блок с описанием товара</label>
				<input type="text" name="desc-goods-field" value="<?php echo get_option('desc-goods-field'); ?>" id="desc-goods-field" class="form-control" placeholder="<?php echo $placeholder; ?>">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="img-goods-field">Блок с фото товара</label>
				<input type="text" name="img-goods-field" value="<?php echo get_option('img-goods-field'); ?>" id="img-goods-field" class="form-control" placeholder="<?php echo $placeholder; ?>">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>

			<div class="col-md-4 margin-btn">
				<input id="save-option-parser" type="submit" value="Сохранить" class="btn btn-success">
			</div>
			<div class="col-md-4">
				<span class="empty_field">Заполните поля</span>
			</div>
		</form>
	</div>
<?php }?>