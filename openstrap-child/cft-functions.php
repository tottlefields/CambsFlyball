<?php

function get_cft_dog_awards ($dog_id){
	global $wpdb;
	
	$awards = $wpdb->get_results("select award, date_format(date_gained, '%d/%m/%Y') as formatted_date from cft_dog_awards where post_id = '".$dog_id."' order by date_gained" );
	
	return $awards;
	
}

function get_dogs_for_team($team_id){
	global $wpdb;
	
	$args = array(
			'post_type'     => 'cft_dog',
			'post_status'   => array('publish'),
			'posts_per_page'=> -1,
			'meta_query' => array(array('key' => 'team', 'value' => $team_id, 'compare' => '=')),
			'order'		=> 'ASC'
	);
	
	$dogs = get_posts($args);
	
	return $dogs;
	
}