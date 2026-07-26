<?php
/**
 * Render the editable offer section.
 *
 * @package ProjectBlocks
 */

$number = sanitize_text_field( $attributes['number'] ?? '02' );
$label = sanitize_text_field( $attributes['label'] ?? __( 'Доступ к материалу', 'project-blocks' ) );
$title = wp_kses_post( $attributes['title'] ?? __( 'Название мастер-класса', 'project-blocks' ) );
$price = sanitize_text_field( $attributes['price'] ?? __( 'Стоимость будет добавлена', 'project-blocks' ) );
$composition_title = sanitize_text_field( $attributes['compositionTitle'] ?? __( 'Состав будет добавлен', 'project-blocks' ) );
$items = is_array( $attributes['items'] ?? null ) ? array_slice( $attributes['items'], 0, 3 ) : array();
$button_text = sanitize_text_field( $attributes['buttonText'] ?? __( 'Оплатить', 'project-blocks' ) );
$button_url = esc_url( $attributes['buttonUrl'] ?? '' );
$typo_label = project_blocks_typography_style( $attributes, 'label' );
$typo_title = project_blocks_typography_style( $attributes, 'title' );
$typo_price = project_blocks_typography_style( $attributes, 'price' );
$typo_composition_title = project_blocks_typography_style( $attributes, 'compositionTitle' );
$typo_item_number = project_blocks_typography_style( $attributes, 'itemNumber' );
$typo_item_text = project_blocks_typography_style( $attributes, 'itemText' );
$typo_button = project_blocks_typography_style( $attributes, 'button' );
$title_id = wp_unique_id( 'master-offer-title-' );
$wrapper = get_block_wrapper_attributes(
	array(
		'class' => 'master-offer section-pad',
		'aria-labelledby' => $title_id,
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shell">
		<div class="master-offer-card reveal">
			<div class="master-offer-copy">
				<p class="eyebrow" style="<?php echo esc_attr( $typo_label ); ?>"><span><?php echo esc_html( $number ); ?></span> <?php echo esc_html( $label ); ?></p>
				<h2 id="<?php echo esc_attr( $title_id ); ?>" style="<?php echo esc_attr( $typo_title ); ?>"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<p class="master-offer-price" style="<?php echo esc_attr( $typo_price ); ?>"><?php echo esc_html( $price ); ?></p>
			</div>
			<div class="master-offer-content">
				<p class="placeholder-caption" style="<?php echo esc_attr( $typo_composition_title ); ?>"><?php echo esc_html( $composition_title ); ?></p>
				<?php foreach ( $items as $index => $item ) : ?>
					<?php
					$number_value = sanitize_text_field( $item['number'] ?? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) );
					$text = wp_kses_post( $item['text'] ?? '' );
					?>
					<div class="master-offer-row">
						<span style="<?php echo esc_attr( $typo_item_number ); ?>"><?php echo esc_html( $number_value ); ?></span>
						<?php if ( $text ) : ?>
							<div class="master-offer-item-text" style="<?php echo esc_attr( $typo_item_text ); ?>"><?php echo $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php else : ?>
							<i aria-hidden="true"></i>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( $button_url ) : ?>
				<a class="button button-primary master-pay-button" style="<?php echo esc_attr( $typo_button ); ?>" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_text ); ?> <span aria-hidden="true">↗</span></a>
			<?php else : ?>
				<button class="button button-primary master-pay-button" style="<?php echo esc_attr( $typo_button ); ?>" type="button" aria-disabled="true" disabled><?php echo esc_html( $button_text ); ?> <span aria-hidden="true">↗</span></button>
			<?php endif; ?>
		</div>
	</div>
</section>
