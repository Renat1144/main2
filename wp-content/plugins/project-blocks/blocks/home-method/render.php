<?php
/**
 * Render the editable homepage method section.
 *
 * @package ProjectBlocks
 */

$steps    = is_array( $attributes['steps'] ?? null ) ? $attributes['steps'] : array();
$image    = project_blocks_image( $attributes );
$title_id = wp_unique_id( 'method-title-' );
$wrapper  = get_block_wrapper_attributes(
	array(
		'class'           => 'method section-pad',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell method-grid">
		<div class="method-copy reveal">
			<p class="section-kicker section-kicker-gold" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'kicker' ) ); ?>"><?php echo esc_html( $attributes['kicker'] ?? '' ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></h2>
			<p class="method-lead" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'lead' ) ); ?>"><?php echo wp_kses_post( $attributes['lead'] ?? '' ); ?></p>
			<ol class="method-steps">
				<?php foreach ( $steps as $step ) : ?>
					<li>
						<span style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'stepNumber' ) ); ?>"><?php echo esc_html( $step['number'] ?? '' ); ?></span>
						<div>
							<h3 style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'stepTitle' ) ); ?>"><?php echo wp_kses_post( $step['title'] ?? '' ); ?></h3>
							<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'stepBody' ) ); ?>"><?php echo wp_kses_post( $step['body'] ?? '' ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
		<div class="method-visual reveal" data-delay="1">
			<div class="method-photo portrait-panel">
				<?php if ( $image['url'] ) : ?>
					<img class="section-portrait method-portrait" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
