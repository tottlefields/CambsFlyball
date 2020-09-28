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
			
			
			</div>
			
			
			
		</div>
	</header>
</article>