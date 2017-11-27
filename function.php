<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/******  Shortcode for FAR Admin *********/
function far_admin_html_fun(){
global $wpdb;
$html = '';
 $html .= '<div class="wrap">
				<h2>Find & Replace Text</h2>
        <p> Find and replace plugin find the text from defult posts, pages and replace it with provided text.</p>
				 	<table>
				 		<tr>
				 		 	<td><h4> Find Text : </h4></td>
				 			<td><input type="text" name="find_text" class="find"></td>
				 		</tr>
				 		<tr>
				 			 <td><h4> Replace Text : </h4></td>
				 			 <td><input type="text" name="replace_text" class="replace"></td>
				 		</tr>
				 		  
				 		<tr>
				 			<td colspan="2" align="center" class="loader"><input type="submit" value="Submit" class="button button-primary button-large" id="submit" ></td>
				 		</tr>
				</table>
		  </div><br/>
<div class="result"></div>';

$atts = shortcode_atts( array( "name" => '', "admin_url" => admin_url('admin-ajax.php'),"plugin_dir_loader_img_url" => plugins_url( 'images/loading.png', __FILE__ ) ), $atts );
wp_enqueue_style( 'stylesheet', plugins_url('css/style.css', __FILE__) );
wp_register_script( 'far_admin_ajax', plugins_url('js/far_admin.js', __FILE__), false );
wp_enqueue_script( 'far_admin_ajax' ); 
wp_localize_script( 'far_admin_ajax', 'far_admin_ajax_obj', $atts );
return $html;
}
add_shortcode('far_admin_html','far_admin_html_fun');
/******* End Shortcode ********/


/******* Ajax call for Far admin *****/
function far_admin_ajax_fun(){

global $wpdb;
$find_text = sanitize_text_field( $_GET['find_text'] );
$replace_text = sanitize_text_field( $_GET['replace_text'] ); 
$dbconnObj = new FARConnection();
if( !empty( $find_text ) && !empty( $replace_text ) ){
	$tablename = $wpdb->prefix.'posts';
	$condition = "post_content LIKE '%".$find_text."%' AND post_status = 'publish'";
	$fieldname = '*';
	$results = $dbconnObj->Select($fieldname, $tablename, $condition);
	$tableName = $wpdb->prefix."far";
	$condition = '';
	$record = $dbconnObj->Delete($tableName, $condition);
	$user_info = get_userdata(1);
	$username = $user_info->user_login;
	if( !empty( $results ) ){
	    foreach ( $results as $result ){
	        $rplace_string = str_replace( $find_text, $replace_text, $result->post_content );
	        $field = "post_content='".$rplace_string."'";
	        $my_post = array( 'ID' => $result->ID, 'post_title' => $result->post_title, 'post_content' => $rplace_string );
	        wp_update_post( $my_post );
	        $dataarr = array( 
	                          'post_id' => $result->ID, 
	                          'post_title' => $result->post_title, 
	                          'post_type'  => $result->post_type,
	                          'post_content' => $result->post_content,
	                          'edited_content' => $rplace_string,
	                          'user_name' => $username,
	                          'modified_date' => date('Y-m-d h:i:s')
	                        );
	        $records[] = $dbconnObj->Insert($dataarr, $tableName);
	       }
	  }
  
	 // Select updated record
	$cnt = 0;
	$fieldname = '*';
	$tablenm = $wpdb->prefix."far";
	$results = $dbconnObj->Select($fieldname, $tablenm, ''); 
	$tablehtml = '';
	$tablehtml .= '<table class="wp-list-table widefat fixed striped pages">
					<thead>
						<tr>
							<th scope="col" class="manage-column column-author" style="">No</th>
							<th scope="col" class="manage-column column-author" style="">Title</th>
							<th scope="col" class="manage-column column-author" style="">Post Type</th>
							<th scope="col" class="manage-column column-author" style="">Modified Date</th>
						</tr>
					</thead>
					<tbody id="the-list">';
	if(!empty($results)){
	  foreach ( $results as $result ){
	  	if( $cnt % 2 == 0 ){
  		$tablehtml .= '<tr>
	  						<td class="post-title page-title column-title">
								<strong>
									<a class="row-title">'.($cnt+1).'</a>
								</strong>
							</td>
							<td class="post-title page-title column-title">
								<strong>
									<a class="row-title" href="'.site_url().'/wp-admin/post.php?post='.$result->post_id.'&amp;action=edit">'.$result->post_title.'</a>
								</strong>
							</td>
							<td class="post-title page-title column-title">
								<strong>
									<a class="row-title">'.$result->post_type.'</a>
								</strong>
							</td>
							<td class="post-title page-title column-title">
								<strong>
									<a class="row-title">'.$result->modified_date.'</a>
								</strong>
							</td>	
					</tr>';
	  	}else{
	  		$tablehtml .= '<tr>
  								<td class="post-title page-title column-title">
									<strong>
										<a class="row-title">'.($cnt+1).'</a>
									</strong>
								</td>
								<td class="post-title page-title column-title">
									<strong>
										<a class="row-title" href="http://localhost/Activity-Wordpress/wp-admin/post.php?post='.$result->post_id.'&amp;action=edit">'.$result->post_title.'</a>
									</strong>
								</td>
								<td class="post-title page-title column-title">
									<strong>
										<a class="row-title">'.$result->post_type.'</a>
									</strong>
								</td>
								<td class="post-title page-title column-title">
									<strong>
										<a class="row-title">'.$result->modified_date.'</a>
									</strong>
								</td>
						<tr>';
		  }
		  	$cnt++;
		 }
	}else{
			$tablehtml .= '<tr>
								<td class="post-title page-title column-title" colspan="4" style="text-align:center">
									<strong>
										<a class="row-title">No Record Found</a>
									</strong>
								</td>
						 <tr>';
		}
		$tablehtml .= '</tbody>
						<tfoot>
							<tr>
								<th scope="col" class="manage-column column-author" style="">No</th>
								<th scope="col" class="manage-column column-author" style="">Title</th>
								<th scope="col" class="manage-column column-author" style="">Post Type</th>
								<th scope="col" class="manage-column column-author" style="">Modified Date</th>
							</tr>
						</tfoot>
			</table>';
}

$data = array();
$data['tablehtml'] = $tablehtml;
header('Content-type:application/json');
echo json_encode( $data );
exit;
}
add_action('wp_ajax_far_admin_ajax_fun', 'far_admin_ajax_fun');
add_action('wp_ajax_nopriv_far_admin_ajax_fun', 'far_admin_ajax_fun');

/****** End Ajax call for Far admin ********/

