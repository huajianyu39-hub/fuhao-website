<?php
/**
 * 现货与现货定制。
 *
 * @package Fuhao
 */

$fuhao_tone = isset( $args['tone'] ) ? $args['tone'] : '';

$fuhao_features = array(
	array(
		'icon'  => 'box',
		'title' => __( '国内一件代发', 'fuhao' ),
		'text'  => __( '按订单打包发出，适合电商卖家、私域与礼品渠道。', 'fuhao' ),
	),
	array(
		'icon'  => 'bag',
		'title' => __( '跨境贴标', 'fuhao' ),
		'text'  => __( '按平台与订单要求贴标，具体标签规范以确认件为准。', 'fuhao' ),
	),
	array(
		'icon'  => 'layers',
		'title' => __( '平台面单', 'fuhao' ),
		'text'  => __( '支持常用电商平台面单，具体平台与格式请询价确认。', 'fuhao' ),
	),
	array(
		'icon'  => 'box',
		'title' => __( '真空压缩包装', 'fuhao' ),
		'text'  => __( '棉被、衣物等大件可做压缩包装，降低运输体积。', 'fuhao' ),
	),
);
?>
<section class="section<?php echo 'paper' === $fuhao_tone ? ' section--paper' : ''; ?>" id="stock">
	<div class="container">
		<div class="section-head section-head--split reveal">
			<div>
				<p class="eyebrow"><?php echo esc_html( fuhao_get( 'sec_stock_eyebrow' ) ); ?></p>
				<h2><?php echo esc_html( fuhao_get( 'sec_stock_title' ) ); ?></h2>
			</div>
			<p class="section-head__text"><?php esc_html_e( '部分款式提供现货与现货定制。贴标、面单、代发与真空压缩服务免费，运费另计；具体可供款式与数量请询价确认。', 'fuhao' ); ?></p>
		</div>

		<div class="stock-grid">
			<?php foreach ( $fuhao_features as $fuhao_feature ) : ?>
				<article class="stock-card reveal">
					<span class="stock-card__icon">
						<?php echo fuhao_icon( $fuhao_feature['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</span>
					<h3><?php echo esc_html( $fuhao_feature['title'] ); ?></h3>
					<p><?php echo esc_html( $fuhao_feature['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="section-foot">
			<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
				<?php esc_html_e( '咨询现货款式', 'fuhao' ); ?>
				<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
			</a>
			<a class="btn btn--outline" href="<?php echo esc_url( fuhao_page_url( 'stock' ) ); ?>">
				<?php esc_html_e( '了解现货供应', 'fuhao' ); ?>
			</a>
		</div>
	</div>
</section>
