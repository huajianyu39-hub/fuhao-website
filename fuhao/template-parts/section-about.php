<?php
/**
 * 工厂实力。
 *
 * @package Fuhao
 */

$fuhao_tone        = isset( $args['tone'] ) ? $args['tone'] : '';
$fuhao_about_image  = fuhao_get( 'about_image' );
$fuhao_about_points = array_filter(
	array(
		fuhao_get( 'about_point1' ),
		fuhao_get( 'about_point2' ),
		fuhao_get( 'about_point3' ),
		fuhao_get( 'about_point4' ),
	)
);
?>
<section class="section<?php echo 'paper' === $fuhao_tone ? ' section--paper' : ''; ?>" id="about">
	<div class="container about__grid">
		<div class="about__media reveal">
			<?php if ( $fuhao_about_image ) : ?>
				<img src="<?php echo esc_url( $fuhao_about_image ); ?>" alt="工厂实景" loading="lazy">
			<?php else : ?>
				<div class="media-holder media-holder--portrait">
					<?php fuhao_placeholder( 'factory', __( '工厂照片', 'fuhao' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="about__badge">
				<strong><?php echo esc_html( fuhao_get( 'stat1_number' ) ); ?></strong>
				<span>工厂制造经验</span>
			</div>
		</div>

		<div class="about__content reveal">
			<p class="eyebrow"><?php echo esc_html( fuhao_get( 'about_eyebrow' ) ); ?></p>
			<h2><?php echo esc_html( fuhao_get( 'about_title' ) ); ?></h2>
			<p><?php echo esc_html( fuhao_get( 'about_text' ) ); ?></p>

			<?php if ( $fuhao_about_points ) : ?>
				<ul class="check-list">
					<?php foreach ( $fuhao_about_points as $fuhao_point ) : ?>
						<li>
							<?php echo fuhao_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
							<span><?php echo esc_html( $fuhao_point ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<a class="btn btn--dark" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>">
				<?php esc_html_e( '预约验厂 / 获取产品目录', 'fuhao' ); ?>
				<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
			</a>
		</div>
	</div>
</section>
