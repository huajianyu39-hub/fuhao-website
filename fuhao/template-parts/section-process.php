<?php
/**
 * 生产制造流程。
 *
 * @package Fuhao
 */

$fuhao_tone = isset( $args['tone'] ) ? $args['tone'] : '';

$fuhao_steps = array(
	array( __( '需求沟通', 'fuhao' ), __( '明确产品结构、材质、数量与目标市场，输出初步方案与报价。', 'fuhao' ) ),
	array( __( '设计与打样', 'fuhao' ), __( '结构优化、面料与五金选型，确认工艺后安排打样。', 'fuhao' ) ),
	array( __( '样品确认', 'fuhao' ), __( '寄样确认细节，签署产前样与检验标准，锁定大货标准。', 'fuhao' ) ),
	array( __( '原料与生产', 'fuhao' ), __( '备料、裁剪、车缝、组装，按生产节点同步进度。', 'fuhao' ) ),
	array( __( '品质检验', 'fuhao' ), __( 'IQC / IPQC / OQC 三道检验，按确认标准出货。', 'fuhao' ) ),
	array( __( '包装出货', 'fuhao' ), __( '按客户要求包装、贴标，配合物流安排出货。', 'fuhao' ) ),
);
?>
<section class="section<?php echo 'paper' === $fuhao_tone ? ' section--paper' : ''; ?>" id="process">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<p class="eyebrow"><?php echo esc_html( fuhao_get( 'sec_process_eyebrow' ) ); ?></p>
			<h2><?php echo esc_html( fuhao_get( 'sec_process_title' ) ); ?></h2>
			<p class="section-head__text"><?php esc_html_e( '每个环节都有专人跟进，关键节点同步进度。', 'fuhao' ); ?></p>
		</div>

		<ol class="process-grid">
			<?php foreach ( $fuhao_steps as $fuhao_index => $fuhao_step ) : ?>
				<li class="process-step reveal">
					<span class="process-step__num"><?php echo esc_html( sprintf( '%02d', $fuhao_index + 1 ) ); ?></span>
					<h3><?php echo esc_html( $fuhao_step[0] ); ?></h3>
					<p><?php echo esc_html( $fuhao_step[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
