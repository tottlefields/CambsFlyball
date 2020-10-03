<?php
/**
 * Template Name: Admin Accounts
 *
 * Page template for
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */

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
				#$data_parts => NAME NUMBER MEMB_ID QUANTITY RATE
				if ($data_parts[0] == ""){ continue; }
	
				$description = '';
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
				
				$invoice = array(
						"description"	=> $description,
						"quantity"		=> $data_parts[3],
						"price"			=> $data_parts[4],
						"total_amount"	=> $data_parts[3] * $data_parts[4]
				);
				
				if(!isset($invoices[$data_parts[2]])){
					$invoices[$data_parts[2]] = array();
				}
				array_push($invoices[$data_parts[2]], $invoice);
			}
		}
	}
	
	foreach ($invoices as $user_id => $all_invoices){
		$user = get_user_by( 'ID', $user_id );
		
		$msg = "
<p>Please find details below of a new invoice added to your account:-</p>
				
<table class='table-visible' style='margin:15px;'>
	<th>Date</th><th>Description</th><th class='text-center'>Quantity</th><th class='text-center'>Cost</th><th class='text-center'>Total</th></tr>";
		
		foreach ($all_invoices as $invoice){
			addInvoice($date_in, $invoice['total_amount'], $user_id, $category, $event_desc, $description);
			$msg .= "
<tr><td>".$date_in."</td><td>".$description."</td><td class='text-center'>".$invoice['quantity']."</td><td class='text-center'>&pound;".number_format($invoice['price'], 2)."</td><td class='text-center'>&pound;".number_format($invoice['total_amount'], 2)."</td></tr>";
			
		}
		$msg .= "
				</table>
				
				<p>To view your current account status with the club please login to the club website and visit the <a href=\"https://cambridgeshire-flyball.org.uk/members-only/account/\">My Account</a> page.  From here you can see the last 2 years of your account history.</p>
						
<p>Any issues or queries please let me know.</p>
						
Many thanks,<br />
Ellen<br />";
			
		//EMAIL.....!		
		sendEmail($user->user_email, $user->first_name, 'New Invoice Added', $msg);
	}
	
	wp_safe_redirect('/admin/accounts/'); exit;
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
	                
	                <?php //$users = get_users( [ 'role__in' => [ 'contributor', 'editor' ] ] ); //debug_array($users); ?>
	                
	                <div class="row">
	                	<div class="col-md-12 hidden-xs hidden-sm">
	                		<div class="panel-group" role="tablist">
	                			<div class="panel panel-default">
	                				<div class="panel-heading" role="tab" id="collapseInvoicingHeading">
	                					<h4 class="panel-title">
	                						<a href="#collapseInvoicing" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseInvoicing">Bulk Invoicing</a>
	                					</h4>
	                				</div>
									<div id="collapseInvoicing" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="collapseInvoicingHeading">
										<div class="panel-body">
											<div class="row">
												<div class="col-xs-12">
													<form class="form-horizontal" role="form" autocomplete="off" method="post" enctype="multipart/form-data">
														<div class="form-group">
															<label for="date_in" class="sr-only">Date</label>
															<div class="input-group col-md-2 date">
																<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
																<input type="text" id="date_in" name="date_in" class="form-control" placeholder="Date" />
															</div>														
															<label for="category" class="sr-only">Category</label>
															<div class="col-md-3">
																<select class="form-control" id="category" name="category" title="Category">
																	<option value="" hidden>Category</option>
																	<option value="Flyball">Training Fees</option>
																	<option value="Entry Fees">Entry Fees</option>
																	<option value="Camping">Camping Fees</option>
																	<option value="Special Event">Special Event</option>
																	<option value="Bonus Ball">Bonus Ball</option>
																	<option value="Membership">Membership</option>
																	<option value="Mercahndise">Merchandise</option>
																</select>
															</div>
															<label for="event_desc" class="sr-only">Event</label>
															<div class="col-md-3">
																<input type="text" id="event_desc" name="event_desc" class="form-control" placeholder="Event/Description" />
															</div>
															
															<div class="col-md-3">
																<label class="btn btn-default btn-block" for="csv_file" id="upload-file-btn">
																	<input id="csv_file" name="csv_file" type="file" style="display:none" onchange="jQuery('#upload-file-label').html(this.files[0].name);jQuery('#upload-file-btn').addClass('btn-success');">
																	<span id="upload-file-label">Choose File</span>
																</label>
																<!-- <span class='label label-info' id="upload-file-info"></span> -->
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-block btn-primary" id="add_invoices" name="add_invoices" value="yes">Go!</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
	                			</div>
	                		</div>			
						</div>	                
	                </div>
	                
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
		$("#date_in").datepicker({
			format : 'dd/mm/yyyy',
			autoclose : true,
			todayHighlight : true,
			todayBtn : true,
			zIndexOffset : 2000
		});
    });
</script>

