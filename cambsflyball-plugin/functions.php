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

//Google Custom Search Widget
//require(get_template_directory() . '/inc/widgets/openstrap-google-cse-widget.php');

//Social Icon Box
//require(get_template_directory() . '/inc/widgets/openstrap-social-box-widget.php');

//Front Page Text
require('widgets/wpb-widget.php');

//Feedburner Subscription
//require(get_template_directory() . '/inc/widgets/openstrap-feedburner-widget.php');

function cft_load_custom_widgets() {
        register_widget( 'wpb_widget' );
}
add_action('widgets_init', 'cft_load_custom_widgets');
