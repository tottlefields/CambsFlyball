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
<article>
	<header class="entry-header">
		<!-- <hgroup> -->
			<h1><?php the_title(); ?>
			
			<?php $kc_name = get_post_meta( get_the_ID() , 'kc_name', true );
			if ( isset($kc_name) && $kc_name != '' ) : ?><div class="pull-right hidden-xs"><small><em>	 <?php echo $kc_name; ?></em></small></div>
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
		<div class="row" id="dog_details">
			<div class="col-xs-6 col-sm-4 col-md-3"><?php the_post_thumbnail('thumbnail', ['class' => 'img-responsive responsive--full pull-left', 'title' => the_title_attribute( 'echo=0' ) ]); ?></div>
			<div class="hidden-xs col-sm-4 col-md-4">
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
					    if (isset($team)){
					    	echo '<a href="'.get_permalink( $team->ID ).'">'.$team->post_title.'</a>';
					    }
					    else{
					    	echo '&nbsp;';
					    } ?></p></div>
					  </div>
					  
					  <?php 
					  $ukfl_no = get_field('ukfl_number');
					  if (isset($ukfl_no)){ ?>
					  
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
			<div class="hidden-xs hidden-sm col-md-2">
				<div class="row">
					<div class="col-md-12">
						<strong><small>Awards Gained</small></strong><small>
						<ul>
						<li>FD (19/03/2011)</li>
						</ul>
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
			
	</div>
	<?php the_content(); ?>
	
	
	
	
	</div><!-- .entry-content -->	
	<footer class="entry-meta">					
		<p><?php wp_link_pages(); ?></p>
		<hr/>
		<?php if(!empty($display_post_page_nav)):?>
		<div class="panel panel-default">
		  <div class="panel-heading">
		 
			<nav class="nav-single">
				<div class="row">	
					<div class="col-md-6">
						<span class="nav-previous pull-left"><?php previous_post_link( '%link', '<i class="icon-arrow-left"></i> %title' ); ?></span>
					</div>	
					<div class="col-md-6">
						<span class="nav-next pull-right"><?php next_post_link( '%link', '%title <i class="icon-arrow-right"></i>' ); ?></span>
					</div>	
				</div>	
			</nav><!-- .nav-single -->	
		  
		  </div>
		  
		  <div class="panel-body">
			<div class="cat-tag-info">
				<div class="row">
				<div class="col-md-12 post_cats">
				<?php _e('<i class="icon-folder-open"></i> &nbsp;', 'openstrap' );?>
				<?php the_category(', '); ?>
				</div>
				</div>
				<?php if(has_tag()):?>
				<div class="row">
				<div class="col-md-12 post_tags">
				<?php _e('<i class="icon-tags"></i> &nbsp;', 'openstrap' );?>
				<?php the_tags('',', ',''); ?>
				</div>				
				</div>
				<?php endif;?>
			</div>				
		  </div>
		</div>	
		<?php endif;?>	
		<?php get_template_part('author-box'); ?>		
		
		<?php comments_template( '', true ); ?>
	</footer>

</article>
