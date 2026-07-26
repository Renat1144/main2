<?php
/**
 * Render the editable audience grid.
 *
 * @package ProjectBlocks
 */

$kicker = sanitize_text_field( $attributes['kicker'] ?? __( 'Направление', 'project-blocks' ) );
$title = wp_kses_post( $attributes['title'] ?? __( 'Для кого этот мастер-класс', 'project-blocks' ) );
$items = is_array( $attributes['items'] ?? null ) ? array_slice( $attributes['items'], 0, 4 ) : array();
$typo_kicker = project_blocks_typography_style( $attributes, 'kicker' );
$typo_title = project_blocks_typography_style( $attributes, 'title' );
$typo_item_number = project_blocks_typography_style( $attributes, 'itemNumber' );
$typo_item_text = project_blocks_typography_style( $attributes, 'itemText' );
$title_id = wp_unique_id( 'master-audience-title-' );
$wrapper = get_block_wrapper_attributes(
	array(
		'class' => 'master-audience section-pad',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell">
		<div class="master-detail-section-heading master-detail-section-heading-light reveal">
			<p class="section-kicker section-kicker-gold" style="<?php echo esc_attr( $typo_kicker ); ?>"><?php echo esc_html( $kicker ); ?></p>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( $typo_title ); ?>"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		</div>
		<div class="master-audience-grid">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$number = sanitize_text_field( $item['number'] ?? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) );
				$text = wp_kses_post( $item['text'] ?? '' );
				?>
				<div class="master-audience-item reveal"<?php echo 1 === $index % 2 ? ' data-delay="1"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<span style="<?php echo esc_attr( $typo_item_number ); ?>"><?php echo esc_html( $number ); ?></span>
					<?php if ( $text ) : ?>
						<div class="master-audience-text" style="<?php echo esc_attr( $typo_item_text ); ?>"><?php echo $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php else : ?>
						<div class="placeholder-lines" aria-hidden="true"><i></i><i></i></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
