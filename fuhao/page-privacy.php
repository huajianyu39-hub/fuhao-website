<?php
/**
 * 隐私政策页面。
 *
 * @package Fuhao
 */

get_header();

while ( have_posts() ) :
	the_post();

	$fuhao_sub = has_excerpt()
		? get_the_excerpt()
		: __( '本政策说明本网站在你提交询盘与浏览页面时，如何处理相关信息。', 'fuhao' );

	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow' => __( '隐私政策', 'fuhao' ),
			'sub'     => $fuhao_sub,
			'trail'   => array( array( 'label' => __( '隐私政策', 'fuhao' ) ) ),
		)
	);
	?>
	<div class="page-main">
		<div class="container">
			<div class="legal">
				<?php
				if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) {
					the_content();
				} else {
					echo '<p>' . esc_html__( '本页内容正在完善，如有疑问请通过“联系我们”页面与我们沟通。', 'fuhao' ) . '</p>';
				}
				?>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
