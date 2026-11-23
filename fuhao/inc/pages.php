<?php
/**
 * 首次启用主题时创建基础页面。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 需要自动创建的页面。
 *
 * @return array
 */
function fuhao_default_pages() {
	return array(
		'stock'         => array(
			'title' => __( '现货供应', 'fuhao' ),
		),
		'customization' => array(
			'title' => __( '定制服务', 'fuhao' ),
		),
		'factory'       => array(
			'title' => __( '工厂实力', 'fuhao' ),
		),
		'about'         => array(
			'title' => __( '关于我们', 'fuhao' ),
		),
		'contact'       => array(
			'title' => __( '联系我们', 'fuhao' ),
		),
		'privacy'       => array(
			'title' => __( '隐私政策', 'fuhao' ),
		),
	);
}

/**
 * 创建缺失的基础页面（只执行一次）。
 */
function fuhao_maybe_create_default_pages() {
	if ( get_option( 'fuhao_default_pages_v1' ) ) {
		return;
	}

	$created = false;
	$order   = 0;

	foreach ( fuhao_default_pages() as $slug => $page ) {
		$order++;

		if ( get_page_by_path( $slug ) ) {
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'menu_order'   => $order,
				'post_content' => '',
			)
		);

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			$created = true;
		}
	}

	update_option( 'fuhao_default_pages_v1', 1 );

	if ( $created ) {
		flush_rewrite_rules();
	}
}
add_action( 'after_switch_theme', 'fuhao_maybe_create_default_pages', 20 );
add_action( 'admin_init', 'fuhao_maybe_create_default_pages' );
