<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once(  FAR_PLUGIN_DIR . 'function.php' );
include_once(  FAR_PLUGIN_DIR . 'Classes/farConnection.php' );
include_once(  ABSPATH . WPINC . '/class-wp-editor.php'  );
$dbconnObj = new FARConnection();

function far_admin_actions() {
   add_menu_page( 'Find And Replace', 'Find And Replace', 'manage_options', 'far-dashboard', 'far_admin');
}
add_action('admin_menu', 'far_admin_actions');

function far_admin() {
	$Path=$_SERVER['REQUEST_URI'];
	$url_array = explode("&",$Path);
	if(!in_array('edit',$url_array)){
	echo do_shortcode('[far_admin_html]');
	?>
	
	<div id="ajax-response"></div>	
	
   
<?php }} ?>