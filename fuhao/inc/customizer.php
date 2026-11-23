<?php
/**
 * 后台「外观 → 自定义」设置。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 允许锚点链接（#contact）与普通 URL。
 *
 * @param string $value 输入值。
 * @return string
 */
function fuhao_sanitize_link( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value || '#' === substr( $value, 0, 1 ) ) {
		return $value;
	}

	return esc_url_raw( $value );
}

/**
 * 添加文本类设置与控制。
 */
function fuhao_customize_text( $wp_customize, $section, $id, $label, $type = 'text', $sanitize = 'sanitize_text_field', $description = '' ) {
	$defaults = fuhao_defaults();
	$key      = str_replace( 'fuhao_', '', $id );
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
		)
	);

	$wp_customize->add_control(
		$id,
		array(
			'label'       => $label,
			'section'     => $section,
			'type'        => $type,
			'description' => $description,
		)
	);
}

/**
 * 添加图片设置与控制。
 */
function fuhao_customize_image( $wp_customize, $section, $id, $label, $description = '' ) {
	$defaults = fuhao_defaults();
	$key      = str_replace( 'fuhao_', '', $id );
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			$id,
			array(
				'label'       => $label,
				'section'     => $section,
				'description' => $description,
			)
		)
	);
}

/**
 * 注册所有自定义器面板。
 *
 * @param WP_Customize_Manager $wp_customize 自定义器对象。
 */
function fuhao_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'fuhao_panel',
		array(
			'title'       => __( '豪富主题设置', 'fuhao' ),
			'description' => __( '公司信息与工厂数据分开填写：公司专注箱包销售与服务，工厂拥有 26+ 年箱包制造经验。', 'fuhao' ),
			'priority'    => 25,
		)
	);

	/* 品牌 */
	$wp_customize->add_section(
		'fuhao_brand',
		array(
			'title' => __( '品牌名称', 'fuhao' ),
			'panel' => 'fuhao_panel',
		)
	);
	fuhao_customize_text( $wp_customize, 'fuhao_brand', 'fuhao_brand_name', '网站品牌名', 'text', 'sanitize_text_field', '显示在页头与页脚，如“豪富箱包”。' );
	fuhao_customize_text( $wp_customize, 'fuhao_brand', 'fuhao_brand_tagline', '品牌副标题', 'text', 'sanitize_text_field', '如“FUHAO · 箱包制造”。' );

	/* 产品联系与 1688 */
	$wp_customize->add_section(
		'fuhao_product_contact',
		array(
			'title'       => __( '产品联系与 1688', 'fuhao' ),
			'panel'       => 'fuhao_panel',
			'description' => __( '显示在每个产品详情页的“找到这款产品”区域；留空的项自动不显示。', 'fuhao' ),
		)
	);
	fuhao_customize_text(
		$wp_customize,
		'fuhao_product_contact',
		'fuhao_1688_store',
		'1688 店铺链接',
		'url',
		'esc_url_raw',
		'没有单独填写商品链接的产品，会使用这个店铺链接。'
	);
	fuhao_customize_image(
		$wp_customize,
		'fuhao_product_contact',
		'fuhao_wechat_qr',
		'微信二维码',
		'建议上传正方形二维码图片；留空则不显示微信入口。'
	);

	/* 主视觉 */
	$wp_customize->add_section(
		'fuhao_hero',
		array(
			'title' => __( '首页主视觉', 'fuhao' ),
			'panel' => 'fuhao_panel',
		)
	);
	fuhao_customize_text(
		$wp_customize,
		'fuhao_hero',
		'fuhao_hero_interval',
		'切换间隔（秒）',
		'number',
		'absint',
		'自动轮播的间隔秒数，建议 3–7 秒；鼠标悬停时会暂停。'
	);

	fuhao_customize_image( $wp_customize, 'fuhao_hero', 'fuhao_hero_image', '海报 1 · 图片', '公司常驻海报。建议尺寸 1920×1080 以上，深色、留白较多的工厂或产品照片效果最好。留空则使用内置深色背景。' );
	fuhao_customize_text( $wp_customize, 'fuhao_hero', 'fuhao_hero_eyebrow', '海报 1 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_hero', 'fuhao_hero_title', '海报 1 · 大标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_hero', 'fuhao_hero_text', '海报 1 · 说明文字', 'textarea', 'sanitize_textarea_field' );
	fuhao_customize_text( $wp_customize, 'fuhao_hero', 'fuhao_hero_btn_text', '海报 1 · 按钮文字' );
	fuhao_customize_text( $wp_customize, 'fuhao_hero', 'fuhao_hero_btn_url', '海报 1 · 按钮链接', 'text', 'fuhao_sanitize_link', '可以是 #contact 这样的页内锚点，也可以是完整网址。' );
	fuhao_customize_text( $wp_customize, 'fuhao_hero', 'fuhao_hero_btn2_text', '海报 1 · 次按钮文字' );
	fuhao_customize_text( $wp_customize, 'fuhao_hero', 'fuhao_hero_btn2_url', '海报 1 · 次按钮链接', 'text', 'fuhao_sanitize_link' );

	for ( $fuhao_slide = 2; $fuhao_slide <= 5; $fuhao_slide++ ) {
		$fuhao_prefix = 'fuhao_slide' . $fuhao_slide . '_';
		$fuhao_label  = sprintf( '海报 %d · ', $fuhao_slide );

		fuhao_customize_image( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'image', $fuhao_label . '图片', '第 2 张可放当季爆款，按钮链接粘贴对应产品页网址；没有图片的海报位会自动跳过。' );
		fuhao_customize_text( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'eyebrow', $fuhao_label . '小标签' );
		fuhao_customize_text( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'title', $fuhao_label . '大标题' );
		fuhao_customize_text( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'text', $fuhao_label . '说明文字', 'textarea', 'sanitize_textarea_field' );
		fuhao_customize_text( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'btn_text', $fuhao_label . '按钮文字' );
		fuhao_customize_text( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'btn_url', $fuhao_label . '按钮链接', 'text', 'fuhao_sanitize_link', '粘贴产品页或任意页面网址；留空则不显示按钮。' );
		fuhao_customize_text( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'btn2_text', $fuhao_label . '次按钮文字' );
		fuhao_customize_text( $wp_customize, 'fuhao_hero', $fuhao_prefix . 'btn2_url', $fuhao_label . '次按钮链接', 'text', 'fuhao_sanitize_link' );
	}

	/* 首页版块标题 */
	$wp_customize->add_section(
		'fuhao_home_titles',
		array(
			'title'       => __( '首页版块标题', 'fuhao' ),
			'panel'       => 'fuhao_panel',
			'description' => __( '只改版块的小标签与大标题；正文与按钮文案保持不变。', 'fuhao' ),
		)
	);
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_cooperation_eyebrow', '合作方式 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_cooperation_title', '合作方式 · 大标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_products_eyebrow', '产品分类 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_products_title', '产品分类 · 大标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_oem_eyebrow', '定制服务 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_process_eyebrow', '制造流程 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_process_title', '制造流程 · 大标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_stock_eyebrow', '现货供应 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_stock_title', '现货供应 · 大标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_industries_eyebrow', '服务行业 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_industries_title', '服务行业 · 大标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_contact_eyebrow', '询盘表单 · 小标签' );
	fuhao_customize_text( $wp_customize, 'fuhao_home_titles', 'fuhao_sec_contact_title', '询盘表单 · 大标题' );

	/* 工厂数据 */
	$wp_customize->add_section(
		'fuhao_stats',
		array(
			'title'       => __( '工厂数据', 'fuhao' ),
			'panel'       => 'fuhao_panel',
			'description' => '显示在首页主视觉底部；公司成立时间与工厂经验请分开表述。',
		)
	);
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat1_number', '数据 1' );
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat1_label', '说明 1' );
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat2_number', '数据 2' );
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat2_label', '说明 2' );
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat3_number', '数据 3' );
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat3_label', '说明 3' );
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat4_number', '数据 4' );
	fuhao_customize_text( $wp_customize, 'fuhao_stats', 'fuhao_stat4_label', '说明 4' );

	/* 工厂实力 */
	$wp_customize->add_section(
		'fuhao_about',
		array(
			'title' => __( '工厂实力', 'fuhao' ),
			'panel' => 'fuhao_panel',
		)
	);
	fuhao_customize_text( $wp_customize, 'fuhao_about', 'fuhao_about_eyebrow', '小标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_about', 'fuhao_about_title', '主标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_about', 'fuhao_about_text', '介绍文字', 'textarea', 'sanitize_textarea_field' );
	fuhao_customize_image( $wp_customize, 'fuhao_about', 'fuhao_about_image', '工厂照片', '建议使用竖版或方形照片（如厂房、车间、质检场景）。' );
	fuhao_customize_text( $wp_customize, 'fuhao_about', 'fuhao_about_point1', '优势 1' );
	fuhao_customize_text( $wp_customize, 'fuhao_about', 'fuhao_about_point2', '优势 2' );
	fuhao_customize_text( $wp_customize, 'fuhao_about', 'fuhao_about_point3', '优势 3' );
	fuhao_customize_text( $wp_customize, 'fuhao_about', 'fuhao_about_point4', '优势 4' );

	/* OEM / ODM */
	$wp_customize->add_section(
		'fuhao_oem',
		array(
			'title' => __( 'OEM / ODM 板块', 'fuhao' ),
			'panel' => 'fuhao_panel',
		)
	);
	fuhao_customize_text( $wp_customize, 'fuhao_oem', 'fuhao_oem_title', '标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_oem', 'fuhao_oem_text', '介绍文字', 'textarea', 'sanitize_textarea_field' );

	/* 联系方式 */
	$wp_customize->add_section(
		'fuhao_contact',
		array(
			'title' => __( '联系方式与询盘', 'fuhao' ),
			'panel' => 'fuhao_panel',
		)
	);
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_person1', '联系人 1 姓名' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_phone', '联系人 1 电话' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_person2', '联系人 2 姓名' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_phone2', '联系人 2 电话' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_person3', '联系人 3 姓名' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_phone3', '联系人 3 电话' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_email', '商务邮箱（主）', 'email', 'sanitize_email' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_email2', '备用邮箱 1', 'email', 'sanitize_email' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_email3', '备用邮箱 2', 'email', 'sanitize_email' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_whatsapp', 'WhatsApp 号码' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_wechat', '微信号（可选）' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_address', '工厂地址', 'textarea', 'sanitize_textarea_field' );
	fuhao_customize_text( $wp_customize, 'fuhao_contact', 'fuhao_contact_hours', '工作时间' );
	fuhao_customize_text(
		$wp_customize,
		'fuhao_contact',
		'fuhao_inquiry_email',
		'询盘接收邮箱',
		'email',
		'sanitize_email',
		'留空则发送到 WordPress 管理员邮箱：' . get_option( 'admin_email' ) . '。建议同时安装 WP Mail SMTP 插件，保证邮件送达。'
	);

	/* 页脚 */
	$wp_customize->add_section(
		'fuhao_footer',
		array(
			'title' => __( '页脚信息', 'fuhao' ),
			'panel' => 'fuhao_panel',
		)
	);
	fuhao_customize_text( $wp_customize, 'fuhao_footer', 'fuhao_footer_desc', '公司简介', 'textarea', 'sanitize_textarea_field' );
	fuhao_customize_text( $wp_customize, 'fuhao_footer', 'fuhao_footer_icp', 'ICP 备案号', 'text', 'sanitize_text_field', '如暂无备案号可留空。' );
}
add_action( 'customize_register', 'fuhao_customize_register' );
