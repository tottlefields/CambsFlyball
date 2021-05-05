<?php
/**
 * Template Name: Results (Full-Width Template)
 */

get_header(); 

$year = ($wp_query->query_vars['cft-year']) ? urldecode($wp_query->query_vars['cft-year']) : date('Y');
$page_title = $year." ".get_the_title();

$comps = get_posts(array(
  'post_status'     => array('publish'),
  'posts_per_page'  => -1,
  'category'        => 16,
  'order'           => 'DESC',
  'meta_key'        => 'start_date',
  'orderby'         => 'meta_value_num',
  'meta_query'      => array(
    array('key' => 'start_date', 'value' => $year.'0101', 'compare' => '>='),
    array('key' => 'end_date', 'value' => $year.'1231', 'compare' => '<=')
  )
));
//debug_array($comps);
?>



<!-- Main Content -->	
<div class="col-md-12" role="main">
	<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
      <h1 class="entry-title"><?php echo $page_title; ?></h1>
    </header>

<?php 
foreach($comps as $comp) : setup_postdata($comp); 
  $start_date = DateTime::createFromFormat('Ymd', get_post_meta( $comp->ID, 'start_date', true ));
  $end_date   = DateTime::createFromFormat('Ymd', get_post_meta( $comp->ID, 'end_date', true ));
  $dates = $start_date->format('jS M');
  if (isset($end_date) && $end_date != '' && $start_date != $end_date){ $dates .= ' to '.$end_date->format('jS M'); }
?>

    <article id="post-<?php echo $comp->ID; ?>" <?php post_class('', $comp->ID); ?>>
	    <header>
        <hgroup>
          <h3>
          <a href="<?php echo get_post_permalink($comp->ID) ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'openstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php echo $comp->post_title; ?></a>
          <div class="pull-right hidden-xs">
            <small class="visible-sm"><?php echo $start_date->format('jS M'); ?></small>
            <small class="hidden-sm"><?php echo $dates; ?></small>
          </div>
          </h3>
          <hr class="post-meta-hr">
        </hgroup>
      </header>
    
  <?php if ( has_post_thumbnail($comp->ID)) : ?>
		  <div class="featured-img pull-left"><?php echo get_the_post_thumbnail($comp->ID, 'thumbnail'); ?></div>
	<?php endif; ?>
	<div class="clearfix"/>
</article>

<?php endforeach;?>  
  </article><!-- #post -->
</div>	
<!-- End Main Content -->	


<?php get_footer(); ?>
