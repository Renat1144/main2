<footer class="site-footer">
	<div class="shell footer-grid">
		<div class="footer-brand">
			<a class="brand brand-footer" href="<?php echo esc_url( home_url( '/#top' ) ); ?>">
				<span class="brand-mark" aria-hidden="true"></span>
				<span class="brand-copy">
					<span class="brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					<span class="brand-caption"><?php echo esc_html( custom_site_theme_mod( 'brand_caption' ) ); ?></span>
				</span>
			</a>
			<p><?php echo esc_html( custom_site_theme_mod( 'footer_description' ) ); ?></p>
		</div>

		<div class="footer-column">
			<h2><?php echo esc_html( custom_site_theme_mod( 'footer_navigation_title' ) ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'fallback_cb'    => 'custom_site_theme_footer_menu_fallback',
					'depth'          => 1,
				)
			);
			?>
		</div>

		<div class="footer-column">
			<h2><?php echo esc_html( custom_site_theme_mod( 'footer_documents_title' ) ); ?></h2>
			<?php for ( $document = 1; $document <= 3; $document++ ) : ?>
				<?php
				$document_label = custom_site_theme_mod( 'footer_document_' . $document . '_label' );
				$document_url   = custom_site_theme_mod( 'footer_document_' . $document . '_url' );
				?>
				<?php if ( $document_url ) : ?>
					<a href="<?php echo esc_url( $document_url ); ?>"><?php echo esc_html( $document_label ); ?></a>
				<?php else : ?>
					<button type="button" data-placeholder-action><?php echo esc_html( $document_label ); ?></button>
				<?php endif; ?>
			<?php endfor; ?>
		</div>

		<div class="footer-column footer-contact">
			<h2><?php echo esc_html( custom_site_theme_mod( 'footer_contacts_title' ) ); ?></h2>
			<p><?php echo esc_html( custom_site_theme_mod( 'footer_contact_text' ) ); ?></p>
			<?php if ( custom_site_theme_mod( 'footer_contact_url' ) ) : ?>
				<a class="footer-contact-link" href="<?php echo esc_url( custom_site_theme_mod( 'footer_contact_url' ) ); ?>">
					<?php echo esc_html( custom_site_theme_mod( 'footer_contact_label' ) ); ?> ↗
				</a>
			<?php else : ?>
				<button class="footer-contact-link" type="button" data-placeholder-action>
					<?php echo esc_html( custom_site_theme_mod( 'footer_contact_label' ) ); ?> ↗
				</button>
			<?php endif; ?>
		</div>
	</div>

	<div class="shell footer-bottom">
		<p>© <span id="current-year"><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<p><?php echo esc_html( custom_site_theme_mod( 'footer_legal' ) ); ?></p>
		<a href="#top"><?php echo esc_html( custom_site_theme_mod( 'footer_back_to_top' ) ); ?></a>
	</div>
</footer>

<div class="toast" role="status" aria-live="polite" aria-atomic="true">
	<?php esc_html_e( 'Ссылка будет добавлена позже', 'custom-site-theme' ); ?>
</div>

<?php wp_footer(); ?>
</body>
</html>
