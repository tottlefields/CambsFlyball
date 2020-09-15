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

function getRecentAccounts(){
	global $wpdb;	
	
	$transactions = $wpdb->get_results("select date_in, case when method='INVOICE' then concat_ws(':', method, category, event_desc) else concat_ws(':', method, user_id) end as groupCol,
	method, category, case when method='INVOICE' then event_desc else group_concat(distinct u.display_name) end as description, sum(amount) as total, group_concat(distinct user_id) as users, 
	count(distinct user_id) as count
	from cft_members_money m inner join wp_users u on m.user_id=u.id
	WHERE date_in > DATE_SUB(CURDATE(), INTERVAL 2 MONTH) and date_in <= CURDATE() group by 1,2 order by 1 desc,2");
	
	return $transactions;
}

function addPayment($date, $amount, $user_id, $method, $description=null){
	global $wpdb;
	
	$wpdb->insert('cft_members_money', array(
			'date_in' => dateToSQL($date),
			'amount' => $amount,
			'user_id' => $user_id,
			'method' => $method,
			'description' => $description
	));
	$insert_id = $wpdb->insert_id;
	
	return $insert_id;
	
}




