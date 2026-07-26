<?php
/**
 * Render the editable homepage masterclass cards.
 *
 * @package ProjectBlocks
 */

$cards    = is_array( $attributes['cards'] ?? null ) ? $attributes['cards'] : array();
$title_id = wp_unique_id( 'masterclasses-title-' );
$wrapper  = get_block_wrapper_attributes(
	array(
		'class'           => 'masterclasses section-pad',
		'id'              => 'masterclasses',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell">
		<div class="section-heading reveal">
			<div>
				<p class="section-kicker" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'kicker' ) ); ?>"><?php echo esc_html( $attributes['kicker'] ?? '' ); ?></p>
				<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></h2>
			</div>
			<p class="section-intro" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'intro' ) ); ?>"><?php echo wp_kses_post( $attributes['intro'] ?? '' ); ?></p>
		</div>
		<div class="masterclass-grid">
			<?php foreach ( $cards as $index => $card ) : ?>
				<?php $image = project_blocks_image( $card ); ?>
				<article class="masterclass-card reveal"<?php echo 1 === $index ? ' data-delay="1"' : ''; ?><?php echo 2 === $index ? ' data-delay="2"' : ''; ?>>
					<div class="photo-placeholder masterclass-photo<?php echo $image['url'] ? ' project-blocks-has-image' : ''; ?>">
						<?php if ( $image['url'] ) : ?>
							<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
						<?php else : ?>
							<span><?php esc_html_e( 'ТУТ БУДЕТ ФОТО', 'project-blocks' ); ?></span>
						<?php endif; ?>
					</div>
					<div class="masterclass-content">
						<p class="card-label" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'label' ) ); ?>"><?php echo esc_html( $card['label'] ?? '' ); ?></p>
						<h3 style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'cardTitle' ) ); ?>"><?php echo wp_kses_post( $card['title'] ?? '' ); ?></h3>
						<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'cardBody' ) ); ?>"><?php echo wp_kses_post( $card['body'] ?? '' ); ?></p>
						<?php if ( ! empty( $card['buttonUrl'] ) ) : ?>
							<a class="card-link card-link-primary" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'button' ) ); ?>" href="<?php echo esc_url( $card['buttonUrl'] ); ?>"><?php echo esc_html( $card['buttonText'] ?? '' ); ?> <span aria-hidden="true">↗</span></a>
						<?php else : ?>
							<button class="card-link" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'button' ) ); ?>" type="button" data-placeholder-action><?php echo esc_html( $card['buttonText'] ?? '' ); ?> <span aria-hidden="true">↗</span></button>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
