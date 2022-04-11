<?php
/**
 * Template Name: My Account
 *
 * Page template for
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */


global $wpdb, $current_user;
if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }

$page_title = get_the_title();

$username = urldecode($wp_query->query_vars['cft-member']);
$user = $current_user;
if (isset($username) && $username != ''){
	if (current_user_can('administrator') || current_user_can('editor')){
		$user = get_user_by( 'login', $username );
		$page_title = 'Account for '.$user->display_name;
	}
	else {
		wp_safe_redirect('/members-only/account/'); exit;
	}
}

get_header();

?>

	<!-- Main Content -->	
	<div class="col-md-12" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	        <header class="entry-header">
	                <h1 class="entry-title"><?php echo $page_title; ?></h1>
	        </header>
	
	        <div class="entry-content">
	        	<div class="row"><div class="col-xs-12">
	        <?php   
		        	$money = get_members_money($user);
		        	if ($money['balance'] > 0) {
		        		echo '<div class="alert alert-danger" role="alert">You currently owe <span class="text-danger"><strong>&pound;'.number_format($money['balance'], 2).'</strong></span> to Cambridgeshire Flyball.<span class="pull-right"><a href="https://www.paypal.com/paypalme/cambsflyball" target="_blank"><img src="https://cambridgeshire-flyball.org.uk/wp-content/uploads/2021/11/paypal.png" style="height: 30px; margin-top: -5px;"></a></span></div>';
		        	} else {
		        		echo '<div class="alert alert-success" role="alert">You are currently <span class="text-success"><strong>&pound;'.number_format(-1*$money['balance'], 2).'</strong></span> in credit with Cambridgeshire Flyball.</div>';
	        		}
	        	?>
			</div></div>
			
			<div class="row">
				<div class="col-md-8">
					<div class="panel panel-default">
						<div class="panel-heading"><h3 class="panel-title">Money</h3></div>
						<div class="panel-body" style="font-size: 0.8em">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<?php for ($y = date('Y')-1; $y<= date('Y'); $y++) {?>
								<div class="panel panel-danger">
									<div class="panel-heading" role="tab" id="heading<?php echo $y; ?>">
										<h4 class="panel-title">
											<a<?php if (date('Y') != $y) { echo ' class="collapsed"'; }?> role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $y; ?>" aria-expanded="true" aria-controls="collapse<?php echo $y; ?>"><?php echo $y; ?></a>
										</h4>
									</div>
									<div id="collapse<?php echo $y; ?>" class="panel-collapse collapse<?php if (date('Y') == $y) { echo ' in'; }?>" role="tabpanel" aria-labelledby="heading<?php echo $y; ?>">
										<?php $balance = getBalanceByDate($y.'-01-01', $user); $transactions = getUserTransYear($y, $user);  $running_total = $balance;?>
										<table class="table table-condensed">
											<tr>
												<td>01-Jan</td>
												<td class="visible-xs">&nbsp;</td>
												<td colspan="2" class="hidden-xs text-muted"><em>Balance Carried Forward</em></td>
												<td>&nbsp;</td>
												<td class="text-right">
											<?php if ($balance < 0){
												echo '<span class="text-danger">-&pound;'.number_format(-1*$balance, 2).'</span>';
											} else{
												echo '<span class="text-success">&pound;'.number_format($balance, 2).'</span>';
											} ?>
											</td></tr>
											<?php foreach ($transactions as $row){ 
												$amount = '&pound;'.number_format($row->amount, 2);
												if ($row->method == 'INVOICE'){
													$running_total -= number_format($row->amount, 2);
													$amount = '-&pound;'.number_format($row->amount, 2);
												} else {
													$running_total += number_format($row->amount, 2);
												}
												
												if ($running_total < 0){
													$r_total = '<span class="text-danger">-&pound;'.number_format(-1*$running_total, 2).'</span>';
												} else {
													$r_total = '<span class="text-success">&pound;'.number_format($running_total, 2).'</span>';
												}
												
												?>
											<tr>
												<td style="white-space:nowrap;"><?php echo SQLToDate($row->date_in, 'd-M'); ?></td>
												<td><?php echo $row->method; ?></td>
                       							<td class="hidden-xs"><?php echo $row->description; ?></td>
												<td class="text-right"><?php echo $amount; ?></td>
												<td class="text-right"><?php echo $r_total; ?></td>
											</tr>
											<?php } ?>
										</table>
									</div>
								</div>
							<?php } ?>
							</div>
						</div>
					</div>				
				</div>
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading"><h3 class="panel-title">Dogs</h3></div>
						<div class="panel-body">
							<div class="row">
							<?php $dogs = get_dogs_for_user($user); ?>						
							<?php 
							while (list($i, $post) = each($dogs)) :
				    			setup_postdata($post); ?>
								<div class="col-xs-6 col-sm-3 col-md-6">
									<?php get_template_part( 'part-templates/content', get_post_type() ); ?>
								</div>
							
							<?php endwhile; wp_reset_postdata(); ?>
							</div>
						</div>
					</div>				
				</div>
			</div>
			
			<div class="row">				
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"><h3 class="panel-title">Competitions</h3></div>
						<div class="panel-body">


						</div>
					</div>
				</div>
			</div>
	                
	        </div><!-- .entry-content -->
		</article><!-- #post -->
	
	<?php openstrap_custom_pagination(); ?>
	</div>	
	<!-- End Main Content -->	


<?php get_footer(); ?>

