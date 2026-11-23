<?php
/**
 * 定制服务页面。
 *
 * @package Fuhao
 */

get_header();

while ( have_posts() ) :
	the_post();

	$fuhao_sub = has_excerpt()
		? get_the_excerpt()
		: __( '支持现货定制与批量定制：LOGO、面料、结构、五金与包装可按品牌要求调整。起订量、打样费与交期按款式和工艺评估。', 'fuhao' );

	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow' => __( '定制服务', 'fuhao' ),
			'sub'     => $fuhao_sub,
			'trail'   => array( array( 'label' => __( '定制服务', 'fuhao' ) ) ),
		)
	);

	if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
		?>
		<div class="page-main">
			<div class="container">
				<span id="quote" class="anchor-target" aria-hidden="true"></span>
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
					<p class="eyebrow"><?php esc_html_e( '合作方式', 'fuhao' ); ?></p>
					<h2><?php esc_html_e( '从现货款微调，到完整 OEM / ODM 开发', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '如果已有参考款，可以在现货款基础上做 LOGO、配色与包装定制；如果是全新产品，可按来样、来图或联合开发的方式推进。', 'fuhao' ); ?></p>

					<ul class="check-list">
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '现货定制：在现有款式上调整 LOGO、配色与包装', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '批量定制：来样、来图开发，按项目评估打样与生产', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( 'OEM / ODM：按品牌标准完成开发、打样与量产', 'fuhao' ); ?></span>
						</li>
					</ul>

					<div class="btn-row">
						<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
							<?php esc_html_e( '提交定制需求', 'fuhao' ); ?>
							<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</a>
						<a class="btn btn--outline" href="<?php echo esc_url( fuhao_page_url( 'factory' ) ); ?>"><?php esc_html_e( '了解工厂实力', 'fuhao' ); ?></a>
					</div>
				</div>

				<aside class="intro-grid__aside">
					<h3><?php esc_html_e( '定制前请准备', 'fuhao' ); ?></h3>
					<ol class="number-list">
						<li><?php esc_html_e( '参考款、设计图或样品', 'fuhao' ); ?></li>
						<li><?php esc_html_e( '目标数量与预计返单频率', 'fuhao' ); ?></li>
						<li><?php esc_html_e( '面料、颜色与工艺偏好', 'fuhao' ); ?></li>
						<li><?php esc_html_e( '包装、标签与出货要求', 'fuhao' ); ?></li>
						<li><?php esc_html_e( '目标交期与销售渠道', 'fuhao' ); ?></li>
					</ol>
					<p class="note"><?php esc_html_e( '资料越完整，打样与报价越准确。', 'fuhao' ); ?></p>
				</aside>
			</div>
		</div>
	</div>

	<section class="section section--paper">
		<div class="container">
			<div class="section-head section-head--center reveal">
				<p class="eyebrow"><?php esc_html_e( '定制范围', 'fuhao' ); ?></p>
				<h2><?php esc_html_e( '可以调整的部分', 'fuhao' ); ?></h2>
				<p class="section-head__text"><?php esc_html_e( '具体可选方案取决于款式与工艺，业务同事会在评估后给出建议。', 'fuhao' ); ?></p>
			</div>

			<div class="feature-grid">
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '面料与配色', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按用途选择面料，并按品牌色卡调整配色。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<h3><?php esc_html_e( 'LOGO 工艺', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '丝印、刺绣、热转印等工艺，按款式与材质评估。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '结构与尺寸', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '调整分区、口袋、肩带与整体尺寸。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '五金与拉链', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按款式选择五金颜色、拉链与扣具。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '包装与标签', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '吊牌、洗水标、条码与包装方式可按渠道要求制作。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '出货配套', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '支持贴标、平台面单与一件代发等出货配套。', 'fuhao' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="section-head reveal">
				<p class="eyebrow"><?php esc_html_e( '定制流程', 'fuhao' ); ?></p>
				<h2><?php esc_html_e( '从需求到交付，按节点推进', 'fuhao' ); ?></h2>
			</div>

			<div class="process-grid process-grid--six">
				<article class="process-step reveal">
					<span class="process-step__num">01</span>
					<h3><?php esc_html_e( '需求沟通', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '确认用途、数量、参考款与目标交期。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">02</span>
					<h3><?php esc_html_e( '方案与报价', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '给出面料、工艺与包装建议，并确认报价。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">03</span>
					<h3><?php esc_html_e( '打样', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按确认方案制作样品。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">04</span>
					<h3><?php esc_html_e( '样品确认', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '确认样品与包装细节后进入量产。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">05</span>
					<h3><?php esc_html_e( '批量生产', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按确认样与订单要求安排生产。', 'fuhao' ); ?></p>
				</article>
				<article class="process-step reveal">
					<span class="process-step__num">06</span>
					<h3><?php esc_html_e( '质检交付', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '出货前检查，按约定方式交付。', 'fuhao' ); ?></p>
				</article>
			</div>

			<div class="note-box">
				<h3><?php esc_html_e( '关于起订量、打样费与交期', 'fuhao' ); ?></h3>
				<p><?php esc_html_e( '不同款式、面料与工艺的起订量、打样费和交期差异较大，我们不设统一承诺，会在评估后以书面报价确认。', 'fuhao' ); ?></p>
			</div>
		</div>
	</section>

	<span id="quote" class="anchor-target" aria-hidden="true"></span>
	<section class="cta-band">
		<div class="container cta-band__inner">
			<div>
				<h2><?php esc_html_e( '有参考款或设计图？', 'fuhao' ); ?></h2>
				<p><?php esc_html_e( '把资料发给我们，业务同事会评估可行的工艺、起订量与打样安排。', 'fuhao' ); ?></p>
			</div>
			<div class="btn-row">
				<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '提交定制需求', 'fuhao' ); ?></a>
			</div>
		</div>
	</section>
		<?php
	endif;
	?>
	<?php
endwhile;

get_footer();
