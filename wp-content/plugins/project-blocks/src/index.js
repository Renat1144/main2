import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	Button,
	PanelBody,
	RangeControl,
	SelectControl,
	TextControl as WordPressTextControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import heroMetadata from '../blocks/master-hero/block.json';
import videoMetadata from '../blocks/master-video/block.json';
import descriptionMetadata from '../blocks/master-description/block.json';
import audienceMetadata from '../blocks/master-audience/block.json';
import offerMetadata from '../blocks/master-offer/block.json';
import './home';

const RICH_FORMATS = [ 'core/bold', 'core/italic', 'core/link' ];
const PLAIN_FORMATS = [];
const TextControl = ( props ) => (
	<WordPressTextControl __next40pxDefaultSize { ...props } />
);

const FONT_OPTIONS = [
	{ label: __( 'По дизайну', 'project-blocks' ), value: '' },
	{
		label: __( 'Акцентный — Bodoni / Didot', 'project-blocks' ),
		value: 'display',
	},
	{
		label: __( 'Основной — Avenir / Segoe UI', 'project-blocks' ),
		value: 'body',
	},
	{ label: __( 'Служебный — Inter', 'project-blocks' ), value: 'utility' },
	{ label: __( 'Системный с засечками', 'project-blocks' ), value: 'serif' },
	{ label: __( 'Системный без засечек', 'project-blocks' ), value: 'sans' },
];

const FONT_STACKS = {
	display: '"Bodoni 72", Didot, "Times New Roman", serif',
	body: '"Avenir Next", Avenir, "Segoe UI", Arial, sans-serif',
	utility: 'Inter, "Helvetica Neue", Arial, sans-serif',
	serif: 'Georgia, "Times New Roman", serif',
	sans: 'Arial, "Helvetica Neue", sans-serif',
};

const typographyStyle = ( typography = {}, field ) => {
	const settings = typography[ field ] || {};
	return {
		fontFamily: FONT_STACKS[ settings.fontFamily ] || undefined,
		fontSize: settings.fontSize ? `${ settings.fontSize }px` : undefined,
	};
};

function TypographyControls( { fields, typography = {}, setAttributes } ) {
	const updateField = ( field, property, value ) => {
		const nextTypography = { ...typography };
		const nextField = { ...( nextTypography[ field ] || {} ) };

		if ( value === undefined || value === '' ) {
			delete nextField[ property ];
		} else {
			nextField[ property ] = value;
		}

		if ( Object.keys( nextField ).length ) {
			nextTypography[ field ] = nextField;
		} else {
			delete nextTypography[ field ];
		}

		setAttributes( { typography: nextTypography } );
	};

	return (
		<PanelBody
			title={ __( 'Шрифт и размер', 'project-blocks' ) }
			initialOpen={ false }
		>
			<p className="project-blocks-typography-help">
				{ __(
					'Настройки применяются только к выбранному виду текста. «По дизайну» возвращает исходное оформление.',
					'project-blocks'
				) }
			</p>
			{ fields.map( ( { key, label } ) => {
				const settings = typography[ key ] || {};
				return (
					<fieldset
						className="project-blocks-typography-field"
						key={ key }
					>
						<legend>{ label }</legend>
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Шрифт', 'project-blocks' ) }
							value={ settings.fontFamily || '' }
							options={ FONT_OPTIONS }
							onChange={ ( value ) =>
								updateField( key, 'fontFamily', value )
							}
						/>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							allowReset
							label={ __( 'Размер, px', 'project-blocks' ) }
							min={ 8 }
							max={ 200 }
							step={ 1 }
							value={ settings.fontSize }
							onChange={ ( value ) =>
								updateField( key, 'fontSize', value )
							}
						/>
					</fieldset>
				);
			} ) }
		</PanelBody>
	);
}

const replaceItem = ( items, index, change ) =>
	items.map( ( item, itemIndex ) =>
		itemIndex === index ? { ...item, ...change } : item
	);

function HeroEdit( { attributes, setAttributes, isSelected } ) {
	const {
		number,
		label,
		title,
		description,
		cardTitle,
		cardContent,
		imageId,
		imageUrl,
		imageAlt,
		imageCaption,
		imagePlaceholderCaption,
		buttonText,
		buttonUrl,
		typography,
	} = attributes;
	const blockProps = useBlockProps( { className: 'master-detail-hero' } );
	const selectImage = ( media ) => {
		setAttributes( {
			imageId: media.id || 0,
			imageUrl: media.url || '',
			imageAlt: imageAlt || media.alt || '',
		} );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Изображение', 'project-blocks' ) }
					initialOpen
				>
					<TextControl
						label={ __( 'Alt-текст', 'project-blocks' ) }
						value={ imageAlt }
						onChange={ ( value ) =>
							setAttributes( { imageAlt: value } )
						}
						help={ __(
							'Кратко опишите изображение для доступности.',
							'project-blocks'
						) }
					/>
					<TextControl
						label={ __( 'Подпись заглушки', 'project-blocks' ) }
						value={ imagePlaceholderCaption }
						onChange={ ( value ) =>
							setAttributes( { imagePlaceholderCaption: value } )
						}
					/>
				</PanelBody>
				<PanelBody
					title={ __( 'Кнопка', 'project-blocks' ) }
					initialOpen={ false }
				>
					<TextControl
						label={ __( 'Текст кнопки', 'project-blocks' ) }
						value={ buttonText }
						onChange={ ( value ) =>
							setAttributes( { buttonText: value } )
						}
					/>
					<TextControl
						label={ __( 'Ссылка кнопки', 'project-blocks' ) }
						type="url"
						value={ buttonUrl }
						onChange={ ( value ) =>
							setAttributes( { buttonUrl: value } )
						}
						placeholder="https://"
					/>
				</PanelBody>
				<TypographyControls
					fields={ [
						{
							key: 'label',
							label: __( 'Номер и подпись', 'project-blocks' ),
						},
						{
							key: 'title',
							label: __( 'Главный заголовок', 'project-blocks' ),
						},
						{
							key: 'description',
							label: __( 'Описание', 'project-blocks' ),
						},
						{
							key: 'cardTitle',
							label: __( 'Заголовок карточки', 'project-blocks' ),
						},
						{
							key: 'cardContent',
							label: __( 'Текст карточки', 'project-blocks' ),
						},
						{
							key: 'imageCaption',
							label: __(
								'Подпись изображения',
								'project-blocks'
							),
						},
						{
							key: 'button',
							label: __( 'Кнопка', 'project-blocks' ),
						},
					] }
					typography={ typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="master-detail-glow" aria-hidden="true" />
				<div className="shell master-detail-hero-grid">
					<div className="master-detail-hero-copy reveal">
						<p className="eyebrow eyebrow-light">
							<RichText
								tagName="span"
								style={ typographyStyle( typography, 'label' ) }
								value={ number }
								onChange={ ( value ) =>
									setAttributes( { number: value } )
								}
								allowedFormats={ PLAIN_FORMATS }
								placeholder="01"
							/>
							<RichText
								tagName="span"
								className="project-blocks-inline-label"
								style={ typographyStyle( typography, 'label' ) }
								value={ label }
								onChange={ ( value ) =>
									setAttributes( { label: value } )
								}
								allowedFormats={ PLAIN_FORMATS }
								placeholder={ __(
									'Мастер-класс',
									'project-blocks'
								) }
							/>
						</p>
						<RichText
							tagName="h1"
							style={ typographyStyle( typography, 'title' ) }
							value={ title }
							onChange={ ( value ) =>
								setAttributes( { title: value } )
							}
							allowedFormats={ RICH_FORMATS }
							placeholder={ __(
								'Название мастер-класса',
								'project-blocks'
							) }
						/>
						{ ( description || isSelected ) && (
							<RichText
								tagName="div"
								className="master-detail-description"
								style={ typographyStyle(
									typography,
									'description'
								) }
								value={ description }
								onChange={ ( value ) =>
									setAttributes( { description: value } )
								}
								allowedFormats={ RICH_FORMATS }
								placeholder={ __(
									'Добавьте краткое описание…',
									'project-blocks'
								) }
							/>
						) }
						<div className="copy-placeholder copy-placeholder-dark copy-placeholder-lead">
							<RichText
								tagName="span"
								className="placeholder-caption"
								style={ typographyStyle(
									typography,
									'cardTitle'
								) }
								value={ cardTitle }
								onChange={ ( value ) =>
									setAttributes( { cardTitle: value } )
								}
								allowedFormats={ PLAIN_FORMATS }
								placeholder={ __(
									'Заголовок карточки',
									'project-blocks'
								) }
							/>
							{ cardContent || isSelected ? (
								<RichText
									tagName="div"
									className="project-blocks-card-content"
									style={ typographyStyle(
										typography,
										'cardContent'
									) }
									value={ cardContent }
									onChange={ ( value ) =>
										setAttributes( { cardContent: value } )
									}
									allowedFormats={ RICH_FORMATS }
									placeholder={ __(
										'Содержимое текстовой карточки…',
										'project-blocks'
									) }
								/>
							) : (
								<>
									<span className="placeholder-line placeholder-line-full" />
									<span className="placeholder-line placeholder-line-medium" />
									<span className="placeholder-line placeholder-line-short" />
								</>
							) }
						</div>
						{ ( buttonText || isSelected ) && (
							<div className="master-detail-hero-actions">
								<RichText
									tagName="span"
									className="button button-primary"
									style={ typographyStyle(
										typography,
										'button'
									) }
									value={ buttonText }
									onChange={ ( value ) =>
										setAttributes( { buttonText: value } )
									}
									allowedFormats={ PLAIN_FORMATS }
									placeholder={ __(
										'Добавить кнопку',
										'project-blocks'
									) }
								/>
							</div>
						) }
					</div>

					<div className="master-detail-visual reveal">
						<div
							className="master-detail-orbit"
							aria-hidden="true"
						/>
						{ imageUrl ? (
							<figure className="master-detail-photo master-detail-photo-image">
								<img src={ imageUrl } alt={ imageAlt } />
								{ ( imageCaption || isSelected ) && (
									<RichText
										tagName="figcaption"
										className="project-blocks-image-caption"
										style={ typographyStyle(
											typography,
											'imageCaption'
										) }
										value={ imageCaption }
										onChange={ ( value ) =>
											setAttributes( {
												imageCaption: value,
											} )
										}
										allowedFormats={ RICH_FORMATS }
										placeholder={ __(
											'Подпись к изображению',
											'project-blocks'
										) }
									/>
								) }
							</figure>
						) : (
							<div className="photo-placeholder master-detail-photo">
								<span>
									{ __( 'ТУТ БУДЕТ ФОТО', 'project-blocks' ) }
								</span>
								<small
									style={ typographyStyle(
										typography,
										'imageCaption'
									) }
								>
									{ imagePlaceholderCaption }
								</small>
							</div>
						) }
						<div className="project-blocks-media-actions">
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ selectImage }
									allowedTypes={ [ 'image' ] }
									value={ imageId }
									render={ ( { open } ) => (
										<Button
											variant="primary"
											onClick={ open }
										>
											{ imageUrl
												? __(
														'Заменить',
														'project-blocks'
												  )
												: __(
														'Выбрать изображение',
														'project-blocks'
												  ) }
										</Button>
									) }
								/>
							</MediaUploadCheck>
							{ imageUrl && (
								<Button
									variant="secondary"
									onClick={ () =>
										setAttributes( {
											imageId: 0,
											imageUrl: '',
										} )
									}
								>
									{ __( 'Убрать', 'project-blocks' ) }
								</Button>
							) }
						</div>
					</div>
				</div>
			</section>
		</>
	);
}

function VideoEdit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: 'master-video-section section-pad',
	} );
	return (
		<>
			<InspectorControls>
				<TypographyControls
					fields={ [
						{
							key: 'kicker',
							label: __( 'Маленькая подпись', 'project-blocks' ),
						},
						{
							key: 'title',
							label: __( 'Заголовок', 'project-blocks' ),
						},
						{
							key: 'placeholderTitle',
							label: __( 'Заголовок видео', 'project-blocks' ),
						},
						{
							key: 'placeholderText',
							label: __( 'Подпись видео', 'project-blocks' ),
						},
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="shell">
					<div className="master-detail-section-heading reveal">
						<RichText
							tagName="p"
							className="section-kicker"
							style={ typographyStyle(
								attributes.typography,
								'kicker'
							) }
							value={ attributes.kicker }
							onChange={ ( value ) =>
								setAttributes( { kicker: value } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
						<RichText
							tagName="h2"
							style={ typographyStyle(
								attributes.typography,
								'title'
							) }
							value={ attributes.title }
							onChange={ ( value ) =>
								setAttributes( { title: value } )
							}
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
					<div className="master-video-placeholder reveal">
						<span className="master-video-play" aria-hidden="true">
							▶
						</span>
						<RichText
							tagName="strong"
							style={ typographyStyle(
								attributes.typography,
								'placeholderTitle'
							) }
							value={ attributes.placeholderTitle }
							onChange={ ( value ) =>
								setAttributes( { placeholderTitle: value } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
						<RichText
							tagName="small"
							style={ typographyStyle(
								attributes.typography,
								'placeholderText'
							) }
							value={ attributes.placeholderText }
							onChange={ ( value ) =>
								setAttributes( { placeholderText: value } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
					</div>
				</div>
			</section>
		</>
	);
}

function DescriptionEdit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: 'master-copy-section section-pad',
	} );
	return (
		<>
			<InspectorControls>
				<TypographyControls
					fields={ [
						{
							key: 'kicker',
							label: __( 'Маленькая подпись', 'project-blocks' ),
						},
						{
							key: 'title',
							label: __( 'Заголовок', 'project-blocks' ),
						},
						{
							key: 'content',
							label: __( 'Основной текст', 'project-blocks' ),
						},
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="shell master-copy-grid">
					<div className="master-copy-heading reveal">
						<RichText
							tagName="p"
							className="section-kicker"
							style={ typographyStyle(
								attributes.typography,
								'kicker'
							) }
							value={ attributes.kicker }
							onChange={ ( value ) =>
								setAttributes( { kicker: value } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
						<RichText
							tagName="h2"
							style={ typographyStyle(
								attributes.typography,
								'title'
							) }
							value={ attributes.title }
							onChange={ ( value ) =>
								setAttributes( { title: value } )
							}
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
					<div className="copy-placeholder copy-placeholder-light master-copy-placeholder reveal">
						<RichText
							tagName="div"
							className="master-editor-content"
							style={ typographyStyle(
								attributes.typography,
								'content'
							) }
							value={ attributes.content }
							onChange={ ( value ) =>
								setAttributes( { content: value } )
							}
							allowedFormats={ RICH_FORMATS }
							placeholder={ __(
								'Введите описание мастер-класса…',
								'project-blocks'
							) }
						/>
						<span className="placeholder-line placeholder-line-full" />
						<span className="placeholder-line placeholder-line-medium" />
						<span className="placeholder-line placeholder-line-short" />
					</div>
				</div>
			</section>
		</>
	);
}

function AudienceEdit( { attributes, setAttributes, isSelected } ) {
	const items = attributes.items || [];
	const blockProps = useBlockProps( {
		className: 'master-audience section-pad',
	} );
	return (
		<>
			<InspectorControls>
				<TypographyControls
					fields={ [
						{
							key: 'kicker',
							label: __( 'Маленькая подпись', 'project-blocks' ),
						},
						{
							key: 'title',
							label: __( 'Заголовок', 'project-blocks' ),
						},
						{
							key: 'itemNumber',
							label: __( 'Номера карточек', 'project-blocks' ),
						},
						{
							key: 'itemText',
							label: __( 'Тексты карточек', 'project-blocks' ),
						},
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="shell">
					<div className="master-detail-section-heading master-detail-section-heading-light reveal">
						<RichText
							tagName="p"
							className="section-kicker section-kicker-gold"
							style={ typographyStyle(
								attributes.typography,
								'kicker'
							) }
							value={ attributes.kicker }
							onChange={ ( value ) =>
								setAttributes( { kicker: value } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
						<RichText
							tagName="h2"
							style={ typographyStyle(
								attributes.typography,
								'title'
							) }
							value={ attributes.title }
							onChange={ ( value ) =>
								setAttributes( { title: value } )
							}
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
					<div className="master-audience-grid">
						{ items.map( ( item, index ) => (
							<div
								className="master-audience-item reveal"
								key={ index }
							>
								<RichText
									tagName="span"
									style={ typographyStyle(
										attributes.typography,
										'itemNumber'
									) }
									value={ item.number }
									onChange={ ( value ) =>
										setAttributes( {
											items: replaceItem( items, index, {
												number: value,
											} ),
										} )
									}
									allowedFormats={ PLAIN_FORMATS }
								/>
								{ item.text || isSelected ? (
									<RichText
										tagName="div"
										className="master-audience-text"
										style={ typographyStyle(
											attributes.typography,
											'itemText'
										) }
										value={ item.text }
										onChange={ ( value ) =>
											setAttributes( {
												items: replaceItem(
													items,
													index,
													{
														text: value,
													}
												),
											} )
										}
										allowedFormats={ RICH_FORMATS }
										placeholder={ __(
											'Введите текст…',
											'project-blocks'
										) }
									/>
								) : (
									<div
										className="placeholder-lines"
										aria-hidden="true"
									>
										<i />
										<i />
									</div>
								) }
							</div>
						) ) }
					</div>
				</div>
			</section>
		</>
	);
}

function OfferEdit( { attributes, setAttributes, isSelected } ) {
	const items = attributes.items || [];
	const blockProps = useBlockProps( {
		className: 'master-offer section-pad',
	} );
	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Кнопка', 'project-blocks' ) }
					initialOpen
				>
					<TextControl
						label={ __( 'Текст кнопки', 'project-blocks' ) }
						value={ attributes.buttonText }
						onChange={ ( value ) =>
							setAttributes( { buttonText: value } )
						}
					/>
					<TextControl
						label={ __( 'Ссылка кнопки', 'project-blocks' ) }
						type="url"
						value={ attributes.buttonUrl }
						onChange={ ( value ) =>
							setAttributes( { buttonUrl: value } )
						}
						placeholder="https://"
						help={ __(
							'Пока ссылка пуста, кнопка остаётся отключённой.',
							'project-blocks'
						) }
					/>
				</PanelBody>
				<TypographyControls
					fields={ [
						{
							key: 'label',
							label: __( 'Номер и подпись', 'project-blocks' ),
						},
						{
							key: 'title',
							label: __( 'Заголовок', 'project-blocks' ),
						},
						{
							key: 'price',
							label: __( 'Стоимость', 'project-blocks' ),
						},
						{
							key: 'compositionTitle',
							label: __( 'Заголовок состава', 'project-blocks' ),
						},
						{
							key: 'itemNumber',
							label: __( 'Номера пунктов', 'project-blocks' ),
						},
						{
							key: 'itemText',
							label: __( 'Тексты пунктов', 'project-blocks' ),
						},
						{
							key: 'button',
							label: __( 'Кнопка', 'project-blocks' ),
						},
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="shell">
					<div className="master-offer-card reveal">
						<div className="master-offer-copy">
							<p className="eyebrow">
								<RichText
									tagName="span"
									style={ typographyStyle(
										attributes.typography,
										'label'
									) }
									value={ attributes.number }
									onChange={ ( value ) =>
										setAttributes( { number: value } )
									}
									allowedFormats={ PLAIN_FORMATS }
								/>
								<RichText
									tagName="span"
									style={ typographyStyle(
										attributes.typography,
										'label'
									) }
									value={ attributes.label }
									onChange={ ( value ) =>
										setAttributes( { label: value } )
									}
									allowedFormats={ PLAIN_FORMATS }
								/>
							</p>
							<RichText
								tagName="h2"
								style={ typographyStyle(
									attributes.typography,
									'title'
								) }
								value={ attributes.title }
								onChange={ ( value ) =>
									setAttributes( { title: value } )
								}
								allowedFormats={ RICH_FORMATS }
							/>
							<RichText
								tagName="p"
								className="master-offer-price"
								style={ typographyStyle(
									attributes.typography,
									'price'
								) }
								value={ attributes.price }
								onChange={ ( value ) =>
									setAttributes( { price: value } )
								}
								allowedFormats={ PLAIN_FORMATS }
							/>
						</div>
						<div className="master-offer-content">
							<RichText
								tagName="p"
								className="placeholder-caption"
								style={ typographyStyle(
									attributes.typography,
									'compositionTitle'
								) }
								value={ attributes.compositionTitle }
								onChange={ ( value ) =>
									setAttributes( { compositionTitle: value } )
								}
								allowedFormats={ PLAIN_FORMATS }
							/>
							{ items.map( ( item, index ) => (
								<div className="master-offer-row" key={ index }>
									<RichText
										tagName="span"
										style={ typographyStyle(
											attributes.typography,
											'itemNumber'
										) }
										value={ item.number }
										onChange={ ( value ) =>
											setAttributes( {
												items: replaceItem(
													items,
													index,
													{ number: value }
												),
											} )
										}
										allowedFormats={ PLAIN_FORMATS }
									/>
									{ item.text || isSelected ? (
										<RichText
											tagName="div"
											className="master-offer-item-text"
											style={ typographyStyle(
												attributes.typography,
												'itemText'
											) }
											value={ item.text }
											onChange={ ( value ) =>
												setAttributes( {
													items: replaceItem(
														items,
														index,
														{ text: value }
													),
												} )
											}
											allowedFormats={ RICH_FORMATS }
											placeholder={ __(
												'Состав…',
												'project-blocks'
											) }
										/>
									) : (
										<i aria-hidden="true" />
									) }
								</div>
							) ) }
						</div>
						<RichText
							tagName="span"
							className="button button-primary master-pay-button"
							style={ typographyStyle(
								attributes.typography,
								'button'
							) }
							value={ attributes.buttonText }
							onChange={ ( value ) =>
								setAttributes( { buttonText: value } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
					</div>
				</div>
			</section>
		</>
	);
}

registerBlockType( heroMetadata.name, {
	edit: HeroEdit,
	save: () => null,
} );

registerBlockType( videoMetadata.name, {
	edit: VideoEdit,
	save: () => null,
} );

registerBlockType( descriptionMetadata.name, {
	edit: DescriptionEdit,
	save: () => null,
} );

registerBlockType( audienceMetadata.name, {
	edit: AudienceEdit,
	save: () => null,
} );

registerBlockType( offerMetadata.name, {
	edit: OfferEdit,
	save: () => null,
} );
