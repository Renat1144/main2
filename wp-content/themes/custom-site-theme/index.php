<?php
/**
 * Posts fallback.
 *
 * @package CustomSiteTheme
 */

get_header();
?>
<main id="main-content" class="generic-page section-pad">
	<div class="shell generic-page-inner">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'generic-page-card' ); ?>>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<div class="entry-content"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<article class="generic-page-card">
				<h1><?php esc_html_e( 'Материалы пока не добавлены', 'custom-site-theme' ); ?></h1>
			</article>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();

