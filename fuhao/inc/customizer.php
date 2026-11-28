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
 * 添加视频设置与控制。
 *
 * 视频以附件 ID 保存，前台再取 URL；视频留空时继续使用图片。
 */
function fuhao_customize_video( $wp_customize, $section, $id, $label, $description = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			$id,
			array(
				'label'       => $label,
				'section'     => $section,
				'mime_type'   => 'video',
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
			'title'       => __( '富豪主题设置', 'fuhao' ),
			'description' => __( '公司信息与工厂数据分开填写：公司专注箱包销售与服务，工厂拥有 20+ 年箱包制造经验。', 'fuhao' ),
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
	fuhao_customize_text( $wp_customize, 'fuhao_brand', 'fuhao_brand_name', '网站品牌名', 'text', 'sanitize_text_field', '显示在页头与页脚，如“富豪箱包”。' );
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
			'title'       => __( '首页主视觉', 'fuhao' ),
			'description' => __( '每张海报可以是图片、GIF 或视频，标题和按钮请直接做在画面里，主题不再叠加文字。图片建议 1920×1080；视频建议 MP4、5–10 秒、静音、2–5MB。没有图片也没有视频的海报位会自动跳过。', 'fuhao' ),
			'panel'       => 'fuhao_panel',
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

	$fuhao_hero_slides = array(
		1 => array( 'fuhao_hero_image', 'fuhao_hero_mobile', 'fuhao_hero_link', 'fuhao_hero_alt', 'fuhao_hero_video', 'fuhao_hero_video_mobile' ),
		2 => array( 'fuhao_slide2_image', 'fuhao_slide2_mobile', 'fuhao_slide2_link', 'fuhao_slide2_alt', 'fuhao_slide2_video', 'fuhao_slide2_video_mobile' ),
		3 => array( 'fuhao_slide3_image', 'fuhao_slide3_mobile', 'fuhao_slide3_link', 'fuhao_slide3_alt', 'fuhao_slide3_video', 'fuhao_slide3_video_mobile' ),
		4 => array( 'fuhao_slide4_image', 'fuhao_slide4_mobile', 'fuhao_slide4_link', 'fuhao_slide4_alt', 'fuhao_slide4_video', 'fuhao_slide4_video_mobile' ),
		5 => array( 'fuhao_slide5_image', 'fuhao_slide5_mobile', 'fuhao_slide5_link', 'fuhao_slide5_alt', 'fuhao_slide5_video', 'fuhao_slide5_video_mobile' ),
	);

	foreach ( $fuhao_hero_slides as $fuhao_slide => $fuhao_fields ) {
		$fuhao_label = sprintf( '海报 %d · ', $fuhao_slide );

		fuhao_customize_image( $wp_customize, 'fuhao_hero', $fuhao_fields[0], $fuhao_label . '电脑图片 / 视频封面', '整张图直接显示；填了视频时，这张图作为视频封面。建议 1920×1080；GIF 控制在 2MB 内。' );
		fuhao_customize_image( $wp_customize, 'fuhao_hero', $fuhao_fields[1], $fuhao_label . '手机图片 / 视频封面', '可选。手机竖图或方图（建议 1080×1350）；不填则手机沿用电脑图。' );
		fuhao_customize_text(
			$wp_customize,
			'fuhao_hero',
			$fuhao_fields[2],
			$fuhao_label . '点击链接',
			'text',
			'fuhao_sanitize_link',
			'整张海报可点击；可粘贴产品页或任意网址，留空则不可点。'
		);
		fuhao_customize_text(
			$wp_customize,
			'fuhao_hero',
			$fuhao_fields[3],
			$fuhao_label . '图片替代文字',
			'text',
			'sanitize_text_field',
			'用一句话描述图片或视频内容，供搜索引擎和读屏软件使用；可留空。'
		);
		fuhao_customize_video(
			$wp_customize,
			'fuhao_hero',
			$fuhao_fields[4],
			$fuhao_label . '电脑视频（可选）',
			'建议 MP4（H.264），5–10 秒，静音，2–5MB。留空则显示电脑图片。'
		);
		fuhao_customize_video(
			$wp_customize,
			'fuhao_hero',
			$fuhao_fields[5],
			$fuhao_label . '手机视频（可选）',
			'可选。建议竖版或方版，5–10 秒，静音，2–5MB；不填则手机沿用电脑视频。'
		);
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

	/* 工厂页视频 */
	$wp_customize->add_section(
		'fuhao_factory_video',
		array(
			'title'       => __( '工厂页视频', 'fuhao' ),
			'panel'       => 'fuhao_panel',
			'description' => __( '只显示在“工厂实力”页面；视频留空则整段隐藏，不影响现有版面。', 'fuhao' ),
		)
	);
	fuhao_customize_video(
		$wp_customize,
		'fuhao_factory_video',
		'fuhao_factory_video',
		'工厂视频',
		'建议 MP4（H.264），30–60 秒，720p，10–20MB；点击播放，不自动播放声音。'
	);
	fuhao_customize_image(
		$wp_customize,
		'fuhao_factory_video',
		'fuhao_factory_video_poster',
		'视频封面',
		'建议 1920×1080；不填则视频显示第一帧。'
	);
	fuhao_customize_text( $wp_customize, 'fuhao_factory_video', 'fuhao_factory_video_title', '视频标题' );
	fuhao_customize_text( $wp_customize, 'fuhao_factory_video', 'fuhao_factory_video_text', '视频说明', 'textarea', 'sanitize_textarea_field' );

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
