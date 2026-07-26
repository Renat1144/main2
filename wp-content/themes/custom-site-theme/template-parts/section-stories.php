<section class="stories section-pad" aria-labelledby="stories-title">
	<div class="shell">
		<div class="stories-heading reveal">
			<p class="section-kicker"><?php echo esc_html( custom_site_theme_mod( 'stories_kicker' ) ); ?></p>
			<h2 id="stories-title"><?php echo esc_html( custom_site_theme_mod( 'stories_title' ) ); ?></h2>
			<p><?php echo esc_html( custom_site_theme_mod( 'stories_intro' ) ); ?></p>
		</div>

		<div class="stories-grid">
			<?php for ( $item = 1; $item <= 3; $item++ ) : ?>
				<article class="story-card<?php echo 3 === $item ? ' story-card-wide' : ''; ?> reveal"<?php echo 2 === $item ? ' data-delay="1"' : ''; ?>>
					<div class="photo-placeholder story-photo">
						<span><?php esc_html_e( 'ТУТ БУДЕТ ФОТО', 'custom-site-theme' ); ?></span>
						<span class="story-play" aria-hidden="true">▶</span>
					</div>
					<div class="story-copy">
						<p class="card-label"><?php esc_html_e( 'История участницы', 'custom-site-theme' ); ?></p>
						<h3><?php echo esc_html( custom_site_theme_mod( 'story_' . $item . '_name' ) ); ?></h3>
						<p><?php echo esc_html( custom_site_theme_mod( 'story_' . $item . '_body' ) ); ?></p>
					</div>
				</article>
			<?php endfor; ?>
		</div>
	</div>
</section>

