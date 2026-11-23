<?php
/**
 * 关于我们页面。
 *
 * @package Fuhao
 */

get_header();

while ( have_posts() ) :
	the_post();

	$fuhao_sub = has_excerpt()
		? get_the_excerpt()
		: __( '上饶市豪富箱包有限公司，品牌「豪富 / FUHAO」，专注收纳、背包与旅行包类的生产与定制。', 'fuhao' );

	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow' => __( '关于我们', 'fuhao' ),
			'sub'     => $fuhao_sub,
			'trail'   => array( array( 'label' => __( '关于我们', 'fuhao' ) ) ),
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
			<div class="split">
				<div class="split__content">
					<p class="eyebrow"><?php esc_html_e( '品牌与公司', 'fuhao' ); ?></p>
					<h2><?php esc_html_e( '把制造经验，变成稳定的产品供应', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '上饶市豪富箱包有限公司专注箱包产品销售、客户服务与订单对接。品牌使用「豪富 / FUHAO」，产品以收纳、背包与旅行包类为主。', 'fuhao' ); ?></p>
					<p><?php esc_html_e( '生产由拥有 26+ 年经验的箱包工厂完成。工厂 6 个生产厂区与完整产线，为现货、现货定制与批量订单提供支持。', 'fuhao' ); ?></p>

					<div class="note-box note-box--inline">
						<p><?php esc_html_e( '公司是公司，工厂是工厂。成立时间与制造经验分别表述，不把工厂年限写成公司年限。', 'fuhao' ); ?></p>
					</div>

					<div class="btn-row">
						<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
							<?php esc_html_e( '联系我们', 'fuhao' ); ?>
							<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</a>
						<a class="btn btn--outline" href="<?php echo esc_url( fuhao_page_url( 'factory' ) ); ?>"><?php esc_html_e( '了解工厂实力', 'fuhao' ); ?></a>
					</div>
				</div>

				<div class="split__media">
					<div class="media-holder media-holder--portrait">
						<?php fuhao_placeholder( 'bag', __( '品牌 / 产品照片', 'fuhao' ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<section class="section section--paper">
		<div class="container">
			<div class="section-head section-head--center reveal">
				<p class="eyebrow"><?php esc_html_e( '合作方式', 'fuhao' ); ?></p>
				<h2><?php esc_html_e( '我们如何与你合作', 'fuhao' ); ?></h2>
			</div>

			<div class="cooperation-grid">
				<article class="cooperation-card reveal">
					<span class="cooperation-card__num">01</span>
					<span class="cooperation-card__icon"><?php echo fuhao_icon( 'box' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '现货供应', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '适合快速补货与试销，支持一件代发、跨境贴标与平台面单。', 'fuhao' ); ?></p>
					<a class="cooperation-card__link" href="<?php echo esc_url( fuhao_page_url( 'stock' ) ); ?>">
						<?php esc_html_e( '了解现货供应', 'fuhao' ); ?>
						<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</a>
				</article>
				<article class="cooperation-card reveal">
					<span class="cooperation-card__num">02</span>
					<span class="cooperation-card__icon"><?php echo fuhao_icon( 'layers' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '定制开发', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '支持现货定制、批量定制与 OEM / ODM 开发。', 'fuhao' ); ?></p>
					<a class="cooperation-card__link" href="<?php echo esc_url( fuhao_page_url( 'customization' ) ); ?>">
						<?php esc_html_e( '了解定制服务', 'fuhao' ); ?>
						<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</a>
				</article>
				<article class="cooperation-card reveal">
					<span class="cooperation-card__num">03</span>
					<span class="cooperation-card__icon"><?php echo fuhao_icon( 'factory' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '订单服务', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '从询价、打样到交付，由业务同事按项目跟进。', 'fuhao' ); ?></p>
					<a class="cooperation-card__link" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
						<?php esc_html_e( '联系我们', 'fuhao' ); ?>
						<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</a>
				</article>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="section-head reveal">
				<p class="eyebrow"><?php esc_html_e( '我们坚持', 'fuhao' ); ?></p>
				<h2><?php esc_html_e( '把信息说清楚，把订单做扎实', 'fuhao' ); ?></h2>
			</div>

			<div class="feature-grid feature-grid--three">
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '信息可核对', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '不虚构资质、客户、产能与交期，网站表述以已确认的信息为准。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '需求先确认', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '款式、数量、工艺与交期在评估后以书面报价确认。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<h3><?php esc_html_e( '长期合作', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '从打样到返单，按项目持续跟进。', 'fuhao' ); ?></p>
				</article>
			</div>

			<div class="company-meta">
				<div>
					<span><?php esc_html_e( '公司名称', 'fuhao' ); ?></span>
					<strong><?php esc_html_e( '上饶市豪富箱包有限公司', 'fuhao' ); ?></strong>
				</div>
				<div>
					<span><?php esc_html_e( '品牌', 'fuhao' ); ?></span>
					<strong><?php esc_html_e( '豪富 / FUHAO', 'fuhao' ); ?></strong>
				</div>
				<div>
					<span><?php esc_html_e( '成立时间', 'fuhao' ); ?></span>
					<strong><?php esc_html_e( '2026 年', 'fuhao' ); ?></strong>
				</div>
				<div>
					<span><?php esc_html_e( '地址', 'fuhao' ); ?></span>
					<strong><?php echo esc_html( fuhao_get( 'contact_address' ) ); ?></strong>
				</div>
			</div>
		</div>
	</section>
		<?php
	endif;
	?>
	<?php
endwhile;

get_footer();
