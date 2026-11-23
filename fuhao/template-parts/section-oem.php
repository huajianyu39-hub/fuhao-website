<?php
/**
 * OEM / ODM 定制服务。
 *
 * @package Fuhao
 */

$fuhao_services = array(
	__( '来图 / 来样打样', 'fuhao' ),
	__( '面料与五金选型', 'fuhao' ),
	__( 'Logo、织唛与包装定制', 'fuhao' ),
	__( '打样与批量生产衔接', 'fuhao' ),
	__( '出货前按确认标准检验', 'fuhao' ),
);
?>
<section class="section oem" id="oem">
	<div class="container oem__grid">
		<div class="oem__intro reveal">
			<p class="eyebrow"><?php echo esc_html( fuhao_get( 'sec_oem_eyebrow' ) ); ?></p>
			<h2><?php echo esc_html( fuhao_get( 'oem_title' ) ); ?></h2>
			<p><?php echo esc_html( fuhao_get( 'oem_text' ) ); ?></p>
			<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
				<?php esc_html_e( '提交定制需求', 'fuhao' ); ?>
				<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
			</a>
		</div>

		<div class="oem__panel reveal">
			<div class="oem-card">
				<h3><?php esc_html_e( 'OEM 来样定制', 'fuhao' ); ?></h3>
				<p><?php esc_html_e( '提供样品、图纸或参考款，我们完成结构拆解、打样与量产，按确认样与工艺标准生产。', 'fuhao' ); ?></p>
			</div>
			<div class="oem-card">
				<h3><?php esc_html_e( 'ODM 设计开发', 'fuhao' ); ?></h3>
				<p><?php esc_html_e( '从产品定位、结构设计到面料选型，配合开发可量产的新款。', 'fuhao' ); ?></p>
			</div>

			<ul class="oem-list">
				<?php foreach ( $fuhao_services as $fuhao_service ) : ?>
					<li>
						<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						<span><?php echo esc_html( $fuhao_service ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
