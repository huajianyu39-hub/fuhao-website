<?php
/**
 * 普通页面模板。
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
				<p class="eyebrow"><?php bloginfo( 'name' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php fuhao_breadcrumb( array( array( 'label' => get_the_title() ) ) ); ?>
			</div>
		</section>

		<div class="page-main">
			<div class="container">
				<article <?php post_class(); ?>>
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
