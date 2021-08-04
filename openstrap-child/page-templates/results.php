<?php
/**
 * Template Name: Results (Full-Width Template)
 */

get_header(); 

$year = ($wp_query->query_vars['cft-year']) ? urldecode($wp_query->query_vars['cft-year']) : date('Y');
$page_title = $year." ".get_the_title();

$end_date = $year.'1231';
if ($year == date('Y')){ $end_date = date('Ymd'); }

$comps = get_posts(array(
  'post_status'     => array('publish'),
  'posts_per_page'  => -1,
  'category'        => 16,
  'order'           => 'DESC',
  'meta_key'        => 'start_date',
  'orderby'         => 'meta_value_num',
  'meta_query'      => array(
    array('key' => 'start_date', 'value' => $year.'0101', 'compare' => '>='),
    array('key' => 'end_date', 'value' => $end_date, 'compare' => '<=')
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
global $post;
foreach($comps as $post) : setup_postdata($post); //setup_postdata($comp); 
  $start_date = DateTime::createFromFormat('Ymd', get_post_meta( get_the_ID(), 'start_date', true ));
  $end_date   = DateTime::createFromFormat('Ymd', get_post_meta( get_the_ID(), 'end_date', true ));
  $dates = $start_date->format('jS M');
  if (isset($end_date) && $end_date != '' && $start_date != $end_date){ $dates .= ' to '.$end_date->format('jS M'); }
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('', get_the_ID()); ?>>
	    <header>
        <hgroup>
          <h3>
          <a href="<?php echo get_post_permalink(get_the_ID()) ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'openstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php echo get_the_title(); ?></a>
          <div class="pull-right hidden-xs">
            <small class="visible-sm-inline hidden-md hidden-lg"><?php echo $start_date->format('jS M'); ?></small>
            <small class="hidden-sm visible-md-inline visible-lg-inline"><?php echo $dates; ?></small>
          </div>
          </h3>
          <hr class="post-meta-hr">
        </hgroup>
      </header>
    
      <div class="row">
        <div class="col-sm-4 col-md-3 hidden-xs">
        <?php if ( has_post_thumbnail(get_the_ID())) : ?>
          <div class="featured-img pull-left"><?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?></div>
        <?php endif; ?>
        </div>
        <?php get_template_part('part-templates/competition', 'results'); ?>
      </div>
	    <div class="clearfix"/>
    </article>

<?php endforeach;?>  
  </article><!-- #post -->
</div>	
<!-- End Main Content -->	


<?php get_footer(); ?>
