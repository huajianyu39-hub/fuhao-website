<?php
/**
 * 联系我们：扫码联系区（名片二维码 + 微信/电话）。
 *
 * @package Fuhao
 */

$fuhao_company = get_bloginfo( 'name' );
$fuhao_phone   = fuhao_get( 'contact_phone' );
$fuhao_email   = fuhao_get( 'contact_email' );
$fuhao_address = fuhao_get( 'contact_address' );
$fuhao_wechat  = fuhao_get( 'wechat_qr' );

$fuhao_vcard = array(
	'BEGIN:VCARD',
	'VERSION:3.0',
	'FN:' . $fuhao_company,
	'ORG:' . $fuhao_company,
);

if ( $fuhao_phone ) {
	$fuhao_vcard[] = 'TEL;TYPE=CELL:' . $fuhao_phone;
}

if ( $fuhao_email ) {
	$fuhao_vcard[] = 'EMAIL;TYPE=WORK:' . $fuhao_email;
}

if ( $fuhao_address ) {
	$fuhao_vcard[] = 'ADR;TYPE:WORK:;;' . str_replace( array( ';', ',' ), ' ', $fuhao_address ) . ';;;;';
}

$fuhao_vcard[] = 'URL:' . home_url( '/' );
$fuhao_vcard[] = 'END:VCARD';
$fuhao_vcard   = implode( "\n", $fuhao_vcard );
?>
<section class="section contact-qr" id="scan-contact">
	<div class="container contact-qr__grid">
		<div class="contact-qr__intro reveal">
			<p class="eyebrow"><?php esc_html_e( '扫码联系', 'fuhao' ); ?></p>
			<h2><?php esc_html_e( '扫码存名片，或加微信直接聊', 'fuhao' ); ?></h2>
			<p><?php esc_html_e( '用手机相机扫一扫即可保存联系人；也可以在下方表单留言，我们会尽快回复。', 'fuhao' ); ?></p>

			<ul class="contact-qr__meta">
				<?php if ( $fuhao_phone ) : ?>
					<li>
						<span><?php esc_html_e( '电话', 'fuhao' ); ?></span>
						<a href="<?php echo esc_url( fuhao_tel_href( $fuhao_phone ) ); ?>"><?php echo esc_html( $fuhao_phone ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( $fuhao_email ) : ?>
					<li>
						<span><?php esc_html_e( '邮箱', 'fuhao' ); ?></span>
						<a href="mailto:<?php echo esc_attr( $fuhao_email ); ?>"><?php echo esc_html( $fuhao_email ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( $fuhao_address ) : ?>
					<li>
						<span><?php esc_html_e( '地址', 'fuhao' ); ?></span>
						<?php echo esc_html( $fuhao_address ); ?>
					</li>
				<?php endif; ?>
			</ul>
		</div>

		<div class="contact-qr__cards reveal">
			<article class="qr-card">
				<div class="qr-card__code" data-fuhao-qr="<?php echo esc_attr( $fuhao_vcard ); ?>" data-fuhao-qr-alt="<?php esc_attr_e( '联系人名片二维码', 'fuhao' ); ?>"></div>
				<h3><?php esc_html_e( '联系人名片', 'fuhao' ); ?></h3>
				<p><?php esc_html_e( '扫码可直接保存电话与邮箱到手机通讯录。', 'fuhao' ); ?></p>
			</article>

			<?php if ( $fuhao_wechat ) : ?>
				<article class="qr-card">
					<img class="qr-card__img" src="<?php echo esc_url( $fuhao_wechat ); ?>" alt="<?php esc_attr_e( '微信二维码', 'fuhao' ); ?>" loading="lazy">
					<h3><?php esc_html_e( '微信咨询', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '扫码添加微信，直接沟通款式与报价。', 'fuhao' ); ?></p>
				</article>
			<?php else : ?>
				<article class="qr-card qr-card--text">
					<h3><?php esc_html_e( '电话 / WhatsApp', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '工作时间直接拨打，沟通更快。', 'fuhao' ); ?></p>
					<?php if ( $fuhao_phone ) : ?>
						<a class="btn btn--accent btn--sm" href="<?php echo esc_url( fuhao_tel_href( $fuhao_phone ) ); ?>"><?php echo esc_html( sprintf( __( '拨打 %s', 'fuhao' ), $fuhao_phone ) ); ?></a>
					<?php endif; ?>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>
