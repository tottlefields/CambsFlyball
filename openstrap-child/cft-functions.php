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

function get_dogs_for_user($user){
	global $wpdb;
	
	$args = array(
			'post_type'     => 'cft_dog',
			'post_status'   => array('publish'),
			'posts_per_page'=> -1,
			'author'        =>  $user->ID,
			'order'			=> 'ASC'
	);
	
	$dogs = get_posts($args);
	
	return $dogs;
	
}


function get_members_money($user){
	global $wpdb;
	
	$money = array();
	
	$money_details = $wpdb->get_results("select method, sum(amount) as total from cft_members_money where user_id=".$user->ID." group by method");
	$money['invoices'] = 0;
	$money['payments'] = 0;
	$money['balance'] = 0;
	$money['details'] = $money_details;
	
	foreach ($money_details as $method){
		if ($method->method == 'INVOICE'){
			$money['invoices'] += $method->total;
		}
		else {
			$money['payments'] += $method->total;			
		}
	}
	
	$money['balance'] = $money['invoices'] - $money['payments'];
	
	
	return $money;
}

function getBalanceByDate($date, $user){
	global $wpdb;
	
	$balance = 0;
	$balance = $wpdb->get_var("SELECT sum(case when method in ('INVOICE') then -1*amount else amount end) as club_balance FROM cft_members_money where date_in<'".$date."' and user_id=".$user->ID);
	
	return $balance;	
}

function getUserTransYear($year, $user){
	global $wpdb;
		
	$transactions = $wpdb->get_results("select date_in, method, category, description, sum(amount) as amount from cft_members_money 
	where year(date_in)= '".$year."' and user_id=".$user->ID." group by date_in, method, description order by date_in, money_id");
	
	return $transactions;
	
}






