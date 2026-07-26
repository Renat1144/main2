<?php
/**
 * Render the editable homepage expertise section.
 *
 * @package ProjectBlocks
 */

$cards    = is_array( $attributes['cards'] ?? null ) ? $attributes['cards'] : array();
$title_id = wp_unique_id( 'expertise-title-' );
$wrapper  = get_block_wrapper_attributes(
	array(
		'class'           => 'expertise section-pad',
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
		<div class="expertise-grid">
			<?php foreach ( $cards as $index => $card ) : ?>
				<article class="expertise-card<?php echo 3 === $index ? ' expertise-card-featured' : ''; ?> reveal"<?php echo in_array( $index, array( 1, 4 ), true ) ? ' data-delay="1"' : ''; ?><?php echo in_array( $index, array( 2, 5 ), true ) ? ' data-delay="2"' : ''; ?>>
					<span class="card-index" style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'cardNumber' ) ); ?>"><?php echo esc_html( $card['number'] ?? '' ); ?></span>
					<h3 style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'cardTitle' ) ); ?>"><?php echo wp_kses_post( $card['title'] ?? '' ); ?></h3>
					<p style="<?php echo esc_attr( project_blocks_typography_style( $attributes, 'cardBody' ) ); ?>"><?php echo wp_kses_post( $card['body'] ?? '' ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
