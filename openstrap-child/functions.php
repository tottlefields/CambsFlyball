<?php
require 'cft-functions.php';
require_once 'ajax.php';

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles', 100 );
add_action( 'wp_enqueue_scripts', 'cft_enqueue_scripts', 200 );
add_action('pre_get_posts', 'sort_teams_correctly');
add_filter('query_vars', 'add_query_vars');
add_filter('rewrite_rules_array', 'add_rewrite_rules');				// hook add_rewrite_rules function into rewrite_rules_array

function enqueue_parent_styles() {
	wp_dequeue_style( 'openstrap-style' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css', array( 'parent-style' ), wp_get_theme()->get('Version') );
}

function cft_enqueue_scripts() {
	global $wp;
	
	// Bootstrap-Select JS
	//wp_register_script ( 'bs-select', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js', array ('jquery-core', 'bootstrap'), '1.13.18', true );
	//wp_enqueue_script ( 'bs-select' );
	
	// BS DatePicker
	wp_register_script ( 'datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js', array ('jquery-core', 'bootstrap'), '1.9.0', true );
	wp_enqueue_script ( 'datepicker' );

	// Main functions js file
	wp_register_script ( 'js-functions', get_stylesheet_directory_uri().'/assets/js/functions.js', array ('jquery'), '0.1.1', true );
	wp_enqueue_script ( 'js-functions' );
}

function add_query_vars($aVars) {
	$aVars[] = "cft-member"; // represents the member as shown in the URL
	$aVars[] = "cft-year"; // represents the year as shown in the URL
	return $aVars;
}

function add_rewrite_rules($aRules) {
	$aNewRules = array(
		'results/([^/]+)/?$' => 'index.php?pagename=results&cft-year=$matches[1]',
	);
	$aRules = $aNewRules + $aRules;
	return $aRules;
}




/**
* Filter the single_template with our custom function
*/
add_filter('single_template', 'my_single_template');
 
/**
* Single template function which will choose our template
*/
function my_single_template($single) {
	global $wp_query, $post;

	// Get the current single post object
	$post = get_queried_object();
	// Set our 'constant' folder path
	$path = 'single/';

	// Set our variable to hold our templates
	$templates = array();

	// Lets handle the custom post type section
	if ( 'post' !== $post->post_type ) {
			$templates[] = $path . 'single-' . $post->post_type . '-' . $post->post_name . '.php';
			$templates[] = $path . 'single-' . $post->post_type . '.php';
	}

	// Lets handle the post post type stuff
	if ( 'post' === $post->post_type ) {
			// Get the post categories
			$categories = get_the_category( $post->ID );
			// Just for incase, check if we have categories
			if ( $categories ) {
					foreach ( $categories as $category ) {
							// Create possible template names
							$templates[] = $path . 'single-cat-' . $category->slug . '.php';
							$templates[] = $path . 'single-cat-' . $category->term_id . '.php';
					} //endforeach
			} //endif $categories
	} // endif  

	// Set our fallback templates
	$templates[] = $path . 'single.php';
	$templates[] = $path . 'default.php';
	$templates[] = 'index.php';

	/**
	 * Now we can search for our templates and load the first one we find
	 * We will use the array ability of locate_template here
	 */
	$template = locate_template( $templates );

	// Return the template rteurned by locate_template
	return $template;
}

function sort_teams_correctly( $query ) {
	
	if( is_admin() ) {
		return $query;
	}
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'cft_team' && $query->is_main_query() ) {
		
		$query->set('orderby', 'meta_value_num');
		$query->set('meta_key', 'sort_order');
		$query->set('order', 'ASC');
		
	}
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'cft_dog' && $query->is_main_query() ) {
		$query->set('posts_per_page', -1 );
	}
	return $query;
}

function SQLToDate($date, $format='d/m/Y'){
	if ($date == ""){ return ""; }
	return date_format(DateTime::createFromFormat('Y-m-d', $date), $format);
}

function dateToSQL($date){
	if ($date == ""){ return ""; }
	return date_format(DateTime::createFromFormat('d/m/Y', $date), 'Y-m-d');
}

function debug_array($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

?>
