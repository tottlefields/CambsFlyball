<?php
require 'cft-functions.php';

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles', 100 );

function enqueue_parent_styles() {
	wp_dequeue_style( 'openstrap-style' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css', array( 'parent-style' ), wp_get_theme()->get('Version') );
}

function SQLToDate($date, $format='d/m/Y'){
	if ($date == ""){ return ""; }
	return date_format(DateTime::createFromFormat('Ymd', $date), $format);
}

?>
