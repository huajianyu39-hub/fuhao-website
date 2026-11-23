<?php
/**
 * Fuhao 箱包制造主题 — 入口文件。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FUHAO_VERSION', '0.3.8' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/icons.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/product-fields.php';
require get_template_directory() . '/inc/pages.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/home-sections.php';
require get_template_directory() . '/inc/home-blocks.php';
require get_template_directory() . '/inc/inquiry.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/patterns.php';

/**
 * 主题基础支持。
 */
function fuhao_setup() {
	load_theme_textdomain( 'fuhao', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( '主导航', 'fuhao' ),
			'footer'  => __( '底部导航', 'fuhao' ),
		)
	);

}
add_action( 'after_setup_theme', 'fuhao_setup' );

/**
 * 加载样式与脚本。
 */
function fuhao_scripts() {
	$css_path = get_template_directory() . '/assets/css/main.css';
	$js_path  = get_template_directory() . '/assets/js/main.js';
	$deps     = array();

	if ( is_singular( 'product' ) || is_page( 'contact' ) ) {
		$qr_path = get_template_directory() . '/assets/js/qrcode.js';

		wp_enqueue_script(
			'fuhao-qrcode',
			get_template_directory_uri() . '/assets/js/qrcode.js',
			array(),
			file_exists( $qr_path ) ? filemtime( $qr_path ) : FUHAO_VERSION,
			true
		);

		$deps[] = 'fuhao-qrcode';
	}

	wp_enqueue_style(
		'fuhao-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		file_exists( $css_path ) ? filemtime( $css_path ) : FUHAO_VERSION
	);

	wp_enqueue_script(
		'fuhao-main',
		get_template_directory_uri() . '/assets/js/main.js',
		$deps,
		file_exists( $js_path ) ? filemtime( $js_path ) : FUHAO_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'fuhao_scripts' );

/**
 * 摘要长度。
 */
function fuhao_excerpt_length() {
	return 30;
}
add_filter( 'excerpt_length', 'fuhao_excerpt_length' );

function fuhao_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'fuhao_excerpt_more' );

/**
 * 让自定义菜单里的 #锚点 链接在子页面也能跳回首页对应版块。
 *
 * @param array $atts 链接属性。
 * @return array
 */
function fuhao_nav_menu_anchor_links( $atts ) {
	if ( ! empty( $atts['href'] ) && '#' === substr( $atts['href'], 0, 1 ) ) {
		$atts['href'] = fuhao_resolve_link( $atts['href'] );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'fuhao_nav_menu_anchor_links' );
