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
$today = new DateTime('now', $tz);


$comps = get_posts(array(
  'post_status'     => array('publish', 'future'),
  'posts_per_page'  => 10,
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

$diary_details = array();
$diary_records = $wpdb->get_results("select dog_id, event_id, event_date, status from cft_events_diary where event_date >=date(NOW())");
foreach ($diary_records as $record){
	if (!isset($diary_details[$record->event_id])){ $diary_details[$record->event_id] = array(); }
	if (!isset($diary_details[$record->event_id][$record->event_date])){ $diary_details[$record->event_id][$record->event_date] = array(); }
	if (!isset($diary_details[$record->event_id][$record->event_date][$record->dog_id])){ $diary_details[$record->event_id][$record->event_date][$record->dog_id]= $record->status; }
}
//debug_array($diary_details);

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
					<div class="col-xs-12"><?php the_content(); ?></div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive" style="font-size:0.9em">
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
						$dates[$comp->ID] = $compDates;
						//array_push($dates, $compDates);
						//debug_array($dates);
					?>
										<th class="text-center" colspan="<?php echo count($compDates); ?>"><a href="<?php echo get_permalink($comp); ?>"><?php echo $comp->post_title; ?></a>
						<?php if (get_post_meta( $comp->ID, 'w3w', true ) != '') { 
							echo '<br /><a href="https://map.what3words.com/'.str_replace('///', '', get_post_meta( $comp->ID, 'w3w', true )).'">
								<img src="'.get_stylesheet_directory_uri().'/assets/images/w3w.png" style="width:25px;box-shadow: none;" />
							</a>';
						} ?>
									</th>
						<?php endforeach;?>
									</tr>
									<tr>
						<?php 
						$colspan = 0;
						foreach ($dates as $compID => $array){
							foreach ($array as $date){
								$colspan++;
								echo '<th class="text-center" width="5%">'.DateTime::createFromFormat('Ymd', $date)->format('jS M').'</th>';
							}
						} ?>
									</tr>
						<?php foreach ($dogs as $dog){
								if (get_post_meta( $dog->ID, 'member', true ) != 1){ continue; }

								$dob = DateTime::createFromFormat('Ymd', get_post_meta( $dog->ID, 'date_of_birth', true ), $tz);
								$age = $dob->diff(new DateTime('now', $tz))->y;
								$ageOK = ($age > 0) ? 1 : 0;

								//if ($dog->post_author == $current_user->ID){
								if (in_array($dog, $myDogs)){
									echo '<tr><td class="danger"><strong>'.$dog->post_title.'</strong></td>';
								}else{
									echo '<tr><td>'.$dog->post_title.'</td>';
								}
								foreach ($dates as $compID => $array){
									$editable = 0;
									if (in_array($dog, $myDogs)){
										$editable = ($today->diff(DateTime::createFromFormat('Ymd', $array[0]))->format('%r%a') > 70) ? 1 : 0;
									}
									if (current_user_can('administrator')){ $editable = 1; }
									
									foreach ($array as $date){
										if (!$ageOK){
											$age = $dob->diff(DateTime::createFromFormat('Ymd', $date))->y;
											$ageOK = ($age > 0) ? 1 : 0;
										}
										if ($ageOK){
											$status = (isset($diary_details[$compID][$date][$dog->ID])) ? $diary_details[$compID][$date][$dog->ID] : '?';
											if ($editable){
												echo '<td data-dog="'.$dog->ID.'" data-event="'.$compID.'" data-date="'.$date.'" data-status="'.$status.'" class="text-center diary-click';
												if ($status == "Y"){
													echo ' success text-success"><i class="fa fa-check" aria-hidden="true"></i>';
												}
												else if ($status == "N"){
													echo ' danger text-danger"><i class="fa fa-times" aria-hidden="true"></i>';
												}
												else if ($status == "?" || $status == "" || !isset($status)){
													echo '">&nbsp;';
												}
												echo '</td>';
											} else {
												echo '<td class="text-center diary-noclick';
												if ($status == "Y"){
													echo ' success text-success"><i class="fa fa-check" aria-hidden="true"></i>';
												}
												else if ($status == "N"){
													echo ' danger text-danger"><i class="fa fa-times" aria-hidden="true"></i>';
												}
												else if ($status == "?" || $status == "" || !isset($status)){
													echo '">&nbsp;';
												}												
												echo '</td>';
											}
										} else {
											echo '<td class="active text-center diary-noclick"><i class="fa fa-times" aria-hidden="true"></i></td>';
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
	                
	        </div><!-- .entry-content -->
		</article><!-- #post -->
	
	</div>	
	<!-- End Main Content -->	


<?php get_footer(); ?>

