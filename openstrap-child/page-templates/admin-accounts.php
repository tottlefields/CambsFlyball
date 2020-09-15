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
	debug_array($_FILES);
	debug_array($_REQUEST);
	wp_die();
	//addPayment($_REQUEST['date_in'], $_REQUEST['amount'], $_REQUEST['user_id'], $_REQUEST['method'], $_REQUEST['description']);
	//wp_safe_redirect('/admin/club-members/'); exit;
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
																	<option value="Camping Fees">Camping Fees</option>
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
																<label class="btn btn-default btn-block" for="my-file-selector" id="upload-file-btn">
																	<input id="my-file-selector" type="file" style="display:none" onchange="jQuery('#upload-file-label').html(this.files[0].name);jQuery('#upload-file-btn').addClass('btn-success');">
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
	                	<thead><tr><th>Date</th><th>Method</th><th >Description</th><th class="text-right">Amount</th></tr></thead><tbody>
						<?php foreach ($transactions as $row){ ?>
							<tr>
								<td style="white-space:nowrap;"><?php echo SQLToDate($row->date_in, 'd-M'); ?></td>
								<td><?php echo $row->method; ?></td>
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

