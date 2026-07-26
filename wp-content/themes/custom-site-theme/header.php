<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#2a074b">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> id="top">
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Перейти к содержимому', 'custom-site-theme' ); ?></a>

<header class="site-header">
	<div class="shell header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/#top' ) ); ?>" aria-label="<?php esc_attr_e( 'На главную', 'custom-site-theme' ); ?>">
			<?php
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			if ( $custom_logo_id ) {
				echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'custom-logo' ) );
			} else {
				echo '<span class="brand-mark" aria-hidden="true"></span>';
			}
			?>
			<span class="brand-copy">
				<span class="brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				<span class="brand-caption"><?php echo esc_html( custom_site_theme_mod( 'brand_caption' ) ); ?></span>
			</span>
		</a>

		<button class="menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Открыть меню', 'custom-site-theme' ); ?>" aria-expanded="false" aria-controls="primary-navigation">
			<span class="menu-toggle-label"><?php echo esc_html( custom_site_theme_mod( 'menu_toggle_label' ) ); ?></span>
			<span class="menu-toggle-icon" aria-hidden="true"><span></span><span></span></span>
		</button>

		<nav class="primary-nav" id="primary-navigation" aria-label="<?php esc_attr_e( 'Основная навигация', 'custom-site-theme' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-nav-list',
					'fallback_cb'    => 'custom_site_theme_primary_menu_fallback',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
</header>
