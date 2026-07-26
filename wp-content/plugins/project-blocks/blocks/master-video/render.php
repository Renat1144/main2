<?php
/**
 * Render the video placeholder section.
 *
 * @package ProjectBlocks
 */

$kicker = sanitize_text_field( $attributes['kicker'] ?? __( 'Материал', 'project-blocks' ) );
$title = wp_kses_post( $attributes['title'] ?? __( 'Видео мастер-класса', 'project-blocks' ) );
$placeholder_title = sanitize_text_field( $attributes['placeholderTitle'] ?? __( 'ТУТ БУДЕТ ВИДЕО', 'project-blocks' ) );
$placeholder_text = sanitize_text_field( $attributes['placeholderText'] ?? __( 'Видеоматериал будет добавлен позже', 'project-blocks' ) );
$typo_kicker = project_blocks_typography_style( $attributes, 'kicker' );
$typo_title = project_blocks_typography_style( $attributes, 'title' );
$typo_placeholder_title = project_blocks_typography_style( $attributes, 'placeholderTitle' );
$typo_placeholder_text = project_blocks_typography_style( $attributes, 'placeholderText' );
$title_id = wp_unique_id( 'master-video-title-' );
$wrapper = get_block_wrapper_attributes(
	array(
		'class' => 'master-video-section section-pad',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell">
		<div class="master-detail-section-heading reveal">
			<p class="section-kicker" style="<?php echo esc_attr( $typo_kicker ); ?>"><?php echo esc_html( $kicker ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( $typo_title ); ?>"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		</div>
		<div class="master-video-placeholder reveal" data-delay="1" role="img" aria-label="<?php esc_attr_e( 'Место для видео мастер-класса', 'project-blocks' ); ?>">
			<span class="master-video-play" aria-hidden="true">▶</span>
			<strong style="<?php echo esc_attr( $typo_placeholder_title ); ?>"><?php echo esc_html( $placeholder_title ); ?></strong>
			<small style="<?php echo esc_attr( $typo_placeholder_text ); ?>"><?php echo esc_html( $placeholder_text ); ?></small>
		</div>
	</div>
</section>
