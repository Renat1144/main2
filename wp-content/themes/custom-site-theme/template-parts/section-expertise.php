<section class="expertise section-pad" aria-labelledby="expertise-title">
	<div class="shell">
		<div class="section-heading reveal">
			<div>
				<p class="section-kicker"><?php echo esc_html( custom_site_theme_mod( 'expertise_kicker' ) ); ?></p>
				<h2 id="expertise-title"><?php echo esc_html( custom_site_theme_mod( 'expertise_title' ) ); ?></h2>
			</div>
			<p class="section-intro"><?php echo esc_html( custom_site_theme_mod( 'expertise_intro' ) ); ?></p>
		</div>

		<div class="expertise-grid">
			<?php for ( $item = 1; $item <= 6; $item++ ) : ?>
				<article class="expertise-card<?php echo 4 === $item ? ' expertise-card-featured' : ''; ?> reveal"<?php echo in_array( $item, array( 2, 5 ), true ) ? ' data-delay="1"' : ''; ?><?php echo in_array( $item, array( 3, 6 ), true ) ? ' data-delay="2"' : ''; ?>>
					<span class="card-index"><?php echo esc_html( str_pad( (string) $item, 2, '0', STR_PAD_LEFT ) ); ?></span>
					<h3><?php echo esc_html( custom_site_theme_mod( 'expertise_' . $item . '_title' ) ); ?></h3>
					<p><?php echo esc_html( custom_site_theme_mod( 'expertise_' . $item . '_body' ) ); ?></p>
				</article>
			<?php endfor; ?>
		</div>
	</div>
</section>

