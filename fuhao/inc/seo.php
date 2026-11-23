<?php
/**
 * 基础 SEO：描述、社交分享与结构化数据。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 当前页面描述。
 *
 * @return string
 */
function fuhao_seo_description() {
	$description = '';

	if ( is_front_page() ) {
		$description = fuhao_get( 'hero_text' );
	} elseif ( is_page() ) {
		$descriptions = array(
			'stock'         => __( '豪富箱包现货与现货定制：支持国内一件代发、跨境贴标、平台面单与真空压缩包装。可供款式、数量与运费请询价确认。', 'fuhao' ),
			'customization' => __( '豪富箱包定制服务：现货定制、批量定制与 OEM / ODM 开发，可调整面料、LOGO、结构、五金与包装。起订量与交期按项目评估。', 'fuhao' ),
			'factory'       => __( '工厂拥有 26+ 年箱包制造经验与 6 个生产厂区，覆盖裁剪、车缝、组装与包装，成品日产能 3–5 万件。', 'fuhao' ),
			'about'         => __( '上饶市豪富箱包有限公司，品牌豪富 / FUHAO，专注收纳、背包与旅行包类的生产与定制。', 'fuhao' ),
			'contact'       => sprintf(
				/* translators: 1: phone, 2: email */
				__( '联系豪富箱包业务团队，咨询产品、定制、样品与验厂。电话 %1$s，邮箱 %2$s。', 'fuhao' ),
				fuhao_get( 'contact_phone' ),
				fuhao_get( 'contact_email' )
			),
			'privacy'       => __( '豪富箱包网站隐私政策：说明询盘信息的收集、使用、保存与联系渠道。', 'fuhao' ),
		);

		$slug = get_post_field( 'post_name', get_queried_object_id() );

		if ( isset( $descriptions[ $slug ] ) ) {
			$description = $descriptions[ $slug ];
		} else {
			$description = get_post_field( 'post_excerpt', get_queried_object_id() );

			if ( '' === trim( (string) $description ) ) {
				$description = get_post_field( 'post_content', get_queried_object_id() );
			}
		}
	} elseif ( is_singular( 'product' ) ) {
		$description = get_post_field( 'post_excerpt', get_queried_object_id() );

		if ( '' === trim( (string) $description ) ) {
			$description = get_post_field( 'post_content', get_queried_object_id() );
		}
	} elseif ( is_singular() ) {
		$description = get_post_field( 'post_excerpt', get_queried_object_id() );

		if ( '' === trim( (string) $description ) ) {
			$description = get_post_field( 'post_content', get_queried_object_id() );
		}
	} elseif ( is_post_type_archive( 'product' ) ) {
		$description = __( '豪富箱包产品中心：折叠双肩包、化妆包、鞋子收纳、棉被衣物收纳、数码收纳、学生书包与多潮流款式。', 'fuhao' );
	} elseif ( is_tax( 'product_cat' ) ) {
		$term        = get_queried_object();
		$description = $term instanceof WP_Term ? $term->description : '';
	} elseif ( is_home() ) {
		$description = fuhao_get( 'footer_desc' );
	}

	$description = wp_strip_all_tags( strip_shortcodes( (string) $description ) );
	$description = trim( preg_replace( '/\s+/', ' ', $description ) );

	return wp_html_excerpt( $description, 150, '…' );
}

/**
 * 当前页面分享图片。
 *
 * @return string
 */
function fuhao_seo_image() {
	$post_id = get_queried_object_id();

	if ( is_singular() && has_post_thumbnail( $post_id ) ) {
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
 * 当前页面规范化链接。
 *
 * @return string
 */
function fuhao_seo_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}

	if ( is_singular() ) {
		$url = get_permalink( get_queried_object_id() );

		return $url ? $url : home_url( '/' );
	}

	if ( is_post_type_archive( 'product' ) ) {
		return fuhao_product_archive_url();
	}

	if ( is_tax() ) {
		$url = get_term_link( get_queried_object() );

		if ( ! is_wp_error( $url ) ) {
			return $url;
		}
	}

	if ( is_search() ) {
		return get_search_link();
	}

	return home_url( '/' );
}

/**
 * 输出描述与 Open Graph 标签。
 */
function fuhao_output_seo_meta() {
	$description = fuhao_seo_description();
	$title       = wp_get_document_title();
	$url         = fuhao_seo_url();
	$image       = fuhao_seo_image();
	$type        = is_singular( 'product' ) ? 'product' : ( is_single() ? 'article' : 'website' );

	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}

	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );
	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $type ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );

	if ( $description ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	}

	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}

	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );

	if ( $description ) {
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	}

	if ( $image ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
	}
}
add_action( 'wp_head', 'fuhao_output_seo_meta', 2 );

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
 * 输出 Organization / WebSite / Product 结构化数据。
 */
function fuhao_output_schema() {
	$organization = array(
		'@type'       => 'Organization',
		'@id'         => home_url( '/#organization' ),
		'name'        => __( '上饶市豪富箱包有限公司', 'fuhao' ),
		'alternateName' => 'FUHAO 豪富箱包',
		'url'         => home_url( '/' ),
		'description' => fuhao_get( 'footer_desc' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => fuhao_get( 'contact_address' ),
			'addressLocality' => '上饶市',
			'addressRegion'   => '江西省',
			'addressCountry'  => 'CN',
		),
		'contactPoint' => array(
			'@type'       => 'ContactPoint',
			'telephone'   => fuhao_get( 'contact_phone' ),
			'email'       => fuhao_get( 'contact_email' ),
			'contactType' => 'sales',
		),
	);

	$website = array(
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'url'             => home_url( '/' ),
		'name'            => get_bloginfo( 'name' ),
		'publisher'       => array( '@id' => home_url( '/#organization' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);

	$graph = array( $organization, $website );

	if ( is_singular( 'product' ) ) {
		$product_id          = get_queried_object_id();
		$product_description = get_post_field( 'post_excerpt', $product_id );

		if ( '' === trim( (string) $product_description ) ) {
			$product_description = wp_html_excerpt( wp_strip_all_tags( get_post_field( 'post_content', $product_id ) ), 150, '…' );
		}

		$product = array(
			'@type'       => 'Product',
			'name'        => get_the_title( $product_id ),
			'description' => wp_strip_all_tags( $product_description ),
			'url'         => get_permalink( $product_id ),
			'brand'       => array(
				'@type' => 'Brand',
				'name'  => 'FUHAO',
			),
		);

		$image = fuhao_seo_image();
		if ( $image ) {
			$product['image'] = $image;
		}

		$terms = get_the_terms( $product_id, 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$product['category'] = $terms[0]->name;
		}

		$graph[] = $product;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo '<script type="application/ld+json">';
	echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); // phpcs:ignore WordPress.Security.EscapeOutput -- JSON-LD。
	echo '</script>' . "\n";
}
add_action( 'wp_head', 'fuhao_output_schema', 3 );
