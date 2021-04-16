<?php
/**
 * Template Name: Frontpage
 *
 */
?>
<?php get_header(); ?>
	<div class="slider_">
		<?php if (function_exists('nivoslider4wp_show')) { nivoslider4wp_show(); } ?>
	</div>
	<div class="separator-slider_">
		<img id="separator_slider" src="<?php bloginfo('template_url'); ?>/images/separator-slider.png"/>
	</div>
	<div class="index-thumb_">
		
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="entry">

<?php the_content('read more'); ?>

<div class="clear"></div>

</div> <!-- end entry -->

<?php endwhile; else: ?>

<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>

<?php endif; ?>
	</div>
	<div class="footer_">
	<div class="pozycjonowanie"><?php the_field('pozycjonowanie'); ?></div>
		<img id="footer" src="<?php bloginfo('template_url'); ?>/images/footer.png" />
	</div>
</div>
<?php get_footer(); ?>

</body>
</html>
