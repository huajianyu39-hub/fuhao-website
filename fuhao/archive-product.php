<?php
/**
 * 产品归档页 /products/：按产品分类分组展示。
 *
 * @package Fuhao
 */

get_header();
?>
<main id="main">
	<section class="page-band">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( '产品中心', 'fuhao' ); ?></p>
			<h1><?php esc_html_e( '产品与定制方案', 'fuhao' ); ?></h1>
			<p class="page-band__sub"><?php esc_html_e( '收纳系列与旅行背包系列，支持来样、来图与品牌贴牌，按项目评估起订量与交期。', 'fuhao' ); ?></p>
			<?php fuhao_breadcrumb( array( array( 'label' => __( '产品中心', 'fuhao' ) ) ) ); ?>
		</div>
	</section>

	<div class="page-main">
		<div class="container">
			<?php get_template_part( 'template-parts/product-category-strip', null, array( 'anchor' => true ) ); ?>

			<?php
			$fuhao_cats = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'orderby'    => 'term_id',
					'order'      => 'ASC',
				)
			);

			if ( ! is_wp_error( $fuhao_cats ) && ! empty( $fuhao_cats ) ) :
				foreach ( $fuhao_cats as $fuhao_cat ) :
					$fuhao_query = new WP_Query(
						array(
							'post_type'           => 'product',
							'post_status'         => 'publish',
							'posts_per_page'      => -1,
							'orderby'             => 'menu_order date',
							'order'               => 'ASC',
							'ignore_sticky_posts' => true,
							'tax_query'           => array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $fuhao_cat->term_id,
								),
							),
						)
					);

					if ( ! $fuhao_query->have_posts() ) {
						wp_reset_postdata();
						continue;
					}

					$fuhao_term_link = get_term_link( $fuhao_cat );
					?>
					<section class="product-group" id="series-<?php echo esc_attr( $fuhao_cat->slug ); ?>">
						<header class="product-group__head">
							<h2>
								<?php echo esc_html( $fuhao_cat->name ); ?>
								<span><?php echo esc_html( sprintf( __( '%d 款', 'fuhao' ), $fuhao_query->post_count ) ); ?></span>
							</h2>
							<?php if ( ! is_wp_error( $fuhao_term_link ) ) : ?>
								<a class="product-group__link" href="<?php echo esc_url( $fuhao_term_link ); ?>">
									<?php esc_html_e( '查看该系列', 'fuhao' ); ?>
									<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
								</a>
							<?php endif; ?>
						</header>
						<div class="product-grid product-grid--archive">
							<?php
							while ( $fuhao_query->have_posts() ) :
								$fuhao_query->the_post();
								get_template_part( 'template-parts/product-card', null, array( 'variant' => 'archive' ) );
							endwhile;
							?>
						</div>
					</section>
					<?php
					wp_reset_postdata();
				endforeach;
			else :
				?>
				<div class="empty-state">
					<h2><?php esc_html_e( '产品正在整理中', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '欢迎直接联系我们，获取最新产品目录与报价。', 'fuhao' ); ?></p>
					<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系我们', 'fuhao' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
