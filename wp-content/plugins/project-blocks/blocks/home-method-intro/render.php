<?php
/**
 * Render the editable homepage method introduction.
 *
 * @package ProjectBlocks
 */

$image     = project_blocks_image( $attributes );
$video_url = esc_url( $attributes['videoUrl'] ?? '' );
$title_id  = wp_unique_id( 'method-intro-title-' );
$wrapper   = get_block_wrapper_attributes(
	array(
		'class'           => 'method-intro section-pad',
		'id'              => 'method',
		'aria-labelledby' => $title_id,
	)
);
$media_class = 'video-placeholder reveal' . ( $image['url'] ? ' project-blocks-video-cover' : '' );
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell method-intro-grid">
		<div class="reveal">
			<p class="section-kicker" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'kicker' ) ); ?>"><?php echo esc_html( $attributes['kicker'] ?? '' ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></h2>
			<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'body' ) ); ?>"><?php echo wp_kses_post( $attributes['body'] ?? '' ); ?></p>
		</div>
		<?php if ( $video_url ) : ?>
			<a class="<?php echo esc_attr( $media_class ); ?>" href="<?php echo esc_url( $video_url ); ?>" target="_blank" rel="noopener">
		<?php else : ?>
			<button class="<?php echo esc_attr( $media_class ); ?>" type="button" data-placeholder-action aria-label="<?php esc_attr_e( 'Видео будет добавлено позже', 'project-blocks' ); ?>">
		<?php endif; ?>
			<?php if ( $image['url'] ) : ?>
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
			<?php endif; ?>
			<span class="video-play" aria-hidden="true">▶</span>
			<span style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'placeholderTitle' ) ); ?>"><?php echo esc_html( $attributes['placeholderTitle'] ?? '' ); ?></span>
			<small style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'placeholderText' ) ); ?>"><?php echo esc_html( $attributes['placeholderText'] ?? '' ); ?></small>
		<?php if ( $video_url ) : ?>
			</a>
		<?php else : ?>
			</button>
		<?php endif; ?>
	</div>
</section>
