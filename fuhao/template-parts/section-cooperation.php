<?php
/**
 * 三类合作方式。
 *
 * @package Fuhao
 */

$fuhao_tone = isset( $args['tone'] ) ? $args['tone'] : '';

$fuhao_modes = array(
	array(
		'num'   => '01',
		'icon'  => 'box',
		'title' => __( '现货供应', 'fuhao' ),
		'text'  => __( '提供现货款，支持国内一件代发、跨境贴标与平台面单；具体可供款式与运费请询价确认。', 'fuhao' ),
		'link'  => fuhao_page_url( 'stock' ),
	),
	array(
		'num'   => '02',
		'icon'  => 'layers',
		'title' => __( '现货定制', 'fuhao' ),
		'text'  => __( '在现货款基础上做 LOGO、配色与包装定制，适合试单与后续返单。', 'fuhao' ),
		'link'  => fuhao_page_url( 'customization' ),
	),
	array(
		'num'   => '03',
		'icon'  => 'factory',
		'title' => __( '批量定制', 'fuhao' ),
		'text'  => __( '来样、来图开发，按项目评估打样、生产与交付，支持外贸订单。', 'fuhao' ),
		'link'  => fuhao_page_url( 'customization' ),
	),
);
?>
<section class="section<?php echo 'paper' === $fuhao_tone ? ' section--paper' : ''; ?>" id="cooperation">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php echo esc_html( fuhao_get( 'sec_cooperation_eyebrow' ) ); ?></p>
			<h2><?php echo esc_html( fuhao_get( 'sec_cooperation_title' ) ); ?></h2>
			<p class="section-head__text"><?php esc_html_e( '根据款式、数量与定制要求选择合作方式；库存数量与交期以询价确认为准。', 'fuhao' ); ?></p>
		</div>

		<div class="cooperation-grid">
			<?php foreach ( $fuhao_modes as $fuhao_mode ) : ?>
				<article class="cooperation-card reveal">
					<span class="cooperation-card__num"><?php echo esc_html( $fuhao_mode['num'] ); ?></span>
					<span class="cooperation-card__icon">
						<?php echo fuhao_icon( $fuhao_mode['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</span>
					<h3><?php echo esc_html( $fuhao_mode['title'] ); ?></h3>
					<p><?php echo esc_html( $fuhao_mode['text'] ); ?></p>
					<a class="cooperation-card__link" href="<?php echo esc_url( $fuhao_mode['link'] ); ?>">
						<?php esc_html_e( '了解详情', 'fuhao' ); ?>
						<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
