<?php
/**
 * 能力注册与执行回调。
 *
 * @package FuhaoAbilities
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_abilities_api_categories_init', 'fuhao_abilities_register_category' );

/**
 * 注册能力分类。分类必须先于能力注册，否则能力会静默注册失败。
 */
function fuhao_abilities_register_category() {
	if ( ! function_exists( 'wp_register_ability_category' ) ) {
		return;
	}

	wp_register_ability_category(
		'fuhao',
		array(
			'label'       => '豪富站点',
			'description' => '豪富箱包官网的页面、产品、分类、媒体、菜单、站点设置与主题选项操作。',
		)
	);
}

add_action( 'wp_abilities_api_init', 'fuhao_abilities_register' );

/**
 * 注册能力，并为未声明 output_schema 的能力补默认值（官方要求必填）。
 *
 * @param string $name 能力名。
 * @param array  $args 能力参数。
 * @return WP_Ability|null
 */
function fuhao_register_ability( $name, $args ) {
	if ( ! isset( $args['output_schema'] ) ) {
		$args['output_schema'] = array( 'type' => 'object' );
	}

	return wp_register_ability( $name, $args );
}

/**
 * 注册全部能力。
 */
function fuhao_abilities_register() {
	if ( ! function_exists( 'wp_register_ability' ) ) {
		return;
	}

	fuhao_register_ability(
		'fuhao/list-pages',
		array(
			'label'              => '列出页面',
			'description'        => '列出站点页面，可按状态、父页面、关键词筛选，返回 ID、标题、别名、状态与链接。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'status'   => array(
						'type'        => 'string',
						'enum'        => array( 'any', 'publish', 'draft', 'pending', 'private', 'trash' ),
						'description' => '页面状态，默认 any。',
					),
					'parent'   => array(
						'type'        => 'integer',
						'description' => '只返回该父页面下的子页面。',
					),
					'search'   => array(
						'type'        => 'string',
						'description' => '按标题搜索。',
					),
					'per_page' => array(
						'type'        => 'integer',
						'minimum'     => 1,
						'maximum'     => 100,
						'description' => '返回条数，默认 50。',
					),
				),
			),
			'execute_callback'   => 'fuhao_ability_list_pages',
			'permission_callback' => function () {
				return current_user_can( 'edit_pages' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'readonly'    => true,
					'idempotent'  => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/get-page',
		array(
			'label'              => '读取页面',
			'description'        => '按 ID 读取单个页面，返回标题、正文（原始区块内容）、摘要、别名、父页面、状态与链接。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'id' => array(
						'type'        => 'integer',
						'description' => '页面 ID。',
					),
				),
				'required'   => array( 'id' ),
			),
			'execute_callback'   => 'fuhao_ability_get_page',
			'permission_callback' => function () {
				return current_user_can( 'edit_pages' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'readonly'   => true,
					'idempotent' => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/create-page',
		array(
			'label'              => '新建页面',
			'description'        => '新建一个页面，支持标题、正文、摘要、别名、父页面、状态与排序。默认保存为草稿。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'title'      => array(
						'type'        => 'string',
						'description' => '页面标题，必填。',
					),
					'content'    => array(
						'type'        => 'string',
						'description' => '页面正文，区块编辑器 HTML。',
					),
					'excerpt'    => array(
						'type'        => 'string',
						'description' => '页面摘要。',
					),
					'slug'       => array(
						'type'        => 'string',
						'description' => '页面别名（URL 片段）。',
					),
					'parent'     => array(
						'type'        => 'integer',
						'description' => '父页面 ID，0 表示顶级页面。',
					),
					'status'     => array(
						'type'        => 'string',
						'enum'        => array( 'draft', 'pending', 'private', 'publish' ),
						'description' => '页面状态，默认 draft。',
					),
					'menu_order' => array(
						'type'        => 'integer',
						'description' => '排序值。',
					),
				),
				'required'   => array( 'title' ),
			),
			'execute_callback'   => 'fuhao_ability_create_page',
			'permission_callback' => function () {
				return current_user_can( 'edit_pages' );
			},
			'meta'               => fuhao_ability_meta(),
		)
	);

	fuhao_register_ability(
		'fuhao/update-page',
		array(
			'label'              => '更新页面',
			'description'        => '更新指定页面的标题、正文、摘要、别名、父页面、状态或排序，只修改传入的字段。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'id'         => array(
						'type'        => 'integer',
						'description' => '页面 ID。',
					),
					'title'      => array(
						'type'        => 'string',
						'description' => '新标题。',
					),
					'content'    => array(
						'type'        => 'string',
						'description' => '新正文，区块编辑器 HTML。',
					),
					'excerpt'    => array(
						'type'        => 'string',
						'description' => '新摘要。',
					),
					'slug'       => array(
						'type'        => 'string',
						'description' => '新别名。',
					),
					'parent'     => array(
						'type'        => 'integer',
						'description' => '新父页面 ID。',
					),
					'status'     => array(
						'type'        => 'string',
						'enum'        => array( 'draft', 'pending', 'private', 'publish', 'trash' ),
						'description' => '新状态。',
					),
					'menu_order' => array(
						'type'        => 'integer',
						'description' => '新排序值。',
					),
				),
				'required'   => array( 'id' ),
			),
			'execute_callback'   => 'fuhao_ability_update_page',
			'permission_callback' => function () {
				return current_user_can( 'edit_pages' );
			},
			'meta'               => fuhao_ability_meta( array( 'idempotent' => true ) ),
		)
	);

	fuhao_register_ability(
		'fuhao/delete-page',
		array(
			'label'              => '删除页面',
			'description'        => '把页面移入回收站；force 为 true 时彻底删除。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'id'    => array(
						'type'        => 'integer',
						'description' => '页面 ID。',
					),
					'force' => array(
						'type'        => 'boolean',
						'description' => 'true 表示彻底删除，默认 false（移入回收站）。',
					),
				),
				'required'   => array( 'id' ),
			),
			'execute_callback'   => 'fuhao_ability_delete_page',
			'permission_callback' => function () {
				return current_user_can( 'delete_pages' );
			},
			'meta'               => fuhao_ability_meta( array( 'destructive' => true ) ),
		)
	);

	fuhao_register_ability(
		'fuhao/list-menus',
		array(
			'label'              => '列出菜单',
			'description'        => '列出所有菜单、菜单位置绑定关系与菜单条目。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(),
			),
			'execute_callback'   => 'fuhao_ability_list_menus',
			'permission_callback' => function () {
				return current_user_can( 'edit_theme_options' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'readonly'   => true,
					'idempotent' => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/update-menu-item',
		array(
			'label'              => '更新菜单条目',
			'description'        => '更新或新建菜单条目。id 为 0 时新建；可改标题、链接、关联页面、排序、父级与状态。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'menu_id'   => array(
						'type'        => 'integer',
						'description' => '菜单 ID。',
					),
					'id'        => array(
						'type'        => 'integer',
						'description' => '菜单条目 ID，0 表示新建，默认 0。',
					),
					'title'     => array(
						'type'        => 'string',
						'description' => '显示名称。',
					),
					'url'       => array(
						'type'        => 'string',
						'description' => '自定义链接。',
					),
					'object_id' => array(
						'type'        => 'integer',
						'description' => '关联对象 ID，例如页面 ID。',
					),
					'object'    => array(
						'type'        => 'string',
						'description' => '对象类型，页面为 page。',
					),
					'type'      => array(
						'type'        => 'string',
						'description' => '条目类型，post_type 或 custom。',
					),
					'parent'    => array(
						'type'        => 'integer',
						'description' => '父级菜单条目 ID。',
					),
					'position'  => array(
						'type'        => 'integer',
						'description' => '排序位置。',
					),
					'status'    => array(
						'type'        => 'string',
						'enum'        => array( 'publish', 'draft' ),
						'description' => '条目状态，默认 publish。',
					),
				),
				'required'   => array( 'menu_id' ),
			),
			'execute_callback'   => 'fuhao_ability_update_menu_item',
			'permission_callback' => function () {
				return current_user_can( 'edit_theme_options' );
			},
			'meta'               => fuhao_ability_meta( array( 'idempotent' => true ) ),
		)
	);

	fuhao_register_ability(
		'fuhao/get-settings',
		array(
			'label'              => '读取站点设置',
			'description'        => '读取站点标题、副标题、首页显示方式、静态首页与文章页设置。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(),
			),
			'execute_callback'   => 'fuhao_ability_get_settings',
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'readonly'   => true,
					'idempotent' => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/update-settings',
		array(
			'label'              => '更新站点设置',
			'description'        => '更新站点标题、副标题、首页显示方式、静态首页与文章页设置。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'title'         => array(
						'type'        => 'string',
						'description' => '站点标题。',
					),
					'description'   => array(
						'type'        => 'string',
						'description' => '站点副标题。',
					),
					'show_on_front' => array(
						'type'        => 'string',
						'enum'        => array( 'posts', 'page' ),
						'description' => '首页显示方式。',
					),
					'page_on_front' => array(
						'type'        => 'integer',
						'description' => '静态首页的页面 ID。',
					),
					'page_for_posts' => array(
						'type'        => 'integer',
						'description' => '文章列表页的页面 ID。',
					),
				),
			),
			'execute_callback'   => 'fuhao_ability_update_settings',
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			},
			'meta'               => fuhao_ability_meta( array( 'idempotent' => true ) ),
		)
	);

	fuhao_register_ability(
		'fuhao/list-plugins',
		array(
			'label'              => '列出插件',
			'description'        => '列出已安装插件及其启用状态。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(),
			),
			'execute_callback'   => 'fuhao_ability_list_plugins',
			'permission_callback' => function () {
				return current_user_can( 'activate_plugins' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'readonly'   => true,
					'idempotent' => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/get-theme-options',
		array(
			'label'              => '读取主题设置',
			'description'        => '读取富豪主题 Customizer 中保存的设置项（fuhao_ 前缀）。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(),
			),
			'execute_callback'   => 'fuhao_ability_get_theme_options',
			'permission_callback' => function () {
				return current_user_can( 'edit_theme_options' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'readonly'   => true,
					'idempotent' => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/set-theme-option',
		array(
			'label'              => '更新主题设置',
			'description'        => '更新富豪主题 Customizer 中的单个设置项，键名不带 fuhao_ 前缀。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'key'   => array(
						'type'        => 'string',
						'description' => '设置键名，例如 brand_name、contact_phone。',
					),
					'value' => array(
						'type'        => 'string',
						'description' => '新值。',
					),
				),
				'required'   => array( 'key', 'value' ),
			),
			'execute_callback'   => 'fuhao_ability_set_theme_option',
			'permission_callback' => function () {
				return current_user_can( 'edit_theme_options' );
			},
			'meta'               => fuhao_ability_meta( array( 'idempotent' => true ) ),
		)
	);
}

/**
 * 生成能力的 meta 配置：默认公开，并按需覆盖行为提示。
 *
 * @param array $annotations 行为提示覆盖项。
 * @return array
 */
function fuhao_ability_meta( $annotations = array() ) {
	return array(
		'public'       => true,
		'show_in_rest' => true,
		'mcp'          => array(
			'public' => true,
			'type'   => 'tool',
		),
		'annotations' => array_merge(
			array(
				'readonly'      => false,
				'destructive'   => false,
				'idempotent'    => false,
				'openWorldHint' => false,
			),
			$annotations
		),
	);
}

/**
 * 构造错误返回。
 *
 * @param string $code    错误代码。
 * @param string $message 错误说明。
 * @param int    $status  HTTP 状态码。
 * @return WP_Error
 */
function fuhao_abilities_error( $code, $message, $status = 400 ) {
	return new WP_Error( $code, $message, array( 'status' => $status ) );
}

/**
 * 页面数据摘要。
 *
 * @param WP_Post $post         页面对象。
 * @param bool    $with_content 是否包含正文与摘要。
 * @return array
 */
function fuhao_abilities_page_data( $post, $with_content = false ) {
	$data = array(
		'id'         => (int) $post->ID,
		'title'      => get_the_title( $post ),
		'slug'       => $post->post_name,
		'status'     => $post->post_status,
		'parent'     => (int) $post->post_parent,
		'menu_order' => (int) $post->menu_order,
		'link'       => get_permalink( $post ),
		'modified'   => $post->post_modified_gmt,
	);

	if ( $with_content ) {
		$data['content'] = $post->post_content;
		$data['excerpt'] = $post->post_excerpt;
	}

	return $data;
}

/**
 * 校验并取回页面对象。
 *
 * @param int $id 页面 ID。
 * @return WP_Post|WP_Error
 */
function fuhao_abilities_require_page( $id ) {
	$post = get_post( $id );

	if ( ! $post || 'page' !== $post->post_type ) {
		return fuhao_abilities_error( 'fuhao_page_not_found', '页面不存在。', 404 );
	}

	return $post;
}

/**
 * 列出页面。
 *
 * @param array $input 输入参数。
 * @return array
 */
function fuhao_ability_list_pages( $input ) {
	$per_page = isset( $input['per_page'] ) ? (int) $input['per_page'] : 50;
	$per_page = max( 1, min( 100, $per_page ) );

	$args = array(
		'post_type'   => 'page',
		'post_status' => 'any',
		'numberposts' => $per_page,
		'orderby'     => 'menu_order title',
		'order'       => 'ASC',
	);

	if ( ! empty( $input['status'] ) && 'any' !== $input['status'] ) {
		$args['post_status'] = sanitize_key( $input['status'] );
	}

	if ( isset( $input['parent'] ) ) {
		$args['post_parent'] = (int) $input['parent'];
	}

	if ( ! empty( $input['search'] ) ) {
		$args['s'] = sanitize_text_field( $input['search'] );
	}

	$posts = get_posts( $args );

	return array(
		'count' => count( $posts ),
		'pages' => array_map( 'fuhao_abilities_page_data', $posts ),
	);
}

/**
 * 读取单个页面。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_get_page( $input ) {
	$post = fuhao_abilities_require_page( (int) $input['id'] );

	if ( is_wp_error( $post ) ) {
		return $post;
	}

	return fuhao_abilities_page_data( $post, true );
}

/**
 * 新建页面。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_create_page( $input ) {
	$title = isset( $input['title'] ) ? sanitize_text_field( $input['title'] ) : '';

	if ( '' === $title ) {
		return fuhao_abilities_error( 'fuhao_title_required', '页面标题不能为空。' );
	}

	$status = isset( $input['status'] ) ? sanitize_key( $input['status'] ) : 'draft';

	if ( 'publish' === $status && ! current_user_can( 'publish_pages' ) ) {
		return fuhao_abilities_error( 'fuhao_cannot_publish', '当前用户没有发布页面的权限。', 403 );
	}

	$data = array(
		'post_type'   => 'page',
		'post_title'  => $title,
		'post_status' => $status,
	);

	if ( isset( $input['content'] ) ) {
		$data['post_content'] = wp_kses_post( $input['content'] );
	}

	if ( isset( $input['excerpt'] ) ) {
		$data['post_excerpt'] = sanitize_text_field( $input['excerpt'] );
	}

	if ( isset( $input['slug'] ) ) {
		$data['post_name'] = sanitize_title( $input['slug'] );
	}

	if ( isset( $input['parent'] ) ) {
		$data['post_parent'] = (int) $input['parent'];
	}

	if ( isset( $input['menu_order'] ) ) {
		$data['menu_order'] = (int) $input['menu_order'];
	}

	$result = wp_insert_post( $data, true );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return fuhao_abilities_page_data( get_post( $result ), true );
}

/**
 * 更新页面。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_update_page( $input ) {
	$post = fuhao_abilities_require_page( (int) $input['id'] );

	if ( is_wp_error( $post ) ) {
		return $post;
	}

	if ( isset( $input['status'] ) && 'publish' === $input['status'] && ! current_user_can( 'publish_pages' ) ) {
		return fuhao_abilities_error( 'fuhao_cannot_publish', '当前用户没有发布页面的权限。', 403 );
	}

	$data = array( 'ID' => (int) $post->ID );

	if ( isset( $input['title'] ) ) {
		$data['post_title'] = sanitize_text_field( $input['title'] );
	}

	if ( isset( $input['content'] ) ) {
		$data['post_content'] = wp_kses_post( $input['content'] );
	}

	if ( isset( $input['excerpt'] ) ) {
		$data['post_excerpt'] = sanitize_text_field( $input['excerpt'] );
	}

	if ( isset( $input['slug'] ) ) {
		$data['post_name'] = sanitize_title( $input['slug'] );
	}

	if ( isset( $input['parent'] ) ) {
		$data['post_parent'] = (int) $input['parent'];
	}

	if ( isset( $input['status'] ) ) {
		$data['post_status'] = sanitize_key( $input['status'] );
	}

	if ( isset( $input['menu_order'] ) ) {
		$data['menu_order'] = (int) $input['menu_order'];
	}

	$result = wp_update_post( $data, true );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return fuhao_abilities_page_data( get_post( $result ), true );
}

/**
 * 删除页面。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_delete_page( $input ) {
	$post = fuhao_abilities_require_page( (int) $input['id'] );

	if ( is_wp_error( $post ) ) {
		return $post;
	}

	$force = ! empty( $input['force'] );
	$done  = $force ? wp_delete_post( $post->ID, true ) : wp_trash_post( $post->ID );

	if ( ! $done ) {
		return fuhao_abilities_error( 'fuhao_delete_failed', '删除页面失败。', 500 );
	}

	return array(
		'id'      => (int) $post->ID,
		'deleted' => true,
		'force'   => $force,
	);
}

/**
 * 列出菜单、位置绑定与条目。
 *
 * @return array
 */
function fuhao_ability_list_menus() {
	$menus     = wp_get_nav_menus();
	$locations = get_nav_menu_locations();
	$result    = array();

	foreach ( $menus as $menu ) {
		$items     = wp_get_nav_menu_items( $menu->term_id );
		$item_data = array();

		foreach ( (array) $items as $item ) {
			$item_data[] = array(
				'id'          => (int) $item->ID,
				'title'       => $item->title,
				'url'         => $item->url,
				'status'      => $item->post_status,
				'position'    => (int) $item->menu_order,
				'parent'      => (int) $item->menu_item_parent,
				'object'      => $item->object,
				'object_id'   => (int) $item->object_id,
				'type'        => $item->type,
			);
		}

		$menu_locations = array();
		foreach ( $locations as $location => $menu_id ) {
			if ( (int) $menu_id === (int) $menu->term_id ) {
				$menu_locations[] = $location;
			}
		}

		$result[] = array(
			'id'        => (int) $menu->term_id,
			'name'      => $menu->name,
			'slug'      => $menu->slug,
			'locations' => $menu_locations,
			'items'     => $item_data,
		);
	}

	return array( 'menus' => $result );
}

/**
 * 更新或新建菜单条目。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_update_menu_item( $input ) {
	$menu_id = isset( $input['menu_id'] ) ? (int) $input['menu_id'] : 0;
	$menu    = wp_get_nav_menu_object( $menu_id );

	if ( ! $menu ) {
		return fuhao_abilities_error( 'fuhao_menu_not_found', '菜单不存在。', 404 );
	}

	$item_id   = isset( $input['id'] ) ? (int) $input['id'] : 0;
	$object_id = isset( $input['object_id'] ) ? (int) $input['object_id'] : 0;
	$type      = isset( $input['type'] ) ? sanitize_key( $input['type'] ) : ( $object_id > 0 ? 'post_type' : 'custom' );
	$object    = isset( $input['object'] ) ? sanitize_key( $input['object'] ) : ( $object_id > 0 ? 'page' : '' );

	$args = array(
		'menu-item-title'     => isset( $input['title'] ) ? sanitize_text_field( $input['title'] ) : '',
		'menu-item-url'       => isset( $input['url'] ) ? esc_url_raw( $input['url'] ) : '',
		'menu-item-object-id' => $object_id,
		'menu-item-object'    => $object,
		'menu-item-type'      => $type,
		'menu-item-parent-id' => isset( $input['parent'] ) ? (int) $input['parent'] : 0,
		'menu-item-position'  => isset( $input['position'] ) ? (int) $input['position'] : 0,
		'menu-item-status'    => isset( $input['status'] ) ? sanitize_key( $input['status'] ) : 'publish',
	);

	$result = wp_update_nav_menu_item( $menu_id, $item_id, $args );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return array(
		'id'      => (int) $result,
		'menu_id' => (int) $menu->term_id,
		'created' => 0 === $item_id,
	);
}

/**
 * 读取站点设置。
 *
 * @return array
 */
function fuhao_ability_get_settings() {
	return array(
		'title'          => get_option( 'blogname' ),
		'description'    => get_option( 'blogdescription' ),
		'show_on_front'  => get_option( 'show_on_front' ),
		'page_on_front'  => (int) get_option( 'page_on_front' ),
		'page_for_posts' => (int) get_option( 'page_for_posts' ),
		'timezone'       => get_option( 'timezone_string' ),
	);
}

/**
 * 更新站点设置。
 *
 * @param array $input 输入参数。
 * @return array
 */
function fuhao_ability_update_settings( $input ) {
	if ( isset( $input['title'] ) ) {
		update_option( 'blogname', sanitize_text_field( $input['title'] ) );
	}

	if ( isset( $input['description'] ) ) {
		update_option( 'blogdescription', sanitize_text_field( $input['description'] ) );
	}

	if ( isset( $input['show_on_front'] ) && in_array( $input['show_on_front'], array( 'posts', 'page' ), true ) ) {
		update_option( 'show_on_front', $input['show_on_front'] );
	}

	if ( isset( $input['page_on_front'] ) ) {
		update_option( 'page_on_front', (int) $input['page_on_front'] );
	}

	if ( isset( $input['page_for_posts'] ) ) {
		update_option( 'page_for_posts', (int) $input['page_for_posts'] );
	}

	return fuhao_ability_get_settings();
}

/**
 * 列出插件。
 *
 * @return array
 */
function fuhao_ability_list_plugins() {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$all     = get_plugins();
	$active  = (array) get_option( 'active_plugins', array() );
	$plugins = array();

	foreach ( $all as $file => $data ) {
		$plugins[] = array(
			'plugin'  => $file,
			'name'    => $data['Name'],
			'version' => $data['Version'],
			'active'  => in_array( $file, $active, true ),
		);
	}

	return array( 'plugins' => $plugins );
}

/**
 * 读取富豪主题设置项。
 *
 * @return array
 */
function fuhao_ability_get_theme_options() {
	$mods   = get_theme_mods();
	$result = array();

	foreach ( (array) $mods as $key => $value ) {
		if ( 0 === strpos( $key, 'fuhao_' ) ) {
			$result[ substr( $key, 6 ) ] = $value;
		}
	}

	return array( 'options' => $result );
}

/**
 * 更新单个富豪主题设置项。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_set_theme_option( $input ) {
	$key = isset( $input['key'] ) ? sanitize_key( $input['key'] ) : '';

	if ( '' === $key ) {
		return fuhao_abilities_error( 'fuhao_option_key_required', '设置键名不能为空。' );
	}

	$value = $input['value'];

	if ( is_array( $value ) || is_object( $value ) ) {
		return fuhao_abilities_error( 'fuhao_option_value_invalid', '设置值必须是字符串。' );
	}

	$value = sanitize_text_field( (string) $value );

	set_theme_mod( 'fuhao_' . $key, $value );

	return array(
		'key'   => $key,
		'value' => get_theme_mod( 'fuhao_' . $key ),
	);
}
