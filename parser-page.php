<?php
function parser_admin () {
	$array_category = [
		'taxonomy'      => 'product_cat',
		'orderby'       => 'id', 
		'order'         => 'ASC',
		'hide_empty'    => false, 
		'parent'         => '',
		'hierarchical'  => true,
	];
$categories = get_terms($array_category); ?>
<div class="container">
	<h2><?php echo get_admin_page_title() ?></h2>
	<form action="" method="post" id="form-get-content-parser">
	    <label>Категория:</label>
	    <select type="text" id="cate" class="form-control col-md-6" name="category">
	        <?php foreach ($categories as $category): ?>
	        	<?php if ($category->parent): ?>
	            	<option value="<?php echo $category->term_id?>,<?php echo $category->parent;?>">&nbsp;&nbsp;&nbsp;<?php echo $category->name;?></option>
	          	<?php else: ?>
	            	<option value="<?php echo $category->term_id?>"><?php echo $category->name?></option>
	          	<?php endif; ?>
	        <?php endforeach; ?>
	    </select>
	        <label for="url">URL адрес:</label>
	 		<input type="text" value="<?php echo $_POST['url'];?>" id="url" class="form-control col-md-6" name="url"/>
	 		<label for="current">Первая страница</label>
	 		<input type="text" value="" id="current" class="form-control col-md-6" name="current" />
	 		<label for="next">По какую страницу парсить</label>
	 		<input type="text" value="" id="next" class="form-control col-md-6" name="next"/> 
	    	<input type="submit" class="btn btn-success button action" value="Получаем контент">
	    	<img class="loading" src="<?php echo plugins_url('parser_olx/images/loader.gif');?>">
	    	<div class="content_count">Прошло&nbsp;<div id="count">1</div>&nbsp;секунд</div>
	    	<div class="col-md-4 fix-position-message"><span class="empty_field">Заполните поля</span></div>
	</form>	
</div>
	<?php
		if(isset($_POST['current']) && isset($_POST['next']) && !empty($_POST['current']) && !empty($_POST['next'])){
	    	$start_time = microtime(true);
	      	$url = $_POST['url'];
	      	if (preg_match('/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' , $url) !== 1): ?>
	        	<span class="error_url">Введите корректный урл!</span>;
	      	<?php endif;?>
	      	<?php
		      	$start = trim($_POST['current']);
		      	$end = trim($_POST['next']);
		      	$category = array_map('intval' , explode(',' , $_POST['category']));
		      	parser($url, $start, $end , $category);
	      	?>
	      <b class="time_script">Час виповнення скрипту: <?php echo echo_time($start_time); ?>&nbsp;секунд</b>
	<?php
	  	}
	}