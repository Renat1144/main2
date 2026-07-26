<?php
/**
 * Render the editable homepage hero.
 *
 * @package ProjectBlocks
 */

$number        = sanitize_text_field( $attributes['number'] ?? '01' );
$eyebrow       = sanitize_text_field( $attributes['eyebrow'] ?? '' );
$title         = wp_kses_post( $attributes['title'] ?? '' );
$lead          = wp_kses_post( $attributes['lead'] ?? '' );
$primary_label = sanitize_text_field( $attributes['primaryLabel'] ?? '' );
$primary_url   = esc_url( $attributes['primaryUrl'] ?? '#masterclasses' );
$second_label  = sanitize_text_field( $attributes['secondaryLabel'] ?? '' );
$second_url    = esc_url( $attributes['secondaryUrl'] ?? '#method' );
$image         = project_blocks_image( $attributes );
$title_id      = wp_unique_id( 'home-hero-title-' );
$wrapper       = get_block_wrapper_attributes(
	array(
		'class'           => 'hero',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="hero-aurora" aria-hidden="true"></div>
	<div class="shell hero-grid">
		<div class="hero-copy reveal">
			<p class="eyebrow" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'eyebrow' ) ); ?>"><span><?php echo esc_html( $number ); ?></span> <?php echo esc_html( $eyebrow ); ?></p>
			<h1 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<p class="hero-lead" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'lead' ) ); ?>"><?php echo $lead; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<div class="hero-actions">
				<a class="button button-primary" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'button' ) ); ?>" href="<?php echo esc_url( $primary_url ); ?>"><?php echo esc_html( $primary_label ); ?> <span aria-hidden="true">↗</span></a>
				<a class="text-link" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'button' ) ); ?>" href="<?php echo esc_url( $second_url ); ?>"><?php echo esc_html( $second_label ); ?></a>
			</div>
		</div>
		<div class="hero-visual reveal" data-delay="1">
			<div class="photo-placeholder-portrait hero-portrait-frame">
				<?php if ( $image['url'] ) : ?>
					<img class="hero-portrait-image" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" decoding="async" fetchpriority="high">
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
