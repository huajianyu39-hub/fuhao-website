<?php
/**
 * 工厂实力页面。
 *
 * @package Fuhao
 */

get_header();

while ( have_posts() ) :
	the_post();

	$fuhao_sub = has_excerpt()
		? get_the_excerpt()
		: __( '工厂拥有 26+ 年箱包制造经验与 6 个生产厂区，覆盖裁剪、车缝、组装与包装环节，成品日产能 3–5 万件。', 'fuhao' );

	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow' => __( '工厂实力', 'fuhao' ),
			'sub'     => $fuhao_sub,
			'trail'   => array( array( 'label' => __( '工厂实力', 'fuhao' ) ) ),
		)
	);
	?>
	<section class="stat-band">
		<div class="container stat-band__grid">
			<div class="stat-band__item">
				<strong>26+</strong>
				<span><?php esc_html_e( '年工厂箱包制造经验', 'fuhao' ); ?></span>
			</div>
			<div class="stat-band__item">
				<strong>6 个</strong>
				<span><?php esc_html_e( '生产厂区', 'fuhao' ); ?></span>
			</div>
			<div class="stat-band__item">
				<strong>300+</strong>
				<span><?php esc_html_e( '工厂员工', 'fuhao' ); ?></span>
			</div>
			<div class="stat-band__item">
				<strong>3–5 万</strong>
				<span><?php esc_html_e( '成品日产能', 'fuhao' ); ?></span>
			</div>
		</div>
	</section>

	<?php
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
					<p class="eyebrow"><?php esc_html_e( '公司与工厂', 'fuhao' ); ?></p>
					<h2><?php esc_html_e( '公司负责销售与服务，工厂负责生产', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '上饶市豪富箱包有限公司专注箱包产品销售、客户服务与订单对接，生产由自有箱包工厂完成。', 'fuhao' ); ?></p>
					<p><?php esc_html_e( '工厂覆盖裁剪、车缝、组装与包装环节，支持现货、现货定制与批量订单。', 'fuhao' ); ?></p>

					<ul class="check-list">
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '6 个生产厂区，覆盖不同产品线', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '裁剪、车缝、组装、包装完整产线', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( 'IQC / IPQC / OQC 三道质检', 'fuhao' ); ?></span>
						</li>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php esc_html_e( '成品日产能 3–5 万件', 'fuhao' ); ?></span>
						</li>
					</ul>

					<a class="btn btn--dark" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
						<?php esc_html_e( '预约验厂', 'fuhao' ); ?>
						<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</a>
				</div>

				<div class="split__media">
					<div class="media-holder media-holder--landscape">
						<?php fuhao_placeholder( 'factory', __( '工厂实景照片', 'fuhao' ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<section class="section section--paper">
		<div class="container">
			<div class="section-head section-head--center reveal">
				<p class="eyebrow"><?php esc_html_e( '生产与品控', 'fuhao' ); ?></p>
				<h2><?php esc_html_e( '从裁剪到包装的完整产线', 'fuhao' ); ?></h2>
				<p class="section-head__text"><?php esc_html_e( '按产品结构与订单要求组织生产，出货前完成质检。', 'fuhao' ); ?></p>
			</div>

			<div class="feature-grid">
				<article class="feature-card reveal">
					<span class="feature-card__icon"><?php echo fuhao_icon( 'layers' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '裁剪备料', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按版型与用料要求完成面料裁剪与备料。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<span class="feature-card__icon"><?php echo fuhao_icon( 'bag' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '车缝成型', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按工序完成车缝、组装与五金安装。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<span class="feature-card__icon"><?php echo fuhao_icon( 'box' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '组装包装', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '按出货要求完成折叠、贴标与包装。', 'fuhao' ); ?></p>
				</article>
				<article class="feature-card reveal">
					<span class="feature-card__icon"><?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?></span>
					<h3><?php esc_html_e( '三道质检', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( 'IQC 来料、IPQC 过程与 OQC 出货检查。', 'fuhao' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="note-box note-box--wide">
				<div>
					<p class="eyebrow"><?php esc_html_e( '验厂与资料', 'fuhao' ); ?></p>
					<h2><?php esc_html_e( '欢迎到厂参观，按流程安排接待', 'fuhao' ); ?></h2>
				</div>
				<div>
					<p><?php esc_html_e( '如需到厂参观验厂，请提前联系业务同事，方便安排接待。工厂照片、产品目录与营业执照等主体资料，可按合作需要提供。', 'fuhao' ); ?></p>
					<div class="btn-row">
						<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系业务同事', 'fuhao' ); ?></a>
						<a class="btn btn--outline" href="<?php echo esc_url( fuhao_product_archive_url() ); ?>"><?php esc_html_e( '查看产品中心', 'fuhao' ); ?></a>
					</div>
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
