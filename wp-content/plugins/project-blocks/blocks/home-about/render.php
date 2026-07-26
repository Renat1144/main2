<?php
/**
 * Render the editable homepage about section.
 *
 * @package ProjectBlocks
 */

$credentials = is_array( $attributes['credentials'] ?? null ) ? $attributes['credentials'] : array();
$image       = project_blocks_image( $attributes );
$title_id    = wp_unique_id( 'about-title-' );
$wrapper     = get_block_wrapper_attributes(
	array(
		'class'           => 'about section-pad',
		'id'              => 'about',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell about-grid">
		<div class="about-visual reveal">
			<div class="photo-frame">
				<div class="photo-placeholder-about portrait-panel">
					<?php if ( $image['url'] ) : ?>
						<img class="section-portrait about-portrait" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
					<?php endif; ?>
				</div>
			</div>
			<p class="vertical-label" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'verticalLabel' ) ); ?>" aria-hidden="true"><?php echo esc_html( $attributes['verticalLabel'] ?? '' ); ?></p>
		</div>
		<div class="about-copy reveal" data-delay="1">
			<p class="section-kicker" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'kicker' ) ); ?>"><?php echo esc_html( $attributes['kicker'] ?? '' ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></h2>
			<p class="about-name" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'name' ) ); ?>"><?php echo wp_kses_post( $attributes['name'] ?? '' ); ?></p>
			<p class="about-role" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'role' ) ); ?>"><?php echo wp_kses_post( $attributes['role'] ?? '' ); ?></p>
			<div class="credentials" role="list">
				<?php foreach ( $credentials as $credential ) : ?>
					<article class="credential" role="listitem">
						<span class="credential-mark" aria-hidden="true">✦</span>
						<div>
							<h3 style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'credentialTitle' ) ); ?>"><?php echo wp_kses_post( $credential['title'] ?? '' ); ?></h3>
							<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'credentialBody' ) ); ?>"><?php echo wp_kses_post( $credential['body'] ?? '' ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
