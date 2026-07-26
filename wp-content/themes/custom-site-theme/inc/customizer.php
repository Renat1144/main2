<?php
/**
 * Native WordPress controls for the homepage and footer content.
 *
 * @package CustomSiteTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function custom_site_theme_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'custom_site_content',
		array(
			'title'       => __( 'Содержимое сайта', 'custom-site-theme' ),
			'description' => __( 'Глобальные подписи шапки и содержимое подвала. Секции страниц редактируются прямо на их макете в разделе «Страницы».', 'custom-site-theme' ),
			'priority'    => 30,
		)
	);

	$sections = array(
		'branding' => array(
			'title'  => 'Бренд и подписи',
			'fields' => array(
				array( 'brand_caption', 'Подпись под названием', 'text' ),
				array( 'menu_toggle_label', 'Подпись кнопки мобильного меню', 'text' ),
			),
		),
		'hero' => array(
			'title'  => 'Первый экран',
			'fields' => array(
				array( 'hero_eyebrow', 'Надзаголовок', 'text' ),
				array( 'hero_title', 'Главный заголовок', 'text' ),
				array( 'hero_lead', 'Описание', 'textarea' ),
				array( 'hero_primary_label', 'Текст основной кнопки', 'text' ),
				array( 'hero_secondary_label', 'Текст второй ссылки', 'text' ),
			),
		),
		'manifesto' => array(
			'title'  => 'Идея проекта',
			'fields' => array(
				array( 'manifesto_kicker', 'Метка раздела', 'text' ),
				array( 'manifesto_title', 'Заголовок', 'text' ),
				array( 'manifesto_emphasis', 'Акцент в заголовке', 'text' ),
				array( 'manifesto_body', 'Описание', 'textarea' ),
			),
		),
		'about' => array(
			'title'  => 'О специалисте',
			'fields' => array(
				array( 'about_kicker', 'Метка раздела', 'text' ),
				array( 'about_title', 'Заголовок', 'text' ),
				array( 'about_name', 'Имя', 'text' ),
				array( 'about_role', 'Профессиональное описание', 'textarea' ),
				array( 'about_vertical_label', 'Вертикальная подпись', 'text' ),
				array( 'credential_1_title', 'Заголовок пункта 1', 'text' ),
				array( 'credential_1_body', 'Описание пункта 1', 'textarea' ),
				array( 'credential_2_title', 'Заголовок пункта 2', 'text' ),
				array( 'credential_2_body', 'Описание пункта 2', 'textarea' ),
				array( 'credential_3_title', 'Заголовок пункта 3', 'text' ),
				array( 'credential_3_body', 'Описание пункта 3', 'textarea' ),
			),
		),
		'expertise' => array(
			'title'  => 'Направления работы',
			'fields' => array(
				array( 'expertise_kicker', 'Метка раздела', 'text' ),
				array( 'expertise_title', 'Заголовок', 'text' ),
				array( 'expertise_intro', 'Вводный текст', 'textarea' ),
				array( 'expertise_1_title', 'Карточка 1 — заголовок', 'text' ),
				array( 'expertise_1_body', 'Карточка 1 — описание', 'textarea' ),
				array( 'expertise_2_title', 'Карточка 2 — заголовок', 'text' ),
				array( 'expertise_2_body', 'Карточка 2 — описание', 'textarea' ),
				array( 'expertise_3_title', 'Карточка 3 — заголовок', 'text' ),
				array( 'expertise_3_body', 'Карточка 3 — описание', 'textarea' ),
				array( 'expertise_4_title', 'Карточка 4 — заголовок', 'text' ),
				array( 'expertise_4_body', 'Карточка 4 — описание', 'textarea' ),
				array( 'expertise_5_title', 'Карточка 5 — заголовок', 'text' ),
				array( 'expertise_5_body', 'Карточка 5 — описание', 'textarea' ),
				array( 'expertise_6_title', 'Карточка 6 — заголовок', 'text' ),
				array( 'expertise_6_body', 'Карточка 6 — описание', 'textarea' ),
			),
		),
		'method_intro' => array(
			'title'  => 'Вводный блок метода',
			'fields' => array(
				array( 'method_intro_kicker', 'Метка раздела', 'text' ),
				array( 'method_intro_title', 'Заголовок', 'text' ),
				array( 'method_intro_body', 'Описание', 'textarea' ),
			),
		),
		'method' => array(
			'title'  => 'Метод',
			'fields' => array(
				array( 'method_kicker', 'Метка раздела', 'text' ),
				array( 'method_title', 'Заголовок', 'text' ),
				array( 'method_lead', 'Вводный текст', 'textarea' ),
				array( 'method_step_1_title', 'Шаг 1 — заголовок', 'text' ),
				array( 'method_step_1_body', 'Шаг 1 — описание', 'textarea' ),
				array( 'method_step_2_title', 'Шаг 2 — заголовок', 'text' ),
				array( 'method_step_2_body', 'Шаг 2 — описание', 'textarea' ),
				array( 'method_step_3_title', 'Шаг 3 — заголовок', 'text' ),
				array( 'method_step_3_body', 'Шаг 3 — описание', 'textarea' ),
			),
		),
		'masterclasses' => array(
			'title'  => 'Мастер-классы',
			'fields' => array(
				array( 'masterclasses_kicker', 'Метка раздела', 'text' ),
				array( 'masterclasses_title', 'Заголовок', 'text' ),
				array( 'masterclasses_intro', 'Вводный текст', 'textarea' ),
				array( 'masterclass_1_title', 'Карточка 1 — заголовок', 'text' ),
				array( 'masterclass_1_body', 'Карточка 1 — описание', 'textarea' ),
				array( 'masterclass_2_title', 'Карточка 2 — заголовок', 'text' ),
				array( 'masterclass_2_body', 'Карточка 2 — описание', 'textarea' ),
				array( 'masterclass_3_title', 'Карточка 3 — заголовок', 'text' ),
				array( 'masterclass_3_body', 'Карточка 3 — описание', 'textarea' ),
			),
		),
		'stories' => array(
			'title'  => 'Истории участниц',
			'fields' => array(
				array( 'stories_kicker', 'Метка раздела', 'text' ),
				array( 'stories_title', 'Заголовок', 'text' ),
				array( 'stories_intro', 'Вводный текст', 'textarea' ),
				array( 'story_1_name', 'История 1 — имя', 'text' ),
				array( 'story_1_body', 'История 1 — описание', 'textarea' ),
				array( 'story_2_name', 'История 2 — имя', 'text' ),
				array( 'story_2_body', 'История 2 — описание', 'textarea' ),
				array( 'story_3_name', 'История 3 — имя', 'text' ),
				array( 'story_3_body', 'История 3 — описание', 'textarea' ),
			),
		),
		'coaching' => array(
			'title'  => 'Коуч-сессия',
			'fields' => array(
				array( 'coaching_eyebrow', 'Надзаголовок', 'text' ),
				array( 'coaching_title', 'Заголовок', 'text' ),
				array( 'coaching_body', 'Описание', 'textarea' ),
				array( 'coaching_button', 'Текст кнопки', 'text' ),
			),
		),
		'footer' => array(
			'title'  => 'Подвал и контакты',
			'fields' => array(
				array( 'footer_description', 'Описание проекта', 'textarea' ),
				array( 'footer_navigation_title', 'Заголовок навигации', 'text' ),
				array( 'footer_documents_title', 'Заголовок документов', 'text' ),
				array( 'footer_document_1_label', 'Документ 1 — название', 'text' ),
				array( 'footer_document_1_url', 'Документ 1 — ссылка', 'url' ),
				array( 'footer_document_2_label', 'Документ 2 — название', 'text' ),
				array( 'footer_document_2_url', 'Документ 2 — ссылка', 'url' ),
				array( 'footer_document_3_label', 'Документ 3 — название', 'text' ),
				array( 'footer_document_3_url', 'Документ 3 — ссылка', 'url' ),
				array( 'footer_contacts_title', 'Заголовок контактов', 'text' ),
				array( 'footer_contact_text', 'Контактный текст', 'textarea' ),
				array( 'footer_contact_label', 'Подпись контактной ссылки', 'text' ),
				array( 'footer_contact_url', 'Адрес контактной ссылки', 'url' ),
				array( 'footer_legal', 'Реквизиты и юридическая подпись', 'textarea' ),
				array( 'footer_back_to_top', 'Текст ссылки наверх', 'text' ),
			),
		),
		'master_page' => array(
			'title'  => 'Страница мастер-класса',
			'fields' => array(
				array( 'master_intro', 'Вводный текст', 'textarea' ),
				array( 'master_price', 'Стоимость', 'text' ),
			),
		),
	);

	// Содержимое страниц перенесено в визуальные Gutenberg-блоки. Оставляем
	// в Customizer только действительно глобальные элементы сайта.
	$sections = array_intersect_key(
		$sections,
		array_flip( array( 'branding', 'footer' ) )
	);

	$defaults = custom_site_theme_defaults();
	$priority = 10;

	foreach ( $sections as $section_id => $section ) {
		$control_section = 'custom_site_' . $section_id;
		$wp_customize->add_section(
			$control_section,
			array(
				'title'    => $section['title'],
				'panel'    => 'custom_site_content',
				'priority' => $priority,
			)
		);

		foreach ( $section['fields'] as $field ) {
			list( $key, $label, $type ) = $field;
			$setting_id = 'custom_site_' . $key;
			$sanitize   = 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field';

			if ( 'url' === $type ) {
				$sanitize = 'esc_url_raw';
			}

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
					'sanitize_callback' => $sanitize,
				)
			);
			$wp_customize->add_control(
				$setting_id,
				array(
					'label'   => $label,
					'section' => $control_section,
					'type'    => $type,
				)
			);
		}

		$priority += 10;
	}

	$image_controls = array();

	foreach ( $image_controls as $image_control ) {
		list( $key, $section_id, $label ) = $image_control;
		$setting_id = 'custom_site_' . $key . '_image';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => $label,
					'section' => $section_id,
				)
			)
		);
	}
}
add_action( 'customize_register', 'custom_site_theme_customize_register' );
