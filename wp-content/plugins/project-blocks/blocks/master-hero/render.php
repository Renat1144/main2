<?php
/**
 * Render the editable master-class hero.
 *
 * @package ProjectBlocks
 */

$number       = sanitize_text_field( $attributes['number'] ?? '01' );
$label        = sanitize_text_field( $attributes['label'] ?? __( 'Мастер-класс', 'project-blocks' ) );
$title        = wp_kses_post( $attributes['title'] ?? __( 'Название мастер-класса', 'project-blocks' ) );
$description  = wp_kses_post( $attributes['description'] ?? '' );
$card_title   = wp_kses_post( $attributes['cardTitle'] ?? __( 'Текст будет добавлен', 'project-blocks' ) );
$card_content = wp_kses_post( $attributes['cardContent'] ?? '' );
$image_id     = absint( $attributes['imageId'] ?? 0 );
$image_url    = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
$image_url    = $image_url ?: esc_url_raw( $attributes['imageUrl'] ?? '' );
$image_alt    = sanitize_text_field( $attributes['imageAlt'] ?? '' );
$image_caption = wp_kses_post( $attributes['imageCaption'] ?? '' );
$placeholder_caption = sanitize_text_field( $attributes['imagePlaceholderCaption'] ?? __( 'Обложка мастер-класса', 'project-blocks' ) );
$button_text  = sanitize_text_field( $attributes['buttonText'] ?? '' );
$button_url   = esc_url( $attributes['buttonUrl'] ?? '' );
$typo_label   = project_blocks_typography_style( $attributes, 'label' );
$typo_title   = project_blocks_typography_style( $attributes, 'title' );
$typo_description = project_blocks_typography_style( $attributes, 'description' );
$typo_card_title = project_blocks_typography_style( $attributes, 'cardTitle' );
$typo_card_content = project_blocks_typography_style( $attributes, 'cardContent' );
$typo_image_caption = project_blocks_typography_style( $attributes, 'imageCaption' );
$typo_button  = project_blocks_typography_style( $attributes, 'button' );
$title_id     = wp_unique_id( 'master-detail-title-' );
$wrapper      = get_block_wrapper_attributes(
	array(
		'class'           => 'master-detail-hero',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="master-detail-glow" aria-hidden="true"></div>
	<div class="shell master-detail-hero-grid">
		<div class="master-detail-hero-copy reveal">
			<p class="eyebrow eyebrow-light" style="<?php echo esc_attr( $typo_label ); ?>"><span><?php echo esc_html( $number ); ?></span> <?php echo esc_html( $label ); ?></p>
			<h1 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( $typo_title ); ?>"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<?php if ( $description ) : ?>
				<div class="master-detail-description" style="<?php echo esc_attr( $typo_description ); ?>"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
			<div class="copy-placeholder copy-placeholder-dark copy-placeholder-lead">
				<span class="placeholder-caption" style="<?php echo esc_attr( $typo_card_title ); ?>"><?php echo $card_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php if ( $card_content ) : ?>
					<div class="project-blocks-card-content" style="<?php echo esc_attr( $typo_card_content ); ?>"><?php echo $card_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php else : ?>
					<span class="placeholder-line placeholder-line-full"></span>
					<span class="placeholder-line placeholder-line-medium"></span>
					<span class="placeholder-line placeholder-line-short"></span>
				<?php endif; ?>
			</div>
			<?php if ( $button_text ) : ?>
				<div class="master-detail-hero-actions">
					<?php if ( $button_url ) : ?>
						<a class="button button-primary" style="<?php echo esc_attr( $typo_button ); ?>" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_text ); ?> <span aria-hidden="true">↗</span></a>
					<?php else : ?>
						<button class="button button-primary" style="<?php echo esc_attr( $typo_button ); ?>" type="button" aria-disabled="true" disabled><?php echo esc_html( $button_text ); ?> <span aria-hidden="true">↗</span></button>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="master-detail-visual reveal" data-delay="1">
			<div class="master-detail-orbit" aria-hidden="true"></div>
			<?php if ( $image_url ) : ?>
				<figure class="master-detail-photo master-detail-photo-image">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="eager" decoding="async">
					<?php if ( $image_caption ) : ?>
						<figcaption class="project-blocks-image-caption" style="<?php echo esc_attr( $typo_image_caption ); ?>"><?php echo $image_caption; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php else : ?>
				<div class="photo-placeholder master-detail-photo">
					<span><?php esc_html_e( 'ТУТ БУДЕТ ФОТО', 'project-blocks' ); ?></span>
					<small style="<?php echo esc_attr( $typo_image_caption ); ?>"><?php echo esc_html( $placeholder_caption ); ?></small>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
