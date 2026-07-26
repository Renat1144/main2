<?php
/**
 * Render the editable homepage manifesto.
 *
 * @package ProjectBlocks
 */

$gallery       = is_array( $attributes['gallery'] ?? null ) ? $attributes['gallery'] : array();
$gallery_class = array(
	'gallery-photo gallery-photo-wide',
	'gallery-photo',
	'gallery-photo gallery-photo-tall',
	'gallery-photo',
	'gallery-photo gallery-photo-wide',
);
$title_id      = wp_unique_id( 'manifesto-title-' );
$wrapper       = get_block_wrapper_attributes(
	array(
		'class'           => 'manifesto section-pad',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell manifesto-grid">
		<p class="section-kicker reveal" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'kicker' ) ); ?>"><?php echo esc_html( $attributes['kicker'] ?? '' ); ?></p>
		<div class="manifesto-copy reveal" data-delay="1">
			<h2 id="<?php echo esc_attr( $title_id ); ?>"><span style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'title' ) ); ?>"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></span><em style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'emphasis' ) ); ?>"><?php echo wp_kses_post( $attributes['emphasis'] ?? '' ); ?></em></h2>
			<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'body' ) ); ?>"><?php echo wp_kses_post( $attributes['body'] ?? '' ); ?></p>
		</div>
	</div>
	<div class="gallery-strip reveal" aria-label="<?php esc_attr_e( 'Фотогалерея', 'project-blocks' ); ?>">
		<?php foreach ( $gallery_class as $index => $class_name ) : ?>
			<?php $image = project_blocks_image( $gallery[ $index ] ?? array() ); ?>
			<div class="photo-placeholder <?php echo esc_attr( $class_name . ( $image['url'] ? ' project-blocks-has-image project-blocks-gallery-image' : '' ) ); ?>">
				<?php if ( $image['url'] ) : ?>
					<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
				<?php else : ?>
					<span><?php esc_html_e( 'ТУТ БУДЕТ ФОТО', 'project-blocks' ); ?></span>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
