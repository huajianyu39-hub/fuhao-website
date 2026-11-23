<?php
/**
 * 产品分类归档页。
 *
 * @package Fuhao
 */

get_header();

$fuhao_term = get_queried_object();
?>
<main id="main">
	<section class="page-band">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( '产品中心', 'fuhao' ); ?></p>
			<h1><?php echo esc_html( $fuhao_term->name ); ?></h1>
			<?php if ( ! empty( $fuhao_term->description ) ) : ?>
				<p class="page-band__sub"><?php echo esc_html( $fuhao_term->description ); ?></p>
			<?php endif; ?>
			<?php
			fuhao_breadcrumb(
				array(
					array(
						'label' => __( '产品中心', 'fuhao' ),
						'url'   => fuhao_product_archive_url(),
					),
					array( 'label' => $fuhao_term->name ),
				)
			);
			?>
		</div>
	</section>

	<div class="page-main">
		<div class="container">
			<?php get_template_part( 'template-parts/product-category-strip' ); ?>
			<?php if ( have_posts() ) : ?>
				<div class="product-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/product-card' );
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 1,
						'prev_text'          => __( '上一页', 'fuhao' ),
						'next_text'          => __( '下一页', 'fuhao' ),
						'screen_reader_text' => __( '产品分页', 'fuhao' ),
					)
				);
				?>
			<?php else : ?>
				<div class="empty-state">
					<h2><?php esc_html_e( '该分类暂无产品', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '欢迎直接联系我们，获取最新产品目录与报价。', 'fuhao' ); ?></p>
					<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系我们', 'fuhao' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
