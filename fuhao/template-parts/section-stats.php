<?php
/**
 * 首页工厂数据条：独立版块，不参与海报轮播。
 *
 * @package Fuhao
 */

$fuhao_stats = array(
	array( fuhao_get( 'stat1_number' ), fuhao_get( 'stat1_label' ) ),
	array( fuhao_get( 'stat2_number' ), fuhao_get( 'stat2_label' ) ),
	array( fuhao_get( 'stat3_number' ), fuhao_get( 'stat3_label' ) ),
	array( fuhao_get( 'stat4_number' ), fuhao_get( 'stat4_label' ) ),
);
?>
<section class="stat-band" aria-label="<?php esc_attr_e( '工厂数据', 'fuhao' ); ?>">
	<div class="container stat-band__grid">
		<?php foreach ( $fuhao_stats as $fuhao_stat ) : ?>
			<div class="stat-band__item">
				<strong><?php echo esc_html( $fuhao_stat[0] ); ?></strong>
				<span><?php echo esc_html( $fuhao_stat[1] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
