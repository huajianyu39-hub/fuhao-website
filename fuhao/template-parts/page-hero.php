<?php
/**
 * 内页顶部标题区。
 *
 * @package Fuhao
 */

$fuhao_hero = wp_parse_args(
	isset( $args ) && is_array( $args ) ? $args : array(),
	array(
		'eyebrow' => '',
		'title'   => get_the_title(),
		'sub'     => '',
		'trail'   => array(),
	)
);
?>
<section class="page-band">
	<div class="container">
		<?php if ( $fuhao_hero['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $fuhao_hero['eyebrow'] ); ?></p>
		<?php endif; ?>

		<h1><?php echo esc_html( $fuhao_hero['title'] ); ?></h1>

		<?php if ( $fuhao_hero['sub'] ) : ?>
			<p class="page-band__sub"><?php echo esc_html( $fuhao_hero['sub'] ); ?></p>
		<?php endif; ?>

		<?php
		if ( $fuhao_hero['trail'] ) {
			fuhao_breadcrumb( $fuhao_hero['trail'] );
		}
		?>
	</div>
</section>
