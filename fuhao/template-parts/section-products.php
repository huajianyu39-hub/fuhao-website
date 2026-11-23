<?php
/**
 * 产品分类展示。
 *
 * @package Fuhao
 */

$fuhao_tone       = isset( $args['tone'] ) ? $args['tone'] : '';
$fuhao_categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'number'     => 8,
		'orderby'    => 'term_order',
	)
);

$fuhao_icons = array(
	'foldable-backpack'     => 'backpack',
	'cosmetic-bag'          => 'bag',
	'shoe-storage'          => 'box',
	'quilt-clothing-storage' => 'layers',
	'digital-storage'       => 'laptop',
	'student-backpack'      => 'backpack',
	'dopamine-fashion'      => 'bag',
);
?>
<section class="section<?php echo 'paper' === $fuhao_tone ? ' section--paper' : ''; ?>" id="products">
	<div class="container">
		<div class="section-head section-head--split reveal">
			<div>
				<p class="eyebrow"><?php echo esc_html( fuhao_get( 'sec_products_eyebrow' ) ); ?></p>
				<h2><?php echo esc_html( fuhao_get( 'sec_products_title' ) ); ?></h2>
			</div>
			<p class="section-head__text"><?php esc_html_e( '支持来样定制、来图开发与品牌贴牌。具体款式、起订量与交期请询价确认。', 'fuhao' ); ?></p>
		</div>

		<?php if ( ! is_wp_error( $fuhao_categories ) && $fuhao_categories ) : ?>
			<div class="category-grid">
				<?php foreach ( $fuhao_categories as $fuhao_category ) : ?>
					<?php
					$fuhao_term_link = get_term_link( $fuhao_category );
					if ( is_wp_error( $fuhao_term_link ) ) {
						continue;
					}
					$fuhao_icon = isset( $fuhao_icons[ $fuhao_category->slug ] ) ? $fuhao_icons[ $fuhao_category->slug ] : 'box';
					?>
					<a class="category-card reveal" href="<?php echo esc_url( $fuhao_term_link ); ?>">
						<span class="category-card__icon">
							<?php echo fuhao_icon( $fuhao_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</span>
						<h3><?php echo esc_html( $fuhao_category->name ); ?></h3>
						<p><?php echo esc_html( $fuhao_category->description ? $fuhao_category->description : __( '查看该分类的产品与定制选项。', 'fuhao' ) ); ?></p>
						<span class="category-card__link">
							<?php esc_html_e( '查看分类', 'fuhao' ); ?>
							<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( '产品分类正在整理中', 'fuhao' ); ?></h2>
				<p><?php esc_html_e( '欢迎直接联系我们，获取最新产品目录与报价。', 'fuhao' ); ?></p>
				<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系我们', 'fuhao' ); ?></a>
			</div>
		<?php endif; ?>

		<div class="section-foot">
			<a class="btn btn--outline" href="<?php echo esc_url( fuhao_product_archive_url() ); ?>">
				<?php esc_html_e( '查看全部产品', 'fuhao' ); ?>
				<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
			</a>
		</div>
	</div>
</section>
