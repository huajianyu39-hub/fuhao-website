<?php
/**
 * 产品展示字段：列表预览图、产品图集与 1688 商品链接。
 *
 * 字段使用 WordPress 文章元数据（post meta），后台与 REST / Abilities 共用同一份数据。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'FUHAO_GALLERY_LIMIT' ) ) {
	define( 'FUHAO_GALLERY_LIMIT', 6 );
}

/**
 * 清理列表预览图 ID。
 *
 * @param mixed $value 输入值。
 * @return int
 */
function fuhao_sanitize_card_image( $value ) {
	return absint( $value );
}

/**
 * 清理产品图集 ID 数组。
 *
 * @param mixed $value 输入值。
 * @return array
 */
function fuhao_sanitize_gallery( $value ) {
	$ids = array();

	foreach ( (array) $value as $id ) {
		$id = absint( $id );

		if ( ! $id || in_array( $id, $ids, true ) ) {
			continue;
		}

		$ids[] = $id;

		if ( count( $ids ) >= FUHAO_GALLERY_LIMIT ) {
			break;
		}
	}

	return $ids;
}

/**
 * 清理 1688 链接。
 *
 * @param mixed $value 输入值。
 * @return string
 */
function fuhao_sanitize_1688_url( $value ) {
	return esc_url_raw( trim( (string) $value ) );
}

/**
 * 元数据写入权限。
 *
 * @param bool   $allowed  默认是否允许。
 * @param string $meta_key 元数据键。
 * @param int    $post_id  产品 ID。
 * @return bool
 */
function fuhao_meta_auth( $allowed, $meta_key, $post_id ) {
	return current_user_can( 'edit_post', $post_id );
}

/**
 * 注册产品元数据，同时暴露给 REST。
 */
function fuhao_register_product_fields_meta() {
	register_post_meta(
		'product',
		'_fuhao_card_image',
		array(
			'type'              => 'integer',
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => array(
				'schema' => array(
					'type'    => 'integer',
					'minimum' => 0,
				),
			),
			'sanitize_callback' => 'fuhao_sanitize_card_image',
			'auth_callback'     => 'fuhao_meta_auth',
		)
	);

	register_post_meta(
		'product',
		'_fuhao_gallery',
		array(
			'type'              => 'array',
			'single'            => true,
			'default'           => array(),
			'show_in_rest'      => array(
				'schema' => array(
					'type'     => 'array',
					'items'    => array( 'type' => 'integer' ),
					'maxItems' => FUHAO_GALLERY_LIMIT,
				),
			),
			'sanitize_callback' => 'fuhao_sanitize_gallery',
			'auth_callback'     => 'fuhao_meta_auth',
		)
	);

	register_post_meta(
		'product',
		'_fuhao_url_1688',
		array(
			'type'              => 'string',
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => array(
				'schema' => array(
					'type' => 'string',
				),
			),
			'sanitize_callback' => 'fuhao_sanitize_1688_url',
			'auth_callback'     => 'fuhao_meta_auth',
		)
	);
}
add_action( 'init', 'fuhao_register_product_fields_meta', 20 );

/**
 * 列表卡片使用的图片 ID：优先预览图，其次主图。
 *
 * @param int $post_id 产品 ID。
 * @return int
 */
function fuhao_get_product_card_image_id( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$image   = absint( get_post_meta( $post_id, '_fuhao_card_image', true ) );

	if ( $image && wp_attachment_is_image( $image ) ) {
		return $image;
	}

	return (int) get_post_thumbnail_id( $post_id );
}

/**
 * 读取产品图集 ID，最多 6 张。
 *
 * @param int $post_id 产品 ID。
 * @return array
 */
function fuhao_get_product_gallery_ids( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$ids     = fuhao_sanitize_gallery( get_post_meta( $post_id, '_fuhao_gallery', true ) );

	return array_values(
		array_filter(
			$ids,
			static function ( $id ) {
				return wp_attachment_is_image( $id );
			}
		)
	);
}

/**
 * 产品的 1688 链接：优先单品链接，其次店铺链接。
 *
 * @param int $post_id 产品 ID。
 * @return string
 */
function fuhao_get_product_1688_url( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$url     = (string) get_post_meta( $post_id, '_fuhao_url_1688', true );

	if ( '' === $url ) {
		$url = (string) fuhao_get( '1688_store' );
	}

	return $url;
}

/**
 * 产品编辑页加字段面板。
 */
function fuhao_add_product_fields_box() {
	add_meta_box(
		'fuhao_product_fields',
		__( '产品展示与 1688 链接', 'fuhao' ),
		'fuhao_render_product_fields_box',
		'product',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'fuhao_add_product_fields_box' );

/**
 * 渲染字段面板。
 *
 * @param WP_Post $post 当前产品。
 */
function fuhao_render_product_fields_box( $post ) {
	wp_nonce_field( 'fuhao_product_fields', 'fuhao_product_fields_nonce' );

	$card_id  = absint( get_post_meta( $post->ID, '_fuhao_card_image', true ) );
	$gallery  = fuhao_get_product_gallery_ids( $post->ID );
	$url_1688 = (string) get_post_meta( $post->ID, '_fuhao_url_1688', true );
	$items    = array();

	foreach ( $gallery as $attachment_id ) {
		$thumb = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
		if ( $thumb ) {
			$items[] = array(
				'id'  => (int) $attachment_id,
				'url' => $thumb[0],
				'alt' => get_the_title( $attachment_id ),
			);
		}
	}
	?>
	<div class="fuhao-field">
		<p><strong><?php esc_html_e( '列表预览图', 'fuhao' ); ?></strong></p>
		<p class="description"><?php esc_html_e( '只影响产品列表卡片；留空则使用主图。', 'fuhao' ); ?></p>
		<input type="hidden" name="fuhao_card_image" value="<?php echo esc_attr( $card_id ? $card_id : '' ); ?>">
		<div class="fuhao-field__preview" data-fuhao-card-preview>
			<?php
			if ( $card_id && wp_attachment_is_image( $card_id ) ) {
				echo wp_get_attachment_image( $card_id, 'thumbnail', false, array( 'alt' => get_the_title( $post ) ) );
			} else {
				echo '<span class="fuhao-field__empty">' . esc_html__( '未选择，使用主图', 'fuhao' ) . '</span>';
			}
			?>
		</div>
		<p>
			<button type="button" class="button" data-fuhao-pick-card><?php esc_html_e( '选择预览图', 'fuhao' ); ?></button>
			<button type="button" class="button-link" data-fuhao-clear-card><?php esc_html_e( '清除', 'fuhao' ); ?></button>
		</p>
	</div>

	<hr>

	<div class="fuhao-field">
		<p><strong><?php esc_html_e( '产品图集', 'fuhao' ); ?></strong></p>
		<p class="description"><?php echo esc_html( sprintf( __( '最多 %d 张；详情页可点缩略图切换大图。', 'fuhao' ), FUHAO_GALLERY_LIMIT ) ); ?></p>
		<input type="hidden" name="fuhao_gallery" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>">
		<ul class="fuhao-gallery" data-fuhao-gallery data-items="<?php echo esc_attr( wp_json_encode( $items ) ); ?>"></ul>
		<p>
			<button type="button" class="button" data-fuhao-pick-gallery><?php esc_html_e( '添加图片', 'fuhao' ); ?></button>
			<button type="button" class="button-link" data-fuhao-clear-gallery><?php esc_html_e( '清空图集', 'fuhao' ); ?></button>
		</p>
	</div>

	<hr>

	<p>
		<label for="fuhao_url_1688"><strong><?php esc_html_e( '1688 商品链接', 'fuhao' ); ?></strong></label>
	</p>
	<input type="url" class="widefat" name="fuhao_url_1688" id="fuhao_url_1688" value="<?php echo esc_attr( $url_1688 ); ?>" placeholder="https://detail.1688.com/offer/....html">
	<p class="description"><?php esc_html_e( '留空则使用“外观 → 自定义 → 产品联系与 1688”中的店铺链接；两者都为空时，产品页不显示 1688 入口。', 'fuhao' ); ?></p>
	<?php
}

/**
 * 保存字段面板。
 *
 * @param int $post_id 产品 ID。
 */
function fuhao_save_product_fields( $post_id ) {
	if ( ! isset( $_POST['fuhao_product_fields_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['fuhao_product_fields_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'fuhao_product_fields' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['fuhao_card_image'] ) ) {
		$card_id = absint( wp_unslash( $_POST['fuhao_card_image'] ) );

		if ( $card_id && wp_attachment_is_image( $card_id ) ) {
			update_post_meta( $post_id, '_fuhao_card_image', $card_id );
		} else {
			delete_post_meta( $post_id, '_fuhao_card_image' );
		}
	}

	if ( isset( $_POST['fuhao_gallery'] ) ) {
		$raw     = sanitize_text_field( wp_unslash( $_POST['fuhao_gallery'] ) );
		$gallery = fuhao_sanitize_gallery( $raw ? explode( ',', $raw ) : array() );
		$gallery = array_values(
			array_filter(
				$gallery,
				static function ( $id ) {
					return wp_attachment_is_image( $id );
				}
			)
		);

		if ( $gallery ) {
			update_post_meta( $post_id, '_fuhao_gallery', $gallery );
		} else {
			delete_post_meta( $post_id, '_fuhao_gallery' );
		}
	}

	if ( isset( $_POST['fuhao_url_1688'] ) ) {
		$url = fuhao_sanitize_1688_url( wp_unslash( $_POST['fuhao_url_1688'] ) );

		if ( '' !== $url ) {
			update_post_meta( $post_id, '_fuhao_url_1688', $url );
		} else {
			delete_post_meta( $post_id, '_fuhao_url_1688' );
		}
	}
}
add_action( 'save_post_product', 'fuhao_save_product_fields' );

/**
 * 产品编辑页的媒体库脚本。
 */
function fuhao_product_fields_admin_assets() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'product' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();

	$js_path  = get_template_directory() . '/assets/js/admin-product.js';
	$css_path = get_template_directory() . '/assets/css/admin-product.css';

	wp_enqueue_script(
		'fuhao-admin-product',
		get_template_directory_uri() . '/assets/js/admin-product.js',
		array(),
		file_exists( $js_path ) ? filemtime( $js_path ) : FUHAO_VERSION,
		true
	);

	wp_enqueue_style(
		'fuhao-admin-product',
		get_template_directory_uri() . '/assets/css/admin-product.css',
		array(),
		file_exists( $css_path ) ? filemtime( $css_path ) : FUHAO_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'fuhao_product_fields_admin_assets' );
