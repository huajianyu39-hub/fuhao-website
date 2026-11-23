<?php
/**
 * 首页主视觉：5 个海报位轮播。
 *
 * @package Fuhao
 */

$fuhao_slides = array(
	array(
		'image'    => fuhao_get( 'hero_image' ),
		'eyebrow'  => fuhao_get( 'hero_eyebrow' ),
		'title'    => fuhao_get( 'hero_title' ),
		'text'     => fuhao_get( 'hero_text' ),
		'btn_text' => fuhao_get( 'hero_btn_text' ),
		'btn_url'  => fuhao_get( 'hero_btn_url' ),
		'btn2_text' => fuhao_get( 'hero_btn2_text' ),
		'btn2_url'  => fuhao_get( 'hero_btn2_url' ),
	),
);

for ( $fuhao_index = 2; $fuhao_index <= 5; $fuhao_index++ ) {
	$fuhao_slides[] = array(
		'image'     => fuhao_get( 'slide' . $fuhao_index . '_image' ),
		'eyebrow'   => fuhao_get( 'slide' . $fuhao_index . '_eyebrow' ),
		'title'     => fuhao_get( 'slide' . $fuhao_index . '_title' ),
		'text'      => fuhao_get( 'slide' . $fuhao_index . '_text' ),
		'btn_text'  => fuhao_get( 'slide' . $fuhao_index . '_btn_text' ),
		'btn_url'   => fuhao_get( 'slide' . $fuhao_index . '_btn_url' ),
		'btn2_text' => fuhao_get( 'slide' . $fuhao_index . '_btn2_text' ),
		'btn2_url'  => fuhao_get( 'slide' . $fuhao_index . '_btn2_url' ),
	);
}

$fuhao_visible = array();
foreach ( $fuhao_slides as $fuhao_slide ) {
	if ( $fuhao_slide['image'] || $fuhao_slide['title'] || $fuhao_slide['text'] ) {
		$fuhao_visible[] = $fuhao_slide;
	}
}

$fuhao_count   = count( $fuhao_visible );
$fuhao_seconds = absint( fuhao_get( 'hero_interval' ) );
$fuhao_seconds = $fuhao_seconds ? max( 2, min( 20, $fuhao_seconds ) ) : 3;
$fuhao_stats   = array(
	array( fuhao_get( 'stat1_number' ), fuhao_get( 'stat1_label' ) ),
	array( fuhao_get( 'stat2_number' ), fuhao_get( 'stat2_label' ) ),
	array( fuhao_get( 'stat3_number' ), fuhao_get( 'stat3_label' ) ),
	array( fuhao_get( 'stat4_number' ), fuhao_get( 'stat4_label' ) ),
);
$fuhao_hero_class = 'hero' . ( $fuhao_count > 1 ? ' hero--carousel' : '' );
?>
<section class="<?php echo esc_attr( $fuhao_hero_class ); ?>" id="home"<?php echo $fuhao_count > 1 ? ' data-hero-carousel data-interval="' . esc_attr( $fuhao_seconds ) . '"' : ''; ?>>
	<div class="hero__slides">
		<?php foreach ( $fuhao_visible as $fuhao_key => $fuhao_slide ) : ?>
			<?php
			$fuhao_slide_attrs = 0 === $fuhao_key ? '' : ' aria-hidden="true"';
			?>
			<div class="hero-slide<?php echo 0 === $fuhao_key ? ' is-active' : ''; ?><?php echo $fuhao_slide['image'] ? '' : ' hero-slide--plain'; ?>" data-hero-slide role="group" aria-roledescription="<?php esc_attr_e( '幻灯片', 'fuhao' ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '第 %1$d 张，共 %2$d 张', 'fuhao' ), $fuhao_key + 1, $fuhao_count ) ); ?>"<?php echo $fuhao_slide_attrs; // phpcs:ignore WordPress.Security.EscapeOutput -- 固定字符串。 ?>>
				<?php if ( $fuhao_slide['image'] ) : ?>
					<img class="hero-slide__img" src="<?php echo esc_url( $fuhao_slide['image'] ); ?>" alt="" <?php echo 0 === $fuhao_key ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
				<?php endif; ?>
				<div class="hero-slide__shade" aria-hidden="true"></div>
				<div class="container hero__inner">
					<?php if ( $fuhao_slide['eyebrow'] ) : ?>
						<p class="eyebrow eyebrow--light"><?php echo esc_html( $fuhao_slide['eyebrow'] ); ?></p>
					<?php endif; ?>
					<?php if ( $fuhao_slide['title'] ) : ?>
						<?php if ( 0 === $fuhao_key ) : ?>
							<h1 class="hero__title"><?php echo esc_html( $fuhao_slide['title'] ); ?></h1>
						<?php else : ?>
							<p class="hero__title"><?php echo esc_html( $fuhao_slide['title'] ); ?></p>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( $fuhao_slide['text'] ) : ?>
						<p class="hero__text"><?php echo esc_html( $fuhao_slide['text'] ); ?></p>
					<?php endif; ?>

					<?php if ( $fuhao_slide['btn_text'] || $fuhao_slide['btn2_text'] ) : ?>
						<div class="hero__actions">
							<?php if ( $fuhao_slide['btn_text'] ) : ?>
								<a class="btn btn--accent" href="<?php echo esc_url( fuhao_resolve_link( $fuhao_slide['btn_url'] ) ); ?>">
									<?php echo esc_html( $fuhao_slide['btn_text'] ); ?>
									<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
								</a>
							<?php endif; ?>
							<?php if ( $fuhao_slide['btn2_text'] ) : ?>
								<a class="btn btn--ghost" href="<?php echo esc_url( fuhao_resolve_link( $fuhao_slide['btn2_url'] ) ); ?>">
									<?php echo esc_html( $fuhao_slide['btn2_text'] ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( $fuhao_count > 1 ) : ?>
		<div class="container hero__nav">
			<div class="hero__dots" aria-label="<?php esc_attr_e( '海报切换', 'fuhao' ); ?>">
				<?php for ( $fuhao_dot = 0; $fuhao_dot < $fuhao_count; $fuhao_dot++ ) : ?>
					<button type="button" class="hero__dot<?php echo 0 === $fuhao_dot ? ' is-active' : ''; ?>" data-hero-dot="<?php echo esc_attr( $fuhao_dot ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '第 %d 张海报', 'fuhao' ), $fuhao_dot + 1 ) ); ?>" aria-current="<?php echo 0 === $fuhao_dot ? 'true' : 'false'; ?>"></button>
				<?php endfor; ?>
			</div>
			<div class="hero__arrows">
				<button type="button" class="hero__arrow hero__arrow--prev" data-hero-prev aria-label="<?php esc_attr_e( '上一张海报', 'fuhao' ); ?>">
					<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
				</button>
				<button type="button" class="hero__arrow hero__arrow--next" data-hero-next aria-label="<?php esc_attr_e( '下一张海报', 'fuhao' ); ?>">
					<?php echo fuhao_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。 ?>
				</button>
			</div>
		</div>
	<?php endif; ?>

	<div class="container">
		<dl class="hero__stats">
			<?php foreach ( $fuhao_stats as $fuhao_stat ) : ?>
				<div class="stat">
					<dt class="stat__num"><?php echo esc_html( $fuhao_stat[0] ); ?></dt>
					<dd class="stat__label"><?php echo esc_html( $fuhao_stat[1] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
