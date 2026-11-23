<?php
/**
 * 页头。
 *
 * @package Fuhao
 */

$fuhao_phone    = fuhao_get( 'contact_phone' );
$fuhao_email    = fuhao_get( 'contact_email' );
$fuhao_whatsapp = fuhao_get( 'contact_whatsapp' );
$fuhao_brand    = fuhao_get( 'brand_name' );
$fuhao_tagline  = fuhao_get( 'brand_tagline' );
$fuhao_contact  = fuhao_page_url( 'contact' );
$fuhao_quote    = fuhao_page_url( 'customization' ) . '#quote';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">跳到主要内容</a>

<?php if ( $fuhao_phone || $fuhao_email ) : ?>
	<div class="topbar">
		<div class="container topbar__inner">
			<div class="topbar__contact">
				<?php if ( $fuhao_phone ) : ?>
					<a href="<?php echo esc_url( fuhao_tel_href( $fuhao_phone ) ); ?>">
						<?php echo fuhao_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						<span><?php echo esc_html( $fuhao_phone ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $fuhao_email ) : ?>
					<a href="mailto:<?php echo esc_attr( $fuhao_email ); ?>">
						<?php echo fuhao_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						<span><?php echo esc_html( $fuhao_email ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $fuhao_whatsapp ) : ?>
					<a href="<?php echo esc_url( fuhao_whatsapp_href( $fuhao_whatsapp ) ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo fuhao_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						<span>WhatsApp</span>
					</a>
				<?php endif; ?>
			</div>
			<div class="topbar__meta">
				<span class="topbar__note">支持中英文沟通 · 欢迎询价</span>
				<?php fuhao_language_switch(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="brand__name"><?php echo esc_html( $fuhao_brand ); ?></span>
					<span class="brand__tagline"><?php echo esc_html( $fuhao_tagline ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<nav class="primary-nav" id="primary-nav" aria-label="主导航">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'fallback_cb'    => 'fuhao_nav_fallback',
					'depth'          => 2,
				)
			);
			?>
			<a class="btn btn--accent nav-cta" href="<?php echo esc_url( $fuhao_quote ); ?>">
				<?php esc_html_e( '获取定制报价', 'fuhao' ); ?>
			</a>
		</nav>

		<div class="header-actions">
			<a class="btn btn--accent btn--sm header-cta" href="<?php echo esc_url( $fuhao_quote ); ?>">
				<?php esc_html_e( '获取报价', 'fuhao' ); ?>
			</a>
			<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="打开导航菜单">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>
