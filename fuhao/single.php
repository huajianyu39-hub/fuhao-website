<?php
/**
 * 文章详情。
 *
 * @package Fuhao
 */

get_header();
?>
<main id="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="page-band">
			<div class="container">
				<p class="eyebrow"><?php esc_html_e( '新闻与动态', 'fuhao' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php fuhao_breadcrumb( array( array( 'label' => get_the_title() ) ) ); ?>
			</div>
		</section>

		<div class="page-main">
			<div class="container">
				<article <?php post_class(); ?>>
					<p class="post-item__meta"><?php echo esc_html( get_the_date() ); ?></p>
					<div class="entry-content">
						<?php
						the_content();
						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( '页码：', 'fuhao' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
				</article>
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
