<?php
function debug ($param) {
	echo '<pre>' . print_r($param , true) . '</pre>';
}
function echo_time ($start_time) {                                                    
	$time = microtime(true) - $start_time;                                             
	$time = mb_substr($time , 0, 5);
	return $time;
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
	if ($options){
		foreach ($options as $key => $option) {
			$option = trim(sanitize_text_field($option));
			if(get_option($key)){
				update_option($key , $option);
			}
			else{
				add_option($key , $option);
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
