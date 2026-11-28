<?php
/**
 * SEO 补充：产品结构化数据，以及为 SureRank 补齐归档与产品描述。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 搜索结果与 404 不收录。
 *
 * @param array $robots 指令。
 * @return array
 */
function fuhao_robots( $robots ) {
	if ( is_search() || is_404() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'fuhao_robots' );

/**
 * 产品页封面图。
 *
 * @return string
 */
function fuhao_product_image() {
	$post_id = get_queried_object_id();

	if ( has_post_thumbnail( $post_id ) ) {
		$image = get_the_post_thumbnail_url( $post_id, 'large' );

		if ( $image ) {
			return $image;
		}
	}

	$image = fuhao_get( 'hero_image' );

	if ( $image ) {
		return $image;
	}

	return get_site_icon_url( 512 );
}

/**
 * 产品页 Product 结构化数据。
 *
 * SureRank 只为 WooCommerce 商品输出 Product，自定义产品类型需要在这里补一条。
 */
function fuhao_output_product_schema() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	$product_id  = get_queried_object_id();
	$description = get_post_field( 'post_excerpt', $product_id );

	if ( '' === trim( (string) $description ) ) {
		$description = wp_html_excerpt( wp_strip_all_tags( get_post_field( 'post_content', $product_id ) ), 150, '…' );
	}

	$product = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'Product',
		'name'         => get_the_title( $product_id ),
		'description'  => wp_strip_all_tags( (string) $description ),
		'url'          => get_permalink( $product_id ),
		'brand'        => array(
			'@type' => 'Brand',
			'name'  => 'FUHAO',
		),
		'manufacturer' => array(
			'@type' => 'Organization',
			'name'  => '上饶市富豪箱包有限公司',
		),
	);

	$image = fuhao_product_image();
	if ( $image ) {
		$product['image'] = $image;
	}

	$terms = get_the_terms( $product_id, 'product_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$product['category'] = $terms[0]->name;
	}

	echo '<script type="application/ld+json">';
	echo wp_json_encode( $product, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); // phpcs:ignore WordPress.Security.EscapeOutput -- JSON-LD。
	echo '</script>' . "\n";
}
add_action( 'wp_head', 'fuhao_output_product_schema', 20 );

/**
 * 首页 WebPage 名称使用站点名称，而不是最新文章标题。
 *
 * @param array $data SureRank 结构化数据变量。
 * @return array
 */
function fuhao_surerank_home_schema_title( $data ) {
	if ( ( is_front_page() || is_home() ) && isset( $data['current']['title'] ) ) {
		$data['current']['title'] = get_bloginfo( 'name' );
	}

	return $data;
}
add_filter( 'surerank_schema_data', 'fuhao_surerank_home_schema_title' );

/**
 * 为 SureRank 没有取到分享图的页面补上主题主视觉。
 *
 * 已有特色图的文章或产品由 SureRank 自行取图，这里不覆盖。
 *
 * @param array $meta SureRank 页面元数据。
 * @return array
 */
function fuhao_surerank_social_image( $meta ) {
	if ( ! is_array( $meta ) ) {
		return $meta;
	}

	if ( is_singular() && has_post_thumbnail( get_queried_object_id() ) ) {
		return $meta;
	}

	$image = fuhao_get( 'hero_image' );

	if ( ! $image ) {
		$image = get_site_icon_url( 512 );
	}

	if ( ! $image ) {
		return $meta;
	}

	if ( empty( $meta['facebook_image_url'] ) ) {
		$meta['facebook_image_url'] = $image;
	}

	if ( empty( $meta['twitter_image_url'] ) ) {
		$meta['twitter_image_url'] = $image;
	}

	return $meta;
}
add_filter( 'surerank_set_meta', 'fuhao_surerank_social_image', 20 );

/**
 * 产品归档标题使用「产品中心」，其余归档保持 SureRank 默认。
 *
 * @param string $label 归档标签。
 * @param string $type  归档类型。
 * @return string
 */
function fuhao_surerank_archive_label( $label, $type ) {
	if ( is_post_type_archive( 'product' ) ) {
		return __( '产品中心', 'fuhao' );
	}

	return $label;
}
add_filter( 'surerank_archive_title_label', 'fuhao_surerank_archive_label', 10, 2 );

/**
 * 为 SureRank 补齐产品归档、产品分类与产品详情的描述。
 *
 * 只使用主题内容填充没有手动填写的字段，后台 SureRank 里保存过的描述优先。
 *
 * @param array $meta SureRank 页面元数据。
 * @return array
 */
function fuhao_surerank_meta( $meta ) {
	if ( ! is_array( $meta ) ) {
		return $meta;
	}

	$fields = array( 'page_description', 'facebook_description', 'twitter_description' );

	if ( is_post_type_archive( 'product' ) ) {
		$post_type   = get_post_type_object( 'product' );
		$description = $post_type && ! empty( $post_type->description ) ? (string) $post_type->description : '';

		foreach ( $fields as $field ) {
			if ( empty( $meta[ $field ] ) ) {
				$meta[ $field ] = $description;
			}
		}

		return $meta;
	}

	if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
		$term        = get_queried_object();
		$description = $term instanceof WP_Term ? (string) $term->description : '';

		foreach ( $fields as $field ) {
			if ( empty( $meta[ $field ] ) ) {
				$meta[ $field ] = $description;
			}
		}

		return $meta;
	}

	if ( ! is_singular( 'product' ) ) {
		return $meta;
	}

	$post_id     = get_queried_object_id();
	$description = (string) get_post_field( 'post_excerpt', $post_id );

	if ( '' === trim( $description ) ) {
		$description = wp_html_excerpt( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 150, '…' );
	}

	$description = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( strip_shortcodes( $description ) ) ) );

	foreach ( $fields as $field ) {
		if ( '' === (string) get_post_meta( $post_id, 'surerank_settings_' . $field, true ) ) {
			$meta[ $field ] = $description;
		}
	}

	return $meta;
}
add_filter( 'surerank_set_meta', 'fuhao_surerank_meta', 20 );
