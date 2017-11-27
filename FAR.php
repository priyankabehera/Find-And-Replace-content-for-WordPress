<?php
/*
  Plugin Name: Find And Replace content for WordPress
  Plugin URI: https://wordpress.org/plugins/Find-And-Replace/
  Description: This plugin is use for content replacement in easy way by finding it in posts and pages and replace it.
  Version: 1.1
  Author: Janki Moradiya
  Author URI: https://profiles.wordpress.org/jankimoradiya
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'FAR_PLUGIN_DIR', plugin_dir_path( __FILE__ )  );
require_once( FAR_PLUGIN_DIR . 'setting.php');

function far_activation() {
	$dbconnObj = new FARConnection();
	$dbconnObj->Create();
}
register_activation_hook( __FILE__, 'far_activation');

function far_deactivation() {
	global $wpdb;
	$tableName = $wpdb->prefix."far";
	$dbconnObj = new FARConnection();
	$record = $dbconnObj->Drop($tableName);
	
}
register_deactivation_hook( __FILE__, 'far_deactivation');

