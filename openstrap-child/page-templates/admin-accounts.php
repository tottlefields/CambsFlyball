<?php
/**
 * Template Name: Admin Accounts
 *
 * Page template for
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */

if (isset($_REQUEST) && $_REQUEST['add_income'] != ''){
	if ($_REQUEST['method'] == 'INVOICE'){
		$invoice = array(
				"date"			=> $_REQUEST['date_in'],
				"category"		=> 'Flyball',
				"event_desc"	=> $_REQUEST['description'],
				"description"	=> $_REQUEST['description'],
				"quantity"		=> 1,
				"price"			=> $_REQUEST['amount'],
				"total_amount"	=> $_REQUEST['amount']
		);
		emailInvoice($_REQUEST['user_id'], array($invoice));
	} else {
		addPayment($_REQUEST['date_in'], $_REQUEST['amount'], $_REQUEST['user_id'], $_REQUEST['method'], $_REQUEST['description']);
	}
	wp_safe_redirect($_SERVER['HTTP_REFERER']); exit;
}

if (isset($_REQUEST) && $_REQUEST['add_invoices'] != ''){
	
	$event_desc = $_REQUEST['event_desc'];
	$date_in = $_REQUEST['date_in'];
	$category = $_REQUEST['category'];
	$invoices = array();
	
	if (isset($_FILES['csv_file'])){
		if(is_uploaded_file($_FILES['csv_file']['tmp_name'])){
			$handle = fopen($_FILES['csv_file']['tmp_name'], "r");
			while (($data = fgets($handle, 1000)) !== FALSE) {
				if (preg_match("/^NAME/", $data)){
					continue;
				}
				
				$data_parts = preg_split("/[,|\t]/", rtrim($data));
				#$data_parts => NAME NUMBER MEMB_ID DESC QUANTITY RATE
				if ($data_parts[0] == ""){ continue; }
	
				$description = $data_parts[3];
				if ($data_parts[3] == ''){
					switch ($category) {
						case "Flyball":
							$description = "Training - ".$event_desc;
							break;
						case "Bonus Ball":
							$description = $event_desc;
							break;
						case "Entry Fees":
							$description = $event_desc." - ".$data_parts[0];
							break;
						case "Camping":
							$description = $event_desc." - ".$category;
							break;
						case "Membership":
							if($event_desc != ''){
								$description = $event_desc;
							}
							else{
								$description = $category." - ".$data_parts[0];
							}
							break;
						case "Special Event":
							if ($data_parts[2] == $data_parts[1]){
								$description = $event_desc;
							}
							else {
								$description = $event_desc." - ".$data_parts[0];
							}
							break;
						case "Merchandise":
							if ($data_parts[2] == $data_parts[1]){
								$description = $event_desc;
							}
							else {
								$description = $event_desc." - ".$data_parts[0];
							}
							break;
					}
				}
				
				$invoice = array(
						"date"			=> $date_in,
						"category"		=> $category,
						"event_desc"	=> $event_desc,
						"description"	=> $description,
						"quantity"		=> $data_parts[4],
						"price"			=> $data_parts[5],
						"total_amount"	=> $data_parts[4] * $data_parts[5]
				);
				
				if(!isset($invoices[$data_parts[2]])){
					$invoices[$data_parts[2]] = array();
				}
				array_push($invoices[$data_parts[2]], $invoice);
			}
		}
	}
	
	foreach ($invoices as $user_id => $all_invoices){		
		emailInvoice($user_id, $all_invoices);
	}
	
	wp_safe_redirect($_SERVER['HTTP_REFERER']); exit;
}



global $wpdb, $current_user;
if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (! current_user_can('administrator') && ! current_user_can('editor') ){ wp_safe_redirect('/members-only/account/'); exit; }

get_header(); ?>

	<!-- Main Content -->	
	<div class="col-md-12" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	        <header class="entry-header">
	                <h1 class="entry-title"><?php the_title(); ?></h1>
	        </header>
	
	        <div class="entry-content">
	                <?php the_content(); ?>
	                
					<?php get_template_part('part-templates/accounts', 'individual'); ?>	 
					<?php get_template_part('part-templates/accounts', 'bulk'); ?>
	                
	                <?php $transactions = getRecentAccounts(); ?>
	                
	                <table class="table">
	                	<thead><tr><th>Date</th><th>Method</th><th class="hidden-xs">Category</th><th >Description</th><th class="text-right">Amount</th></tr></thead><tbody>
						<?php foreach ($transactions as $row){ ?>
							<tr>
								<td style="white-space:nowrap;"><?php echo SQLToDate($row->date_in, 'd-M'); ?></td>
								<td><?php echo $row->method; ?></td>
								<td class="hidden-xs"><?php echo $row->category; ?></td>
								<td>
								<?php if ($row->count == 1) { echo '<a href="/members-only/account/?cft-member='.$row->users.'">'.$row->description.'</a>'; } else { echo $row->description; } ?>
								</td>
								<td class="text-right">&pound;<?php echo number_format($row->total, 2); ?></td>
							</tr>
						<?php } ?>
	                	</tbody>
	                </table>
	        </div><!-- .entry-content -->
		</article><!-- #post -->
	
	<?php openstrap_custom_pagination(); ?>
	</div>	
	<!-- End Main Content -->	


<?php get_footer(); ?>
<script>
	jQuery(document).ready(function($) {
		$(".date_in").datepicker({
			format : 'dd/mm/yyyy',
			autoclose : true,
			todayHighlight : true,
			todayBtn : true,
			zIndexOffset : 2000
		});
    });
</script>

