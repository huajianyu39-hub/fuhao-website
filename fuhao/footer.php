<?php
/**
 * 页脚。
 *
 * @package Fuhao
 */

$fuhao_phone    = fuhao_get( 'contact_phone' );
$fuhao_email    = fuhao_get( 'contact_email' );
$fuhao_whatsapp = fuhao_get( 'contact_whatsapp' );
$fuhao_address  = fuhao_get( 'contact_address' );
$fuhao_icp      = fuhao_get( 'footer_icp' );
$fuhao_brand    = fuhao_get( 'brand_name' );
$fuhao_tagline  = fuhao_get( 'brand_tagline' );
$fuhao_cats     = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'number'     => 8,
	)
);
?>
<footer class="site-footer">
	<div class="container">
		<div class="footer__grid">
			<div class="footer__brand">
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="brand__name"><?php echo esc_html( $fuhao_brand ); ?></span>
					<span class="brand__tagline"><?php echo esc_html( $fuhao_tagline ); ?></span>
				</a>
				<p><?php echo esc_html( fuhao_get( 'footer_desc' ) ); ?></p>
			</div>

			<div>
				<h2 class="footer__title">产品系列</h2>
				<?php if ( ! is_wp_error( $fuhao_cats ) && $fuhao_cats ) : ?>
					<ul class="footer-list">
						<?php foreach ( $fuhao_cats as $fuhao_cat ) : ?>
							<?php $fuhao_term_link = get_term_link( $fuhao_cat ); ?>
							<?php if ( ! is_wp_error( $fuhao_term_link ) ) : ?>
								<li><a href="<?php echo esc_url( $fuhao_term_link ); ?>"><?php echo esc_html( $fuhao_cat->name ); ?></a></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<ul class="footer-list">
						<li><a href="<?php echo esc_url( fuhao_product_archive_url() ); ?>">产品中心</a></li>
					</ul>
				<?php endif; ?>
			</div>

			<div>
				<h2 class="footer__title">快速链接</h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-list',
						'fallback_cb'    => 'fuhao_footer_nav_fallback',
						'depth'          => 1,
					)
				);
				?>
			</div>

			<div>
				<h2 class="footer__title">联系我们</h2>
				<div class="footer__contact">
					<?php if ( $fuhao_phone ) : ?>
						<a href="<?php echo esc_url( fuhao_tel_href( $fuhao_phone ) ); ?>"><?php echo esc_html( $fuhao_phone ); ?></a>
					<?php endif; ?>
					<?php if ( $fuhao_email ) : ?>
						<a href="mailto:<?php echo esc_attr( $fuhao_email ); ?>"><?php echo esc_html( $fuhao_email ); ?></a>
					<?php endif; ?>
					<?php if ( $fuhao_whatsapp ) : ?>
						<a href="<?php echo esc_url( fuhao_whatsapp_href( $fuhao_whatsapp ) ); ?>" target="_blank" rel="noopener noreferrer">WhatsApp：<?php echo esc_html( $fuhao_whatsapp ); ?></a>
					<?php endif; ?>
					<?php if ( $fuhao_address ) : ?>
						<span><?php echo esc_html( $fuhao_address ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="footer__bottom">
			<p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> 版权所有</p>
			<?php if ( $fuhao_icp ) : ?>
				<p>
					<a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $fuhao_icp ); ?></a>
				</p>
			<?php endif; ?>
		</div>
	</div>
</footer>

<button class="to-top" type="button" aria-label="返回顶部">
	<?php echo fuhao_icon( 'arrow-up' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
</button>

<?php wp_footer(); ?>
</body>
</html>
