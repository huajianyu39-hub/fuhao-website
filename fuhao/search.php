<?php
/**
 * 搜索结果。
 *
 * @package Fuhao
 */

get_header();
?>
<main id="main">
	<section class="page-band">
		<div class="container">
			<p class="eyebrow"><?php bloginfo( 'name' ); ?></p>
			<h1><?php esc_html_e( '搜索结果', 'fuhao' ); ?></h1>
			<p class="page-band__sub"><?php printf( esc_html__( '关键词：%s', 'fuhao' ), esc_html( get_search_query() ) ); ?></p>
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
					<h2><?php esc_html_e( '没有找到相关内容', 'fuhao' ); ?></h2>
					<p><?php esc_html_e( '试试其他关键词，或直接联系我们获取产品资料。', 'fuhao' ); ?></p>
					<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系我们', 'fuhao' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
