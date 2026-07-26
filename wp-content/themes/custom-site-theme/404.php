<?php
/**
 * Not found template.
 *
 * @package CustomSiteTheme
 */

get_header();
?>
<main id="main-content" class="error-page">
	<div class="shell error-page-inner reveal is-visible">
		<p class="eyebrow eyebrow-light"><span>404</span> <?php esc_html_e( 'Страница не найдена', 'custom-site-theme' ); ?></p>
		<h1><?php esc_html_e( 'Такой страницы нет', 'custom-site-theme' ); ?></h1>
		<p><?php esc_html_e( 'Вернитесь на главную страницу и выберите нужный раздел.', 'custom-site-theme' ); ?></p>
		<a class="button button-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'custom-site-theme' ); ?> <span aria-hidden="true">↗</span></a>
	</div>
</main>
<?php
get_footer();

