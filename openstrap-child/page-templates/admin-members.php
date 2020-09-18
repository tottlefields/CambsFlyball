<?php
/**
 * Template Name: Admin Members
 *
 * Page template for
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */

if (isset($_REQUEST) && $_REQUEST['add_income'] != ''){
	addPayment($_REQUEST['date_in'], $_REQUEST['amount'], $_REQUEST['user_id'], $_REQUEST['method'], $_REQUEST['description']);
	wp_safe_redirect('/admin/club-members/'); exit;
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
	                
	                <?php $users = get_users( [ 'role__in' => [ 'author', 'editor' ] ] ); //debug_array($users); ?>
	                
	                <div class="row">
	                	<div class="col-md-12 hidden-xs hidden-sm">
	                		<div class="panel-group" role="tablist">
	                			<div class="panel panel-default">
	                				<div class="panel-heading" role="tab" id="collapseIncomeHeading">
	                					<h4 class="panel-title">
	                						<a href="#collapseIncome" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseIncome">Add Individual Income</a>
	                					</h4>
	                				</div>
									<div id="collapseIncome" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="collapseIncomeHeading">
										<div class="panel-body">
											<div class="row">
												<div class="col-xs-12">
													<form class="form-horizontal" role="form" autocomplete="off" method="post">
														<div class="form-group">
															<label for="date_in" class="sr-only">Date</label>
															<div class="input-group col-md-2 date">
																<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
																<input type="text" id="date_in" name="date_in" class="form-control" placeholder="Date" />
															</div>													
															<label for="user_id" class="sr-only">Member</label>
															<div class="col-md-3">
																<select class="form-control  id="user_id" name="user_id" title="Member">
																	<option value="" hidden>Member</option>
																	<?php foreach ($users as $user) {
																		echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
																	} ?>
																</select>
															</div>															
															<label for="method" class="sr-only">Method</label>
															<div class="col-md-2">
																<select class="form-control" id="method" name="method" title="Method">
																	<option value="" hidden>Method</option>
																	<option value="DEPOSIT">BACS</option>
																	<option value="CONTRA">Contra</option>
																	<option value="CASH">Cash</option>
																	<option value="CHQ">Cheque</option>
																	<!-- <option value="PAYPAL">PayPal</option> -->
																</select>
															</div>															
															<!-- <label for="category" class="sr-only">Category</label>
															<div class="col-md-2">
																<select class="form-control" id="category" name="category" title="Category">
																	<option value="" hidden>Category</option>
																	<option value="Flyball">Flyball</option>
																</select>
															</div> -->
															<label for="description" class="sr-only">Description</label>
															<div class="col-md-2">
																<input type="text" id="description" name="description" class="form-control" placeholder="Description" />
															</div>	
															<label for="amount" class="sr-only">Amount</label>
															<div class="input-group col-md-2">
																<span class="input-group-addon"><i class="fas fa-pound-sign"></i></span>
																<input type="text" id="amount" name="amount" class="form-control" placeholder="Amount" />
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-block btn-primary" id="add_income" name="add_income" value="yes">Go!</button>
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
	                
	                <table class="table">
	                	<thead><tr><th>Name</th><th class="text-center hidden-xs">Invoices</th><th class="text-center hidden-xs">Payments</th><th class="text-center">Balance</th></tr></thead><tbody>
	                <?php
	                foreach ($users as $user){
	                	$money = get_members_money($user);
	                	$balance = ($money['balance'] > 0) ? '<span class="text-danger"><strong>&pound;'.number_format($money['balance'], 2).'</strong></span>' : '<span class="text-success">&pound;'.number_format(-1*$money['balance'], 2).'</span>';
	                	echo '
						<tr>
							<td><a href="/members-only/account/?cft-member='.$user->user_login.'">'.$user->display_name.'</a></td>
							<td class="text-center hidden-xs">&pound;'.number_format($money['invoices'], 2).'</td>
							<td class="text-center hidden-xs">&pound;'.number_format($money['payments'], 2).'</td>
							<td class="text-center">'.$balance.'</td>
						</tr>';
	                }
	                ?>
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

