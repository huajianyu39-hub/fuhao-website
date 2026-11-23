<?php
/**
 * 主题内置 SVG 图标（线性风格，跟随文字颜色）。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 输出一个图标。
 *
 * @param string $name 图标名称。
 * @param string $class 附加 class。
 * @return string
 */
function fuhao_icon( $name, $class = '' ) {
	$icons = array(
		'phone'    => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
		'mail'     => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'pin'      => '<path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'chat'     => '<path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5z"/>',
		'check'    => '<path d="m20 6-11 11-5-5"/>',
		'arrow'    => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
		'arrow-up' => '<path d="M12 19V5"/><path d="m6 11 6-6 6 6"/>',
		'box'      => '<path d="M21 8.5 12 3 3 8.5v7L12 21l9-5.5v-7z"/><path d="M3 8.5 12 14l9-5.5"/><path d="M12 14v7"/>',
		'bag'      => '<path d="M6 8.5A2.5 2.5 0 0 1 8.5 6h7A2.5 2.5 0 0 1 18 8.5V19a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8.5z"/><path d="M9 6V5a3 3 0 0 1 6 0v1"/><path d="M6 11h12"/>',
		'layers'   => '<path d="m12 3 9 5-9 5-9-5 9-5z"/><path d="m3 12.5 9 5 9-5"/><path d="m3 17 9 5 9-5"/>',
		'backpack' => '<path d="M7 9a5 5 0 0 1 10 0v11H7V9z"/><path d="M9 9V7a3 3 0 0 1 6 0v2"/><path d="M7 13h10"/><path d="M10 17h4"/>',
		'laptop'   => '<rect x="4" y="5" width="16" height="12" rx="2"/><path d="M2 20h20"/><path d="M9 9h6"/>',
		'mountain' => '<path d="m3 19 6-9 4 6 2.5-3.5L21 19H3z"/><circle cx="17.5" cy="5.5" r="1.8"/>',
		'factory'  => '<path d="M3 21h18"/><path d="M5 21V10l4.5 3V10L14 13V6.5a1 1 0 0 1 1.6-.8L21 9.5V21"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return '<svg class="icon' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}
