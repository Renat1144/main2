<?php
/**
 * Render the editable homepage stories.
 *
 * @package ProjectBlocks
 */

$stories = is_array( $attributes['stories'] ?? null ) ? $attributes['stories'] : array();
$title_id = wp_unique_id( 'stories-title-' );
$wrapper = get_block_wrapper_attributes(
	array(
		'class'           => 'stories section-pad',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell">
		<div class="stories-heading reveal">
			<p class="section-kicker" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'kicker' ) ); ?>"><?php echo esc_html( $attributes['kicker'] ?? '' ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></h2>
			<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'intro' ) ); ?>"><?php echo wp_kses_post( $attributes['intro'] ?? '' ); ?></p>
		</div>
		<div class="stories-grid">
			<?php foreach ( $stories as $index => $story ) : ?>
				<?php $image = project_blocks_image( $story ); ?>
				<article class="story-card<?php echo 2 === $index ? ' story-card-wide' : ''; ?> reveal"<?php echo 1 === $index ? ' data-delay="1"' : ''; ?>>
					<div class="photo-placeholder story-photo<?php echo $image['url'] ? ' project-blocks-has-image' : ''; ?>">
						<?php if ( $image['url'] ) : ?>
							<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
						<?php else : ?>
							<span><?php esc_html_e( 'ТУТ БУДЕТ ФОТО', 'project-blocks' ); ?></span>
						<?php endif; ?>
						<span class="story-play" aria-hidden="true">▶</span>
					</div>
					<div class="story-copy">
						<p class="card-label" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'label' ) ); ?>"><?php echo esc_html( $story['label'] ?? '' ); ?></p>
						<h3 style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'name' ) ); ?>"><?php echo wp_kses_post( $story['name'] ?? '' ); ?></h3>
						<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'body' ) ); ?>"><?php echo wp_kses_post( $story['body'] ?? '' ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
