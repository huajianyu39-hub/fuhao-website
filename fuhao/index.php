<?php
/**
 * 文章列表 / 默认模板。
 *
 * @package Fuhao
 */

get_header();

$fuhao_heading = is_home() ? __( '新闻与动态', 'fuhao' ) : wp_strip_all_tags( get_the_archive_title() );
?>
<main id="main">
	<section class="page-band">
		<div class="container">
			<p class="eyebrow"><?php bloginfo( 'name' ); ?></p>
			<h1><?php echo esc_html( $fuhao_heading ); ?></h1>
		</div>
	</section>

	<div class="page-main">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="post-list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article <?php post_class( 'post-item' ); ?>>
							<p class="post-item__meta"><?php echo esc_html( get_the_date() ); ?></p>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
						</article>
					<?php endwhile; ?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 1,
						'prev_text'          => __( '上一页', 'fuhao' ),
						'next_text'          => __( '下一页', 'fuhao' ),
						'screen_reader_text' => __( '分页导航', 'fuhao' ),
					)
				);
				?>
			<?php else : ?>
				<div class="empty-state">
					<h2><?php esc_html_e( '暂无内容', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '这里还没有发布内容，欢迎先浏览我们的产品。', 'fuhao' ); ?></p>
					<a class="btn btn--accent" href="<?php echo esc_url( fuhao_product_archive_url() ); ?>"><?php esc_html_e( '浏览产品', 'fuhao' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
