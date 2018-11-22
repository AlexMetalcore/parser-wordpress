<?php
	function debug ($param) {
		echo '<pre>' . print_r($param , true) . '</pre>';
	}
	function curl_content($url){
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
	    $html = curl_exec($ch);
	    return $html;
	    unset($url);
	}
	function get_one_post($names) {
	    global $wpdb;
	    $get_title = $wpdb->get_row("SELECT ID , post_title  FROM wp_posts WHERE post_type = 'product' AND post_title = '$names'");
	    return $get_title;
	}
	function parser ($url, $start, $end , $category){
		if($start < $end)  {
	     	$get_content = curl_content($url);
	     	$doc = phpQuery::newDocument($get_content);
	     	$contents = $doc->find('#offers_table');
	      	$contents = $contents->find('.breakword');
	      	foreach ($contents as $content) {
	        	$content = pq($content);
	        	$name_product = trim($content->find('.lheight20')->children()->text());
	          	$links = $content->find('a')->attr('href');
	          	$price = $content->find('p.price > strong')->text();
	          	$get_item_content = curl_content($links);
	          	$doc_item = phpQuery::newDocument($get_item_content);
	          	$content_item = $doc_item->find('.descriptioncontent');
	          	$description = trim($content_item->find('#textContent')->html());
	          	$img_array = explode(',' , get_img($links));
	          	if (!get_one_post($name_product)->post_title) {
	          		$current_user = wp_get_current_user();
	            	$post_parameters = [
			            'post_author'   => $current_user->ID,
			            'post_content'  => $description,
			            'post_status'   => 'publish',
			            'post_title'    => $name_product,
			            'post_parent'   => '',
			            'post_type'     => 'product',
	            	];
		            $post_id = wp_insert_post($post_parameters, $wp_error);
		            wp_set_post_terms($post_id, $category, 'product_cat' , false);
		            foreach ($img_array as $img) {
		                $new_img = basename($img);
		                $upload_dir = (object) wp_upload_dir($time);
		              	$path = $upload_dir->path.'/' . $new_img;
		              	if (!file_exists($path)) {
		                  	copy($img, $path);
		              	}
		                $attachment = array(
		                    'post_author' => $current_user->ID,
		                    'post_mime_type' => 'image/jpeg',
		                    'post_title' => preg_replace( '/\.[^.]+$/', '', $new_img),
		                    'post_content' => $new_img,
		                    'post_status' => 'publish',
		                    'guid' => $upload_dir->url.'/' . $new_img
		                );
		                $attachment_id = wp_insert_attachment($attachment, $upload_dir->url.'/' .  $new_img);
		                require_once(ABSPATH . 'wp-admin/includes/image.php');
		                $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_dir->path.'/' .  $new_img);
		                wp_update_attachment_metadata($attachment_id, $attachment_data);
		                add_post_meta($post_id, '_wp_attached_file', $upload_dir->url.'/' . $new_img , false);
		            }
		            add_post_meta($post_id, '_regular_price', $price, true);
	          	}
	          	else {
	          		$id = get_one_post($name_product)->ID;
	            	$post_id = get_post($id);
	                wp_update_post($post_id);
	          	}
			    $get_next_link = $doc->find('.item > .current')->parent()->next()->children()->attr('href');
			    if(!empty($get_next_link)){
			    	$start++;
			    	parser($get_next_link, $start, $end , $category);
			    }
	  		}
		}
	}
	function saveDataOption ($options = []) {
		global $wpdb;
		$table_name = $wpdb->prefix . "option_parser";
		if ($options){
			foreach ($options as $key => $option) {
				if ($wpdb->get_row("SELECT * FROM $table_name WHERE option_name = '$key'")){
					$wpdb->update( $table_name, array('option_value' => trim(sanitize_text_field($option))), array('option_name' => $key));
				}
				else{
					$wpdb->insert($table_name, array( 'option_name' => $key , 'option_value' => trim(sanitize_text_field($option))), array('%s'));
				}
			}
			return true;
		}
		return false;
	}
	function getDataPrepare () {
		$array_label = [
			'Блок в котором находяться все товары',
			'Блок с названием продукта',
			'Блок с ценой',
			'Блок с описанием товара',
			'Блок с фото товара'
		];
		global $wpdb;
		$table_name = $wpdb->prefix . "option_parser";
		$get_all_options_parser = $wpdb->get_results("SELECT * FROM $table_name");
		$array_data_options = [];
		$count = 0;
		foreach ($get_all_options_parser as $option) {
			$option->name_label = $array_label[$count++];
			$array_data_options[] = $option;
		}
		return $array_data_options;
	}
	function parser_admin () {
		$array_category = [
			'taxonomy'      => 'product_cat',
			'orderby'       => 'id', 
			'order'         => 'ASC',
			'hide_empty'    => false, 
			'parent'         => '',
			'hierarchical'  => true,
		];
	$categories = get_terms($array_category); 
	?>
	  <div class="container">
	  <h2><?php echo get_admin_page_title() ?></h2>
	    <form action="" method="post">
	        <label>Категория:</label><br>
	        <select type="text" id="cate" class="form-control col-md-6" name="category">
	          <?php foreach ($categories as $category): ?>
	          <?php if ($category->parent): ?>
	                <option value="<?php echo $category->term_id?>,<?php echo $category->parent;?>">&nbsp;&nbsp;&nbsp;<?php echo $category->name;?></option>
	          <?php else: ?>
	                <option value="<?php echo $category->term_id?>"><?php echo $category->name?></option>
	          <?php endif; ?>
	          <?php endforeach; ?>
	        </select>
	        <br>
	        <label for="url">URL адрес:</label>
	 		<input type="text" value="<?php echo $_POST['url'];?>" id="url" class="form-control col-md-6" name="url"/><br>
	 		<label for="current">Первая страница</label>
	 		<input type="text" value="" id="current" class="form-control col-md-6" name="current" /><br>
	 		<label for="next">По какую страницу парсить</label>
	 		<input type="text" value="" id="next" class="form-control col-md-6" name="next"/><br> 
	    	<input type="submit" class="btn btn-success button action" value="Получаем контент">
	    	<img class="loading" src="<?php echo plugins_url('parser_olx/images/loader.gif');?>">
	    	<div class="content_count">Прошло&nbsp;<div id="count">1</div>&nbsp;секунд</div>
	    	<div class="col-md-4">
				<span class="empty_field">Заполните поля</span>
			</div>
	    </form>	
	  </div>
	  <?php
	    if(isset($_POST['current']) && isset($_POST['next']) && !empty($_POST['current']) && !empty($_POST['next'])){
	      $start_time = microtime(true);
	      $url = $_POST['url'];
	      if (preg_match('/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' , $url) !== 1): ?>
	          <span class="error_url">Введіть коректний урл!</span>;
	      <?php endif;?>
	      <?php
	      $start = trim($_POST['current']);
	      $end = trim($_POST['next']);
	      $category = array_map('intval' , explode(',' , $_POST['category']));
	      parser($url, $start, $end , $category);
	      function echo_time ($start_time) {                                                      
	        $time = microtime(true) - $start_time;                                             
	        $time = mb_substr($time , 0, 5);
	        return $time;
	      }
	      ?>
	      <b class="time_script">Час виповнення скрипту: <?php echo echo_time($start_time); ?>&nbsp;секунд</b>
	<?php
	  }
	}
