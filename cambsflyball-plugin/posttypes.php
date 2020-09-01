<?php

// Our custom post type function
function register_custom_posttypes() {

	$team_args = array(
			'labels' => array(
					'name' 			=> __( 'Teams', 'cft' ),
					'singular_name' 	=> __( 'Team', 'cft' ),
					'add_new_item' 		=> __( 'Add New Team', 'cft' ),
					'edit_item' 		=> __( 'Edit Team', 'cft' ),
					'new_item' 		=> __( 'New', 'cft' ),
					'view_item' 		=> __( 'View Team', 'cft' ),
					'search_items' 		=> __( 'Search', 'cft' ),
					'not_found' 		=> __( 'No results found.', 'cft' ),
					'not_found_in_trash' 	=> __( 'No results found.', 'cft' ),
					'featured_image'	=> __( 'Logo', 'cft' ),
					'set_featured_image' 	=> __( 'Select Logo', 'cft' ),
					'remove_featured_image' => __( 'Remove Logo', 'cft' ),
					'use_featured_image' 	=> __( 'Select Logo', 'cft' ),
			),
			'public' 		=> true,
			'show_ui' 		=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical'		=> false,
			'rewrite'		=> array( 'slug' => get_option('cft_team_slug','teams') ),
			'supports' 		=> array( 'title', 'author', 'thumbnail', 'custom-fields' ),
			'has_archive' 		=> true,
			'show_in_nav_menus' 	=> true,
			'menu_icon' 		=> 'dashicons-shield',
			'menu_position'		=> 30,
	);

	$dog_args = array(
		'labels' => array(
                        'name'                  => __( 'Dogs', 'cft' ),
                        'singular_name'         => __( 'Dog', 'cft' ),
                        'add_new_item'          => __( 'Add New Dog', 'cft' ),
                        'edit_item'             => __( 'Edit Dog', 'cft' ),
                        'new_item'              => __( 'New', 'cft' ),
                        'view_item'             => __( 'View Dog', 'cft' ),
                        'search_items'          => __( 'Search', 'cft' ),
                        'not_found'             => __( 'No results found.', 'cft' ),
                        'not_found_in_trash'    => __( 'No results found.', 'cft' ),
                ),
                'public'                => true,
                'show_ui'               => true,
                'publicly_queryable'    => true,
                'exclude_from_search'   => false,
                'hierarchical'          => false,
                'rewrite'               => array( 'slug' => get_option('cft_dog_slug','dogs') ),
		'supports'		=> array( 'title', 'author', 'thumbnail', 'custom-fields' ),
                'has_archive'           => true,
		'menu_position'		=> 32,
	);

	register_post_type('cft_team',  $team_args);
	register_post_type('cft_dog', $dog_args);
	
/*	register_taxonomy('dog-breeds', array('cft_dog'),
			array(
					'hierarchical' => false,
					'label' => 'Dog Breeds',
					'singular_label' => 'Dog Breed',
					'rewrite' => true,
					'capabilities' => array(
							'assign_terms' => 'read'
					)
			)
	);
 */	
}
// Hooking up our function to theme setup
add_action( 'init', 'register_custom_posttypes' );

function fontawesome_icon_dashboard() {
	echo "<style type='text/css' media='screen'>
			#adminmenu #menu-posts-cft_dog div.wp-menu-image:before {
				font-family: Fontawesome !important;
				content: '\\f1b0';
			}";
}
add_action('admin_head', 'fontawesome_icon_dashboard');

function set_admin_menu_separator() { do_action( 'admin_init', 29 ); }
add_action( 'admin_menu', 'set_admin_menu_separator' );


// List Clubs/Teams alphabetically
/*add_filter('posts_orderby', 'club_team_orderby');
function club_team_orderby($sql){
	global $wpdb, $wp_query;
	if (is_admin() && (is_cft_team() || is_ukfl_sub_team())){
		return $wpdb->prefix."posts.post_title ASC";
	}
	return $sql;
}*/


//Set up admin tables for teams/clubs
//add_filter( 'manage_posts_columns', 'cft_team_custom_columns' );
//add_action( 'manage_posts_custom_column' , 'cft_team_show_columns', 10, 2 );

/*function cft_team_custom_columns($defaults) {
	global $wp_query;
	if (is_cft_team()){
		unset($defaults['date']);
                $defaults['team_mandate'] = 'GC Mandate';
		$defaults['region'] = 'UKFL Region';
		$defaults['club_logo'] = 'Club Logo';
		$defaults['author'] = 'Team Captain';
		$defaults['title'] = 'Team Name';
		return $defaults;
	}
	if (is_cft_dog()){
		unset($defaults['date']);
		$defaults['title'] = "UKFL No.";
	        $defaults['dog_name'] = "Dog's Name";
	        $defaults['dog_status'] = "Status";
                $defaults['author'] = 'Owner';
                $defaults['dog_mandate'] = 'GC Mandate';
                $defaults['team_name'] = 'Team';
	}
	return $defaults;
}*/

/*function cft_team_show_columns($column, $post_id){
	switch ($column) {
		case 'dog_name':
			echo get_post_meta($post_id, 'cft_dog_name', true) ;
			break;
		case 'team_name':
			$club_id = wp_get_post_parent_id($post_id);
			echo get_the_title( $club_id );
			break;	
	}
}*/


