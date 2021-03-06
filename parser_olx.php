<?php
/**
 * Plugin Name: Парсер Wordpress Woocomerce
 * Description: Парсер данних с сайтов в Wordpress на базе Woocomerce
 * Plugin URI:  https://ipnethost.kiev.ua/
 * Author URI:  https://ipnethost.kiev.ua/
 * Author:      Саша Александров
 * Version:     Версия плагина, 1.0
 */
require __DIR__.'/functions.php';
require __DIR__.'/settings-parser.php';
require __DIR__.'/phpQuery.php';

if (!defined( 'ABSPATH' )){
	header('HTTP/1.0 403 Forbidden');
	exit('Доступ запрещен!.');
}

add_action('admin_menu', 'check_parser_admin');

function check_parser_admin(){
 	$page_olx = add_menu_page('Парсинг контента с интернет магазина', 'Парсер Woocommerce', 'manage_options', 'parser-page', 'parser_admin', plugins_url( 'parser_olx/images/parse.png'),4);
 	add_action( 'admin_print_scripts-' . $page_olx, 'parser_script' );
 	$settings = add_submenu_page( 'parser-page', 'Настройки парсера', 'Настройки парсера', 'manage_options', 'settings-page' , 'settings_parser', 4);	
	add_action( 'admin_print_scripts-' . $settings, 'parser_script' );
}

function parser_script () {
	wp_enqueue_script('jquery');
	wp_enqueue_script('parser_olx' , plugins_url('/js/parser_olx.js' , __FILE__));
	wp_enqueue_style('parser_olx' , plugins_url('/css/parser_olx.css' , __FILE__));
	wp_enqueue_style('bootstrap-min' , plugins_url('/css/bootstrap.min.css' , __FILE__));
}

function parser_option_table () {
   global $wpdb;

   $table_name = $wpdb->prefix . "option_parser";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  option_name text NOT NULL,
		  option_value text NOT NULL,
		  UNIQUE KEY id (id)
	);";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
   }
}
register_activation_hook(__FILE__, 'active_parser_olx');
register_activation_hook(__FILE__, 'parser_option_table');
add_action('admin_init', 'redirect_parser_olx');

function active_parser_olx() {
    add_option('parser_do_activation_redirect', true);
}

function redirect_parser_olx() {
    if (get_option('parser_do_activation_redirect', false)) {
        delete_option('parser_do_activation_redirect');
        wp_redirect(admin_url('admin.php?page=parser-page'));
    }
}
