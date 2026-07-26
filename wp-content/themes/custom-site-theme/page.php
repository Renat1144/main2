<?php
/**
 * Generic page template.
 *
 * @package CustomSiteTheme
 */

get_header();

while ( have_posts() ) :
	the_post();
	if ( has_block( 'project-blocks/master-hero', get_the_content() ) ) :
		?>
		<main id="main-content" class="master-detail-page">
			<?php the_content(); ?>
		</main>
		<?php
	else :
		?>
		<main id="main-content" class="generic-page section-pad">
			<div class="shell generic-page-inner">
			<article <?php post_class( 'generic-page-card' ); ?>>
				<p class="section-kicker"><?php esc_html_e( 'Страница', 'custom-site-theme' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
			</div>
		</main>
		<?php
	endif;
endwhile;

get_footer();
