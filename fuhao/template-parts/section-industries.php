<?php
/**
 * 服务行业与合作方式。
 *
 * @package Fuhao
 */

$fuhao_tone = isset( $args['tone'] ) ? $args['tone'] : '';

$fuhao_industries = array(
	__( '食品饮料', 'fuhao' ),
	__( '日化', 'fuhao' ),
	__( '鞋服零售', 'fuhao' ),
	__( '跨境电商', 'fuhao' ),
	__( '礼品定制', 'fuhao' ),
);

$fuhao_cooperations = array(
	array(
		'title' => __( 'OEM / ODM', 'fuhao' ),
		'text'  => __( '来样、来图或联合开发，按品牌标准完成打样与量产。', 'fuhao' ),
	),
	array(
		'title' => __( '现货代发', 'fuhao' ),
		'text'  => __( '现货款按订单打包发出，支持国内一件代发。', 'fuhao' ),
	),
	array(
		'title' => __( '跨境贴标', 'fuhao' ),
		'text'  => __( '按跨境电商平台要求贴标、包装与出货。', 'fuhao' ),
	),
	array(
		'title' => __( '外贸订单', 'fuhao' ),
		'text'  => __( '支持外贸订单的生产与交付，具体条款按项目确认。', 'fuhao' ),
	),
);
?>
<section class="section<?php echo 'paper' === $fuhao_tone ? ' section--paper' : ''; ?>" id="industries">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php echo esc_html( fuhao_get( 'sec_industries_eyebrow' ) ); ?></p>
			<h2><?php echo esc_html( fuhao_get( 'sec_industries_title' ) ); ?></h2>
			<p class="section-head__text"><?php esc_html_e( '产品用于食品饮料、日化、鞋服零售、跨境电商、礼品定制等行业的品牌客户。', 'fuhao' ); ?></p>
		</div>

		<ul class="industry-list reveal">
			<?php foreach ( $fuhao_industries as $fuhao_industry ) : ?>
				<li class="industry-chip"><?php echo esc_html( $fuhao_industry ); ?></li>
			<?php endforeach; ?>
		</ul>

		<div class="mode-grid">
			<?php foreach ( $fuhao_cooperations as $fuhao_cooperation ) : ?>
				<article class="mode-card reveal">
					<h3><?php echo esc_html( $fuhao_cooperation['title'] ); ?></h3>
					<p><?php echo esc_html( $fuhao_cooperation['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
