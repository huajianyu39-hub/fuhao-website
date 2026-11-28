<?php
/**
 * 模板辅助函数：默认文案、链接处理、导航回退、语言切换等。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 主题默认文案。后台自定义器中未填写时使用这里的值。
 *
 * @return array
 */
function fuhao_defaults() {
	return array(
		'brand_name'      => '富豪箱包',
		'brand_tagline'   => 'FUHAO · 箱包制造',

		'1688_store'      => '',
		'wechat_qr'       => '',

		'hero_image'     => '',
		'hero_link'      => '',
		'hero_alt'       => '',
		'hero_interval'  => 3,

		'sec_cooperation_eyebrow' => '合作方式',
		'sec_cooperation_title'   => '现货、现货定制与批量定制',
		'sec_products_eyebrow'    => '产品中心',
		'sec_products_title'      => '7 大产品分类，覆盖收纳与出行',
		'sec_oem_eyebrow'         => '定制服务',
		'sec_process_eyebrow'     => '制造流程',
		'sec_process_title'       => '六步完成，从图纸到出货',
		'sec_stock_eyebrow'       => '现货供应',
		'sec_stock_title'         => '现货与现货定制，按订单安排发出',
		'sec_industries_eyebrow'  => '服务行业',
		'sec_industries_title'    => '服务行业与合作方式',
		'sec_contact_eyebrow'     => '联系我们',
		'sec_contact_title'       => '把需求发给我们，我们会尽快回复',

		'stat1_number'   => '20+ 年',
		'stat1_label'    => '工厂箱包制造经验',
		'stat2_number'   => '6 个',
		'stat2_label'    => '生产厂区',
		'stat3_number'   => '300+',
		'stat3_label'    => '工厂员工',
		'stat4_number'   => '3–5 万',
		'stat4_label'    => '成品日产能',

		'about_eyebrow'  => '工厂实力',
		'about_title'    => '6 个生产厂区，完整箱包产线',
		'about_text'     => '上饶市富豪箱包有限公司专注箱包产品销售与客户服务；生产由拥有 20+ 年经验的箱包工厂完成。工厂覆盖裁剪、车缝、组装与包装环节，支持现货、现货定制与批量订单。',
		'about_image'    => '',
		'about_point1'   => '工厂拥有 20+ 年箱包制造经验，6 个生产厂区',
		'about_point2'   => '裁剪、车缝、组装、包装完整产线',
		'about_point3'   => 'IQC / IPQC / OQC 三道质检',
		'about_point4'   => '支持现货、现货定制与批量订单',

		'oem_title'      => '从一张图纸，到一批可以交付的包',
		'oem_text'       => '工厂打样团队与产线，支持 OEM 来样定制与 ODM 设计开发。面料、五金、结构与包装，可按你的品牌标准落地。',

		'contact_person1' => '林俊杰',
		'contact_phone'   => '19884501223',
		'contact_person2' => '徐爱清',
		'contact_phone2'  => '15216056787',
		'contact_person3' => '林观忠',
		'contact_phone3'  => '18870301878',
		'contact_email'   => '19884501223@163.com',
		'contact_email2'  => '819935459@qq.com',
		'contact_email3'  => 'huajianyu39@gmail.com',
		'contact_whatsapp' => '19884501223',
		'contact_wechat'  => '',
		'contact_address' => '江西省上饶市广信区煌固镇八都山底街工业区',
		'contact_hours'   => '',
		'inquiry_email'   => '19884501223@163.com',

		'footer_desc'     => '上饶市富豪箱包有限公司，专注收纳、背包与旅行包类的生产与定制，支持现货、现货定制与批量订单。',
		'footer_icp'      => '',
	);
}

/**
 * 读取主题设置（自定义器 → 默认值）。
 *
 * @param string $key 设置键（不含 fuhao_ 前缀）。
 * @return mixed
 */
function fuhao_get( $key ) {
	$defaults = fuhao_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return get_theme_mod( 'fuhao_' . $key, $default );
}

/**
 * 把媒体库视频附件 ID 转成前台地址。
 *
 * @param int $attachment_id 视频附件 ID。
 * @return string
 */
function fuhao_video_url( $attachment_id ) {
	$attachment_id = absint( $attachment_id );

	if ( ! $attachment_id || ! wp_attachment_is( 'video', $attachment_id ) ) {
		return '';
	}

	$url = wp_get_attachment_url( $attachment_id );

	return $url ? $url : '';
}

/**
 * 将 #anchor 链接转换为跨页面也可用的绝对链接。
 *
 * @param string $url 链接。
 * @return string
 */
function fuhao_resolve_link( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return home_url( '/' );
	}

	if ( '#' === substr( $url, 0, 1 ) ) {
		return is_front_page() ? $url : home_url( '/' ) . $url;
	}

	return $url;
}

/**
 * 电话链接。
 *
 * @param string $phone 电话号码。
 * @return string
 */
function fuhao_tel_href( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * 产品中心链接（归档页）。
 *
 * @return string
 */
function fuhao_product_archive_url() {
	$url = get_post_type_archive_link( 'product' );

	return $url ? $url : home_url( '/products/' );
}

/**
 * 页面链接：优先取已创建页面的固定链接，否则回退到同路径。
 *
 * @param string $slug 页面别名。
 * @return string
 */
function fuhao_page_url( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		$url = get_permalink( $page );
		if ( $url ) {
			return $url;
		}
	}

	return home_url( '/' . trim( $slug, '/' ) . '/' );
}

/**
 * WhatsApp 链接（中国手机号自动补 86）。
 *
 * @param string $number 号码。
 * @return string
 */
function fuhao_whatsapp_href( $number ) {
	$digits = preg_replace( '/\D+/', '', (string) $number );

	if ( '' === $digits ) {
		return '';
	}

	if ( 11 === strlen( $digits ) && '1' === substr( $digits, 0, 1 ) ) {
		$digits = '86' . $digits;
	}

	return 'https://wa.me/' . $digits;
}

/**
 * 对外联系人列表。
 *
 * @return array
 */
function fuhao_contact_people() {
	$phones = array(
		fuhao_get( 'contact_phone' ),
		fuhao_get( 'contact_phone2' ),
		fuhao_get( 'contact_phone3' ),
	);
	$names  = array(
		fuhao_get( 'contact_person1' ),
		fuhao_get( 'contact_person2' ),
		fuhao_get( 'contact_person3' ),
	);
	$people = array();

	foreach ( $phones as $index => $phone ) {
		$phone = trim( (string) $phone );

		if ( '' === $phone ) {
			continue;
		}

		$people[] = array(
			'name'  => isset( $names[ $index ] ) ? $names[ $index ] : '',
			'phone' => $phone,
			'href'  => fuhao_tel_href( $phone ),
		);
	}

	return $people;
}

/**
 * 对外邮箱列表（去重、过滤非法地址）。
 *
 * @return array
 */
function fuhao_contact_emails() {
	$emails = array(
		fuhao_get( 'contact_email' ),
		fuhao_get( 'contact_email2' ),
		fuhao_get( 'contact_email3' ),
	);

	$emails = array_filter( $emails, 'is_email' );

	return array_values( array_unique( $emails ) );
}

/**
 * 主菜单回退：主题启用后无需配置菜单即可显示首页锚点导航。
 *
 * @param array $args wp_nav_menu 参数。
 */
function fuhao_nav_fallback( $args = array() ) {
	$menu_class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'nav-list';
	$items      = array(
		array( '首页', home_url( '/' ) ),
		array( '产品中心', fuhao_product_archive_url() ),
		array( '现货供应', fuhao_page_url( 'stock' ) ),
		array( '定制服务', fuhao_page_url( 'customization' ) ),
		array( '工厂实力', fuhao_page_url( 'factory' ) ),
		array( '关于我们', fuhao_page_url( 'about' ) ),
		array( '联系我们', fuhao_page_url( 'contact' ) ),
	);

	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( fuhao_resolve_link( $item[1] ) ),
			esc_html( $item[0] )
		);
	}
	echo '</ul>';
}

/**
 * 页脚菜单回退。
 *
 * @param array $args wp_nav_menu 参数。
 */
function fuhao_footer_nav_fallback( $args = array() ) {
	$menu_class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'footer-list';
	$items      = array(
		array( '产品中心', fuhao_product_archive_url() ),
		array( '现货供应', fuhao_page_url( 'stock' ) ),
		array( '定制服务', fuhao_page_url( 'customization' ) ),
		array( '工厂实力', fuhao_page_url( 'factory' ) ),
		array( '关于我们', fuhao_page_url( 'about' ) ),
		array( '联系我们', fuhao_page_url( 'contact' ) ),
		array( '隐私政策', fuhao_page_url( 'privacy' ) ),
	);

	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( $item[1] ),
			esc_html( $item[0] )
		);
	}
	echo '</ul>';
}

/**
 * 语言切换位置。安装 Polylang 后自动显示语言列表，否则显示占位按钮。
 */
function fuhao_language_switch() {
	echo '<div class="lang-switch" aria-label="语言切换">';

	if ( function_exists( 'pll_the_languages' ) ) {
		$fuhao_langs = pll_the_languages(
			array(
				'raw'                    => 1,
				'display_names_as'       => 'name',
				'hide_if_no_translation' => 0,
			)
		);

		if ( is_array( $fuhao_langs ) ) {
			echo '<ul class="lang-switch__pll">';
			foreach ( $fuhao_langs as $fuhao_lang ) {
				printf(
					'<li%1$s><a href="%2$s">%3$s</a></li>',
					! empty( $fuhao_lang['current_lang'] ) ? ' class="current-lang"' : '',
					esc_url( $fuhao_lang['url'] ),
					esc_html( $fuhao_lang['name'] )
				);
			}
			echo '</ul>';
		}
	} else {
		echo '<span class="lang-switch__item is-active" aria-current="true">中文</span>';
		echo '<button type="button" class="lang-switch__item lang-switch__item--soon" aria-disabled="true" title="English version coming soon">EN</button>';
		echo '<span class="screen-reader-text">英文版即将上线</span>';
	}

	echo '</div>';
}

/**
 * 图片占位块。
 *
 * @param string $icon 图标名称。
 * @param string $label 占位文字。
 */
function fuhao_placeholder( $icon = 'box', $label = '图片待替换' ) {
	echo '<span class="placeholder" role="img" aria-label="' . esc_attr( $label ) . '">';
	echo fuhao_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- 内置 SVG。
	echo '<span>' . esc_html( $label ) . '</span>';
	echo '</span>';
}

/**
 * 简易面包屑。
 *
 * @param array $trail 层级数组，每项含 label 与可选 url。
 */
function fuhao_breadcrumb( $trail = array() ) {
	echo '<nav class="breadcrumb" aria-label="面包屑导航">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">首页</a>';

	foreach ( $trail as $item ) {
		echo '<span class="breadcrumb__sep" aria-hidden="true">/</span>';
		if ( ! empty( $item['url'] ) ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item['label'] ) . '</span>';
		}
	}

	echo '</nav>';
}
