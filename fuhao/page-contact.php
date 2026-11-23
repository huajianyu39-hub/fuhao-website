<?php
/**
 * 联系我们页面。
 *
 * @package Fuhao
 */

get_header();

while ( have_posts() ) :
	the_post();

	$fuhao_sub = has_excerpt()
		? get_the_excerpt()
		: __( '产品询价、定制开发、样品与验厂，都可以直接联系业务同事，我们会尽快回复。', 'fuhao' );

	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow' => __( '联系我们', 'fuhao' ),
			'sub'     => $fuhao_sub,
			'trail'   => array( array( 'label' => __( '联系我们', 'fuhao' ) ) ),
		)
	);

	get_template_part( 'template-parts/section-contact-qr' );

	if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
		?>
		<div class="page-main">
			<div class="container">
				<div class="entry-content entry-content--wide">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
		<?php
	else :
		?>
	<div class="page-main page-main--tight">
		<div class="container">
			<div class="info-grid">
				<article class="info-card">
					<h3><?php esc_html_e( '产品询价', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '请提供款式或参考图、预计数量与收货城市，方便我们确认可供情况与运费。', 'fuhao' ); ?></p>
				</article>
				<article class="info-card">
					<h3><?php esc_html_e( '打样与定制', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '请提供设计图、样品或参考款，以及面料、工艺与目标交期要求。', 'fuhao' ); ?></p>
				</article>
				<article class="info-card">
					<h3><?php esc_html_e( '到厂参观', 'fuhao' ); ?></h3>
					<p><?php esc_html_e( '如需到厂参观验厂，请提前预约，方便安排接待。', 'fuhao' ); ?></p>
				</article>
			</div>
		</div>
	</div>
		<?php
	endif;
	?>

	<?php get_template_part( 'template-parts/section-contact' ); ?>
	<?php
endwhile;

get_footer();
