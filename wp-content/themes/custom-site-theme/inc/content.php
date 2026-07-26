<?php
/**
 * Default editable content and helper functions.
 *
 * @package CustomSiteTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function custom_site_theme_defaults() {
	return array(
		'brand_caption'            => 'Основное направление',
		'menu_toggle_label'        => 'Меню',
		'hero_eyebrow'             => 'Основное направление',
		'hero_title'               => 'Основной заголовок проекта',
		'hero_lead'                => 'Краткое описание главного предложения и результата, который получает посетитель. Финальный текст будет добавлен позже.',
		'hero_primary_label'       => 'Выбрать направление',
		'hero_secondary_label'     => 'Узнать о подходе',
		'manifesto_kicker'         => 'Идея проекта',
		'manifesto_title'          => 'Текст о ценностях, подходе и возможности создавать изменения',
		'manifesto_emphasis'       => 'осознанно',
		'manifesto_body'           => 'Здесь появится подтверждающий абзац или короткая история. Сейчас блок сохраняет композицию и примерную длину будущего текста.',
		'about_kicker'             => 'Знакомство',
		'about_title'              => 'О специалисте',
		'about_name'               => 'Имя специалиста',
		'about_role'               => 'Краткое описание профессионального направления и основной специализации.',
		'about_vertical_label'     => 'Профессиональный путь',
		'credential_1_title'       => 'Образование',
		'credential_1_body'        => 'Информация об основном и дополнительном образовании будет добавлена позже.',
		'credential_2_title'       => 'Практика',
		'credential_2_body'        => 'Описание опыта работы, профессиональных проектов и форматов практики.',
		'credential_3_title'       => 'Авторский подход',
		'credential_3_body'        => 'Краткое описание методики и принципов работы будет размещено здесь.',
		'expertise_kicker'         => 'Компетенции',
		'expertise_title'          => 'Основные направления работы',
		'expertise_intro'          => 'Содержимое карточек будет заменено после утверждения финальных текстов.',
		'expertise_1_title'        => 'Название направления',
		'expertise_1_body'         => 'Краткое описание направления, его задач и формата работы.',
		'expertise_2_title'        => 'Название направления',
		'expertise_2_body'         => 'Краткое описание направления, его задач и формата работы.',
		'expertise_3_title'        => 'Название направления',
		'expertise_3_body'         => 'Краткое описание направления, его задач и формата работы.',
		'expertise_4_title'        => 'Опыт и профессиональная практика',
		'expertise_4_body'         => 'Здесь будет размещён расширенный текст об опыте, проектах и результатах работы.',
		'expertise_5_title'        => 'Практический подход',
		'expertise_5_body'         => 'Описание принципов построения программ и индивидуальной работы.',
		'expertise_6_title'        => 'Постоянное развитие',
		'expertise_6_body'         => 'Информация о дополнительном обучении и профессиональном развитии.',
		'method_intro_kicker'      => 'Видео о подходе',
		'method_intro_title'       => 'Что представляет собой метод?',
		'method_intro_body'        => 'Здесь будет размещено вводное видео. До получения материала используется нейтральная заглушка нужного формата.',
		'method_kicker'            => 'Метод',
		'method_title'             => 'Структура психологической работы',
		'method_lead'              => 'Нейтральное описание принципа работы. Финальная формулировка появится после предоставления текста.',
		'method_step_1_title'      => 'Определение запроса',
		'method_step_1_body'       => 'Осознание текущей ситуации и формулирование направления работы.',
		'method_step_2_title'      => 'Формирование нового сценария',
		'method_step_2_body'       => 'Работа с внутренними установками и доступными способами изменений.',
		'method_step_3_title'      => 'Закрепление результата',
		'method_step_3_body'       => 'Перенос новых стратегий в повседневную жизнь и практику.',
		'masterclasses_kicker'     => 'Материалы',
		'masterclasses_title'      => 'Мастер-классы',
		'masterclasses_intro'      => 'Содержимое карточек и материалы будут добавляться по мере подготовки.',
		'masterclass_1_title'      => 'Мастер-класс №1',
		'masterclass_1_body'       => 'Краткое описание темы и результата будет добавлено позже.',
		'masterclass_2_title'      => 'Мастер-класс №2',
		'masterclass_2_body'       => 'Краткое описание темы и результата будет добавлено позже.',
		'masterclass_3_title'      => 'Мастер-класс №3',
		'masterclass_3_body'       => 'Краткое описание темы и результата будет добавлено позже.',
		'stories_kicker'           => 'Истории',
		'stories_title'            => 'Результаты участниц',
		'stories_intro'            => 'Фотографии, имена и тексты историй будут добавлены после подготовки материалов.',
		'story_1_name'             => 'Имя участницы',
		'story_1_body'             => 'Описание исходной ситуации и результата будет размещено здесь.',
		'story_2_name'             => 'Имя участницы',
		'story_2_body'             => 'Описание исходной ситуации и результата будет размещено здесь.',
		'story_3_name'             => 'Имя участницы',
		'story_3_body'             => 'Описание исходной ситуации и результата будет размещено здесь.',
		'coaching_eyebrow'         => 'Индивидуальный формат',
		'coaching_title'           => 'Коуч-сессия',
		'coaching_body'            => 'Описание формата индивидуальной работы, условий и доступных вариантов будет добавлено позже.',
		'coaching_button'          => 'Оставить заявку',
		'footer_description'       => 'Краткое описание проекта и его основного направления.',
		'footer_navigation_title'  => 'Навигация',
		'footer_documents_title'   => 'Документы',
		'footer_document_1_label'  => 'Политика конфиденциальности',
		'footer_document_1_url'    => '',
		'footer_document_2_label'  => 'Пользовательское соглашение',
		'footer_document_2_url'    => '',
		'footer_document_3_label'  => 'Согласие на обработку данных',
		'footer_document_3_url'    => '',
		'footer_contacts_title'    => 'Контакты',
		'footer_contact_text'      => 'Контактные данные будут добавлены позже.',
		'footer_contact_label'     => 'Адрес электронной почты',
		'footer_contact_url'       => '',
		'footer_legal'             => 'Реквизиты и сведения об организации будут добавлены позже.',
		'footer_back_to_top'       => 'Наверх ↑',
		'master_intro'             => 'Текст будет добавлен',
		'master_price'             => 'Стоимость будет добавлена',
	);
}

function custom_site_theme_mod( $key ) {
	$defaults = custom_site_theme_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return get_theme_mod( 'custom_site_' . $key, $default );
}

function custom_site_theme_image( $key, $fallback = '' ) {
	$image = get_theme_mod( 'custom_site_' . $key . '_image', '' );

	if ( $image ) {
		return $image;
	}

	return $fallback ? get_template_directory_uri() . '/assets/images/' . ltrim( $fallback, '/' ) : '';
}
