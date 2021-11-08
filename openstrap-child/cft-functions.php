<?php

$tz  = new DateTimeZone('Europe/London');

function get_cft_dog_awards ($dog_id){
	global $wpdb;
	
	$awards = $wpdb->get_results("select award, date_format(date_gained, '%Y%m%d') as race_date, date_format(date_gained, '%d/%m/%Y') as formatted_date 
	from cft_dog_awards where post_id = '".$dog_id."' order by date_gained" );
	
	return $awards;
	
}

function get_dogs_for_team($team_id){
	global $wpdb;
	
	$args = array(
			'post_type'     => 'cft_dog',
			'post_status'   => array('publish'),
			'posts_per_page'=> -1,
			'meta_query'	=> array(array('key' => 'team', 'value' => $team_id, 'compare' => '=')),
			'orderby'		=> 'name',
			'order'			=> 'ASC'
	);
	
	$dogs = get_posts($args);
	
	return $dogs;
	
}

function get_all_dogs(){
	global $wpdb;
	
	$args = array(
			'post_type'     => 'cft_dog',
			'post_status'   => array('publish'),
			'posts_per_page'=> -1,
			'orderby'		=> 'name',
			'order'			=> 'ASC'
	);
	
	$dogs = get_posts($args);
	
	return $dogs;
	
}

function get_dogs_for_user($user){
	global $wpdb;
	
	$owned = get_posts(array(
			'post_type'     => 'cft_dog',
			'post_status'   => array('publish', 'retired'),
			'posts_per_page'=> -1,
			'fields'		=> 'ids',
			'author'        =>  $user->ID
	));
	$handled = get_posts(array(
			'post_type'     => 'cft_dog',
			'post_status'   => array('publish', 'retired'),
			'posts_per_page'=> -1,
			'fields'		=> 'ids',
			'meta_query'	=> array(array('key' => 'handler', 'value' => $user->ID, 'compare' => '='))			
	));
	
	if (count($owned) + count($handled) == 0){
		return array();
	}

	$dogs = get_posts(array(
			'post_type'     => 'cft_dog',
			'post_status'   => array('publish', 'retired'),
			'posts_per_page'=> -1,
			'post__in'		=> array_unique (array_merge ($owned, $handled))
	));	
	
	return $dogs;
	
}


function get_results_for_team($team_id){
	global $wpdb;

	return $wpdb->get_results("select post_title as event_title, post_name as slug, team_type, 
	race_date, case when (division>0) then concat('Div ', division) else division end as division, seed, place, fastest_time, new_fastest 
	from cft_event_stats left outer join wp_posts on event_id=wp_posts.ID 
	where team_id=".$team_id." order by race_date desc limit 8");
}


function get_results_for_event($event_id){
	global $wpdb;

	return $wpdb->get_results("select team, post_name as slug, team_type, race_date, division, seed, place, fastest_time, new_fastest 
	from cft_event_stats left outer join wp_posts on team_id=wp_posts.ID 
	where event_id=".$event_id." order by race_date, division+0, place+0");
}


function get_stats_for_event($event_id){
	global $wpdb;

	return $wpdb->get_results("select dog_id, post_title as name, post_name as slug, points, heats, fastest_time, average_time, consistency, team, race_date, meta_value as is_member 
	from cft_dog_stats t1 left outer join wp_posts t2 on t1.dog_id=t2.ID
	left outer join wp_postmeta t3 on t2.ID=t3.post_id
	where event_id=".$event_id." and t3.meta_key='member' 
	order by post_title");
}


function get_stats_for_dog($dog_id, $name){
	global $wpdb;

	return $wpdb->get_results("select dog_id, post_title as event_title, post_name as slug, points, heats, t1.fastest_time, average_time, consistency, 
	t1.team, t1.race_date, case when (division>0) then concat('Div ', division) else division end as division, place, team_type 
	from cft_dog_stats t1 left outer join wp_posts t2 on t1.event_id=t2.ID 
	left outer join cft_event_stats t3 on t1.event_id=t3.event_id and t1.race_date=t3.race_date 
	where case when t1.team = 'Singles' then t3.team='Singles - ".$name."' else t1.team=t3.team end and dog_id=".$dog_id." 
	order by race_date DESC");
}

function get_awards_for_event($start_date, $end_date){
	global $wpdb;

	$awards = array();
	$details = $wpdb->get_results("select post_id, award from cft_dog_awards where date_gained between '".$start_date->format('Y-m-d')."' and '".$end_date->format('Y-m-d')."'");
	foreach ($details as $award){
		$awards[$award->post_id] = $award->award;
	}
	return $awards;
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
	method, category, case when method='INVOICE' then event_desc else group_concat(distinct u.display_name) end as description, sum(amount) as total, group_concat(distinct u.user_login) as users, 
	count(distinct user_id) as count
	from cft_members_money m left outer join wp_users u on m.user_id=u.id
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

function addInvoice($date, $amount, $user_id, $category, $event_desc=null, $description=null){
	global $wpdb;
	
	$wpdb->insert('cft_members_money', array(
			'date_in' => dateToSQL($date),
			'amount' => $amount,
			'user_id' => $user_id,
			'method' => 'INVOICE',
			'category' => $category,
			'event_desc' => $event_desc,
			'description' => $description
	));
	$insert_id = $wpdb->insert_id;
	
	return $insert_id;
	
}


function sendEmail($to_email, $member_name, $msg_subject, $msg){
	
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'Reply-To:captain@cambridgeshire-flyball.org.uk';
	
	ob_start();
	include(get_stylesheet_directory() . '/assets/email-templates/member-email.html');
	$email_content = ob_get_contents();
	ob_end_clean();
	
	
	$email_content = str_replace('[[MEMBER]]', $member_name, $email_content);
	$email_content = str_replace('[[MSG]]', $msg, $email_content);	
	
	wp_mail($to_email, "[Cambridgeshire Flyball] ".$msg_subject, $email_content, $headers);
}

function emailInvoice($user_id, $invoices){
	global $wpdb;
	
	$user = get_user_by( 'ID', $user_id );

	$msg = "
<p>Please find details below of a new invoice(s) added to your account:-</p>
		
<table class='table-visible' style='margin:15px;'>
	<th>Date</th><th>Description</th><th class='text-center'>Quantity</th><th class='text-center'>Cost</th><th class='text-center'>Total</th></tr>";

	foreach ($invoices as $invoice){
		addInvoice($invoice['date'], $invoice['total_amount'], $user_id, $invoice['category'], $invoice['event_desc'], $invoice['description']);
		$msg .= "
	<tr><td>".$invoice['date']."</td><td>".$invoice['description']."</td><td class='text-center'>".$invoice['quantity']."</td><td class='text-center'>&pound;".number_format($invoice['price'], 2)."</td><td class='text-center'>&pound;".number_format($invoice['total_amount'], 2)."</td></tr>";
		
	}
	$msg .= "
				</table>
		
				<p>To view your current account status with the club please login to the club website and visit the <a href=\"https://cambridgeshire-flyball.org.uk/members-only/account/\">My Account</a> page.  From here you can see the last 2 years of your account history.</p>

				<div class='well'>
					<em>Bank Account Details</em><br />
					Name: <strong>CAMBRIDGESHIRE FLYBALL TEAM</strong><br />
					Sort Code: <strong>20-29-68</strong>
					A/C No: <strong>83848558</strong><br />
				</div>
		
<p>Any issues or queries please let me know.</p>
		
Many thanks,<br />
Ellen<br />";

	//EMAIL.....!
	sendEmail($user->user_email, $user->first_name, 'New Invoice(s) Added', $msg);
}

function getOrdinal($number=0){
	// Handles special case three digit numbers ending
	// with 11, 12 or 13 - ie, 111th, 112th, 113th, 211th, et al
	If ($number > 99) {
					$intEndNum = substr($number,-2);
					If ($intEndNum >= 11 And $intEndNum <= 13) {
									switch ($intEndNum){
													Case (11 or 12 or 13):
													Return "th";
													break;
									}
					}
	}
	If ($number >= 21) {
	// Handles 21st, 22nd, 23rd, et al
					switch (substr($number,-1)) {
									Case 0:
									Return "th";
									break;
									Case 1:
									Return "st";
									break;
									Case 2:
									Return "nd";
									break;
									Case 3:
									Return "rd";
									break;
									Case (4 || 5 || 6 || 7 || 8 || 9):
									Return "th";
									break;
					}
	} else {
					// Handles 1st to 20th
					switch ($number){
									Case 1:
									Return "st";
									break;
									Case 2:
									Return "nd";
									break;
									Case 3:
									Return "rd";
									break;
									Case (4 || 5 || 6 || 7 || 8 || 9 || 10 || 11 || 12 || 13 || 14 || 15 || 16 || 17 || 18 || 19 || 20):
									Return "th";
									break;
					}
	}
} // end func am_GetOrdinal



