<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	<header>
		<hgroup>
			<h3>
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'openstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				<?php $fastest_time = get_post_meta( get_the_ID() , 'fastest_time', true );
				if ( isset($fastest_time) && $fastest_time >0 ) : ?>
				<div class="pull-right hidden-xs">
					<small class="visible-sm"><strong><?php echo $fastest_time; ?></strong></small>
					<small class="hidden-sm">Fastest recorded time is <strong><?php echo $fastest_time; ?></strong></small>
				</div>
				<?php endif;?>
			</h3>
			<hr class="post-meta-hr"/>
		</hgroup>
		
		<?php $dogs = get_dogs_for_team(get_the_ID());
		$results = get_results_for_team(get_the_ID());
		if (count($dogs)>0){ ?>
		<h5>Dogs in this team -<?php foreach ($dogs as $dog){ echo ' <a href="'.get_permalink( $dog->ID ).'">'.$dog->post_title.'</a>'; }?></h5>
		<?php } else { ?>
		<h5><em>This team is not currently active or racing.</em></h5>
		<?php } ?>
		<div class="row">
			<div class="hidden-xs col-sm-12 col-md-5">
				<div class="row">
				<?php foreach ($dogs as $dog){ 				
					$pic2 = get_post_meta( $dog->ID , 'flyball_photo', true );?>
					<div class="col-sm-2 col-md-4" style="padding-bottom:15px">
						<a href="<?php echo get_permalink( $dog->ID ); ?>" class="th" title="<?php echo $dog->post_title; ?>" >
						<?php echo wp_get_attachment_image( $pic2, 'thumbnail', "", array( "class" => "wp-post-image img-responsive responsive--full" ) ); ?></a>
					</div>
				<?php } ?>
				</div>
			</div>
			
			<div class="col-xs-12 col-sm-12 col-md-7">
			<?php if (count($results) > 0){ 
				echo '<small><table class="table table-condensed">
				<tr>
					<th style="background-color:#E5E5E5" class="text-center">Date</div></th>
					<th style="background-color:#E5E5E5" class="text-center">Event</div></th>
					<th style="background-color:#E5E5E5" class="text-center">Div (Seed)</div></th>
					<th style="background-color:#E5E5E5" class="text-center">Place</div></th>
					<th style="background-color:#E5E5E5" class="text-center">F/T</div></th>
				</tr>';
				foreach ($results as $row){
					$race_date = DateTime::createFromFormat('Ymd', $row->race_date);
					echo '<tr>
						<td class="text-center">'.$race_date->format('jS F Y').'</div></td>
						<td class="text-center"><a href="/'.$row->slug.'">'.$row->event_title.'</a></div></td>
						<td class="text-center">'.$row->division.' ('.$row->seed.getOrdinal($row->seed).')</td>';
					if ($row->place == 1){  echo '<td class="text-center"><span class="text-primary"><strong>1st</strong></span></td>'; }
					else { echo '<td class="text-center">'.$row->place.getOrdinal($row->place).'</td>'; }
					if ($row->new_fastest == 1){ echo '<td class="text-center"><span class="text-primary"><strong>'.$row->fastest_time.'</strong></span></td>'; }
					else { echo '<td class="text-center">'.$row->fastest_time.'</td>'; }
					echo '</tr>';
				}
				echo '</table></small>';
				
				//debug_array($results); 
				} ?>			
			</div>
			
			
			
		</div>
	</header>
</article>