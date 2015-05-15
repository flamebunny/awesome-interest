<?php


/**
 * @package awesome-interest
 * @version 1
 */




/*	
	Plugin Name: Awesome Interest
	Plugin URI: http://www.pixeltradr.com
	Description: A widget that allows visitors to register their interest in your wedding and reception
	Author: Deepesh Budhia
	Version: 1
	Author URI: http://www.pixeltradr.com
	
	
	
	
	Copyright 2014  pixeltradr.com  (email : dbudhia@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/
 


 if(file_exists(ABSPATH . "wp-content/plugins/awesome-interest/lang.php")) {
	include (ABSPATH . "wp-content/plugins/awesome-interest/lang.php");
} else {
	echo "Awesome Interest error: language file not found.";
}


/**
 * action, activation and deactivation bits
 */
register_activation_hook(__FILE__, 'on_activation');
register_deactivation_hook(__FILE__, 'on_deactivation');
add_action('admin_menu', 'awesome_interest_add_menu');
add_shortcode('awesomeinterest', 'awesome_interest_add_form');


//---------------------------------------------------------------






/**
 * on_activation
 * 
 */
function on_activation(){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "awesome_interest_table";

	$sql = "
		CREATE TABLE `".$table_name."` (
			`id`		INT NOT NULL AUTO_INCREMENT,
			`name`		VARCHAR( 128 ),
			`count`		VARCHAR( 128 ),
			`event_wedding`		BOOL,
			`event_reception`		BOOL,
			PRIMARY KEY (  `id` ),
			UNIQUE (`id`)
		)
	";
	
	$wpdb->query($wpdb->prepare($sql));
}


//---------------------------------------------------------------






/**
 * on_deactivation
 * 
 */
function on_deactivation(){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "awesome_interest_table";
	
	$sql = "DROP TABLE `".$table_name."`";
	
	$wpdb->query($wpdb->prepare($sql));
}


//---------------------------------------------------------------






/**
 * add_menu
 * 
 */
function awesome_interest_add_menu(){
	add_options_page('Awesome-Interest', 'Awesome-Interest', 'level_10', 'awesome_interest', 'awesome_interest_admin_view');
}


//---------------------------------------------------------------








/**
 * awesome_interest_admin_view
 * 	import the plugins admin view
 */
function awesome_interest_admin_view(){
	include 'admin_page.php';
}


//---------------------------------------------------------------




/**
 * load widget
 * 
 */
//include 'sample-widget.php';
include 'widget.php';


//---------------------------------------------------------------



/**
 * Ajax and such
 */
 if(!is_admin()){
	wp_enqueue_scripts('jquery');
	wp_enqueue_script('awesome_js', 						plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ));
	wp_enqueue_style('awesome_css', 						plugin_dir_url( __FILE__ ) . 'css/style.css');
	wp_localize_script('awesome_js', 						'ajax_loc', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));	
 }
 
add_action( 'wp_ajax_nopriv_awesome_interest_submit', 	'awesome_interest_submit');
add_action( 'wp_ajax_awesome_interest_submit', 			'awesome_interest_submit');

//------------------------------------------------------------------------


/**
 * process data submitted from the front facing widget form
 * 
 */
function awesome_interest_submit(){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "awesome_interest_table";
	
	// gather
	$check_wedding 		= sanitize_text_field($_POST['check_wedding']);
	if($check_wedding == 'true'){
		$check_wedding = 1;
	}else{
		$check_wedding = 0;
	}
	
	$check_reception 	= sanitize_text_field($_POST['check_reception']);
	if($check_reception == 'true'){
		$check_reception = 1;
	}else{
		$check_reception = 0;
	}	
	
	$guestname 			= sanitize_text_field($_POST['guestname']);
	$guestcount 		= sanitize_text_field($_POST['guestcount']);

	// save in the database
	$wpdb->insert( $table_name, array( 'name' => $guestname, 'count' => $guestcount, 'event_wedding' => $check_wedding, 'event_reception' => $check_reception ) );	
	
	echo "name = ".$guestname.", count = ".$guestcount.", event_wedding = ".$check_wedding.", event_reception = ".$check_reception;
	
	
	exit;
}

//------------------------------------------------------------------------



/**
 * awesome_interest_admin_view
 * 	import the plugins admin view
 */
function awesome_interest_add_form() {
	global $wpdb, $current_user, $al_lang, $post;
	
	?>
		<script type="text/javascript">
			//setting vars that rely on PHP for their values.  
			var success_text 				= '<?php echo $al_lang['success']; ?>';
			var check_wedding_id 			= 'check-wedding_<?php echo $post->ID; ?>';
			var check_reception_id 			= 'check-reception_<?php echo $post->ID; ?>';
			var guestname_id 				= 'guestname_<?php echo $post->ID; ?>';
			var guestcount_id 				= 'guestcount_<?php echo $post->ID; ?>';	
			var send_id 					= 'send_<?php echo $post->ID; ?>';	
		</script>
	
	<?php
		$return.=	'<div id="awesome_interest_form_container" class="awesome_interest_form_container">' .				
						'<form name="awesome_interest_form" id="awesome_interest_form" method="post" action="#">' .				
							'<fieldset>' .
								'<h3>'.$al_lang['question1'].'</h3>' .								
								'<div class="checkbox">' .
									'<input type="checkbox" id="check-wedding_'.$post->ID.'" name="check-wedding_'.$post->ID.'">' .
									'<label for="check-wedding_'.$post->ID.'">'.$al_lang['answer1'].'</label>' .
								'</div>' .
								
								'<div class="checkbox">' .
									'<input type="checkbox" id="check-reception_'.$post->ID.'" name="check-reception_'.$post->ID.'">' .
									'<label for="check-reception_'.$post->ID.'">'.$al_lang['answer2'].'</label>' .
								'</div>' .
							'</fieldset>' .
							'<fieldset id="guests">' .
								'<h3>Guest Details:</h3>' .
								'<input type="text" placeholder="'.$al_lang['question2'].'" name="guestname_'.$post->ID.'" id="guestname_'.$post->ID.'">' .
								'<input type="text" placeholder="'.$al_lang['question3'].'" name="guestcount_'.$post->ID.'" id="guestcount_'.$post->ID.'">' .
								'<input type="submit" value="submit" id="send_'.$post->ID.'" class="submit">' .
							'</fieldset>' .						
						'</form>' .		
					'</div>';			
	
	return $return;
}


/* End of file awesome-interest.php */