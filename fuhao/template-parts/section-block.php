<?php
/**
 * 自定义宣传版块。
 *
 * @package Fuhao
 */

$fuhao_block_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$fuhao_block_tone = isset( $args['tone'] ) ? $args['tone'] : '';
$fuhao_block_eyebrow  = (string) get_post_meta( $fuhao_block_id, '_fuhao_block_eyebrow', true );
$fuhao_block_title    = (string) get_post_meta( $fuhao_block_id, '_fuhao_block_title', true );
$fuhao_block_text     = (string) get_post_meta( $fuhao_block_id, '_fuhao_block_text', true );
$fuhao_block_btn_text = (string) get_post_meta( $fuhao_block_id, '_fuhao_block_btn_text', true );
$fuhao_block_btn_url  = (string) get_post_meta( $fuhao_block_id, '_fuhao_block_btn_url', true );
$fuhao_block_image    = get_the_post_thumbnail_url( $fuhao_block_id, 'large' );

if ( ! $fuhao_block_title && ! $fuhao_block_text && ! $fuhao_block_image ) {
	return;
}
?>
<section class="section block-section<?php echo 'paper' === $fuhao_block_tone ? ' section--paper' : ''; ?><?php echo $fuhao_block_image ? ' block-section--media' : ''; ?>" id="block-<?php echo esc_attr( $fuhao_block_id ); ?>">
	<div class="container block-section__grid">
		<div class="block-section__content reveal">
			<?php if ( $fuhao_block_eyebrow ) : ?>
				<p class="eyebrow"><?php echo esc_html( $fuhao_block_eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( $fuhao_block_title ) : ?>
				<h2><?php echo esc_html( $fuhao_block_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $fuhao_block_text ) : ?>
				<?php echo wp_kses_post( wpautop( esc_html( $fuhao_block_text ) ) ); ?>
			<?php endif; ?>
			<?php if ( $fuhao_block_btn_text && $fuhao_block_btn_url ) : ?>
				<p class="block-section__action">
					<a class="btn btn--accent" href="<?php echo esc_url( fuhao_resolve_link( $fuhao_block_btn_url ) ); ?>">
						<?php echo esc_html( $fuhao_block_btn_text ); ?>
						<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $fuhao_block_image ) : ?>
			<div class="block-section__media reveal">
				<img src="<?php echo esc_url( $fuhao_block_image ); ?>" alt="<?php echo esc_attr( $fuhao_block_title ? $fuhao_block_title : get_the_title( $fuhao_block_id ) ); ?>" loading="lazy">
			</div>
		<?php endif; ?>
	</div>
</section>
