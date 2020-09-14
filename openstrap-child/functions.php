<?php
require 'cft-functions.php';

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles', 100 );
add_action('pre_get_posts', 'sort_teams_correctly');
add_filter('query_vars', 'add_query_vars');

function enqueue_parent_styles() {
	wp_dequeue_style( 'openstrap-style' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css', array( 'parent-style' ), wp_get_theme()->get('Version') );
}

function add_query_vars($aVars) {
	$aVars[] = "cft-member"; // represents the country as shown in the URL
	//$aVars[] = "ukfl-action"; // represents the country as shown in the URL
	return $aVars;
}

function sort_teams_correctly( $query ) {
	
	if( is_admin() ) {
		return $query;
	}
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'cft_team' && $query->is_main_query() ) {
		
		$query->set('orderby', 'meta_value');
		$query->set('meta_key', 'sort_order');
		$query->set('order', 'ASC');
		
	}
	return $query;
}

function SQLToDate($date, $format='d/m/Y'){
	if ($date == ""){ return ""; }
	return date_format(DateTime::createFromFormat('Y-m-d', $date), $format);
}

function debug_array($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

?>
