<?php
/**
 * Plugin Name: Project Blocks
 * Description: Нативные Gutenberg-блоки для главной страницы и мастер-классов проекта.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 8.0
 * Author: Project Team
 * Text Domain: project-blocks
 *
 * @package ProjectBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PROJECT_BLOCKS_VERSION', '1.0.0' );
define( 'PROJECT_BLOCKS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PROJECT_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Build a safe inline typography declaration for one editable text role.
 *
 * @param array  $attributes Block attributes.
 * @param string $field Typography field key.
 * @return string
 */
function project_blocks_typography_style( $attributes, $field ) {
	$font_stacks = array(
		'display' => '"Bodoni 72", Didot, "Times New Roman", serif',
		'body'    => '"Avenir Next", Avenir, "Segoe UI", Arial, sans-serif',
		'utility' => 'Inter, "Helvetica Neue", Arial, sans-serif',
		'serif'   => 'Georgia, "Times New Roman", serif',
		'sans'    => 'Arial, "Helvetica Neue", sans-serif',
	);
	$typography = isset( $attributes['typography'] ) && is_array( $attributes['typography'] ) ? $attributes['typography'] : array();
	$settings   = isset( $typography[ $field ] ) && is_array( $typography[ $field ] ) ? $typography[ $field ] : array();
	$styles     = array();
	$font_key   = sanitize_key( $settings['fontFamily'] ?? '' );

	if ( isset( $font_stacks[ $font_key ] ) ) {
		$styles[] = 'font-family:' . $font_stacks[ $font_key ];
	}

	if ( isset( $settings['fontSize'] ) && is_numeric( $settings['fontSize'] ) ) {
		$font_size = min( 200, max( 8, (float) $settings['fontSize'] ) );
		$font_size = rtrim( rtrim( number_format( $font_size, 2, '.', '' ), '0' ), '.' );
		$styles[]  = 'font-size:' . $font_size . 'px';
	}

	return implode( ';', $styles );
}

/**
 * Resolve an image stored as a media-library ID with a URL fallback.
 *
 * @param array $values Image values.
 * @return array{url:string,alt:string}
 */
function project_blocks_image( $values ) {
	$image_id  = absint( $values['imageId'] ?? 0 );
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
	$image_url = $image_url ?: esc_url_raw( $values['imageUrl'] ?? '' );

	return array(
		'url' => $image_url,
		'alt' => sanitize_text_field( $values['imageAlt'] ?? '' ),
	);
}

/**
 * Register the shared editor bundle and every block metadata directory.
 */
function project_blocks_register_blocks() {
	$asset_file = PROJECT_BLOCKS_PATH . 'build/index.asset.php';
	$script_file = PROJECT_BLOCKS_PATH . 'build/index.js';
	$editor_style_file = PROJECT_BLOCKS_PATH . 'assets/editor-foundations.css';
	$home_style_file = PROJECT_BLOCKS_PATH . 'assets/home-blocks.css';
	$theme_style_file = get_template_directory() . '/assets/css/site.css';

	if ( ! file_exists( $asset_file ) || ! file_exists( $script_file ) ) {
		return;
	}

	$asset = require $asset_file;
	wp_register_script(
		'project-blocks-editor',
		PROJECT_BLOCKS_URL . 'build/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	wp_register_style(
		'project-blocks-editor-foundations',
		PROJECT_BLOCKS_URL . 'assets/editor-foundations.css',
		array(),
		file_exists( $editor_style_file ) ? filemtime( $editor_style_file ) : PROJECT_BLOCKS_VERSION
	);

	wp_register_style(
		'project-blocks-editor-site',
		get_template_directory_uri() . '/assets/css/site.css',
		array(),
		file_exists( $theme_style_file ) ? filemtime( $theme_style_file ) : PROJECT_BLOCKS_VERSION
	);

	wp_register_style(
		'project-blocks-home',
		PROJECT_BLOCKS_URL . 'assets/home-blocks.css',
		array(),
		file_exists( $home_style_file ) ? filemtime( $home_style_file ) : PROJECT_BLOCKS_VERSION
	);

	$block_directories = glob( PROJECT_BLOCKS_PATH . 'blocks/*', GLOB_ONLYDIR );
	foreach ( $block_directories as $block_directory ) {
		if ( file_exists( $block_directory . '/block.json' ) ) {
			register_block_type( $block_directory );
		}
	}
}
add_action( 'init', 'project_blocks_register_blocks' );

/**
 * Add a dedicated inserter category without changing core categories.
 *
 * @param array $categories Existing categories.
 * @return array
 */
function project_blocks_category( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'project-blocks',
			'title' => __( 'Блоки проекта', 'project-blocks' ),
			'icon'  => 'admin-customizer',
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'project_blocks_category' );

/**
 * Create a serializable dynamic block structure.
 *
 * @param string $name Block name without namespace.
 * @param array  $attributes Block attributes.
 * @return array
 */
function project_blocks_block( $name, $attributes ) {
	$attributes['lock'] = array(
		'move'   => true,
		'remove' => true,
	);

	return array(
		'blockName'    => 'project-blocks/' . $name,
		'attrs'        => $attributes,
		'innerBlocks'  => array(),
		'innerHTML'    => '',
		'innerContent' => array(),
	);
}

/**
 * Return the complete editable master-class page structure.
 *
 * @param array $values Values imported from an existing page.
 * @return array
 */
function project_blocks_masterclass_blocks( $values = array() ) {
	$values = wp_parse_args(
		$values,
		array(
			'title'       => __( 'Название мастер-класса', 'project-blocks' ),
			'intro'       => __( 'Текст будет добавлен', 'project-blocks' ),
			'description' => __( 'Описание мастер-класса будет добавлено позже.', 'project-blocks' ),
			'price'       => __( 'Стоимость будет добавлена', 'project-blocks' ),
			'image_id'    => 0,
			'image_url'   => '',
			'image_alt'   => __( 'Обложка мастер-класса', 'project-blocks' ),
		)
	);

	$title = sanitize_text_field( $values['title'] );

	return array(
		project_blocks_block(
			'master-hero',
			array(
				'number'                  => '01',
				'label'                   => __( 'Мастер-класс', 'project-blocks' ),
				'title'                   => $title,
				'description'             => '',
				'cardTitle'               => sanitize_text_field( $values['intro'] ),
				'cardContent'             => '',
				'imageId'                 => absint( $values['image_id'] ),
				'imageUrl'                => esc_url_raw( $values['image_url'] ),
				'imageAlt'                => sanitize_text_field( $values['image_alt'] ),
				'imageCaption'            => '',
				'imagePlaceholderCaption' => __( 'Обложка мастер-класса', 'project-blocks' ),
				'buttonText'              => '',
				'buttonUrl'               => '',
			)
		),
		project_blocks_block(
			'master-video',
			array(
				'kicker'          => __( 'Материал', 'project-blocks' ),
				'title'           => __( 'Видео мастер-класса', 'project-blocks' ),
				'placeholderTitle'=> __( 'ТУТ БУДЕТ ВИДЕО', 'project-blocks' ),
				'placeholderText' => __( 'Видеоматериал будет добавлен позже', 'project-blocks' ),
			)
		),
		project_blocks_block(
			'master-description',
			array(
				'kicker'  => __( 'О материале', 'project-blocks' ),
				'title'   => __( 'Описание мастер-класса', 'project-blocks' ),
				'content' => wp_kses_post( $values['description'] ),
			)
		),
		project_blocks_block(
			'master-audience',
			array(
				'kicker' => __( 'Направление', 'project-blocks' ),
				'title'  => __( 'Для кого этот мастер-класс', 'project-blocks' ),
				'items'  => array(
					array( 'number' => '01', 'text' => '' ),
					array( 'number' => '02', 'text' => '' ),
					array( 'number' => '03', 'text' => '' ),
					array( 'number' => '04', 'text' => '' ),
				),
			)
		),
		project_blocks_block(
			'master-offer',
			array(
				'number'           => '02',
				'label'            => __( 'Доступ к материалу', 'project-blocks' ),
				'title'            => $title,
				'price'            => sanitize_text_field( $values['price'] ),
				'compositionTitle' => __( 'Состав будет добавлен', 'project-blocks' ),
				'items'            => array(
					array( 'number' => '01', 'text' => '' ),
					array( 'number' => '02', 'text' => '' ),
					array( 'number' => '03', 'text' => '' ),
				),
				'buttonText'       => __( 'Оплатить', 'project-blocks' ),
				'buttonUrl'        => '',
			)
		),
	);
}

/**
 * Return block markup for a new or migrated master-class page.
 *
 * @param array $values Initial values.
 * @return string
 */
function project_blocks_masterclass_markup( $values = array() ) {
	return serialize_blocks( project_blocks_masterclass_blocks( $values ) );
}

/**
 * Return the complete editable homepage structure using the current theme values.
 *
 * @return array
 */
function project_blocks_homepage_blocks() {
	$value = static function ( $key ) {
		return function_exists( 'custom_site_theme_mod' ) ? custom_site_theme_mod( $key ) : '';
	};
	$image = static function ( $key, $fallback ) {
		$url = function_exists( 'custom_site_theme_image' ) ? custom_site_theme_image( $key, $fallback ) : '';

		return array(
			'imageId'  => $url ? absint( attachment_url_to_postid( $url ) ) : 0,
			'imageUrl' => esc_url_raw( $url ),
			'imageAlt' => __( 'Портрет специалиста', 'project-blocks' ),
		);
	};

	$credentials = array();
	$expertise   = array();
	$steps       = array();
	$mastercards = array();
	$stories     = array();
	$gallery     = array_fill(
		0,
		5,
		array(
			'imageId'  => 0,
			'imageUrl' => '',
			'imageAlt' => '',
		)
	);
	$master_page = get_page_by_path( 'master1', OBJECT, 'page' );
	$master_url  = $master_page ? get_permalink( $master_page ) : home_url( '/master1/' );

	for ( $index = 1; $index <= 3; $index++ ) {
		$credentials[] = array(
			'title' => sanitize_text_field( $value( 'credential_' . $index . '_title' ) ),
			'body'  => wp_kses_post( $value( 'credential_' . $index . '_body' ) ),
		);
		$steps[]       = array(
			'number' => str_pad( (string) $index, 2, '0', STR_PAD_LEFT ),
			'title'  => sanitize_text_field( $value( 'method_step_' . $index . '_title' ) ),
			'body'   => wp_kses_post( $value( 'method_step_' . $index . '_body' ) ),
		);
		$mastercards[] = array(
			'label'      => sprintf( __( 'Мастер-класс %02d', 'project-blocks' ), $index ),
			'title'      => sanitize_text_field( $value( 'masterclass_' . $index . '_title' ) ),
			'body'       => wp_kses_post( $value( 'masterclass_' . $index . '_body' ) ),
			'imageId'    => 0,
			'imageUrl'   => '',
			'imageAlt'   => '',
			'buttonText' => 1 === $index ? __( 'Получить', 'project-blocks' ) : __( 'Скоро', 'project-blocks' ),
			'buttonUrl'  => 1 === $index ? esc_url_raw( $master_url ) : '',
		);
		$stories[]     = array(
			'label'    => __( 'История участницы', 'project-blocks' ),
			'name'     => sanitize_text_field( $value( 'story_' . $index . '_name' ) ),
			'body'     => wp_kses_post( $value( 'story_' . $index . '_body' ) ),
			'imageId'  => 0,
			'imageUrl' => '',
			'imageAlt' => '',
		);
	}

	for ( $index = 1; $index <= 6; $index++ ) {
		$expertise[] = array(
			'number' => str_pad( (string) $index, 2, '0', STR_PAD_LEFT ),
			'title'  => sanitize_text_field( $value( 'expertise_' . $index . '_title' ) ),
			'body'   => wp_kses_post( $value( 'expertise_' . $index . '_body' ) ),
		);
	}

	return array(
		project_blocks_block(
			'home-hero',
			array_merge(
				array(
					'number'         => '01',
					'eyebrow'        => sanitize_text_field( $value( 'hero_eyebrow' ) ),
					'title'          => wp_kses_post( $value( 'hero_title' ) ),
					'lead'           => wp_kses_post( $value( 'hero_lead' ) ),
					'primaryLabel'   => sanitize_text_field( $value( 'hero_primary_label' ) ),
					'primaryUrl'     => '#masterclasses',
					'secondaryLabel' => sanitize_text_field( $value( 'hero_secondary_label' ) ),
					'secondaryUrl'   => '#method',
				),
				$image( 'hero', 'photo2.png' )
			)
		),
		project_blocks_block(
			'home-manifesto',
			array(
				'kicker'   => sanitize_text_field( $value( 'manifesto_kicker' ) ),
				'title'    => wp_kses_post( $value( 'manifesto_title' ) ),
				'emphasis' => wp_kses_post( $value( 'manifesto_emphasis' ) ),
				'body'     => wp_kses_post( $value( 'manifesto_body' ) ),
				'gallery'  => $gallery,
			)
		),
		project_blocks_block(
			'home-about',
			array_merge(
				array(
					'kicker'        => sanitize_text_field( $value( 'about_kicker' ) ),
					'title'         => wp_kses_post( $value( 'about_title' ) ),
					'name'          => wp_kses_post( $value( 'about_name' ) ),
					'role'          => wp_kses_post( $value( 'about_role' ) ),
					'verticalLabel' => sanitize_text_field( $value( 'about_vertical_label' ) ),
					'credentials'   => $credentials,
				),
				$image( 'about', 'photo1.png' )
			)
		),
		project_blocks_block(
			'home-expertise',
			array(
				'kicker' => sanitize_text_field( $value( 'expertise_kicker' ) ),
				'title'  => wp_kses_post( $value( 'expertise_title' ) ),
				'intro'  => wp_kses_post( $value( 'expertise_intro' ) ),
				'cards'  => $expertise,
			)
		),
		project_blocks_block(
			'home-method-intro',
			array(
				'kicker'           => sanitize_text_field( $value( 'method_intro_kicker' ) ),
				'title'            => wp_kses_post( $value( 'method_intro_title' ) ),
				'body'             => wp_kses_post( $value( 'method_intro_body' ) ),
				'imageId'          => 0,
				'imageUrl'         => '',
				'imageAlt'         => __( 'Обложка видео', 'project-blocks' ),
				'videoUrl'         => '',
				'placeholderTitle' => __( 'ТУТ БУДЕТ ФОТО', 'project-blocks' ),
				'placeholderText'  => __( 'Обложка будущего видео', 'project-blocks' ),
			)
		),
		project_blocks_block(
			'home-method',
			array_merge(
				array(
					'kicker' => sanitize_text_field( $value( 'method_kicker' ) ),
					'title'  => wp_kses_post( $value( 'method_title' ) ),
					'lead'   => wp_kses_post( $value( 'method_lead' ) ),
					'steps'  => $steps,
				),
				$image( 'method', 'photo3.png' )
			)
		),
		project_blocks_block(
			'home-masterclasses',
			array(
				'kicker' => sanitize_text_field( $value( 'masterclasses_kicker' ) ),
				'title'  => wp_kses_post( $value( 'masterclasses_title' ) ),
				'intro'  => wp_kses_post( $value( 'masterclasses_intro' ) ),
				'cards'  => $mastercards,
			)
		),
		project_blocks_block(
			'home-stories',
			array(
				'kicker'  => sanitize_text_field( $value( 'stories_kicker' ) ),
				'title'   => wp_kses_post( $value( 'stories_title' ) ),
				'intro'   => wp_kses_post( $value( 'stories_intro' ) ),
				'stories' => $stories,
			)
		),
		project_blocks_block(
			'home-coaching',
			array_merge(
				array(
					'number'     => '03',
					'eyebrow'    => sanitize_text_field( $value( 'coaching_eyebrow' ) ),
					'title'      => wp_kses_post( $value( 'coaching_title' ) ),
					'body'       => wp_kses_post( $value( 'coaching_body' ) ),
					'buttonText' => sanitize_text_field( $value( 'coaching_button' ) ),
					'buttonUrl'  => '',
				),
				$image( 'coaching', 'photo4.png' )
			)
		),
	);
}

/**
 * Return serialized homepage block markup.
 *
 * @return string
 */
function project_blocks_homepage_markup() {
	return serialize_blocks( project_blocks_homepage_blocks() );
}

/**
 * Migrate the configured front page once and retain a database backup.
 *
 * @return true|WP_Error
 */
function project_blocks_migrate_homepage() {
	if ( get_option( 'project_blocks_homepage_migrated_v1' ) ) {
		return true;
	}

	$page_id = absint( get_option( 'page_on_front' ) );
	$page    = $page_id ? get_post( $page_id ) : null;
	if ( ! $page instanceof WP_Post || 'page' !== $page->post_type ) {
		return new WP_Error( 'project_blocks_homepage_missing', __( 'Главная страница не найдена.', 'project-blocks' ) );
	}

	if ( false === get_option( 'project_blocks_homepage_backup_v1', false ) ) {
		add_option(
			'project_blocks_homepage_backup_v1',
			array(
				'created_at' => current_time( 'mysql' ),
				'post'       => $page->to_array(),
				'template'   => get_post_meta( $page->ID, '_wp_page_template', true ),
				'theme_mods' => get_theme_mods(),
			),
			'',
			false
		);
	}

	if ( has_block( 'project-blocks/home-hero', $page->post_content ) ) {
		update_option(
			'project_blocks_homepage_migrated_v1',
			array(
				'page_id'             => $page->ID,
				'migrated_at'         => current_time( 'mysql' ),
				'already_block_based' => true,
			),
			false
		);
		return true;
	}

	$result = wp_update_post(
		array(
			'ID'           => $page->ID,
			'post_content' => project_blocks_homepage_markup(),
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	update_option(
		'project_blocks_homepage_migrated_v1',
		array(
			'page_id'     => $page->ID,
			'migrated_at' => current_time( 'mysql' ),
		),
		false
	);

	return true;
}

/**
 * Migrate the existing master1 page once and retain a database backup.
 *
 * @return true|WP_Error
 */
function project_blocks_migrate_master1() {
	if ( get_option( 'project_blocks_master1_migrated_v1' ) ) {
		return true;
	}

	$page = get_page_by_path( 'master1', OBJECT, 'page' );
	if ( ! $page instanceof WP_Post ) {
		return new WP_Error( 'project_blocks_page_missing', __( 'Страница master1 не найдена.', 'project-blocks' ) );
	}

	if ( false === get_option( 'project_blocks_master1_backup_v1', false ) ) {
		add_option(
			'project_blocks_master1_backup_v1',
			array(
				'created_at' => current_time( 'mysql' ),
				'post'       => $page->to_array(),
				'template'   => get_post_meta( $page->ID, '_wp_page_template', true ),
				'theme_mods' => get_theme_mods(),
			),
			'',
			false
		);
	}

	if ( has_block( 'project-blocks/master-hero', $page->post_content ) ) {
		update_option(
			'project_blocks_master1_migrated_v1',
			array( 'page_id' => $page->ID, 'migrated_at' => current_time( 'mysql' ), 'already_block_based' => true ),
			false
		);
		return true;
	}

	$image_url = function_exists( 'custom_site_theme_image' ) ? custom_site_theme_image( 'master_cover' ) : '';
	$image_id  = $image_url ? attachment_url_to_postid( $image_url ) : 0;
	$intro     = function_exists( 'custom_site_theme_mod' ) ? custom_site_theme_mod( 'master_intro' ) : __( 'Текст будет добавлен', 'project-blocks' );
	$price     = function_exists( 'custom_site_theme_mod' ) ? custom_site_theme_mod( 'master_price' ) : __( 'Стоимость будет добавлена', 'project-blocks' );

	$result = wp_update_post(
		array(
			'ID'           => $page->ID,
			'post_content' => project_blocks_masterclass_markup(
				array(
					'title'       => $page->post_title,
					'intro'       => $intro,
					'description' => $page->post_content,
					'price'       => $price,
					'image_id'    => $image_id,
					'image_url'   => $image_url,
					'image_alt'   => __( 'Обложка мастер-класса', 'project-blocks' ),
				)
			),
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	update_option(
		'project_blocks_master1_migrated_v1',
		array( 'page_id' => $page->ID, 'migrated_at' => current_time( 'mysql' ) ),
		false
	);

	return true;
}

/**
 * Activation performs the guarded one-time migration.
 */
function project_blocks_activate() {
	project_blocks_migrate_master1();
	project_blocks_migrate_homepage();
}
register_activation_hook( __FILE__, 'project_blocks_activate' );

/**
 * Retry only when a previous activation happened before the page existed.
 */
function project_blocks_maybe_migrate() {
	if ( ! get_option( 'project_blocks_master1_migrated_v1' ) ) {
		project_blocks_migrate_master1();
	}
	if ( ! get_option( 'project_blocks_homepage_migrated_v1' ) ) {
		project_blocks_migrate_homepage();
	}
}
add_action( 'admin_init', 'project_blocks_maybe_migrate' );

/**
 * Register an insertable pattern using the same guarded structure.
 */
function project_blocks_register_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category(
		'project-blocks',
		array( 'label' => __( 'Мастер-классы проекта', 'project-blocks' ) )
	);

	register_block_pattern(
		'project-blocks/masterclass-page',
		array(
			'title'       => __( 'Страница мастер-класса', 'project-blocks' ),
			'description' => __( 'Готовая защищённая структура страницы мастер-класса.', 'project-blocks' ),
			'categories'  => array( 'project-blocks' ),
			'content'     => project_blocks_masterclass_markup(),
		)
	);
}
add_action( 'init', 'project_blocks_register_patterns', 20 );

/**
 * Pre-fill the editor when the dedicated “new master-class” action is used.
 *
 * @param string  $content Default content.
 * @param WP_Post $post New post object.
 * @return string
 */
function project_blocks_default_content( $content, $post ) {
	$requested = isset( $_GET['project_masterclass'] ) ? sanitize_key( wp_unslash( $_GET['project_masterclass'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'page' === $post->post_type && '1' === $requested && current_user_can( 'edit_pages' ) ) {
		return project_blocks_masterclass_markup();
	}

	return $content;
}
add_filter( 'default_content', 'project_blocks_default_content', 10, 2 );

/**
 * Supply a useful title for the dedicated creation flow.
 *
 * @param string  $title Default title.
 * @param WP_Post $post New post object.
 * @return string
 */
function project_blocks_default_title( $title, $post ) {
	$requested = isset( $_GET['project_masterclass'] ) ? sanitize_key( wp_unslash( $_GET['project_masterclass'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'page' === $post->post_type && '1' === $requested && current_user_can( 'edit_pages' ) ) {
		return __( 'Новый мастер-класс', 'project-blocks' );
	}

	return $title;
}
add_filter( 'default_title', 'project_blocks_default_title', 10, 2 );

/**
 * Add a safe shortcut under Pages and redirect before admin output starts.
 */
function project_blocks_add_masterclass_menu() {
	$hook = add_submenu_page(
		'edit.php?post_type=page',
		__( 'Добавить мастер-класс', 'project-blocks' ),
		__( 'Добавить мастер-класс', 'project-blocks' ),
		'edit_pages',
		'project-blocks-new-masterclass',
		'__return_null'
	);

	add_action(
		'load-' . $hook,
		static function () {
			wp_safe_redirect( admin_url( 'post-new.php?post_type=page&project_masterclass=1' ) );
			exit;
		}
	);
}
add_action( 'admin_menu', 'project_blocks_add_masterclass_menu' );
