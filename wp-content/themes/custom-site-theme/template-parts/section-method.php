<section class="method section-pad" aria-labelledby="method-title">
	<div class="shell method-grid">
		<div class="method-copy reveal">
			<p class="section-kicker section-kicker-gold"><?php echo esc_html( custom_site_theme_mod( 'method_kicker' ) ); ?></p>
			<h2 id="method-title"><?php echo esc_html( custom_site_theme_mod( 'method_title' ) ); ?></h2>
			<p class="method-lead"><?php echo esc_html( custom_site_theme_mod( 'method_lead' ) ); ?></p>
			<ol class="method-steps">
				<?php for ( $step = 1; $step <= 3; $step++ ) : ?>
					<li>
						<span><?php echo esc_html( str_pad( (string) $step, 2, '0', STR_PAD_LEFT ) ); ?></span>
						<div>
							<h3><?php echo esc_html( custom_site_theme_mod( 'method_step_' . $step . '_title' ) ); ?></h3>
							<p><?php echo esc_html( custom_site_theme_mod( 'method_step_' . $step . '_body' ) ); ?></p>
						</div>
					</li>
				<?php endfor; ?>
			</ol>
		</div>

		<div class="method-visual reveal" data-delay="1">
			<div class="method-photo portrait-panel">
				<img class="section-portrait method-portrait" src="<?php echo esc_url( custom_site_theme_image( 'method', 'photo3.png' ) ); ?>" width="1122" height="1402" alt="<?php esc_attr_e( 'Портрет специалиста', 'custom-site-theme' ); ?>" loading="lazy" decoding="async">
			</div>
		</div>
	</div>
</section>

