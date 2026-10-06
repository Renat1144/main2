<?php
/**
 * One-time creation of the pages and menus represented by the static source.
 *
 * @package CustomSiteTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function custom_site_theme_ensure_page( $title, $slug, $content = '' ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $page instanceof WP_Post ) {
		return (int) $page->ID;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		),
		true
	);

	return is_wp_error( $page_id ) ? 0 : (int) $page_id;
}

function custom_site_theme_create_menu( $name, $items ) {
	$menu = wp_get_nav_menu_object( $name );
	$menu_id = $menu ? (int) $menu->term_id : wp_create_nav_menu( $name );

	if ( is_wp_error( $menu_id ) ) {
		return 0;
	}

	$existing_items = wp_get_nav_menu_items( $menu_id );
	if ( empty( $existing_items ) ) {
		foreach ( $items as $item ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => $item['title'],
					'menu-item-url'    => $item['url'],
					'menu-item-status' => 'publish',
				)
			);
		}
	}

	return (int) $menu_id;
}

function custom_site_theme_bootstrap_content() {
	if ( get_option( 'custom_site_theme_bootstrap_v1' ) ) {
		return;
	}

	$front_id  = custom_site_theme_ensure_page( 'Главная', 'home' );
	$master_id = custom_site_theme_ensure_page( 'Мастер-класс №1', 'master1', 'Описание мастер-класса будет добавлено позже.' );

	if ( $front_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_id );
	}

	$primary_id = custom_site_theme_create_menu(
		'Основное меню',
		array(
			array( 'title' => 'Главная', 'url' => home_url( '/#top' ) ),
			array( 'title' => 'Обо мне', 'url' => home_url( '/#about' ) ),
			array( 'title' => 'Мастер-классы', 'url' => home_url( '/#masterclasses' ) ),
			array( 'title' => 'Коуч-сессия', 'url' => home_url( '/#coaching' ) ),
		)
	);

	$footer_id = custom_site_theme_create_menu(
		'Меню в подвале',
		array(
			array( 'title' => 'Обо мне', 'url' => home_url( '/#about' ) ),
			array( 'title' => 'Мастер-классы', 'url' => home_url( '/#masterclasses' ) ),
			array( 'title' => 'О методе', 'url' => home_url( '/#method' ) ),
			array( 'title' => 'Коуч-сессия', 'url' => home_url( '/#coaching' ) ),
		)
	);

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( $primary_id ) {
		$locations['primary'] = $primary_id;
	}
	if ( $footer_id ) {
		$locations['footer'] = $footer_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	if ( 'My WordPress' === get_option( 'blogname' ) || 'WordPress' === get_option( 'blogname' ) ) {
		update_option( 'blogname', custom_site_theme_mod( 'hero_title' ) );
	}
	if ( ! get_option( 'blogdescription' ) ) {
		update_option( 'blogdescription', custom_site_theme_mod( 'brand_caption' ) );
	}

	update_option( 'custom_site_theme_bootstrap_v1', 1 );
}
add_action( 'after_switch_theme', 'custom_site_theme_bootstrap_content' );
