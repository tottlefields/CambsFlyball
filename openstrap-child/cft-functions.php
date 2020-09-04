<?php

function get_cft_dog_awards ($dog_id){
	global $wpdb;
	
	$awards = $wpdb->get_results("select award, date_format(date_gained, '%d/%m/%Y') as formatted_date from cft_dog_awards where post_id = '".$dog_id."' order by date_gained" );
	
	return $awards;
	
}