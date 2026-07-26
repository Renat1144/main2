<section class="hero" aria-labelledby="hero-title">
	<div class="hero-aurora" aria-hidden="true"></div>
	<div class="shell hero-grid">
		<div class="hero-copy reveal">
			<p class="eyebrow"><span>01</span> <?php echo esc_html( custom_site_theme_mod( 'hero_eyebrow' ) ); ?></p>
			<h1 id="hero-title"><?php echo esc_html( custom_site_theme_mod( 'hero_title' ) ); ?></h1>
			<p class="hero-lead"><?php echo esc_html( custom_site_theme_mod( 'hero_lead' ) ); ?></p>
			<div class="hero-actions">
				<a class="button button-primary" href="#masterclasses">
					<?php echo esc_html( custom_site_theme_mod( 'hero_primary_label' ) ); ?> <span aria-hidden="true">↗</span>
				</a>
				<a class="text-link" href="#method"><?php echo esc_html( custom_site_theme_mod( 'hero_secondary_label' ) ); ?></a>
			</div>
		</div>

		<div class="hero-visual reveal" data-delay="1">
			<div class="photo-placeholder-portrait hero-portrait-frame">
				<img class="hero-portrait-image" src="<?php echo esc_url( custom_site_theme_image( 'hero', 'photo2.png' ) ); ?>" width="1122" height="1402" alt="<?php esc_attr_e( 'Портрет специалиста', 'custom-site-theme' ); ?>" decoding="async" fetchpriority="high">
			</div>
		</div>
	</div>
</section>

