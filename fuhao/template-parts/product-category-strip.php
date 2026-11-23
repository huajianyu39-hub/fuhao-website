<?php
/**
 * 产品分类导航条。
 *
 * @package Fuhao
 */

$fuhao_cats = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	)
);

if ( is_wp_error( $fuhao_cats ) || empty( $fuhao_cats ) ) {
	return;
}

$fuhao_is_archive = is_post_type_archive( 'product' );
$fuhao_current    = is_tax( 'product_cat' ) ? get_queried_object_id() : 0;
$fuhao_anchor     = ! empty( $args['anchor'] );
?>
<nav class="cat-strip" aria-label="产品分类">
	<ul class="cat-strip__list">
		<li>
			<a class="cat-strip__link<?php echo $fuhao_is_archive ? ' is-current' : ''; ?>" href="<?php echo esc_url( fuhao_product_archive_url() ); ?>"<?php echo $fuhao_is_archive ? ' aria-current="page"' : ''; ?>>
				<?php esc_html_e( '全部产品', 'fuhao' ); ?>
			</a>
		</li>
		<?php foreach ( $fuhao_cats as $fuhao_cat ) : ?>
			<?php
			$fuhao_link = $fuhao_anchor ? '#series-' . $fuhao_cat->slug : get_term_link( $fuhao_cat );

			if ( is_wp_error( $fuhao_link ) ) {
				continue;
			}
			?>
			<li>
				<a class="cat-strip__link<?php echo (int) $fuhao_cat->term_id === (int) $fuhao_current ? ' is-current' : ''; ?>" href="<?php echo esc_url( $fuhao_link ); ?>"<?php echo (int) $fuhao_cat->term_id === (int) $fuhao_current ? ' aria-current="page"' : ''; ?>>
					<?php echo esc_html( $fuhao_cat->name ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
