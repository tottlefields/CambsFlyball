<?php
/**
 * Content Single
 *
 * Loop content in single post template (single.php)
 *
 * @package Openstrap
 * @subpackage Openstrap
 * @since Openstrap 0.1
 */
?>
<?php 
	$display_post_meta_info = of_get_option('display_post_meta_info');
	$display_post_page_nav = of_get_option('display_post_page_nav');
?>

<?php
$start_date = DateTime::createFromFormat('Ymd', get_post_meta( get_the_ID(), 'start_date', true ));
$end_date   = DateTime::createFromFormat('Ymd', get_post_meta( get_the_ID(), 'end_date', true ));
$dates = $start_date->format('jS M');
if (isset($end_date) && $end_date != '' && $start_date != $end_date){ $dates .= ' to '.$end_date->format('jS M'); }

$team_posts = get_posts(array(
  'post_status'     => array('publish'),
	'post_type' 			=> 'cft_team',
  'posts_per_page'  => -1,
  'order'           => 'ASC',
  'meta_key'        => 'sort_order',
  'orderby'         => 'meta_value_num',
));
$teams = array();
$max_dogs = 6;
foreach($team_posts as $team){
	$dogs = get_dogs_for_team($team->ID);
	if (count($dogs)>0){ 
		if (count($dogs) > $max_dogs) { $max_dogs = count($dogs); }
		$terms = get_the_terms( $team->ID, 'team-type' ); 
		$team->dogs = $dogs;
		$team->team_type = $terms[0];
		array_push($teams, $team);
	}
}

?>

<article>
	<header class="entry-header">
		<hgroup>
			<h1><?php the_title(); ?>
				<div class="pull-right hidden-xs">
					<small class="visible-sm-inline hidden-md hidden-lg"><?php echo $start_date->format('jS M'); ?></small>
          <small class="hidden-sm visible-md-inline visible-lg-inline"><?php echo $dates; ?></small>
        </div>
			</h1>		
			<?php if(!empty($display_post_meta_info)):?>		
			<div class="post-meta entry-header">
			
				<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
					<span class="sticky"><i class="icon-pushpin"></i> <span class="badge"><?php _e( 'Sticky', 'openstrap' ); ?> </span></span>
				<?php endif; ?>
			
				<?php
						printf( __( '<span class="post_date"><i class="icon-calendar"></i> %2$s by %3$s', 'openstrap' ),'meta-prep meta-prep-author',
						sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a></span>',
						get_permalink(),
						esc_attr( get_the_time() ),
						get_the_date()
						),
						sprintf( '<a class="url fn n" href="%1$s" title="%2$s">%3$s</a>',
						get_author_posts_url( get_the_author_meta( 'ID' ) ),
						sprintf( esc_attr__( 'View all posts by %s', 'openstrap' ), get_the_author() ),
						get_the_author()
						)
						);
					?>     
				<div class="pull-right postcomments">					
					<span class="post_comment"><i class="icon-comments"></i>
					<a href="<?php the_permalink() ?>#comments"><?php comments_number(__('No comments', 'openstrap'),__('One comment','openstrap'),__('% comments','openstrap')); ?></a></span>
				</div>				
			</div> 
		<?php endif;?>
		<hr class="post-meta-hr"/>			
		</hgroup>
	</header>
	
	<?php if (DateTime::createFromFormat('Ymd', get_post_meta( get_the_ID(), 'end_date', true ), $tz)->format('Ymd') <= date('Ymd')) { ?>
	<div class="entry-content">
	<?php the_content(); ?>
	</div><!-- .entry-content -->	
	<hr/>
	<div class="row">
		<div class="col-xs-12">
			<h3>Results</h3>
		</div>
	</div>
	<?php } else { 
		$diary_details = array();
		$diary_records = $wpdb->get_results("select dog_id, event_id, event_date, status from cft_events_diary where status<>'' and event_id=".get_the_ID());
		foreach ($diary_records as $record){
			if (!isset($diary_details[$record->event_date])){ $diary_details[$record->event_date] = array(); }
			if (!isset($diary_details[$record->event_date][$record->dog_id])){ $diary_details[$record->event_date][$record->dog_id]= $record->status; }
		}


		if (get_post_meta( get_the_ID(), 'w3w', true ) != '') { ?>
		<what3words-address words="<?php echo str_replace('///', '', get_post_meta( get_the_ID(), 'w3w', true )); ?>" tooltip-location="event location" ></what3words-address>
		<?php } ?>
		<hr/>
	<?php 
		if (isset($end_date) && $end_date != ''){ //} && $start_date != $end_date){ 
			//$dates .= ' to '.$end_date->format('jS M'); 
			for($d = $start_date; $d <= $end_date; $d->modify('+1 day')){
				echo '<h3>'.$d->format('l').'</h3>';
				//debug_array($diary_details[$d->format('Ymd')]);	
				echo '<table class="table">';
				foreach ($teams as $team){
					$dog_count = 0;
					$label = '<span class="label label-primary">'.$team->team_type->name.'</span>';
					if ($team->team_type->slug == 'little-league'){ $label = '<span class="label label-info">'.$team->team_type->name.'</span>'; }
					if ($team->team_type->slug == 'pre-cadets'){ $label = '<span class="label label-default">'.$team->team_type->name.'</span>'; }
					echo '<tr><th>'.$team->post_title.'&nbsp;'.$label.'</th>';
					for ($i=0; $i<$max_dogs; $i++){
						if (isset($team->dogs[$i])){
							$dog = $team->dogs[$i];
							$class = "text-center";
							if ($diary_details[$d->format('Ymd')][$dog->ID] == "Y"){ $class .= ' success text-success"'; $dog_count++; }
							if ($diary_details[$d->format('Ymd')][$dog->ID] == "N"){ $class .= ' danger text-danger"';}
							$diary_details[$d->format('Ymd')][$dog->ID] = 1;
							echo '<td class="'.$class.'" width="10%">'.$dog->post_title.'</td>';
						} else {
							echo '<td width="10%">&nbsp;</td>';
						}
					}
					echo '<th width="5%" class="text-right">'.$dog_count.'</th>';

					echo "</tr>";
				}
				foreach ($diary_details[$d->format('Ymd')] as $dog_id => $notSeen){
					if (!$notSeen){
						//TODO : Add an "other" row...?
						debug_array($diary_details[$d->format('Ymd')]);
						break;						
					}
				}
				echo "</table>";
			}
		}
	} ?>
	<footer class="entry-meta">					
		<p><?php wp_link_pages(); ?></p>
		<hr/>
	
		<?php get_template_part('author-box'); ?>		
		
		<?php comments_template( '', true ); ?>
	</footer>

</article>
