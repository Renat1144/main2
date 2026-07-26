<?php
/**
 * Render the editable homepage coaching section.
 *
 * @package ProjectBlocks
 */

$image      = project_blocks_image( $attributes );
$button_url = esc_url( $attributes['buttonUrl'] ?? '' );
$title_id   = wp_unique_id( 'coaching-title-' );
$wrapper    = get_block_wrapper_attributes(
	array(
		'class'           => 'coaching section-pad',
		'id'              => 'coaching',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell coaching-card reveal">
		<div class="coaching-copy">
			<p class="eyebrow eyebrow-light" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'eyebrow' ) ); ?>"><span><?php echo esc_html( $attributes['number'] ?? '03' ); ?></span> <?php echo esc_html( $attributes['eyebrow'] ?? '' ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></h2>
			<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'body' ) ); ?>"><?php echo wp_kses_post( $attributes['body'] ?? '' ); ?></p>
			<?php if ( $button_url ) : ?>
				<a class="button button-gold" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'button' ) ); ?>" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $attributes['buttonText'] ?? '' ); ?> <span aria-hidden="true">↗</span></a>
			<?php else : ?>
				<button class="button button-gold" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'button' ) ); ?>" type="button" data-placeholder-action><?php echo esc_html( $attributes['buttonText'] ?? '' ); ?> <span aria-hidden="true">↗</span></button>
			<?php endif; ?>
		</div>
		<div class="coaching-photo portrait-panel">
			<?php if ( $image['url'] ) : ?>
				<img class="section-portrait coaching-portrait" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
			<?php endif; ?>
		</div>
	</div>
</section>
