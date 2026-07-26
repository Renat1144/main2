<?php
/**
 * Render the editable description section.
 *
 * @package ProjectBlocks
 */

$kicker = sanitize_text_field( $attributes['kicker'] ?? __( 'О материале', 'project-blocks' ) );
$title = wp_kses_post( $attributes['title'] ?? __( 'Описание мастер-класса', 'project-blocks' ) );
$description = wp_kses_post( $attributes['content'] ?? '' );
$typo_kicker = project_blocks_typography_style( $attributes, 'kicker' );
$typo_title = project_blocks_typography_style( $attributes, 'title' );
$typo_content = project_blocks_typography_style( $attributes, 'content' );
$title_id = wp_unique_id( 'master-about-title-' );
$wrapper = get_block_wrapper_attributes(
	array(
		'class' => 'master-copy-section section-pad',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell master-copy-grid">
		<div class="master-copy-heading reveal">
			<p class="section-kicker" style="<?php echo esc_attr( $typo_kicker ); ?>"><?php echo esc_html( $kicker ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( $typo_title ); ?>"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		</div>
		<div class="copy-placeholder copy-placeholder-light master-copy-placeholder reveal" data-delay="1">
			<div class="master-editor-content" style="<?php echo esc_attr( $typo_content ); ?>"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<span class="placeholder-line placeholder-line-full"></span>
			<span class="placeholder-line placeholder-line-medium"></span>
			<span class="placeholder-line placeholder-line-short"></span>
		</div>
	</div>
</section>
