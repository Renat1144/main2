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
	TextControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import heroMetadata from '../blocks/home-hero/block.json';
import manifestoMetadata from '../blocks/home-manifesto/block.json';
import aboutMetadata from '../blocks/home-about/block.json';
import expertiseMetadata from '../blocks/home-expertise/block.json';
import methodIntroMetadata from '../blocks/home-method-intro/block.json';
import methodMetadata from '../blocks/home-method/block.json';
import masterclassesMetadata from '../blocks/home-masterclasses/block.json';
import storiesMetadata from '../blocks/home-stories/block.json';
import coachingMetadata from '../blocks/home-coaching/block.json';

const RICH_FORMATS = [ 'core/bold', 'core/italic', 'core/link' ];
const PLAIN_FORMATS = [];
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

const replaceItem = ( items, index, change ) =>
	items.map( ( item, itemIndex ) =>
		itemIndex === index ? { ...item, ...change } : item
	);

function TypographyControls( { fields, typography = {}, setAttributes } ) {
	const update = ( field, property, value ) => {
		const next = { ...typography };
		const settings = { ...( next[ field ] || {} ) };
		if ( value === '' || value === undefined ) {
			delete settings[ property ];
		} else {
			settings[ property ] = value;
		}
		if ( Object.keys( settings ).length ) {
			next[ field ] = settings;
		} else {
			delete next[ field ];
		}
		setAttributes( { typography: next } );
	};

	return (
		<PanelBody
			title={ __( 'Шрифт и размер', 'project-blocks' ) }
			initialOpen={ false }
		>
			<p className="project-blocks-typography-help">
				{ __(
					'Выберите вид текста и настройте его отдельно. «По дизайну» возвращает исходный стиль.',
					'project-blocks'
				) }
			</p>
			{ fields.map( ( field ) => {
				const settings = typography[ field.key ] || {};
				return (
					<fieldset
						className="project-blocks-typography-field"
						key={ field.key }
					>
						<legend>{ field.label }</legend>
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Шрифт', 'project-blocks' ) }
							value={ settings.fontFamily || '' }
							options={ FONT_OPTIONS }
							onChange={ ( value ) =>
								update( field.key, 'fontFamily', value )
							}
						/>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							allowReset
							label={ __( 'Размер, px', 'project-blocks' ) }
							min={ 8 }
							max={ 200 }
							value={ settings.fontSize }
							onChange={ ( value ) =>
								update( field.key, 'fontSize', value )
							}
						/>
					</fieldset>
				);
			} ) }
		</PanelBody>
	);
}

function ImageActions( { imageId = 0, imageUrl = '', onChange } ) {
	return (
		<div className="project-blocks-editor-image-actions">
			<MediaUploadCheck>
				<MediaUpload
					allowedTypes={ [ 'image' ] }
					value={ imageId }
					onSelect={ ( media ) =>
						onChange( {
							imageId: media.id || 0,
							imageUrl: media.url || '',
							imageAlt: media.alt || '',
						} )
					}
					render={ ( { open } ) => (
						<Button variant="primary" onClick={ open }>
							{ imageUrl
								? __( 'Заменить', 'project-blocks' )
								: __( 'Выбрать фото', 'project-blocks' ) }
						</Button>
					) }
				/>
			</MediaUploadCheck>
			{ imageUrl && (
				<Button
					variant="secondary"
					onClick={ () => onChange( { imageId: 0, imageUrl: '' } ) }
				>
					{ __( 'Убрать', 'project-blocks' ) }
				</Button>
			) }
		</div>
	);
}

function ImagePanel( { images } ) {
	return (
		<PanelBody
			title={ __( 'Изображения', 'project-blocks' ) }
			initialOpen={ false }
		>
			{ images.map( ( image, index ) => (
				<div
					className="project-blocks-image-inspector-item"
					key={ `${ image.label }-${ index }` }
				>
					<strong>{ image.label }</strong>
					<TextControl
						__next40pxDefaultSize
						label={ __( 'Alt-текст', 'project-blocks' ) }
						value={ image.alt || '' }
						onChange={ image.onAltChange }
						help={ __(
							'Краткое описание изображения для доступности.',
							'project-blocks'
						) }
					/>
				</div>
			) ) }
		</PanelBody>
	);
}

const field = ( key, label ) => ( { key, label } );

function HomeHeroEdit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hero' } );
	const setImage = ( change ) =>
		setAttributes( {
			...change,
			imageAlt: change.imageAlt || attributes.imageAlt,
		} );
	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Кнопки и ссылки', 'project-blocks' ) }
					initialOpen
				>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'Ссылка основной кнопки',
							'project-blocks'
						) }
						value={ attributes.primaryUrl }
						onChange={ ( primaryUrl ) =>
							setAttributes( { primaryUrl } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						label={ __( 'Ссылка «О методе»', 'project-blocks' ) }
						value={ attributes.secondaryUrl }
						onChange={ ( secondaryUrl ) =>
							setAttributes( { secondaryUrl } )
						}
					/>
				</PanelBody>
				<ImagePanel
					images={ [
						{
							label: __( 'Портрет', 'project-blocks' ),
							alt: attributes.imageAlt,
							onAltChange: ( imageAlt ) =>
								setAttributes( { imageAlt } ),
						},
					] }
				/>
				<TypographyControls
					fields={ [
						field(
							'eyebrow',
							__( 'Номер и подпись', 'project-blocks' )
						),
						field( 'title', __( 'Заголовок', 'project-blocks' ) ),
						field( 'lead', __( 'Описание', 'project-blocks' ) ),
						field( 'button', __( 'Кнопки', 'project-blocks' ) ),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section { ...blockProps } aria-labelledby={ undefined }>
				<div className="hero-aurora" aria-hidden="true" />
				<div className="shell hero-grid">
					<div className="hero-copy reveal">
						<p
							className="eyebrow"
							style={ typographyStyle(
								attributes.typography,
								'eyebrow'
							) }
						>
							<RichText
								tagName="span"
								value={ attributes.number }
								onChange={ ( number ) =>
									setAttributes( { number } )
								}
								allowedFormats={ PLAIN_FORMATS }
							/>
							<RichText
								tagName="span"
								value={ attributes.eyebrow }
								onChange={ ( eyebrow ) =>
									setAttributes( { eyebrow } )
								}
								allowedFormats={ PLAIN_FORMATS }
							/>
						</p>
						<RichText
							tagName="h1"
							style={ typographyStyle(
								attributes.typography,
								'title'
							) }
							value={ attributes.title }
							onChange={ ( title ) => setAttributes( { title } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<RichText
							tagName="p"
							className="hero-lead"
							style={ typographyStyle(
								attributes.typography,
								'lead'
							) }
							value={ attributes.lead }
							onChange={ ( lead ) => setAttributes( { lead } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<div className="hero-actions">
							<span
								className="button button-primary"
								style={ typographyStyle(
									attributes.typography,
									'button'
								) }
							>
								<RichText
									tagName="span"
									value={ attributes.primaryLabel }
									onChange={ ( primaryLabel ) =>
										setAttributes( { primaryLabel } )
									}
									allowedFormats={ PLAIN_FORMATS }
								/>{ ' ' }
								<span aria-hidden="true">↗</span>
							</span>
							<RichText
								tagName="span"
								className="text-link"
								style={ typographyStyle(
									attributes.typography,
									'button'
								) }
								value={ attributes.secondaryLabel }
								onChange={ ( secondaryLabel ) =>
									setAttributes( { secondaryLabel } )
								}
								allowedFormats={ PLAIN_FORMATS }
							/>
						</div>
					</div>
					<div className="hero-visual reveal">
						<div className="photo-placeholder-portrait hero-portrait-frame project-blocks-editable-image">
							{ attributes.imageUrl && (
								<img
									className="hero-portrait-image"
									src={ attributes.imageUrl }
									alt={ attributes.imageAlt }
								/>
							) }
							<ImageActions
								imageId={ attributes.imageId }
								imageUrl={ attributes.imageUrl }
								onChange={ setImage }
							/>
						</div>
					</div>
				</div>
			</section>
		</>
	);
}

function ManifestoEdit( { attributes, setAttributes } ) {
	const gallery = attributes.gallery || [];
	const classes = [
		'gallery-photo gallery-photo-wide',
		'gallery-photo',
		'gallery-photo gallery-photo-tall',
		'gallery-photo',
		'gallery-photo gallery-photo-wide',
	];
	return (
		<>
			<InspectorControls>
				<ImagePanel
					images={ gallery.map( ( image, index ) => ( {
						label: `${ __( 'Фото', 'project-blocks' ) } ${
							index + 1
						}`,
						alt: image.imageAlt,
						onAltChange: ( imageAlt ) =>
							setAttributes( {
								gallery: replaceItem( gallery, index, {
									imageAlt,
								} ),
							} ),
					} ) ) }
				/>
				<TypographyControls
					fields={ [
						field(
							'kicker',
							__( 'Маленькая подпись', 'project-blocks' )
						),
						field( 'title', __( 'Заголовок', 'project-blocks' ) ),
						field(
							'emphasis',
							__( 'Акцентная строка', 'project-blocks' )
						),
						field( 'body', __( 'Описание', 'project-blocks' ) ),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( { className: 'manifesto section-pad' } ) }
			>
				<div className="shell manifesto-grid">
					<RichText
						tagName="p"
						className="section-kicker reveal"
						style={ typographyStyle(
							attributes.typography,
							'kicker'
						) }
						value={ attributes.kicker }
						onChange={ ( kicker ) => setAttributes( { kicker } ) }
						allowedFormats={ PLAIN_FORMATS }
					/>
					<div className="manifesto-copy reveal">
						<h2>
							<RichText
								tagName="span"
								style={ typographyStyle(
									attributes.typography,
									'title'
								) }
								value={ attributes.title }
								onChange={ ( title ) =>
									setAttributes( { title } )
								}
								allowedFormats={ RICH_FORMATS }
							/>
							<RichText
								tagName="em"
								style={ typographyStyle(
									attributes.typography,
									'emphasis'
								) }
								value={ attributes.emphasis }
								onChange={ ( emphasis ) =>
									setAttributes( { emphasis } )
								}
								allowedFormats={ RICH_FORMATS }
							/>
						</h2>
						<RichText
							tagName="p"
							style={ typographyStyle(
								attributes.typography,
								'body'
							) }
							value={ attributes.body }
							onChange={ ( body ) => setAttributes( { body } ) }
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
				</div>
				<div className="gallery-strip reveal">
					{ classes.map( ( itemClass, index ) => {
						const image = gallery[ index ] || {};
						return (
							<div
								className={ `photo-placeholder ${ itemClass } project-blocks-editable-image ${
									image.imageUrl
										? 'project-blocks-has-image project-blocks-gallery-image'
										: ''
								}` }
								key={ index }
							>
								{ image.imageUrl ? (
									<img
										src={ image.imageUrl }
										alt={ image.imageAlt || '' }
									/>
								) : (
									<span>ТУТ БУДЕТ ФОТО</span>
								) }
								<ImageActions
									imageId={ image.imageId }
									imageUrl={ image.imageUrl }
									onChange={ ( change ) =>
										setAttributes( {
											gallery: replaceItem(
												gallery,
												index,
												{
													...change,
													imageAlt:
														change.imageAlt ||
														image.imageAlt,
												}
											),
										} )
									}
								/>
							</div>
						);
					} ) }
				</div>
			</section>
		</>
	);
}

function AboutEdit( { attributes, setAttributes } ) {
	const credentials = attributes.credentials || [];
	return (
		<>
			<InspectorControls>
				<ImagePanel
					images={ [
						{
							label: __( 'Портрет', 'project-blocks' ),
							alt: attributes.imageAlt,
							onAltChange: ( imageAlt ) =>
								setAttributes( { imageAlt } ),
						},
					] }
				/>
				<TypographyControls
					fields={ [
						field(
							'kicker',
							__( 'Маленькая подпись', 'project-blocks' )
						),
						field( 'title', __( 'Заголовок', 'project-blocks' ) ),
						field( 'name', __( 'Имя', 'project-blocks' ) ),
						field( 'role', __( 'Описание', 'project-blocks' ) ),
						field(
							'credentialTitle',
							__( 'Заголовки фактов', 'project-blocks' )
						),
						field(
							'credentialBody',
							__( 'Описания фактов', 'project-blocks' )
						),
						field(
							'verticalLabel',
							__( 'Вертикальная подпись', 'project-blocks' )
						),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( { className: 'about section-pad' } ) }
				id="about"
			>
				<div className="shell about-grid">
					<div className="about-visual reveal">
						<div className="photo-frame">
							<div className="photo-placeholder-about portrait-panel project-blocks-editable-image">
								{ attributes.imageUrl && (
									<img
										className="section-portrait about-portrait"
										src={ attributes.imageUrl }
										alt={ attributes.imageAlt }
									/>
								) }
								<ImageActions
									imageId={ attributes.imageId }
									imageUrl={ attributes.imageUrl }
									onChange={ ( change ) =>
										setAttributes( {
											...change,
											imageAlt:
												change.imageAlt ||
												attributes.imageAlt,
										} )
									}
								/>
							</div>
						</div>
						<RichText
							tagName="p"
							className="vertical-label"
							style={ typographyStyle(
								attributes.typography,
								'verticalLabel'
							) }
							value={ attributes.verticalLabel }
							onChange={ ( verticalLabel ) =>
								setAttributes( { verticalLabel } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
					</div>
					<div className="about-copy reveal">
						<RichText
							tagName="p"
							className="section-kicker"
							style={ typographyStyle(
								attributes.typography,
								'kicker'
							) }
							value={ attributes.kicker }
							onChange={ ( kicker ) =>
								setAttributes( { kicker } )
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
							onChange={ ( title ) => setAttributes( { title } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<RichText
							tagName="p"
							className="about-name"
							style={ typographyStyle(
								attributes.typography,
								'name'
							) }
							value={ attributes.name }
							onChange={ ( name ) => setAttributes( { name } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<RichText
							tagName="p"
							className="about-role"
							style={ typographyStyle(
								attributes.typography,
								'role'
							) }
							value={ attributes.role }
							onChange={ ( role ) => setAttributes( { role } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<div className="credentials" role="list">
							{ credentials.map( ( item, index ) => (
								<article
									className="credential"
									role="listitem"
									key={ index }
								>
									<span
										className="credential-mark"
										aria-hidden="true"
									>
										✦
									</span>
									<div>
										<RichText
											tagName="h3"
											style={ typographyStyle(
												attributes.typography,
												'credentialTitle'
											) }
											value={ item.title }
											onChange={ ( title ) =>
												setAttributes( {
													credentials: replaceItem(
														credentials,
														index,
														{ title }
													),
												} )
											}
											allowedFormats={ RICH_FORMATS }
										/>
										<RichText
											tagName="p"
											style={ typographyStyle(
												attributes.typography,
												'credentialBody'
											) }
											value={ item.body }
											onChange={ ( body ) =>
												setAttributes( {
													credentials: replaceItem(
														credentials,
														index,
														{ body }
													),
												} )
											}
											allowedFormats={ RICH_FORMATS }
										/>
									</div>
								</article>
							) ) }
						</div>
					</div>
				</div>
			</section>
		</>
	);
}

function ExpertiseEdit( { attributes, setAttributes } ) {
	const cards = attributes.cards || [];
	return (
		<>
			<InspectorControls>
				<TypographyControls
					fields={ [
						field(
							'kicker',
							__( 'Маленькая подпись', 'project-blocks' )
						),
						field( 'title', __( 'Заголовок', 'project-blocks' ) ),
						field( 'intro', __( 'Вступление', 'project-blocks' ) ),
						field(
							'cardNumber',
							__( 'Номера карточек', 'project-blocks' )
						),
						field(
							'cardTitle',
							__( 'Заголовки карточек', 'project-blocks' )
						),
						field(
							'cardBody',
							__( 'Описания карточек', 'project-blocks' )
						),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( { className: 'expertise section-pad' } ) }
			>
				<div className="shell">
					<div className="section-heading reveal">
						<div>
							<RichText
								tagName="p"
								className="section-kicker"
								style={ typographyStyle(
									attributes.typography,
									'kicker'
								) }
								value={ attributes.kicker }
								onChange={ ( kicker ) =>
									setAttributes( { kicker } )
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
								onChange={ ( title ) =>
									setAttributes( { title } )
								}
								allowedFormats={ RICH_FORMATS }
							/>
						</div>
						<RichText
							tagName="p"
							className="section-intro"
							style={ typographyStyle(
								attributes.typography,
								'intro'
							) }
							value={ attributes.intro }
							onChange={ ( intro ) => setAttributes( { intro } ) }
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
					<div className="expertise-grid">
						{ cards.map( ( item, index ) => (
							<article
								className={ `expertise-card${
									index === 3
										? ' expertise-card-featured'
										: ''
								} reveal` }
								key={ index }
							>
								<RichText
									tagName="span"
									className="card-index"
									style={ typographyStyle(
										attributes.typography,
										'cardNumber'
									) }
									value={ item.number }
									onChange={ ( number ) =>
										setAttributes( {
											cards: replaceItem( cards, index, {
												number,
											} ),
										} )
									}
									allowedFormats={ PLAIN_FORMATS }
								/>
								<RichText
									tagName="h3"
									style={ typographyStyle(
										attributes.typography,
										'cardTitle'
									) }
									value={ item.title }
									onChange={ ( title ) =>
										setAttributes( {
											cards: replaceItem( cards, index, {
												title,
											} ),
										} )
									}
									allowedFormats={ RICH_FORMATS }
								/>
								<RichText
									tagName="p"
									style={ typographyStyle(
										attributes.typography,
										'cardBody'
									) }
									value={ item.body }
									onChange={ ( body ) =>
										setAttributes( {
											cards: replaceItem( cards, index, {
												body,
											} ),
										} )
									}
									allowedFormats={ RICH_FORMATS }
								/>
							</article>
						) ) }
					</div>
				</div>
			</section>
		</>
	);
}

function MethodIntroEdit( { attributes, setAttributes } ) {
	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Видео', 'project-blocks' ) }
					initialOpen
				>
					<TextControl
						__next40pxDefaultSize
						label={ __( 'Ссылка на видео', 'project-blocks' ) }
						value={ attributes.videoUrl }
						onChange={ ( videoUrl ) =>
							setAttributes( { videoUrl } )
						}
						help={ __(
							'Можно оставить пустой, пока видео не опубликовано.',
							'project-blocks'
						) }
					/>
				</PanelBody>
				<ImagePanel
					images={ [
						{
							label: __( 'Обложка видео', 'project-blocks' ),
							alt: attributes.imageAlt,
							onAltChange: ( imageAlt ) =>
								setAttributes( { imageAlt } ),
						},
					] }
				/>
				<TypographyControls
					fields={ [
						field(
							'kicker',
							__( 'Маленькая подпись', 'project-blocks' )
						),
						field( 'title', __( 'Заголовок', 'project-blocks' ) ),
						field( 'body', __( 'Описание', 'project-blocks' ) ),
						field(
							'placeholderTitle',
							__( 'Подпись на видео', 'project-blocks' )
						),
						field(
							'placeholderText',
							__( 'Дополнительная подпись', 'project-blocks' )
						),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( {
					className: 'method-intro section-pad',
				} ) }
				id="method"
			>
				<div className="shell method-intro-grid">
					<div className="reveal">
						<RichText
							tagName="p"
							className="section-kicker"
							style={ typographyStyle(
								attributes.typography,
								'kicker'
							) }
							value={ attributes.kicker }
							onChange={ ( kicker ) =>
								setAttributes( { kicker } )
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
							onChange={ ( title ) => setAttributes( { title } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<RichText
							tagName="p"
							style={ typographyStyle(
								attributes.typography,
								'body'
							) }
							value={ attributes.body }
							onChange={ ( body ) => setAttributes( { body } ) }
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
					<div
						className={ `video-placeholder reveal project-blocks-editable-image${
							attributes.imageUrl
								? ' project-blocks-video-cover'
								: ''
						}` }
					>
						{ attributes.imageUrl && (
							<img
								src={ attributes.imageUrl }
								alt={ attributes.imageAlt }
							/>
						) }
						<span className="video-play" aria-hidden="true">
							▶
						</span>
						<RichText
							tagName="span"
							style={ typographyStyle(
								attributes.typography,
								'placeholderTitle'
							) }
							value={ attributes.placeholderTitle }
							onChange={ ( placeholderTitle ) =>
								setAttributes( { placeholderTitle } )
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
							onChange={ ( placeholderText ) =>
								setAttributes( { placeholderText } )
							}
							allowedFormats={ PLAIN_FORMATS }
						/>
						<ImageActions
							imageId={ attributes.imageId }
							imageUrl={ attributes.imageUrl }
							onChange={ ( change ) =>
								setAttributes( {
									...change,
									imageAlt:
										change.imageAlt || attributes.imageAlt,
								} )
							}
						/>
					</div>
				</div>
			</section>
		</>
	);
}

function MethodEdit( { attributes, setAttributes } ) {
	const steps = attributes.steps || [];
	return (
		<>
			<InspectorControls>
				<ImagePanel
					images={ [
						{
							label: __( 'Портрет', 'project-blocks' ),
							alt: attributes.imageAlt,
							onAltChange: ( imageAlt ) =>
								setAttributes( { imageAlt } ),
						},
					] }
				/>
				<TypographyControls
					fields={ [
						field(
							'kicker',
							__( 'Маленькая подпись', 'project-blocks' )
						),
						field( 'title', __( 'Заголовок', 'project-blocks' ) ),
						field( 'lead', __( 'Описание', 'project-blocks' ) ),
						field(
							'stepNumber',
							__( 'Номера этапов', 'project-blocks' )
						),
						field(
							'stepTitle',
							__( 'Заголовки этапов', 'project-blocks' )
						),
						field(
							'stepBody',
							__( 'Описания этапов', 'project-blocks' )
						),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( { className: 'method section-pad' } ) }
			>
				<div className="shell method-grid">
					<div className="method-copy reveal">
						<RichText
							tagName="p"
							className="section-kicker section-kicker-gold"
							style={ typographyStyle(
								attributes.typography,
								'kicker'
							) }
							value={ attributes.kicker }
							onChange={ ( kicker ) =>
								setAttributes( { kicker } )
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
							onChange={ ( title ) => setAttributes( { title } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<RichText
							tagName="p"
							className="method-lead"
							style={ typographyStyle(
								attributes.typography,
								'lead'
							) }
							value={ attributes.lead }
							onChange={ ( lead ) => setAttributes( { lead } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<ol className="method-steps">
							{ steps.map( ( item, index ) => (
								<li key={ index }>
									<RichText
										tagName="span"
										style={ typographyStyle(
											attributes.typography,
											'stepNumber'
										) }
										value={ item.number }
										onChange={ ( number ) =>
											setAttributes( {
												steps: replaceItem(
													steps,
													index,
													{ number }
												),
											} )
										}
										allowedFormats={ PLAIN_FORMATS }
									/>
									<div>
										<RichText
											tagName="h3"
											style={ typographyStyle(
												attributes.typography,
												'stepTitle'
											) }
											value={ item.title }
											onChange={ ( title ) =>
												setAttributes( {
													steps: replaceItem(
														steps,
														index,
														{ title }
													),
												} )
											}
											allowedFormats={ RICH_FORMATS }
										/>
										<RichText
											tagName="p"
											style={ typographyStyle(
												attributes.typography,
												'stepBody'
											) }
											value={ item.body }
											onChange={ ( body ) =>
												setAttributes( {
													steps: replaceItem(
														steps,
														index,
														{ body }
													),
												} )
											}
											allowedFormats={ RICH_FORMATS }
										/>
									</div>
								</li>
							) ) }
						</ol>
					</div>
					<div className="method-visual reveal">
						<div className="method-photo portrait-panel project-blocks-editable-image">
							{ attributes.imageUrl && (
								<img
									className="section-portrait method-portrait"
									src={ attributes.imageUrl }
									alt={ attributes.imageAlt }
								/>
							) }
							<ImageActions
								imageId={ attributes.imageId }
								imageUrl={ attributes.imageUrl }
								onChange={ ( change ) =>
									setAttributes( {
										...change,
										imageAlt:
											change.imageAlt ||
											attributes.imageAlt,
									} )
								}
							/>
						</div>
					</div>
				</div>
			</section>
		</>
	);
}

function MasterclassesEdit( { attributes, setAttributes } ) {
	const cards = attributes.cards || [];
	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Ссылки карточек', 'project-blocks' ) }
					initialOpen
				>
					{ cards.map( ( item, index ) => (
						<div
							className="project-blocks-image-inspector-item"
							key={ index }
						>
							<strong>{ `${ __(
								'Мастер-класс',
								'project-blocks'
							) } ${ index + 1 }` }</strong>
							<TextControl
								__next40pxDefaultSize
								label={ __(
									'Ссылка кнопки',
									'project-blocks'
								) }
								value={ item.buttonUrl || '' }
								onChange={ ( buttonUrl ) =>
									setAttributes( {
										cards: replaceItem( cards, index, {
											buttonUrl,
										} ),
									} )
								}
							/>
						</div>
					) ) }
				</PanelBody>
				<ImagePanel
					images={ cards.map( ( item, index ) => ( {
						label: `${ __( 'Карточка', 'project-blocks' ) } ${
							index + 1
						}`,
						alt: item.imageAlt,
						onAltChange: ( imageAlt ) =>
							setAttributes( {
								cards: replaceItem( cards, index, {
									imageAlt,
								} ),
							} ),
					} ) ) }
				/>
				<TypographyControls
					fields={ [
						field(
							'kicker',
							__( 'Маленькая подпись', 'project-blocks' )
						),
						field(
							'title',
							__( 'Заголовок раздела', 'project-blocks' )
						),
						field( 'intro', __( 'Вступление', 'project-blocks' ) ),
						field(
							'label',
							__( 'Подписи карточек', 'project-blocks' )
						),
						field(
							'cardTitle',
							__( 'Заголовки карточек', 'project-blocks' )
						),
						field(
							'cardBody',
							__( 'Описания карточек', 'project-blocks' )
						),
						field( 'button', __( 'Кнопки', 'project-blocks' ) ),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( {
					className: 'masterclasses section-pad',
				} ) }
				id="masterclasses"
			>
				<div className="shell">
					<div className="section-heading reveal">
						<div>
							<RichText
								tagName="p"
								className="section-kicker"
								style={ typographyStyle(
									attributes.typography,
									'kicker'
								) }
								value={ attributes.kicker }
								onChange={ ( kicker ) =>
									setAttributes( { kicker } )
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
								onChange={ ( title ) =>
									setAttributes( { title } )
								}
								allowedFormats={ RICH_FORMATS }
							/>
						</div>
						<RichText
							tagName="p"
							className="section-intro"
							style={ typographyStyle(
								attributes.typography,
								'intro'
							) }
							value={ attributes.intro }
							onChange={ ( intro ) => setAttributes( { intro } ) }
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
					<div className="masterclass-grid">
						{ cards.map( ( item, index ) => (
							<article
								className="masterclass-card reveal"
								key={ index }
							>
								<div
									className={ `photo-placeholder masterclass-photo project-blocks-editable-image${
										item.imageUrl
											? ' project-blocks-has-image'
											: ''
									}` }
								>
									{ item.imageUrl ? (
										<img
											src={ item.imageUrl }
											alt={ item.imageAlt || '' }
										/>
									) : (
										<span>ТУТ БУДЕТ ФОТО</span>
									) }
									<ImageActions
										imageId={ item.imageId }
										imageUrl={ item.imageUrl }
										onChange={ ( change ) =>
											setAttributes( {
												cards: replaceItem(
													cards,
													index,
													{
														...change,
														imageAlt:
															change.imageAlt ||
															item.imageAlt,
													}
												),
											} )
										}
									/>
								</div>
								<div className="masterclass-content">
									<RichText
										tagName="p"
										className="card-label"
										style={ typographyStyle(
											attributes.typography,
											'label'
										) }
										value={ item.label }
										onChange={ ( label ) =>
											setAttributes( {
												cards: replaceItem(
													cards,
													index,
													{ label }
												),
											} )
										}
										allowedFormats={ PLAIN_FORMATS }
									/>
									<RichText
										tagName="h3"
										style={ typographyStyle(
											attributes.typography,
											'cardTitle'
										) }
										value={ item.title }
										onChange={ ( title ) =>
											setAttributes( {
												cards: replaceItem(
													cards,
													index,
													{ title }
												),
											} )
										}
										allowedFormats={ RICH_FORMATS }
									/>
									<RichText
										tagName="p"
										style={ typographyStyle(
											attributes.typography,
											'cardBody'
										) }
										value={ item.body }
										onChange={ ( body ) =>
											setAttributes( {
												cards: replaceItem(
													cards,
													index,
													{ body }
												),
											} )
										}
										allowedFormats={ RICH_FORMATS }
									/>
									<RichText
										tagName="span"
										className={ `card-link${
											item.buttonUrl
												? ' card-link-primary'
												: ''
										}` }
										style={ typographyStyle(
											attributes.typography,
											'button'
										) }
										value={ item.buttonText }
										onChange={ ( buttonText ) =>
											setAttributes( {
												cards: replaceItem(
													cards,
													index,
													{ buttonText }
												),
											} )
										}
										allowedFormats={ PLAIN_FORMATS }
									/>
								</div>
							</article>
						) ) }
					</div>
				</div>
			</section>
		</>
	);
}

function StoriesEdit( { attributes, setAttributes } ) {
	const stories = attributes.stories || [];
	return (
		<>
			<InspectorControls>
				<ImagePanel
					images={ stories.map( ( item, index ) => ( {
						label: `${ __( 'История', 'project-blocks' ) } ${
							index + 1
						}`,
						alt: item.imageAlt,
						onAltChange: ( imageAlt ) =>
							setAttributes( {
								stories: replaceItem( stories, index, {
									imageAlt,
								} ),
							} ),
					} ) ) }
				/>
				<TypographyControls
					fields={ [
						field(
							'kicker',
							__( 'Маленькая подпись', 'project-blocks' )
						),
						field(
							'title',
							__( 'Заголовок раздела', 'project-blocks' )
						),
						field( 'intro', __( 'Вступление', 'project-blocks' ) ),
						field(
							'label',
							__( 'Подписи карточек', 'project-blocks' )
						),
						field( 'name', __( 'Имена', 'project-blocks' ) ),
						field(
							'body',
							__( 'Тексты историй', 'project-blocks' )
						),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( { className: 'stories section-pad' } ) }
			>
				<div className="shell">
					<div className="stories-heading reveal">
						<RichText
							tagName="p"
							className="section-kicker"
							style={ typographyStyle(
								attributes.typography,
								'kicker'
							) }
							value={ attributes.kicker }
							onChange={ ( kicker ) =>
								setAttributes( { kicker } )
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
							onChange={ ( title ) => setAttributes( { title } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<RichText
							tagName="p"
							style={ typographyStyle(
								attributes.typography,
								'intro'
							) }
							value={ attributes.intro }
							onChange={ ( intro ) => setAttributes( { intro } ) }
							allowedFormats={ RICH_FORMATS }
						/>
					</div>
					<div className="stories-grid">
						{ stories.map( ( item, index ) => (
							<article
								className={ `story-card${
									index === 2 ? ' story-card-wide' : ''
								} reveal` }
								key={ index }
							>
								<div
									className={ `photo-placeholder story-photo project-blocks-editable-image${
										item.imageUrl
											? ' project-blocks-has-image'
											: ''
									}` }
								>
									{ item.imageUrl ? (
										<img
											src={ item.imageUrl }
											alt={ item.imageAlt || '' }
										/>
									) : (
										<span>ТУТ БУДЕТ ФОТО</span>
									) }
									<span
										className="story-play"
										aria-hidden="true"
									>
										▶
									</span>
									<ImageActions
										imageId={ item.imageId }
										imageUrl={ item.imageUrl }
										onChange={ ( change ) =>
											setAttributes( {
												stories: replaceItem(
													stories,
													index,
													{
														...change,
														imageAlt:
															change.imageAlt ||
															item.imageAlt,
													}
												),
											} )
										}
									/>
								</div>
								<div className="story-copy">
									<RichText
										tagName="p"
										className="card-label"
										style={ typographyStyle(
											attributes.typography,
											'label'
										) }
										value={ item.label }
										onChange={ ( label ) =>
											setAttributes( {
												stories: replaceItem(
													stories,
													index,
													{ label }
												),
											} )
										}
										allowedFormats={ PLAIN_FORMATS }
									/>
									<RichText
										tagName="h3"
										style={ typographyStyle(
											attributes.typography,
											'name'
										) }
										value={ item.name }
										onChange={ ( name ) =>
											setAttributes( {
												stories: replaceItem(
													stories,
													index,
													{ name }
												),
											} )
										}
										allowedFormats={ RICH_FORMATS }
									/>
									<RichText
										tagName="p"
										style={ typographyStyle(
											attributes.typography,
											'body'
										) }
										value={ item.body }
										onChange={ ( body ) =>
											setAttributes( {
												stories: replaceItem(
													stories,
													index,
													{ body }
												),
											} )
										}
										allowedFormats={ RICH_FORMATS }
									/>
								</div>
							</article>
						) ) }
					</div>
				</div>
			</section>
		</>
	);
}

function CoachingEdit( { attributes, setAttributes } ) {
	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Кнопка', 'project-blocks' ) }
					initialOpen
				>
					<TextControl
						__next40pxDefaultSize
						label={ __( 'Ссылка кнопки', 'project-blocks' ) }
						value={ attributes.buttonUrl }
						onChange={ ( buttonUrl ) =>
							setAttributes( { buttonUrl } )
						}
						help={ __(
							'Можно оставить пустой, пока запись не подключена.',
							'project-blocks'
						) }
					/>
				</PanelBody>
				<ImagePanel
					images={ [
						{
							label: __( 'Портрет', 'project-blocks' ),
							alt: attributes.imageAlt,
							onAltChange: ( imageAlt ) =>
								setAttributes( { imageAlt } ),
						},
					] }
				/>
				<TypographyControls
					fields={ [
						field(
							'eyebrow',
							__( 'Номер и подпись', 'project-blocks' )
						),
						field( 'title', __( 'Заголовок', 'project-blocks' ) ),
						field( 'body', __( 'Описание', 'project-blocks' ) ),
						field( 'button', __( 'Кнопка', 'project-blocks' ) ),
					] }
					typography={ attributes.typography }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<section
				{ ...useBlockProps( { className: 'coaching section-pad' } ) }
				id="coaching"
			>
				<div className="shell coaching-card reveal">
					<div className="coaching-copy">
						<p
							className="eyebrow eyebrow-light"
							style={ typographyStyle(
								attributes.typography,
								'eyebrow'
							) }
						>
							<RichText
								tagName="span"
								value={ attributes.number }
								onChange={ ( number ) =>
									setAttributes( { number } )
								}
								allowedFormats={ PLAIN_FORMATS }
							/>
							<RichText
								tagName="span"
								value={ attributes.eyebrow }
								onChange={ ( eyebrow ) =>
									setAttributes( { eyebrow } )
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
							onChange={ ( title ) => setAttributes( { title } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<RichText
							tagName="p"
							style={ typographyStyle(
								attributes.typography,
								'body'
							) }
							value={ attributes.body }
							onChange={ ( body ) => setAttributes( { body } ) }
							allowedFormats={ RICH_FORMATS }
						/>
						<span
							className="button button-gold"
							style={ typographyStyle(
								attributes.typography,
								'button'
							) }
						>
							<RichText
								tagName="span"
								value={ attributes.buttonText }
								onChange={ ( buttonText ) =>
									setAttributes( { buttonText } )
								}
								allowedFormats={ PLAIN_FORMATS }
							/>{ ' ' }
							<span aria-hidden="true">↗</span>
						</span>
					</div>
					<div className="coaching-photo portrait-panel project-blocks-editable-image">
						{ attributes.imageUrl && (
							<img
								className="section-portrait coaching-portrait"
								src={ attributes.imageUrl }
								alt={ attributes.imageAlt }
							/>
						) }
						<ImageActions
							imageId={ attributes.imageId }
							imageUrl={ attributes.imageUrl }
							onChange={ ( change ) =>
								setAttributes( {
									...change,
									imageAlt:
										change.imageAlt || attributes.imageAlt,
								} )
							}
						/>
					</div>
				</div>
			</section>
		</>
	);
}

[
	[ heroMetadata, HomeHeroEdit ],
	[ manifestoMetadata, ManifestoEdit ],
	[ aboutMetadata, AboutEdit ],
	[ expertiseMetadata, ExpertiseEdit ],
	[ methodIntroMetadata, MethodIntroEdit ],
	[ methodMetadata, MethodEdit ],
	[ masterclassesMetadata, MasterclassesEdit ],
	[ storiesMetadata, StoriesEdit ],
	[ coachingMetadata, CoachingEdit ],
].forEach( ( [ metadata, edit ] ) =>
	registerBlockType( metadata.name, { edit, save: () => null } )
);
