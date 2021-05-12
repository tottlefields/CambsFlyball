<?php
/**
 * Template Name: Events Diary
 *
 * Page template for
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */


global $wpdb, $current_user;
if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }

$page_title = get_the_title();

//$user = $current_user;
$tz  = new DateTimeZone('Europe/London');
/*$username = urldecode($wp_query->query_vars['cft-member']);
if (isset($username) && $username != ''){
	if (current_user_can('administrator') || current_user_can('editor')){
		$user = get_user_by( 'login', $username );
		$page_title = 'Account for '.$user->display_name;
	}
	else {
		wp_safe_redirect('/members-only/account/'); exit;
	}
} */


$comps = get_posts(array(
  'post_status'     => array('future'),
  'posts_per_page'  => 8,
  'category'        => 16,
  'order'           => 'ASC',
  'meta_key'        => 'start_date',
  'orderby'         => 'meta_value_num',
  'meta_query'      => array(
    array('key' => 'start_date', 'value' => date('Ymd'), 'compare' => '>=')
  )
));

$myDogs = array();
$otherDogs = array();
$group = get_user_meta($current_user->ID, 'household_group', true);
if (isset($group) && $group != ''){	
	$myDogs = get_posts(array(
		'post_status'     => array('publish'),
		'post_type'				=> 'cft_dog',
		'posts_per_page'	=> -1,
		'orderby'					=> 'title',
		'order'						=> 'ASC',
		'meta_query'			=> array(
			array('key' => 'household_group', 'value' => $group)
		)
	));

	$otherDogs = get_posts(array(
		'post_status'     => array('publish'),
		'post_type'				=> 'cft_dog',
		'posts_per_page'	=> -1,
		'orderby'					=> 'title',
		'order'						=> 'ASC',
		'meta_query'			=> array(
			'relation' => 'OR',
			array('key' => 'household_group', 'value' => $group, 'compare' => '!='),
			array('key' => 'household_group', 'value' => $group, 'compare' => 'NOT EXISTS'),
		)		
	));

} else {
	$myDogs = get_posts(array(
		'post_status'     => array('publish'),
		'post_type'				=> 'cft_dog',
		'posts_per_page'	=> -1,
		'orderby'					=> 'title',
		'order'						=> 'ASC',
		'author'					=> $current_user->ID
	));

	$otherDogs = get_posts(array(
		'post_status'     => array('publish'),
		'post_type'				=> 'cft_dog',
		'posts_per_page'	=> -1,
		'orderby'					=> 'title',
		'order'						=> 'ASC',
		'author__not_in'	=> array($current_user->ID)
	));
}

$dogs = array_merge($myDogs, $otherDogs);

//debug_array($dogs);

get_header();

?>

	<!-- Main Content -->	
	<div class="col-md-12" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    <header class="entry-header">
				<h1 class="entry-title"><?php echo $page_title; ?></h1>
			</header>
			
			<div class="entry-content">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<td rowspan="2">&nbsp;</td>
					<?php
					$dates = array();
					foreach($comps as $comp) : setup_postdata($comp); 
						$start_date = DateTime::createFromFormat('Ymd', get_post_meta( $comp->ID, 'start_date', true ), $tz);
						$end_date   = DateTime::createFromFormat('Ymd', get_post_meta( $comp->ID, 'end_date', true ), $tz);
						//$dates = $start_date->format('jS M');
						$compDates = array();
						if (isset($end_date) && $end_date != ''){ //} && $start_date != $end_date){ 
							//$dates .= ' to '.$end_date->format('jS M'); 
							for($d = $start_date; $d <= $end_date; $d->modify('+1 day')){
								array_push($compDates, $d->format('Ymd'));
							}
						}
						array_push($dates, $compDates);
						//debug_array($dates);
					?>
										<th class="text-center" colspan="<?php echo count($compDates); ?>"><?php echo $comp->post_title; ?></th>
						<?php endforeach;?>
									</tr>
									<tr>
						<?php 
						$colspan = 0;
						foreach ($dates as $array){
							foreach ($array as $date){
								$colspan++;
								echo '<th class="text-center">'.DateTime::createFromFormat('Ymd', $date)->format('jS M').'</th>';
							}
						} ?>
									</tr>
						<?php foreach ($dogs as $dog){
								$dob = DateTime::createFromFormat('Ymd', get_post_meta( $dog->ID, 'date_of_birth', true ), $tz);
								$age = $dob->diff(new DateTime('now', $tz))->y;
								$ageOK = ($age > 0) ? 1 : 0;

								//if ($dog->post_author == $current_user->ID){
								if (in_array($dog, $myDogs)){
									echo '<tr><td class="danger"><strong>'.$dog->post_title.'</strong></td>';
								}else{
									echo '<tr><td>'.$dog->post_title.'</td>';
								}
								foreach ($dates as $array){
									foreach ($array as $date){
										if (!$ageOK){
											$age = $dob->diff(DateTime::createFromFormat('Ymd', $date))->y;
											$ageOK = ($age > 0) ? 1 : 0;
										}
										if ($ageOK){
											echo '<td>&nbsp;</td>';
										} else {
											echo '<td class="active">&nbsp;</td>';
										}
									}
								}
								echo '</tr>';
						} ?>

								</thead>
							</table>
						</div>		
					</div>
				</div>


			<?php //debug_array($comps); ?>
			
			<!--div class="row">
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
			</div> -->
	                
	        </div><!-- .entry-content -->
		</article><!-- #post -->
	
	</div>	
	<!-- End Main Content -->	


<?php get_footer(); ?>

