<?php
/**
 * Template Name: Front Page With Slider Template
 *
 * Page template for Front Page
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */

get_header(); ?>

<?php
	$divclass = (of_get_option('front_page_widget_section_count')=='4') ? '3' : '4';
	$imagepath =  get_template_directory_uri() . '/images/';

	$display_slider = of_get_option('display_slider');
	if(isset($display_slider) && $display_slider==true) {
		get_template_part( 'slides', 'index' );
	}
?>
	
	<?php if(of_get_option('display_blurb') == '1'): ?>	
	<!--blurb-->
	<div class="col-md-12">
	<div class="front-page-blurb">
	  <div class="container text-center">
		<h1><?php echo of_get_option('blurb_heading'); ?></h1>
		<p class="lead"><?php echo of_get_option('blurb_text'); ?></p>
		<?php if(of_get_option('display_blurb_button') == '1'): ?>	
		<p><a class="btn btn-primary btn-sm" href="<?php echo get_permalink( of_get_option('blurb_button_link_page')); ?>"><?php echo of_get_option('blurb_button_title'); ?> &nbsp; <i class="icon-arrow-right icon-large"></i>  </a></p>
		<?php endif; ?>	
		
	<div class="row hidden-xs hidden-sm">
		<div class="col-md-12">
			<div id="flickr" align="center" style="text-align:center;clear:right;"></div>
			<?php //the_content(); ?>
		</div>
	</div>
		<hr class="style-eight"/>
	  </div>
	</div>
	</div>
	<!--/blurb-->
	<?php endif; ?>	

	<div class="col-md-<?php echo $divclass; ?>">
	<?php if ( is_active_sidebar( 'openstrap_front_page_one' ) ) : ?>
	<?php dynamic_sidebar( 'openstrap_front_page_one' ); ?>	
	<?php endif; ?>	
	</div>

	<div class="col-md-<?php echo $divclass; ?>">
	<?php if ( is_active_sidebar( 'openstrap_front_page_two' ) ) : ?>
	<?php dynamic_sidebar( 'openstrap_front_page_two' ); ?>	
	<?php endif; ?>	
	</div>

	<div class="col-md-<?php echo $divclass; ?>">
	<?php if ( is_active_sidebar( 'openstrap_front_page_three' ) ) : ?>
	<?php dynamic_sidebar( 'openstrap_front_page_three' ); ?>	
	<?php endif; ?>	
	</div>
	
	<?php if($divclass=='3'): ?>
	<div class="col-md-<?php echo $divclass; ?>">
	<?php if ( is_active_sidebar( 'openstrap_front_page_four' ) ) : ?>
	<?php dynamic_sidebar( 'openstrap_front_page_four' ); ?>	
	<?php endif; ?>	
	</div>
	<?php endif; ?>	
	</div>
			

<?php get_footer(); ?>
<script>
	jQuery(document).ready(function($) {
        //var ajaxURL = "https://api.flickr.com/services/feeds/photos_public.gne?id=64321729@N04&set_id=72157636057346063&tags=cc176&format=json&jsoncallback=?";
        var ajaxURL = "https://api.flickr.com/services/feeds/photos_public.gne?id=139417838@N03&format=json&jsoncallback=?";
        $.getJSON(ajaxURL,function(data) {
                items = data.items;
                var selArray = new Array;
                for(var i=0; i < 7; i++){
                        var randomNumber = Math.floor(Math.random() * items.length);
                        selArray.push(items[randomNumber]);
                        items.splice(randomNumber,1);
                }
                $.each(selArray, function(i,photo){
                        var photoHTML = '<span style="margin:3px;">';
			if (i>4) { photoHTML = '<span style="margin:3px;" class="hidden-md">'; }
                        photoHTML += '<a href="' + photo.link + '">';
                        photoHTML += '<img class="wp-post-image" src="' + photo.media.m.replace('_m','_q') + '"></a>';
                        $('#flickr').append(photoHTML);
                }); // end each
        }); // end get JSON
	}); // end ready
</script>
