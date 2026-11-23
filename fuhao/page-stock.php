<?php
/**
 * 现货供应页面。
 *
 * @package Fuhao
 */

get_header();

while ( have_posts() ) :
	the_post();

	$fuhao_sub = has_excerpt()
		? get_the_excerpt()
		: __( '部分款式提供现货与现货定制，支持国内一件代发、跨境贴标与平台面单。具体可供款式、数量与运费请询价确认。', 'fuhao' );

	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow' => __( '现货供应', 'fuhao' ),
			'sub'     => $fuhao_sub,
			'trail'   => array( array( 'label' => __( '现货供应', 'fuhao' ) ) ),
		)
	);

	if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
		?>
		<div class="page-main">
			<div class="container">
				<div class="entry-content entry-content--wide">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
		<?php
	else :
		?>
	<div class="page-main">
		<div class="container">
			<div class="intro-grid">
				<div class="intro-grid__content">
					<p class="eyebrow"><?php esc_html_e( '合作说明', 'fuhao' ); ?></p>
					<h2><?php esc_html_e( '按订单安排发出，适合电商与礼品渠道', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '现货与现货定制适合需要快速补货、试销或做活动礼品的客户。网站不公开库存数量，具体可供款式、颜色与数量由业务同事按当批情况确认。', 'fuhao' ); ?></p>

					<ul class="check-list">
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '现货款按现有款式与配色供应', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '现货定制支持 LOGO、配色与包装调整', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '支持国内一件代发、跨境贴标与平台面单', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '棉被、衣物等大件可做真空压缩包装', 'fuhao' ); ?></span>
						</li>
					</ul>

					<div class="btn-row">
						<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
							<?php esc_html_e( '咨询现货款式', 'fuhao' ); ?>
							<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</a>
						<a class="btn btn--outline" href="<?php echo esc_url( fuhao_product_archive_url() ); ?>">
							<?php esc_html_e( '查看产品中心', 'fuhao' ); ?>
						</a>
					</div>
				</div>

				<aside class="intro-grid__aside">
					<h3><?php esc_html_e( '下单前请确认', 'fuhao' ); ?></h3>
					<ol class="number-list">
						<li><?php esc_html_e( '款式、颜色与数量', 'fuhao' ); ?></li>
						<li><?php esc_html_e( '贴标、包装与发货方式', 'fuhao' ); ?></li>
						<li><?php esc_html_e( '收货地址与运费', 'fuhao' ); ?></li>
						<li><?php esc_html_e( '交期与售后要求', 'fuhao' ); ?></li>
					</ol>
					<p class="note"><?php esc_html_e( '贴标、面单、代发与真空压缩服务免费，运费另计。', 'fuhao' ); ?></p>
				</aside>
			</div>
		</div>
	</div>

	<section class="section section--paper">
		<div class="container">
			<div class="section-head section-head--center reveal">
				<p class="eyebrow"><?php esc_html_e( '服务内容', 'fuhao' ); ?></p>
				<h2><?php esc_html_e( '围绕订单的配套服务', 'fuhao' ); ?></h2>
				<p class="section-head__text"><?php esc_html_e( '从贴标、包装到发货，按你的渠道要求处理。', 'fuhao' ); ?></p>
			</div>

			<div class="stock-grid">
				<article class="stock-card reveal">
					<span class="stock-card__icon"><?php echo fuhao_icon( 'box' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '国内一件代发', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按订单打包发出，适合电商卖家、私域与礼品渠道。', 'fuhao' ); ?></p>
				</article>
				<article class="stock-card reveal">
					<span class="stock-card__icon"><?php echo fuhao_icon( 'bag' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '跨境贴标', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按平台与订单要求贴标，具体标签规范以确认件为准。', 'fuhao' ); ?></p>
				</article>
				<article class="stock-card reveal">
					<span class="stock-card__icon"><?php echo fuhao_icon( 'layers' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '平台面单', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '支持常用电商平台面单，具体平台与格式请询价确认。', 'fuhao' ); ?></p>
				</article>
				<article class="stock-card reveal">
					<span class="stock-card__icon"><?php echo fuhao_icon( 'box' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '真空压缩包装', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '棉被、衣物等大件可做压缩包装，降低运输体积。', 'fuhao' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="section-head reveal">
				<p class="eyebrow"><?php esc_html_e( '合作流程', 'fuhao' ); ?></p>
				<h2><?php esc_html_e( '从询价到发出，四步完成', 'fuhao' ); ?></h2>
			</div>

			<div class="process-grid">
				<article class="process-step reveal">
					<span class="process-step__num">01</span>
					<h3><?php esc_html_e( '询价确认', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '提供款式编号、数量与收货方式，我们确认可供情况与报价。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">02</span>
					<h3><?php esc_html_e( '资料确认', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '确认 LOGO、标签、包装与面单要求。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">03</span>
					<h3><?php esc_html_e( '备货 / 生产', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按订单安排现货备货或现货定制。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">04</span>
					<h3><?php esc_html_e( '质检发出', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '出货前检查，并按约定方式交付。', 'fuhao' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="cta-band">
		<div class="container cta-band__inner">
			<div>
				<h2><?php esc_html_e( '想确认现货款式？', 'fuhao' ); ?></h2>
				<p><?php esc_html_e( '把款式、数量和收货城市发给我们，业务同事会回复可供情况与运费。', 'fuhao' ); ?></p>
			</div>
			<div class="btn-row">
				<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系我们', 'fuhao' ); ?></a>
				<?php $fuhao_whatsapp = fuhao_whatsapp_href( fuhao_get( 'contact_whatsapp' ) ); ?>
				<?php if ( $fuhao_whatsapp ) : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( $fuhao_whatsapp ); ?>" target="_blank" rel="noopener">WhatsApp</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
		<?php
	endif;
	?>
	<?php
endwhile;

get_footer();
