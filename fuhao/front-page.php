<?php
/**
 * 首页模板。
 *
 * @package Fuhao
 */

get_header();

$fuhao_order   = fuhao_home_order();
$fuhao_hidden  = fuhao_home_hidden();
$fuhao_light   = 0;
?>
<main id="main">
	<?php get_template_part( 'template-parts/section-hero' ); ?>

	<?php foreach ( $fuhao_order as $fuhao_token ) : ?>
		<?php
		if ( in_array( $fuhao_token, $fuhao_hidden, true ) ) {
			continue;
		}

		$fuhao_tone = '';

		if ( 'oem' !== $fuhao_token ) {
			$fuhao_tone = 0 === $fuhao_light % 2 ? 'paper' : '';
			$fuhao_light++;
		}

		if ( 0 === strpos( $fuhao_token, 'block:' ) ) {
			$fuhao_block_id = (int) substr( $fuhao_token, 6 );

			if ( $fuhao_block_id && 'publish' === get_post_status( $fuhao_block_id ) ) {
				get_template_part(
					'template-parts/section-block',
					null,
					array(
						'post_id' => $fuhao_block_id,
						'tone'    => $fuhao_tone,
					)
				);
			}

			continue;
		}

		get_template_part( 'template-parts/section-' . $fuhao_token, null, array( 'tone' => $fuhao_tone ) );
		?>
	<?php endforeach; ?>
</main>
<?php
get_footer();
