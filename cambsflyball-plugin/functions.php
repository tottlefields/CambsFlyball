<?php
/**
* Plugin Name: Cambs Flyball Plugin
* Description: Cambs Flyball plugin - manage teams/dogs/handlers for @cambsflyball.
* Version: 0.0.1
* Author: PawPrints Design
*
* Text Domain: cambs-flyball
*
* @package CambsFlyball Plugin
* @category Core
* @author PawPrints Design
*/

// Include widget/files...
require_once 'posttypes.php';
//require_once 'shortcodes.php';
//require_once 'custom-metaboxes.php';
//require_once 'ukfl-gocardless.php';


//Front Page Text
require('widgets/wpb-widget.php');

function cft_load_custom_widgets() {
        //register_widget( 'wpb_widget' );
        register_widget( 'cft_widget' );
}
add_action('widgets_init', 'cft_load_custom_widgets');



add_action('acf/save_post', 'my_acf_save_post');
function my_acf_save_post( $post_id ) {
	
	// Get newly saved values.
	//$values = get_fields( $post_id );
		
	// Check the new value of a specific field.	
	$retired = get_field('retired');
	if ($retired == 1) { 
		$my_post = array(
				'ID'            => $post_id,
				'post_status'   => 'retired'
		);
		wp_update_post( $my_post );
		update_field( 'team_name', '', $post_id );
		update_field( 'team', '', $post_id );
		return;
	};
	
	// Check the new value of a specific field.
	$team = get_field('team');
	if( $team ){
		update_field( 'team_name', $team->post_title, $post_id );
	}
	else { update_field( 'team_name', '', $post_id ); }
}







