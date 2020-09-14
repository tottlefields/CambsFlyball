<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
<?php if ( has_post_thumbnail()) : ?>
	<div>
		<a href="<?php the_permalink(); ?>" class="th" title="<?php the_title_attribute(); ?>" ><?php the_post_thumbnail('thumbnail', ['class' => 'aligncenter']); ?></a>
	</div>
<?php endif; ?>
	<h4 class="text-center">
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'openstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a><br />
		<?php $ukfl_no = get_post_meta( get_the_ID() , 'ukfl_number', true ); ?>
		<?php if ( isset($ukfl_no) && $ukfl_no != '' ) : ?>
			<small>(<a href="https://www.ukflyball.org.uk/dogs/<?php echo $ukfl_no; ?>" title="<?php echo esc_attr( sprintf( __( 'UKFL Page for %s', 'openstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" target="_blank"><?php echo $ukfl_no;?></a>)</small>
		<?php endif; ?>
	</h4>
</article>