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
		'brand_caption'            => 'Психолог-тренер, консультант',
		'menu_toggle_label'        => 'Меню',
		'hero_eyebrow'             => 'Давайте знакомиться!',
		'hero_title'               => 'Марина Маркус',
		'hero_lead'                => 'Психолог-тренер, консультант. Специалист по инициациям психологической зрелости.',
		'hero_primary_label'       => 'Выбрать направление',
		'hero_secondary_label'     => 'Познакомиться со мной',
		'manifesto_kicker'         => 'Идея проекта',
		'manifesto_title'          => 'Текст о ценностях, подходе и возможности создавать изменения',
		'manifesto_emphasis'       => 'осознанно',
		'manifesto_body'           => 'Здесь появится подтверждающий абзац или короткая история. Сейчас блок сохраняет композицию и примерную длину будущего текста.',
		'about_kicker'             => 'Знакомство',
		'about_title'              => 'Давайте знакомиться!',
		'about_name'               => 'Меня зовут Марина Маркус',
		'about_role'               => 'Психолог-тренер, консультант. НЛП-мастер и НЛП-тренер. Специалист по инициациям психологической зрелости, эриксоновскому гипнозу, психологии экстремальных и кризисных ситуаций.',
		'about_vertical_label'     => 'Профессиональный путь',
		'credential_1_title'       => 'Два образования',
		'credential_1_body'        => 'Первое высшее образование — техническое: инженер телекоммуникационных систем. Второе образование — практическая психология.',
		'credential_2_title'       => 'Авторские проекты',
		'credential_2_body'        => 'Соавтор трансформационной игры «Семь ключей мудрости». Основатель центра «неСлучайные путешествия».',
		'credential_3_title'       => 'Горы и география',
		'credential_3_body'        => 'Альпинист. Действительный член Русского Географического Общества.',
		'expertise_kicker'         => 'Компетенции',
		'expertise_title'          => 'Специализация и проекты',
		'expertise_intro'          => 'Мои профессиональные направления и авторские проекты.',
		'expertise_1_title'        => 'Психологическая зрелость',
		'expertise_1_body'         => 'Специалист по инициациям психологической зрелости.',
		'expertise_2_title'        => 'НЛП',
		'expertise_2_body'         => 'НЛП-мастер, НЛП-тренер.',
		'expertise_3_title'        => 'Эриксоновский гипноз',
		'expertise_3_body'         => 'Специалист эриксоновского гипноза.',
		'expertise_4_title'        => 'Кризисная психология',
		'expertise_4_body'         => 'Специалист по психологии экстремальных и кризисных ситуаций.',
		'expertise_5_title'        => 'Семь ключей мудрости',
		'expertise_5_body'         => 'Соавтор трансформационной игры «Семь ключей мудрости».',
		'expertise_6_title'        => 'неСлучайные путешествия',
		'expertise_6_body'         => 'Основатель центра «неСлучайные путешествия».',
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
		'footer_description'       => 'Марина Маркус — психолог-тренер, консультант. Специалист по инициациям психологической зрелости.',
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
