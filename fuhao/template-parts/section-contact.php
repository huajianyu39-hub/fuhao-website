<?php
/**
 * 联系方式与询盘表单。
 *
 * @package Fuhao
 */

$fuhao_tone     = isset( $args['tone'] ) ? $args['tone'] : 'paper';
$fuhao_status   = isset( $_GET['inquiry'] ) ? sanitize_key( wp_unslash( $_GET['inquiry'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$fuhao_people   = fuhao_contact_people();
$fuhao_emails   = fuhao_contact_emails();
$fuhao_email    = $fuhao_emails ? $fuhao_emails[0] : '';
$fuhao_wa_phone = fuhao_get( 'contact_whatsapp' );
$fuhao_wa_href  = fuhao_whatsapp_href( $fuhao_wa_phone );
$fuhao_address  = fuhao_get( 'contact_address' );
$fuhao_hours    = fuhao_get( 'contact_hours' );
?>
<section class="section contact<?php echo 'paper' === $fuhao_tone ? ' section--paper' : ''; ?>" id="contact">
	<div class="container contact__grid">
		<div class="contact__info reveal">
			<p class="eyebrow"><?php echo esc_html( fuhao_get( 'sec_contact_eyebrow' ) ); ?></p>
			<h2><?php echo esc_html( fuhao_get( 'sec_contact_title' ) ); ?></h2>
			<p><?php esc_html_e( '无论是新品开发、来样定制，还是想了解产能与交期，都欢迎直接联系我们的业务团队。', 'fuhao' ); ?></p>

			<ul class="contact-list">
				<?php foreach ( $fuhao_people as $fuhao_person ) : ?>
					<li>
						<span class="contact-list__icon">
							<?php echo fuhao_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</span>
						<div>
							<span class="contact-list__label"><?php echo esc_html( $fuhao_person['name'] ? $fuhao_person['name'] : __( '业务联系', 'fuhao' ) ); ?></span>
							<a class="contact-list__value" href="<?php echo esc_url( $fuhao_person['href'] ); ?>"><?php echo esc_html( $fuhao_person['phone'] ); ?></a>
						</div>
					</li>
				<?php endforeach; ?>

				<?php if ( $fuhao_wa_href ) : ?>
					<li>
						<span class="contact-list__icon">
							<?php echo fuhao_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</span>
						<div>
							<span class="contact-list__label"><?php esc_html_e( 'WhatsApp', 'fuhao' ); ?></span>
							<a class="contact-list__value" href="<?php echo esc_url( $fuhao_wa_href ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $fuhao_wa_phone ); ?></a>
						</div>
					</li>
				<?php endif; ?>

				<?php foreach ( $fuhao_emails as $fuhao_index => $fuhao_item_email ) : ?>
					<li>
						<span class="contact-list__icon">
							<?php echo fuhao_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</span>
						<div>
							<span class="contact-list__label"><?php echo 0 === $fuhao_index ? esc_html__( '商务邮箱', 'fuhao' ) : esc_html__( '备用邮箱', 'fuhao' ); ?></span>
							<a class="contact-list__value" href="mailto:<?php echo esc_attr( $fuhao_item_email ); ?>"><?php echo esc_html( $fuhao_item_email ); ?></a>
						</div>
					</li>
				<?php endforeach; ?>

				<?php if ( $fuhao_address ) : ?>
					<li>
						<span class="contact-list__icon">
							<?php echo fuhao_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</span>
						<div>
							<span class="contact-list__label"><?php esc_html_e( '工厂地址', 'fuhao' ); ?></span>
							<span class="contact-list__value"><?php echo esc_html( $fuhao_address ); ?></span>
						</div>
					</li>
				<?php endif; ?>

				<?php if ( $fuhao_hours ) : ?>
					<li>
						<span class="contact-list__icon">
							<?php echo fuhao_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</span>
						<div>
							<span class="contact-list__label"><?php esc_html_e( '工作时间', 'fuhao' ); ?></span>
							<span class="contact-list__value"><?php echo esc_html( $fuhao_hours ); ?></span>
						</div>
					</li>
				<?php endif; ?>
			</ul>

			<p class="contact__note"><?php esc_html_e( '如需到厂参观验厂，请提前预约，方便安排接待。', 'fuhao' ); ?></p>
		</div>

		<div class="contact__form reveal">
			<h3 class="form-title"><?php esc_html_e( '获取报价 / 索取样品', 'fuhao' ); ?></h3>
			<p class="form-sub"><?php esc_html_e( '简单填写需求，我们会尽快与你联系。带 * 为必填项。', 'fuhao' ); ?></p>

			<?php if ( 'success' === $fuhao_status ) : ?>
				<div class="notice notice--success" role="status">
					<?php esc_html_e( '感谢你的询盘，我们会尽快回复。', 'fuhao' ); ?>
					<?php if ( $fuhao_email ) : ?>
						<?php esc_html_e( '也可以直接发送邮件至', 'fuhao' ); ?>
						<a href="mailto:<?php echo esc_attr( $fuhao_email ); ?>"><?php echo esc_html( $fuhao_email ); ?></a>。
					<?php endif; ?>
				</div>
			<?php elseif ( 'error' === $fuhao_status ) : ?>
				<div class="notice notice--error" role="alert">
					<?php esc_html_e( '提交没有成功，请检查必填项与隐私政策勾选后重试。', 'fuhao' ); ?>
					<?php if ( $fuhao_email ) : ?>
						<?php esc_html_e( '或直接发送邮件至', 'fuhao' ); ?>
						<a href="mailto:<?php echo esc_attr( $fuhao_email ); ?>"><?php echo esc_html( $fuhao_email ); ?></a>。
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<form class="inquiry-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="fuhao_inquiry">
				<?php wp_nonce_field( 'fuhao_inquiry', 'fuhao_inquiry_nonce' ); ?>

				<div class="form-hp" aria-hidden="true">
					<label for="inquiry-website">Website</label>
					<input type="text" id="inquiry-website" name="fuhao_website" tabindex="-1" autocomplete="off">
				</div>

				<div class="form-grid">
					<div class="field">
						<label for="inquiry-name"><?php esc_html_e( '姓名', 'fuhao' ); ?> <span class="req">*</span></label>
						<input type="text" id="inquiry-name" name="name" autocomplete="name" required>
					</div>
					<div class="field">
						<label for="inquiry-company"><?php esc_html_e( '公司名称', 'fuhao' ); ?></label>
						<input type="text" id="inquiry-company" name="company" autocomplete="organization">
					</div>
					<div class="field">
						<label for="inquiry-email"><?php esc_html_e( '邮箱', 'fuhao' ); ?> <span class="req">*</span></label>
						<input type="email" id="inquiry-email" name="email" autocomplete="email" required>
					</div>
					<div class="field">
						<label for="inquiry-phone"><?php esc_html_e( '电话 / WhatsApp', 'fuhao' ); ?></label>
						<input type="text" id="inquiry-phone" name="phone" autocomplete="tel">
					</div>
					<div class="field">
						<label for="inquiry-type"><?php esc_html_e( '合作方式', 'fuhao' ); ?></label>
						<select id="inquiry-type" name="product_type">
							<option value="现货供应"><?php esc_html_e( '现货供应', 'fuhao' ); ?></option>
							<option value="现货定制"><?php esc_html_e( '现货定制', 'fuhao' ); ?></option>
							<option value="批量定制 / OEM"><?php esc_html_e( '批量定制 / OEM', 'fuhao' ); ?></option>
							<option value="ODM 设计开发"><?php esc_html_e( 'ODM 设计开发', 'fuhao' ); ?></option>
							<option value="其他"><?php esc_html_e( '其他', 'fuhao' ); ?></option>
						</select>
					</div>
					<div class="field">
						<label for="inquiry-quantity"><?php esc_html_e( '预计采购量', 'fuhao' ); ?></label>
						<input type="text" id="inquiry-quantity" name="quantity" placeholder="<?php esc_attr_e( '如：500 pcs', 'fuhao' ); ?>">
					</div>
					<div class="field field--full">
						<label for="inquiry-message"><?php esc_html_e( '需求说明', 'fuhao' ); ?> <span class="req">*</span></label>
						<textarea id="inquiry-message" name="message" rows="5" placeholder="<?php esc_attr_e( '产品结构、材质、尺寸、交期等要求，也可以说明已有样品或参考款。', 'fuhao' ); ?>" required></textarea>
					</div>
					<div class="field field--full">
						<label class="consent" for="inquiry-consent">
							<input type="checkbox" id="inquiry-consent" name="consent" value="1" required>
							<span>
								<?php esc_html_e( '我已阅读并同意', 'fuhao' ); ?>
								<a href="<?php echo esc_url( fuhao_page_url( 'privacy' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( '隐私政策', 'fuhao' ); ?></a>，
								<?php esc_html_e( '同意网站使用我提交的信息与我联系。', 'fuhao' ); ?>
							</span>
						</label>
					</div>
				</div>

				<button class="btn btn--accent btn--block" type="submit"><?php esc_html_e( '提交询盘', 'fuhao' ); ?></button>
				<p class="form-note"><?php esc_html_e( '你的信息仅用于业务沟通，不会提供给第三方。', 'fuhao' ); ?></p>
			</form>
		</div>
	</div>
</section>
