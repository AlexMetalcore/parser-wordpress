<?php 
function settings_parser () { ?>
<?php
$array_data_options = getDataPrepare(); 
$img = plugins_url('/images/delete-value.jpg' , __FILE__); ?>
	<div class="container">
		<div class="col-md-4">
			<h2><?php echo get_admin_page_title() ?></h2>
		</div>
		<form method="post" id="options-parser">
		<?php if ($array_data_options):?>
		<?php foreach($array_data_options as $option): ?>
			<div class="col-md-4 margin-btn">
				<label for="<?php echo $option->option_name?>"><?php echo $option->name_label?></label>
				<input type="text" name="<?php echo $option->option_name?>" value="<?php echo $option->option_value; ?>" id="<?php echo $option->option_name; ?>" class="form-control" placeholder="Введите класс или идентификатор">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
		<?php endforeach; ?>
		<?php else:?>
			<div class="col-md-4 margin-btn">
				<label for="goods-field">Блок в котором находяться все товары</label>
				<input type="text" name="goods-field" value="" id="goods-field" class="form-control" placeholder="Введите класс или идентификатор">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="name-goods-field">Блок с названием продукта</label>
				<input type="text" name="name-goods-field" value="" id="name-goods-field" class="form-control" placeholder="Введите класс или идентификатор">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="price-goods-field">Блок с ценой</label>
				<input type="text" name="price-goods-field" value="" id="price-goods-field" class="form-control" placeholder="Введите класс или идентификатор">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="desc-goods-field">Блок с описанием товара</label>
				<input type="text" name="desc-goods-field" value="" id="desc-goods-field" class="form-control" placeholder="Введите класс или идентификатор">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<div class="col-md-4 margin-btn">
				<label for="img-goods-field">Блок с фото товара</label>
				<input type="text" name="img-goods-field" value="" id="img-goods-field" class="form-control" placeholder="Введите класс или идентификатор">
				<img src="<?php echo $img; ?>" class="delete-value"/>
			</div>
			<?php endif;?>

			<div class="col-md-4 margin-btn">
				<input id="save-option-parser" type="submit" value="Сохранить" class="btn btn-success">
			</div>
			<div class="col-md-4">
				<span class="empty_field">Заполните поля</span>
			</div>
		</form>
	</div>
	<?php if(isset($_POST) && !empty($_POST['goods-field']) && !empty($_POST['name-goods-field']) && !empty($_POST['price-goods-field']) && !empty($_POST['desc-goods-field']) && !empty($_POST['img-goods-field'])): ?>
		<?php saveDataOption($_POST); ?>
		<?php header('Location: ' . $_SERVER['HTTP_REFERER']); ?>
		<div class="alert alert-warning alert-dismissible" role="alert">
		  <strong>Данные сохранены</strong>
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>
	<?php endif;?>
<?php }?>