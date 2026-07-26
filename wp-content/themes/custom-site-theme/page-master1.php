<?php
/**
 * Master class detail page powered by Gutenberg blocks.
 *
 * @package CustomSiteTheme
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="main-content" class="master-detail-page">
		<?php the_content(); ?>
	</main>
	<?php
endwhile;

get_footer();
