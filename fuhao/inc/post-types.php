<?php
/**
 * 产品文章类型与产品分类。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 注册产品与产品分类。
 */
function fuhao_register_post_types() {
	register_post_type(
		'product',
		array(
			'labels'       => array(
				'name'          => __( '产品', 'fuhao' ),
				'singular_name' => __( '产品', 'fuhao' ),
				'add_new'       => __( '添加产品', 'fuhao' ),
				'add_new_item'  => __( '添加新产品', 'fuhao' ),
				'edit_item'     => __( '编辑产品', 'fuhao' ),
				'new_item'      => __( '新产品', 'fuhao' ),
				'view_item'     => __( '查看产品', 'fuhao' ),
				'search_items'  => __( '搜索产品', 'fuhao' ),
				'not_found'     => __( '暂无产品', 'fuhao' ),
				'menu_name'     => __( '产品', 'fuhao' ),
			),
			'description'  => '上饶市富豪箱包有限公司产品中心，展示收纳包、背包、旅行包等箱包产品与定制方案，支持现货、现货定制与批量订单。',
			'public'       => true,
			'menu_icon'    => 'dashicons-products',
			'menu_position' => 20,
			'has_archive'  => 'products',
			'rewrite'      => array(
				'slug'       => 'products',
				'with_front' => false,
			),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'product_cat',
		'product',
		array(
			'labels'            => array(
				'name'          => __( '产品分类', 'fuhao' ),
				'singular_name' => __( '产品分类', 'fuhao' ),
				'add_new_item'  => __( '添加产品分类', 'fuhao' ),
				'edit_item'     => __( '编辑产品分类', 'fuhao' ),
				'menu_name'     => __( '产品分类', 'fuhao' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'product-category',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'product_tag',
		'product',
		array(
			'labels'            => array(
				'name'          => __( '用途标签', 'fuhao' ),
				'singular_name' => __( '用途标签', 'fuhao' ),
				'add_new_item'  => __( '添加用途标签', 'fuhao' ),
				'edit_item'     => __( '编辑用途标签', 'fuhao' ),
				'menu_name'     => __( '用途标签', 'fuhao' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'product-tag',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'fuhao_register_post_types' );

/**
 * 供货类型：只表示合作方式，不表示库存数量。
 *
 * @return array
 */
function fuhao_supply_types() {
	return array(
		'stock'        => __( '现货', 'fuhao' ),
		'stock_custom' => __( '现货定制', 'fuhao' ),
		'bulk'         => __( '批量定制', 'fuhao' ),
	);
}

/**
 * 读取产品的供货类型。
 *
 * @param int $post_id 产品 ID。
 * @return array
 */
function fuhao_get_supply_type( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$type    = get_post_meta( $post_id, '_fuhao_supply_type', true );
	$types   = fuhao_supply_types();

	if ( isset( $types[ $type ] ) ) {
		return array(
			'key'   => $type,
			'label' => $types[ $type ],
		);
	}

	return array(
		'key'   => '',
		'label' => '',
	);
}

/**
 * 产品编辑页的供货类型选项。
 */
function fuhao_add_product_meta_box() {
	add_meta_box(
		'fuhao_product_supply',
		__( '供货类型', 'fuhao' ),
		'fuhao_render_product_supply_box',
		'product',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'fuhao_add_product_meta_box' );

/**
 * 渲染供货类型选择框。
 *
 * @param WP_Post $post 当前产品。
 */
function fuhao_render_product_supply_box( $post ) {
	wp_nonce_field( 'fuhao_product_supply', 'fuhao_product_supply_nonce' );

	$current = get_post_meta( $post->ID, '_fuhao_supply_type', true );
	$types   = fuhao_supply_types();

	echo '<select name="fuhao_supply_type" id="fuhao_supply_type" style="width:100%">';
	echo '<option value="">' . esc_html__( '未设置', 'fuhao' ) . '</option>';
	foreach ( $types as $key => $label ) {
		printf(
			'<option value="%1$s"%2$s>%3$s</option>',
			esc_attr( $key ),
			selected( $current, $key, false ),
			esc_html( $label )
		);
	}
	echo '</select>';
	echo '<p class="description">' . esc_html__( '只表示合作方式，不表示库存数量。', 'fuhao' ) . '</p>';
}

/**
 * 保存供货类型。
 *
 * @param int $post_id 产品 ID。
 */
function fuhao_save_product_supply( $post_id ) {
	if ( ! isset( $_POST['fuhao_product_supply_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['fuhao_product_supply_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'fuhao_product_supply' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$type  = isset( $_POST['fuhao_supply_type'] ) ? sanitize_key( wp_unslash( $_POST['fuhao_supply_type'] ) ) : '';
	$types = fuhao_supply_types();

	if ( ! array_key_exists( $type, $types ) ) {
		$type = '';
	}

	if ( '' !== $type ) {
		update_post_meta( $post_id, '_fuhao_supply_type', $type );
	} else {
		delete_post_meta( $post_id, '_fuhao_supply_type' );
	}
}
add_action( 'save_post_product', 'fuhao_save_product_supply' );

/**
 * 产品列表显示供货类型列。
 *
 * @param array $columns 列。
 * @return array
 */
function fuhao_product_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['fuhao_supply'] = __( '供货类型', 'fuhao' );
		}
	}

	return $new;
}
add_filter( 'manage_product_posts_columns', 'fuhao_product_columns' );

/**
 * 输出供货类型列内容。
 *
 * @param string $column  列名。
 * @param int    $post_id 产品 ID。
 */
function fuhao_product_column_content( $column, $post_id ) {
	if ( 'fuhao_supply' !== $column ) {
		return;
	}

	$type = fuhao_get_supply_type( $post_id );

	echo $type['label'] ? esc_html( $type['label'] ) : '—';
}
add_action( 'manage_product_posts_custom_column', 'fuhao_product_column_content', 10, 2 );

/**
 * 首次启用主题时创建画册里的 7 个产品分类（只执行一次）。
 */
function fuhao_maybe_add_default_terms() {
	if ( get_option( 'fuhao_default_terms_v2' ) ) {
		return;
	}

	$terms = array(
		'折叠双肩包'   => array( 'foldable-backpack', '可折叠收纳，适合旅行、促销礼品与日常使用。' ),
		'化妆包收纳'   => array( 'cosmetic-bag', '化妆包、洗漱包与随身收纳袋。' ),
		'鞋子收纳'     => array( 'shoe-storage', '鞋袋、鞋盒与旅行鞋类收纳。' ),
		'棉被衣物收纳' => array( 'quilt-clothing-storage', '棉被袋、衣物收纳袋与换季整理用品。' ),
		'数码收纳'     => array( 'digital-storage', '数码配件、线材与电子产品收纳包。' ),
		'学生书包'     => array( 'student-backpack', '学生双肩包、补习袋与校园收纳。' ),
		'多巴胺潮流'   => array( 'dopamine-fashion', '高饱和配色与潮流款式的包袋。' ),
	);

	foreach ( $terms as $name => $term ) {
		if ( ! term_exists( $term[0], 'product_cat' ) ) {
			wp_insert_term(
				$name,
				'product_cat',
				array(
					'slug'        => $term[0],
					'description' => $term[1],
				)
			);
		}
	}

	update_option( 'fuhao_default_terms_v2', 1 );
}
add_action( 'init', 'fuhao_maybe_add_default_terms', 20 );

/**
 * 启用主题后刷新固定链接，保证 /products/ 可访问。
 */
function fuhao_flush_rewrite_on_switch() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'fuhao_flush_rewrite_on_switch' );

/**
 * 产品列表排序与每页数量。
 *
 * @param WP_Query $query 当前查询。
 */
function fuhao_product_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'product' ) || $query->is_tax( 'product_cat' ) ) {
		$query->set( 'posts_per_page', 12 );
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			)
		);
	}
}
add_action( 'pre_get_posts', 'fuhao_product_archive_query' );
