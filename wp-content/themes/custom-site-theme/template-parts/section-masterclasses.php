<section class="masterclasses section-pad" id="masterclasses" aria-labelledby="masterclasses-title">
	<div class="shell">
		<div class="section-heading reveal">
			<div>
				<p class="section-kicker"><?php echo esc_html( custom_site_theme_mod( 'masterclasses_kicker' ) ); ?></p>
				<h2 id="masterclasses-title"><?php echo esc_html( custom_site_theme_mod( 'masterclasses_title' ) ); ?></h2>
			</div>
			<p class="section-intro"><?php echo esc_html( custom_site_theme_mod( 'masterclasses_intro' ) ); ?></p>
		</div>

		<div class="masterclass-grid">
			<?php for ( $item = 1; $item <= 3; $item++ ) : ?>
				<article class="masterclass-card reveal"<?php echo 2 === $item ? ' data-delay="1"' : ''; ?><?php echo 3 === $item ? ' data-delay="2"' : ''; ?>>
					<div class="photo-placeholder masterclass-photo"><span><?php esc_html_e( 'ТУТ БУДЕТ ФОТО', 'custom-site-theme' ); ?></span></div>
					<div class="masterclass-content">
						<p class="card-label"><?php echo esc_html( sprintf( 'Мастер-класс %02d', $item ) ); ?></p>
						<h3><?php echo esc_html( custom_site_theme_mod( 'masterclass_' . $item . '_title' ) ); ?></h3>
						<p><?php echo esc_html( custom_site_theme_mod( 'masterclass_' . $item . '_body' ) ); ?></p>
						<?php if ( 1 === $item ) : ?>
							<?php $master_page = get_page_by_path( 'master1' ); ?>
							<a class="card-link card-link-primary" href="<?php echo esc_url( $master_page ? get_permalink( $master_page ) : home_url( '/master1/' ) ); ?>">
								<?php esc_html_e( 'Получить', 'custom-site-theme' ); ?> <span aria-hidden="true">↗</span>
							</a>
						<?php else : ?>
							<button class="card-link" type="button" data-placeholder-action><?php esc_html_e( 'Скоро', 'custom-site-theme' ); ?> <span aria-hidden="true">↗</span></button>
						<?php endif; ?>
					</div>
				</article>
			<?php endfor; ?>
		</div>
	</div>
</section>

