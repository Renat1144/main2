<section class="about section-pad" id="about" aria-labelledby="about-title">
	<div class="shell about-grid">
		<div class="about-visual reveal">
			<div class="photo-frame">
				<div class="photo-placeholder-about portrait-panel">
					<img class="section-portrait about-portrait" src="<?php echo esc_url( custom_site_theme_image( 'about', 'photo1.png' ) ); ?>" width="1024" height="1536" alt="<?php esc_attr_e( 'Портрет специалиста', 'custom-site-theme' ); ?>" loading="lazy" decoding="async">
				</div>
			</div>
			<p class="vertical-label" aria-hidden="true"><?php echo esc_html( custom_site_theme_mod( 'about_vertical_label' ) ); ?></p>
		</div>

		<div class="about-copy reveal" data-delay="1">
			<p class="section-kicker"><?php echo esc_html( custom_site_theme_mod( 'about_kicker' ) ); ?></p>
			<h2 id="about-title"><?php echo esc_html( custom_site_theme_mod( 'about_title' ) ); ?></h2>
			<p class="about-name"><?php echo esc_html( custom_site_theme_mod( 'about_name' ) ); ?></p>
			<p class="about-role"><?php echo esc_html( custom_site_theme_mod( 'about_role' ) ); ?></p>

			<div class="credentials" role="list">
				<?php for ( $item = 1; $item <= 3; $item++ ) : ?>
					<article class="credential" role="listitem">
						<span class="credential-mark" aria-hidden="true">✦</span>
						<div>
							<h3><?php echo esc_html( custom_site_theme_mod( 'credential_' . $item . '_title' ) ); ?></h3>
							<p><?php echo esc_html( custom_site_theme_mod( 'credential_' . $item . '_body' ) ); ?></p>
						</div>
					</article>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</section>

