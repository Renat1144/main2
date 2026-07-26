<?php
/**
 * Theme setup, assets, navigation and small output helpers.
 *
 * @package CustomSiteTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function custom_site_theme_setup() {
	load_theme_textdomain( 'custom-site-theme', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 360, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	register_nav_menus(
		array(
			'primary' => __( 'Основная навигация', 'custom-site-theme' ),
			'footer'  => __( 'Навигация в подвале', 'custom-site-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'custom_site_theme_setup' );

function custom_site_theme_assets() {
	$theme_dir = get_template_directory();
	$theme_uri = get_template_directory_uri();
	$css_path  = $theme_dir . '/assets/css/site.css';
	$js_path   = $theme_dir . '/assets/js/site.js';

	wp_enqueue_style( 'custom-site-theme', $theme_uri . '/assets/css/site.css', array(), filemtime( $css_path ) );
	wp_enqueue_script( 'custom-site-theme', $theme_uri . '/assets/js/site.js', array(), filemtime( $js_path ), true );
	wp_script_add_data( 'custom-site-theme', 'strategy', 'defer' );
}
add_action( 'wp_enqueue_scripts', 'custom_site_theme_assets' );

function custom_site_theme_favicon() {
	if ( has_site_icon() ) {
		return;
	}

	printf(
		'<link rel="icon" href="%s" type="image/svg+xml">' . "\n",
		esc_url( get_template_directory_uri() . '/assets/images/favicon.svg' )
	);
}
add_action( 'wp_head', 'custom_site_theme_favicon' );

function custom_site_theme_meta_description() {
	if ( is_front_page() ) {
		$description = 'Премиальная главная страница проекта. Тексты и изображения будут добавлены на следующих этапах.';
	} elseif ( is_page( 'master1' ) ) {
		$description = 'Страница мастер-класса №1. Материалы и описание будут добавлены позже.';
	} else {
		return;
	}

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
}
add_action( 'wp_head', 'custom_site_theme_meta_description', 1 );

function custom_site_theme_nav_link_classes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$classes = isset( $atts['class'] ) ? preg_split( '/\s+/', $atts['class'] ) : array();
		$classes[] = 'nav-link';

		if ( in_array( 'current-menu-item', $item->classes, true ) || ( is_front_page() && 'top' === wp_parse_url( $item->url, PHP_URL_FRAGMENT ) ) ) {
			$classes[] = 'is-active';
		}

		$atts['class'] = implode( ' ', array_unique( array_filter( $classes ) ) );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'custom_site_theme_nav_link_classes', 10, 3 );

function custom_site_theme_primary_menu_fallback() {
	$items = array(
		'#top'           => 'Главная',
		'#about'         => 'Обо мне',
		'#masterclasses' => 'Мастер-классы',
		'#coaching'      => 'Коуч-сессия',
	);

	echo '<ul class="primary-nav-list">';
	foreach ( $items as $fragment => $label ) {
		$active = '#top' === $fragment && is_front_page() ? ' is-active' : '';
		printf(
			'<li class="menu-item"><a class="nav-link%s" href="%s">%s</a></li>',
			esc_attr( $active ),
			esc_url( home_url( '/' . $fragment ) ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

function custom_site_theme_footer_menu_fallback() {
	$items = array(
		'#about'         => 'Обо мне',
		'#masterclasses' => 'Мастер-классы',
		'#method'        => 'О методе',
		'#coaching'      => 'Коуч-сессия',
	);

	echo '<ul class="footer-menu">';
	foreach ( $items as $fragment => $label ) {
		printf(
			'<li class="menu-item"><a href="%s">%s</a></li>',
			esc_url( home_url( '/' . $fragment ) ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}
