<?php
/**
 * 首页主视觉：图片、GIF 或视频轮播。
 *
 * 每张海报以完整画面呈现，文字与版式直接做在图片或视频里；
 * 可选填链接，让整张海报可以点击。
 *
 * @package Fuhao
 */

$fuhao_slides = array();
$fuhao_fields = array(
	1 => array( 'hero_image', 'hero_mobile', 'hero_link', 'hero_alt', 'hero_video', 'hero_video_mobile' ),
	2 => array( 'slide2_image', 'slide2_mobile', 'slide2_link', 'slide2_alt', 'slide2_video', 'slide2_video_mobile' ),
	3 => array( 'slide3_image', 'slide3_mobile', 'slide3_link', 'slide3_alt', 'slide3_video', 'slide3_video_mobile' ),
	4 => array( 'slide4_image', 'slide4_mobile', 'slide4_link', 'slide4_alt', 'slide4_video', 'slide4_video_mobile' ),
	5 => array( 'slide5_image', 'slide5_mobile', 'slide5_link', 'slide5_alt', 'slide5_video', 'slide5_video_mobile' ),
);

foreach ( $fuhao_fields as $fuhao_index => $fuhao_keys ) {
	$fuhao_image = fuhao_get( $fuhao_keys[0] );
	$fuhao_video = fuhao_video_url( fuhao_get( $fuhao_keys[4] ) );

	if ( '' === $fuhao_image && '' === $fuhao_video ) {
		continue;
	}

	$fuhao_slides[] = array(
		'image'        => $fuhao_image,
		'mobile'       => fuhao_get( $fuhao_keys[1] ),
		'link'         => fuhao_get( $fuhao_keys[2] ),
		'alt'          => fuhao_get( $fuhao_keys[3] ),
		'video'        => $fuhao_video,
		'video_mobile' => fuhao_video_url( fuhao_get( $fuhao_keys[5] ) ),
	);
}

if ( empty( $fuhao_slides ) ) {
	return;
}

$fuhao_count   = count( $fuhao_slides );
$fuhao_seconds = absint( fuhao_get( 'hero_interval' ) );
$fuhao_seconds = $fuhao_seconds ? max( 2, min( 20, $fuhao_seconds ) ) : 3;
$fuhao_class   = 'hero' . ( $fuhao_count > 1 ? ' hero--carousel' : '' );
?>
<section class="<?php echo esc_attr( $fuhao_class ); ?>" id="home" data-hero-carousel data-interval="<?php echo esc_attr( $fuhao_seconds ); ?>">
	<div class="hero__slides">
		<?php foreach ( $fuhao_slides as $fuhao_key => $fuhao_slide ) : ?>
			<?php
			$fuhao_is_active = 0 === $fuhao_key;
			$fuhao_link     = '' !== trim( (string) $fuhao_slide['link'] ) ? fuhao_resolve_link( $fuhao_slide['link'] ) : '';
			$fuhao_label    = $fuhao_slide['alt'] ? $fuhao_slide['alt'] : sprintf( __( '第 %d 张海报', 'fuhao' ), $fuhao_key + 1 );
			$fuhao_media    = '';

			if ( $fuhao_slide['video'] ) {
				$fuhao_poster = $fuhao_slide['image'] ? $fuhao_slide['image'] : $fuhao_slide['mobile'];
				$fuhao_media  = '<video class="hero-slide__video" data-hero-video data-desktop-src="' . esc_url( $fuhao_slide['video'] ) . '"';

				if ( $fuhao_slide['video_mobile'] ) {
					$fuhao_media .= ' data-mobile-src="' . esc_url( $fuhao_slide['video_mobile'] ) . '"';
				}

				$fuhao_media .= ' src="' . esc_url( $fuhao_slide['video'] ) . '"';

				if ( $fuhao_poster ) {
					$fuhao_media .= ' poster="' . esc_url( $fuhao_poster ) . '"';
				}

				$fuhao_media .= ' muted loop playsinline preload="metadata" aria-hidden="true"></video>';
			} else {
				$fuhao_media = '<picture>';

				if ( $fuhao_slide['mobile'] ) {
					$fuhao_media .= '<source media="(max-width: 920px)" srcset="' . esc_url( $fuhao_slide['mobile'] ) . '">';
				}

				$fuhao_media .= '<img class="hero-slide__img" src="' . esc_url( $fuhao_slide['image'] ) . '" alt="' . esc_attr( $fuhao_slide['alt'] ) . '" ' . ( $fuhao_is_active ? 'fetchpriority="high"' : 'loading="lazy"' ) . '>';
				$fuhao_media .= '</picture>';
			}
			?>
			<div class="hero-slide<?php echo $fuhao_is_active ? ' is-active' : ''; ?>" data-hero-slide role="group" aria-roledescription="<?php esc_attr_e( '幻灯片', 'fuhao' ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '第 %1$d 张，共 %2$d 张', 'fuhao' ), $fuhao_key + 1, $fuhao_count ) ); ?>"<?php echo $fuhao_is_active ? '' : ' aria-hidden="true"'; ?>>
				<?php if ( $fuhao_link ) : ?>
					<a class="hero-slide__link" href="<?php echo esc_url( $fuhao_link ); ?>" aria-label="<?php echo esc_attr( $fuhao_label ); ?>">
						<?php echo $fuhao_media; // phpcs:ignore WordPress.Security.EscapeOutput -- 已按属性转义。 ?>
					</a>
				<?php else : ?>
					<?php echo $fuhao_media; // phpcs:ignore WordPress.Security.EscapeOutput -- 已按属性转义。 ?>
				<?php endif; ?>
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
</section>
