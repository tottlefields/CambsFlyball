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

	$awards = get_cft_dog_awards(get_the_ID());
	$award_dates = array();
	if (count($awards) > 0){
		foreach ($awards as $award){
			$award_dates[$award->race_date]  = $award->award;
		}
	}
?>
<article>
	<header class="entry-header">
		<!-- <hgroup> -->
			<h1><?php the_title(); ?>
			
			<?php $kc_name = get_post_meta( get_the_ID() , 'kc_name', true ); ?>
			<?php if ( isset($kc_name) && $kc_name != '' ) : ?>
				<div class="pull-right hidden-xs"><small><em><?php echo $kc_name; ?></em></small></div>
			<?php endif;?>
			<h1>
			
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
		<!-- </hgroup> -->
	</header>
	
	<div class="entry-content">
		<div class="row dog_details">
			<div class="hidden-sm col-xs-6 col-sm-4 col-md-3"><?php the_post_thumbnail('thumbnail', ['class' => 'img-responsive responsive--full pull-left', 'title' => the_title_attribute( 'echo=0' ) ]); ?></div>
			<div class="hidden-xs col-sm-8 col-md-6 col-lg-4">
				<div class="row">
					<form class="form-horizontal">
					
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">Handled By</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><?php echo get_field('handler')->user_firstname; ?></p></div>
					  </div>
					  
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">Breed</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><?php echo get_the_terms( get_the_ID(), 'dog-breeds')[0]->name; ?></p></div>
					  </div>
					  
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">DOB</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><?php echo get_field('date_of_birth'); ?></p></div>
					  </div>
					  
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">First Comp</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><?php echo get_field('date_first_comp'); ?></p></div>
					  </div>
					  
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">Team</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static">
					    <?php 
					    $team = get_field('team');
					    if (isset($team) && $team != ''){
					    	echo '<a href="'.get_permalink( $team->ID ).'">'.$team->post_title.'</a>';
					    }
					    elseif (get_field('retired') && get_field('retired') == 1){
					    	echo '<span class="text-muted"><em>Retired ('.get_field('date_retired').')</em></span>';
					    }
					    else{
					    	echo '&nbsp;';
					    } ?></p></div>
					  </div>
					  
					  <?php 
					  $ukfl_no = get_field('ukfl_number');
					  if (isset($ukfl_no) && $ukfl_no != ''){ ?>
					  
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">UKFL No</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><a href="https://www.ukflyball.org.uk/dogs/<?php echo $ukfl_no; ?>" title="<?php echo esc_attr( sprintf( __( 'UKFL Page for %s', 'openstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" target="_blank"><?php echo $ukfl_no;?></a></p></div>
					  </div>
					  
					  <?php 
					  $ukfl_height = get_field('ukfl_height');
					  if ($ukfl_height == 12){ $ukfl_height = 'FH'; }
					  elseif ($ukfl_height == 0){ $ukfl_height = '&nbsp'; }
					  else{ $ukfl_height = $ukfl_height.'"'; }
					  ?>
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">UKFL Height</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><?php echo $ukfl_height; ?></p></div>
					  </div>
					  
					  <?php $ukfl_points = get_field('ukfl_points');?>
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">UKFL Points</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><?php echo number_format($ukfl_points, 0); ?></p></div>
					  </div>
					  					  
					  <?php } ?>
					  
					  <?php $pb_time = get_field('fastest_time');
					  if ($pb_time > 0){ ?>
					  <div class="form-group">
					    <label class="col-sm-6 col-md-4 control-label">PB Time</label>
					    <div class="col-sm-6 col-md-8"><p class="form-control-static"><?php echo number_format($pb_time, 2); ?>s (<?php echo get_field('date_fastest_time'); ?>)</p></div>
					  </div>
					  <?php } ?>
					  
					</form>
				</div>
			</div>
			<div class="visible-lg col-lg-2">
				<div class="row">
					<div class="col-md-12">
						<strong><small>Awards Gained</small></strong><small>
						<?php						
						if (count($awards) >0){ ?>
						<ul style="padding-inline-start:15px;">
						<?php foreach ($awards as $award){ ?>
							<li><?php echo $award->award; ?> <small>(<?php echo $award->formatted_date; ?>)</small></li>
						<?php } ?>
						</ul>
						<?php } else { ?>
						<p class="text-muted"><em>No awards earned yet.</em></p>
						<?php } ?>
					</small></div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-4 col-md-3">
			<?php $pic2 = get_post_meta( get_the_ID() , 'flyball_photo', true );
			if ( isset($pic2) && $pic2 > 0 ) :
				echo wp_get_attachment_image( $pic2, 'thumbnail', "", array( "class" => "img-responsive responsive--full pull-right" ) );
			endif; ?>
			</div>
		</div>
		<div class="row dog_details visible-xs">
			<div class="col-xs-12">
				<form class="form-horizontal">
					
					  <div class="form-group">
					    <label class="col-xs-6 control-label">Handled By</label>
					    <div class="col-xs-6"><p class="form-control-static"><?php echo get_field('handler')->user_firstname; ?></p></div>
					  </div>
					  
					  <div class="form-group">
					    <label class="col-xs-6 control-label">Breed</label>
					    <div class="col-xs-6"><p class="form-control-static"><?php echo get_the_terms( get_the_ID(), 'dog-breeds')[0]->name; ?></p></div>
					  </div>
					  
					  <!-- <div class="form-group">
					    <label class="col-xs-6 control-label">DOB</label>
					    <div class="col-xs-6"><p class="form-control-static"><?php echo get_field('date_of_birth'); ?></p></div>
					  </div>
					  
					  <div class="form-group">
					    <label class="col-xs-6 control-label">First Comp</label>
					    <div class="col-xs-6"><p class="form-control-static"><?php echo get_field('date_first_comp'); ?></p></div>
					  </div> -->
					  
					  <div class="form-group">
					    <label class="col-xs-6 control-label">Team</label>
					    <div class="col-xs-6"><p class="form-control-static">
					    <?php 
					    $team = get_field('team');
					    if (isset($team) && $team != ''){
					    	$team_name = $team->post_title;
					    	$team_name = str_ireplace('Cambridgeshire', '', $team_name);
					    	$team_name = str_ireplace('Tottlefields', '', $team_name);
					    	echo '<a href="'.get_permalink( $team->ID ).'">'.$team_name.'</a>';
					    }
					    elseif (get_field('retired') && get_field('retired') == 1){
					    	echo '<span class="text-muted"><em>Retired ('.get_field('date_retired').')</em></span>';
					    }
					    else{
					    	echo '&nbsp;';
					    } ?></p></div>
					  </div>
					  
					  <?php 
					  $ukfl_no = get_field('ukfl_number');
					  if (isset($ukfl_no) && $ukfl_no != ''){ ?>
					  
					  <div class="form-group">
					    <label class="col-xs-6 control-label">UKFL No</label>
					    <div class="col-xs-6"><p class="form-control-static"><a href="https://www.ukflyball.org.uk/dogs/<?php echo $ukfl_no; ?>" title="<?php echo esc_attr( sprintf( __( 'UKFL Page for %s', 'openstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" target="_blank"><?php echo $ukfl_no;?></a></p></div>
					  </div>
					  
					  <?php 
					  $ukfl_height = get_field('ukfl_height');
					  if ($ukfl_height == 12){ $ukfl_height = 'FH'; }
					  elseif ($ukfl_height == 0){ $ukfl_height = '&nbsp'; }
					  else{ $ukfl_height = $ukfl_height.'"'; }
					  ?>
					  <div class="form-group">
					    <label class="col-xs-6 control-label">UKFL Height</label>
					    <div class="col-xs-6"><p class="form-control-static"><?php echo $ukfl_height; ?></p></div>
					  </div>
					  
					  <?php $ukfl_points = get_field('ukfl_points');?>
					  <div class="form-group">
					    <label class="col-xs-6 control-label">UKFL Points</label>
					    <div class="col-xs-6"><p class="form-control-static"><?php echo number_format($ukfl_points, 0); ?></p></div>
					  </div>
					  					  
					  <?php } ?>
					  
					  <?php $pb_time = get_field('fastest_time');
					  if ($pb_time > 0){ ?>
					  <div class="form-group">
					    <label class="col-xs-6 control-label">PB Time</label>
					    <div class="col-xs-6"><p class="form-control-static"><?php echo number_format($pb_time, 2); ?>s (<?php echo get_field('date_fastest_time'); ?>)</p></div>
					  </div>
					  <?php } ?>
				
				
				</form>
			</div>
		</div>

		<hr class="post-meta-hr"/>	

		<?php // the_content(); ?>

		<?php
		//debug_array($award_dates);
		$stats = get_stats_for_dog(get_the_ID(), get_the_title());
		if (count($stats) > 0){
			echo "<h3>".get_the_title()."'s Results</h3>";
			//debug_array($stats[0]);
			echo '<small><table class="table table-condensed">';
			echo '<tr>
				<th class="text-center">Event</th>
				<th class="text-center">Team</th>
				<th class="text-center hidden-xs hidden-sm">Heats Raced</th>
				<th class="text-center">FT</th>
				<th class="text-center hidden-xs hidden-sm">AVT</th>
				<th class="text-center hidden-xs">Consistency (%)</th>
				<th class="text-center">Points</th>
				<th class="text-center hidden-xs hidden-sm">Award</th>
      </tr>';
			foreach ($stats as $row){
				$race_date = DateTime::createFromFormat('Ymd', $row->race_date);
				$award = (isset($award_dates[$row->race_date])) ? '<span class="label label-primary">'.$award_dates[$row->race_date].'</span>' : '&nbsp;';
				echo '<tr>';
				echo '<td class="text-center"><a href="/'.$row->slug.'">'.$row->event_title.'</a><span class="hidden-xs"><br /><em>'.$race_date->format('jS F Y').'</em></span></td>';
				echo '<td class="text-center">'.$row->team.'<span class="hidden-xs"><br /><em>'.$row->place.getOrdinal($row->place);
				if (isset($row->division)){ echo ' ('.$row->division.')' ;} echo '</em></span></td>';
				echo '<td class="text-center hidden-xs hidden-sm">'.$row->heats.'</td>';
				echo '<td class="text-center">'.$row->fastest_time.'</td>';
				echo '<td class="text-center hidden-xs hidden-sm">'.$row->average_time.'</td>';
				echo '<td class="text-center hidden-xs">'.$row->consistency.'</td>';
				echo '<td class="text-center">'.$row->points.'</td>';
				echo '<td class="text-center hidden-xs hidden-sm">'.$award.'</td>';
				echo '</tr>';
			}

			echo '</table></small>';
		}
		?>
	
	
	</div><!-- .entry-content -->	
</article>
