<?php
/**
 * 区块图案：产品详情模板。
 *
 * @package Fuhao
 */

/**
 * 注册产品详情模板图案，供编辑产品时一键插入。
 */
function fuhao_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	$content = <<<'HTML'
<!-- wp:paragraph -->
<p>一句话说明这款产品适合的场景与核心卖点。</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">规格参数</h2>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table"><table><thead><tr><th>尺码</th><th>尺寸</th><th>容量</th><th>单重</th><th>装箱数</th><th>外箱尺寸</th><th>毛重</th></tr></thead><tbody><tr><td>大号</td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td>中号</td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:heading -->
<h2 class="wp-block-heading">可选颜色</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>颜色：黑、粉、天蓝（具体颜色以确认样为准）。</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">定制说明</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item --><li>LOGO 印刷：丝印、热转印、织标等，按图案与数量确认工艺。</li><!-- /wp:list-item --><!-- wp:list-item --><li>颜色与面料可按订单定制。</li><!-- /wp:list-item --><!-- wp:list-item --><li>起订量、样品费与交期请询价确认。</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
HTML;

	register_block_pattern(
		'fuhao/product-detail',
		array(
			'title'       => __( '产品详情模板', 'fuhao' ),
			'description' => __( '简介、规格参数表、可选颜色与定制说明，编辑产品时可直接插入。', 'fuhao' ),
			'categories'  => array( 'text' ),
			'content'     => $content,
		)
	);
}
add_action( 'init', 'fuhao_register_block_patterns' );
