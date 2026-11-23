<?php
/**
 * 产品卡片（支持真实产品与占位数据）。
 *
 * @package Fuhao
 */

$fuhao_fallback = ! empty( $args['fallback'] );
$fuhao_variant  = ! empty( $args['variant'] ) ? sanitize_html_class( $args['variant'] ) : '';
$fuhao_card_id  = $fuhao_fallback ? 0 : fuhao_get_product_card_image_id();

if ( $fuhao_fallback ) {
	$fuhao_title  = isset( $args['title'] ) ? $args['title'] : '';
	$fuhao_desc   = isset( $args['desc'] ) ? $args['desc'] : '';
	$fuhao_tag    = isset( $args['tag'] ) ? $args['tag'] : __( '产品', 'fuhao' );
	$fuhao_icon   = isset( $args['icon'] ) ? $args['icon'] : 'box';
	$fuhao_link   = isset( $args['link'] ) ? fuhao_resolve_link( $args['link'] ) : fuhao_page_url( 'contact' );
	$fuhao_supply = array(
		'key'   => '',
		'label' => isset( $args['supply'] ) ? $args['supply'] : '',
	);
} else {
	$fuhao_title  = get_the_title();
	$fuhao_desc   = get_the_excerpt();
	$fuhao_icon   = 'box';
	$fuhao_link   = get_permalink();
	$fuhao_terms  = get_the_terms( get_the_ID(), 'product_cat' );
	$fuhao_supply = fuhao_get_supply_type();

	if ( $fuhao_terms && ! is_wp_error( $fuhao_terms ) ) {
		$fuhao_tag = $fuhao_terms[0]->name;
	} else {
		$fuhao_tag = __( '产品', 'fuhao' );
	}
}
?>
<article class="product-card reveal<?php echo $fuhao_variant ? ' product-card--' . esc_attr( $fuhao_variant ) : ''; ?>">
	<div class="product-card__media">
		<?php if ( $fuhao_card_id ) : ?>
			<?php echo wp_get_attachment_image( $fuhao_card_id, 'medium_large', false, array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<?php fuhao_placeholder( $fuhao_icon, __( '产品图片', 'fuhao' ) ); ?>
		<?php endif; ?>
	</div>

	<div class="product-card__body">
		<div class="product-card__tags">
			<span class="product-card__tag"><?php echo esc_html( $fuhao_tag ); ?></span>
			<?php if ( ! empty( $fuhao_supply['label'] ) ) : ?>
				<span class="product-card__supply"><?php echo esc_html( $fuhao_supply['label'] ); ?></span>
			<?php endif; ?>
		</div>
		<h3><a href="<?php echo esc_url( $fuhao_link ); ?>"><?php echo esc_html( $fuhao_title ); ?></a></h3>
		<p><?php echo esc_html( wp_strip_all_tags( $fuhao_desc ) ); ?></p>
		<a class="product-card__link" href="<?php echo esc_url( $fuhao_link ); ?>">
			<?php echo $fuhao_fallback ? esc_html__( '咨询此系列', 'fuhao' ) : esc_html__( '查看产品', 'fuhao' ); ?>
			<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
		</a>
	</div>
</article>
