<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Openstrap already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Openstrap
 * @subpackage Openstrap
 * @since Openstrap 0.1
 */

get_header(); ?>

<?php 
	$col =  openstrap_get_content_cols();
	$os_layout = of_get_option('page_layouts');
	
	if ($os_layout ==  "full-width"){
		$col=12;		
	}
	else {
		if($os_layout ==  "sidebar-content" || $os_layout ==  "sidebar-content-sidebar") {
			get_sidebar('left');
		}	
		
		if($os_layout ==  "sidebar-sidebar-content") {		
			get_sidebar('left');
			get_sidebar();		
		}
	}
?>
<div class="col-md-<?php echo $col;?>" role="content">
	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title">Our Dogs</h1>
			</header><!-- .archive-header -->

			<div class="row">	
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
			?>
			<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
				<?php get_template_part( 'part-templates/content', get_post_type() ); ?>
			</div>
			<?php 

			endwhile;

			openstrap_content_nav( 'nav-below' );
			?>
			</div>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->
</div><!-- .col-md-<?php echo $col;?> -->

<?php
	if($os_layout ==  "content-sidebar-sidebar") {
		get_sidebar('left');
	}	
?>
<?php	
	if($os_layout ==  "content-sidebar" || 
	   $os_layout ==  "sidebar-content-sidebar" ||
	   $os_layout ==  "content-sidebar-sidebar") {		
		get_sidebar();
	}
?>
<?php get_footer(); ?>