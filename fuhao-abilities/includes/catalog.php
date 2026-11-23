<?php
/**
 * 产品、产品分类/标签与媒体能力。
 *
 * @package FuhaoAbilities
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_abilities_api_init', 'fuhao_abilities_register_catalog' );

/**
 * 注册产品与媒体能力。
 */
function fuhao_abilities_register_catalog() {
	if ( ! function_exists( 'wp_register_ability' ) ) {
		return;
	}

	fuhao_register_ability(
		'fuhao/list-products',
		array(
			'label'              => '列出产品',
			'description'        => '列出产品，可按状态、分类、关键词筛选，返回 ID、标题、别名、分类、主图、1688 链接与供货类型。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'status'     => array(
						'type'        => 'string',
						'enum'        => array( 'any', 'publish', 'draft', 'pending', 'private', 'trash' ),
						'description' => '产品状态，默认 any。',
					),
					'category'   => array(
						'type'        => 'integer',
						'description' => '只返回该 product_cat 分类下的产品。',
					),
					'search'     => array(
						'type'        => 'string',
						'description' => '按标题搜索。',
					),
					'per_page'   => array(
						'type'        => 'integer',
						'minimum'     => 1,
						'maximum'     => 200,
						'description' => '返回条数，默认 100。',
					),
				),
			),
			'execute_callback'   => 'fuhao_ability_list_products',
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
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
		'fuhao/get-product',
		array(
			'label'              => '读取产品',
			'description'        => '按 ID 读取单个产品，返回正文、摘要、分类、标签、主图、列表预览图、图集、1688 链接与供货类型。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'id' => array(
						'type'        => 'integer',
						'description' => '产品 ID。',
					),
				),
				'required'   => array( 'id' ),
			),
			'execute_callback'   => 'fuhao_ability_get_product',
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
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
		'fuhao/create-product',
		array(
			'label'              => '新建产品',
			'description'        => '新建产品，支持标题、正文、摘要、别名、分类、标签、主图、列表预览图、图集、1688 链接、供货类型与排序。默认草稿。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'title'          => array(
						'type'        => 'string',
						'description' => '产品标题，必填。',
					),
					'slug'           => array(
						'type'        => 'string',
						'description' => '产品别名（URL 片段），建议英文。',
					),
					'content'        => array(
						'type'        => 'string',
						'description' => '产品正文，区块编辑器 HTML。',
					),
					'excerpt'        => array(
						'type'        => 'string',
						'description' => '产品摘要。',
					),
					'status'         => array(
						'type'        => 'string',
						'enum'        => array( 'draft', 'pending', 'private', 'publish' ),
						'description' => '产品状态，默认 draft。',
					),
					'categories'     => array(
						'type'        => 'array',
						'items'       => array( 'type' => 'integer' ),
						'description' => 'product_cat 分类 ID 数组。',
					),
					'tags'           => array(
						'type'        => 'array',
						'items'       => array( 'type' => 'integer' ),
						'description' => 'product_tag 标签 ID 数组。',
					),
					'featured_media' => array(
						'type'        => 'integer',
						'description' => '主图附件 ID。',
					),
					'card_image'     => array(
						'type'        => 'integer',
						'description' => '列表预览图附件 ID；0 表示使用主图。',
					),
					'gallery'        => array(
						'type'        => 'array',
						'items'       => array( 'type' => 'integer' ),
						'description' => '产品图集附件 ID 数组，最多 6 张。',
					),
					'url_1688'       => array(
						'type'        => 'string',
						'description' => '1688 商品链接；留空则使用主题设置里的店铺链接。',
					),
					'supply_type'    => array(
						'type'        => 'string',
						'enum'        => array( '', 'stock', 'stock_custom', 'bulk' ),
						'description' => '供货类型。',
					),
					'menu_order'     => array(
						'type'        => 'integer',
						'description' => '排序值。',
					),
				),
				'required'   => array( 'title' ),
			),
			'execute_callback'   => 'fuhao_ability_create_product',
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
			'meta'               => fuhao_ability_meta(),
		)
	);

	fuhao_register_ability(
		'fuhao/update-product',
		array(
			'label'              => '更新产品',
			'description'        => '更新指定产品，只修改传入的字段；分类、标签与图集数组会整体替换。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'id'             => array(
						'type'        => 'integer',
						'description' => '产品 ID。',
					),
					'title'          => array(
						'type'        => 'string',
						'description' => '新标题。',
					),
					'slug'           => array(
						'type'        => 'string',
						'description' => '新别名。',
					),
					'content'        => array(
						'type'        => 'string',
						'description' => '新正文。',
					),
					'excerpt'        => array(
						'type'        => 'string',
						'description' => '新摘要。',
					),
					'status'         => array(
						'type'        => 'string',
						'enum'        => array( 'draft', 'pending', 'private', 'publish', 'trash' ),
						'description' => '新状态。',
					),
					'categories'     => array(
						'type'        => 'array',
						'items'       => array( 'type' => 'integer' ),
						'description' => 'product_cat 分类 ID 数组，整体替换。',
					),
					'tags'           => array(
						'type'        => 'array',
						'items'       => array( 'type' => 'integer' ),
						'description' => 'product_tag 标签 ID 数组，整体替换。',
					),
					'featured_media' => array(
						'type'        => 'integer',
						'description' => '主图附件 ID，0 表示移除。',
					),
					'card_image'     => array(
						'type'        => 'integer',
						'description' => '列表预览图附件 ID；0 表示改为使用主图。',
					),
					'gallery'        => array(
						'type'        => 'array',
						'items'       => array( 'type' => 'integer' ),
						'description' => '产品图集附件 ID 数组，整体替换，最多 6 张；空数组表示清空。',
					),
					'url_1688'       => array(
						'type'        => 'string',
						'description' => '1688 商品链接；空字符串表示清除，回退到店铺链接。',
					),
					'supply_type'    => array(
						'type'        => 'string',
						'enum'        => array( '', 'stock', 'stock_custom', 'bulk' ),
						'description' => '供货类型，空字符串表示清除。',
					),
					'menu_order'     => array(
						'type'        => 'integer',
						'description' => '排序值。',
					),
				),
				'required'   => array( 'id' ),
			),
			'execute_callback'   => 'fuhao_ability_update_product',
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
			'meta'               => fuhao_ability_meta( array( 'idempotent' => true ) ),
		)
	);

	fuhao_register_ability(
		'fuhao/delete-product',
		array(
			'label'              => '删除产品',
			'description'        => '把产品移入回收站；force 为 true 时彻底删除。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'id'    => array(
						'type'        => 'integer',
						'description' => '产品 ID。',
					),
					'force' => array(
						'type'        => 'boolean',
						'description' => 'true 表示彻底删除，默认 false。',
					),
				),
				'required'   => array( 'id' ),
			),
			'execute_callback'   => 'fuhao_ability_delete_product',
			'permission_callback' => function () {
				return current_user_can( 'delete_posts' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'destructive' => true,
					'idempotent'  => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/list-terms',
		array(
			'label'              => '列出产品分类或标签',
			'description'        => '列出 product_cat 分类或 product_tag 标签，返回 ID、名称、别名、父级与数量。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'taxonomy'   => array(
						'type'        => 'string',
						'enum'        => array( 'product_cat', 'product_tag' ),
						'description' => '分类法。',
					),
					'hide_empty' => array(
						'type'        => 'boolean',
						'description' => '是否隐藏无产品的分类，默认 false。',
					),
					'search'     => array(
						'type'        => 'string',
						'description' => '按名称搜索。',
					),
					'per_page'   => array(
						'type'        => 'integer',
						'minimum'     => 1,
						'maximum'     => 200,
						'description' => '返回条数，默认 100。',
					),
				),
				'required'   => array( 'taxonomy' ),
			),
			'execute_callback'   => 'fuhao_ability_list_terms',
			'permission_callback' => function () {
				return current_user_can( 'manage_categories' );
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
		'fuhao/create-term',
		array(
			'label'              => '新建产品分类或标签',
			'description'        => '新建 product_cat 分类或 product_tag 标签；同名同别名已存在时直接返回现有条目。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'taxonomy'    => array(
						'type'        => 'string',
						'enum'        => array( 'product_cat', 'product_tag' ),
						'description' => '分类法。',
					),
					'name'        => array(
						'type'        => 'string',
						'description' => '名称，必填。',
					),
					'slug'        => array(
						'type'        => 'string',
						'description' => '别名。',
					),
					'description' => array(
						'type'        => 'string',
						'description' => '描述。',
					),
					'parent'      => array(
						'type'        => 'integer',
						'description' => '父级分类 ID，0 表示顶级。',
					),
				),
				'required'   => array( 'taxonomy', 'name' ),
			),
			'execute_callback'   => 'fuhao_ability_create_term',
			'permission_callback' => function () {
				return current_user_can( 'manage_categories' );
			},
			'meta'               => fuhao_ability_meta( array( 'idempotent' => true ) ),
		)
	);

	fuhao_register_ability(
		'fuhao/delete-term',
		array(
			'label'              => '删除产品分类或标签',
			'description'        => '删除指定的 product_cat 分类或 product_tag 标签。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'taxonomy' => array(
						'type'        => 'string',
						'enum'        => array( 'product_cat', 'product_tag' ),
						'description' => '分类法。',
					),
					'id'       => array(
						'type'        => 'integer',
						'description' => '分类或标签 ID。',
					),
				),
				'required'   => array( 'taxonomy', 'id' ),
			),
			'execute_callback'   => 'fuhao_ability_delete_term',
			'permission_callback' => function () {
				return current_user_can( 'manage_categories' );
			},
			'meta'               => fuhao_ability_meta(
				array(
					'destructive' => true,
					'idempotent'  => true,
				)
			),
		)
	);

	fuhao_register_ability(
		'fuhao/list-media',
		array(
			'label'              => '列出媒体',
			'description'        => '列出媒体库附件，可按关键词与 MIME 类型筛选。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'search'   => array(
						'type'        => 'string',
						'description' => '按标题搜索。',
					),
					'mime'     => array(
						'type'        => 'string',
						'description' => 'MIME 类型前缀，例如 image。',
					),
					'per_page' => array(
						'type'        => 'integer',
						'minimum'     => 1,
						'maximum'     => 200,
						'description' => '返回条数，默认 50。',
					),
				),
			),
			'execute_callback'   => 'fuhao_ability_list_media',
			'permission_callback' => function () {
				return current_user_can( 'upload_files' );
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
		'fuhao/upload-media',
		array(
			'label'              => '上传媒体',
			'description'        => '把 base64 图片写入媒体库并生成各尺寸，返回附件 ID 与地址。',
			'category'           => 'fuhao',
			'input_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'filename'  => array(
						'type'        => 'string',
						'description' => '文件名，需带扩展名，例如 hanban-1.jpg。',
					),
					'data'      => array(
						'type'        => 'string',
						'description' => 'base64 数据，可带 data:image/...;base64, 前缀。',
					),
					'title'     => array(
						'type'        => 'string',
						'description' => '媒体标题。',
					),
					'alt'       => array(
						'type'        => 'string',
						'description' => '替代文本。',
					),
					'attach_to' => array(
						'type'        => 'integer',
						'description' => '关联的产品 ID，可选。',
					),
				),
				'required'   => array( 'filename', 'data' ),
			),
			'execute_callback'   => 'fuhao_ability_upload_media',
			'permission_callback' => function () {
				return current_user_can( 'upload_files' );
			},
			'meta'               => fuhao_ability_meta(),
		)
	);
}

/**
 * 产品数据摘要。
 *
 * @param WP_Post $post         产品对象。
 * @param bool    $with_content 是否包含正文与摘要。
 * @return array
 */
function fuhao_abilities_product_data( $post, $with_content = false ) {
	$categories = wp_get_post_terms( $post->ID, 'product_cat', array( 'fields' => 'all' ) );
	$tags       = wp_get_post_terms( $post->ID, 'product_tag', array( 'fields' => 'all' ) );

	$map_terms = static function ( $terms ) {
		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return array_map(
			static function ( $term ) {
				return array(
					'id'   => (int) $term->term_id,
					'name' => $term->name,
					'slug' => $term->slug,
				);
			},
			$terms
		);
	};

	$data = array(
		'id'             => (int) $post->ID,
		'title'          => get_the_title( $post ),
		'slug'           => $post->post_name,
		'status'         => $post->post_status,
		'menu_order'     => (int) $post->menu_order,
		'link'           => get_permalink( $post ),
		'categories'     => $map_terms( $categories ),
		'tags'           => $map_terms( $tags ),
		'featured_media' => (int) get_post_thumbnail_id( $post ),
		'card_image'     => (int) get_post_meta( $post->ID, '_fuhao_card_image', true ),
		'gallery'        => array_values( array_map( 'intval', (array) get_post_meta( $post->ID, '_fuhao_gallery', true ) ) ),
		'url_1688'       => (string) get_post_meta( $post->ID, '_fuhao_url_1688', true ),
		'supply_type'    => (string) get_post_meta( $post->ID, '_fuhao_supply_type', true ),
		'modified'       => $post->post_modified_gmt,
	);

	if ( $with_content ) {
		$data['content'] = $post->post_content;
		$data['excerpt'] = $post->post_excerpt;
	}

	return $data;
}

/**
 * 取回产品对象。
 *
 * @param int $id 产品 ID。
 * @return WP_Post|WP_Error
 */
function fuhao_abilities_require_product( $id ) {
	$post = get_post( $id );

	if ( ! $post || 'product' !== $post->post_type ) {
		return fuhao_abilities_error( 'fuhao_product_not_found', '产品不存在。', 404 );
	}

	return $post;
}

/**
 * 校验供货类型。
 *
 * @param string $value 供货类型。
 * @return string|WP_Error
 */
function fuhao_abilities_sanitize_supply_type( $value ) {
	$value   = sanitize_key( $value );
	$allowed = array( '', 'stock', 'stock_custom', 'bulk' );

	if ( ! in_array( $value, $allowed, true ) ) {
		return fuhao_abilities_error( 'fuhao_invalid_supply_type', '供货类型无效。' );
	}

	return $value;
}

/**
 * 校验单个图片附件。
 *
 * @param mixed $value 附件 ID。
 * @return int|WP_Error
 */
function fuhao_abilities_sanitize_image_id( $value ) {
	$id = absint( $value );

	if ( ! $id ) {
		return 0;
	}

	$attachment = get_post( $id );

	if ( ! $attachment || 'attachment' !== $attachment->post_type || ! wp_attachment_is_image( $id ) ) {
		return fuhao_abilities_error( 'fuhao_invalid_image', '图片附件无效：' . $id );
	}

	return $id;
}

/**
 * 校验产品图集，最多 6 张。
 *
 * @param mixed $value 附件 ID 数组。
 * @return array|WP_Error
 */
function fuhao_abilities_sanitize_gallery( $value ) {
	$ids = array();

	foreach ( (array) $value as $id ) {
		$id = fuhao_abilities_sanitize_image_id( $id );

		if ( is_wp_error( $id ) ) {
			return $id;
		}

		if ( $id && ! in_array( $id, $ids, true ) ) {
			$ids[] = $id;
		}
	}

	if ( count( $ids ) > 6 ) {
		return fuhao_abilities_error( 'fuhao_gallery_too_many', '产品图集最多 6 张。' );
	}

	return $ids;
}

/**
 * 列出产品。
 *
 * @param array $input 输入参数。
 * @return array
 */
function fuhao_ability_list_products( $input ) {
	$per_page = isset( $input['per_page'] ) ? (int) $input['per_page'] : 100;
	$per_page = max( 1, min( 200, $per_page ) );

	$args = array(
		'post_type'   => 'product',
		'post_status' => 'any',
		'numberposts' => $per_page,
		'orderby'     => 'menu_order date',
		'order'       => 'ASC',
	);

	if ( ! empty( $input['status'] ) && 'any' !== $input['status'] ) {
		$args['post_status'] = sanitize_key( $input['status'] );
	}

	if ( ! empty( $input['category'] ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => (int) $input['category'],
			),
		);
	}

	if ( ! empty( $input['search'] ) ) {
		$args['s'] = sanitize_text_field( $input['search'] );
	}

	$posts = get_posts( $args );

	return array(
		'count'    => count( $posts ),
		'products' => array_map( 'fuhao_abilities_product_data', $posts ),
	);
}

/**
 * 读取产品。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_get_product( $input ) {
	$post = fuhao_abilities_require_product( (int) $input['id'] );

	if ( is_wp_error( $post ) ) {
		return $post;
	}

	return fuhao_abilities_product_data( $post, true );
}

/**
 * 新建产品。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_create_product( $input ) {
	$title = isset( $input['title'] ) ? sanitize_text_field( $input['title'] ) : '';

	if ( '' === $title ) {
		return fuhao_abilities_error( 'fuhao_title_required', '产品标题不能为空。' );
	}

	$status = isset( $input['status'] ) ? sanitize_key( $input['status'] ) : 'draft';

	if ( 'publish' === $status && ! current_user_can( 'publish_posts' ) ) {
		return fuhao_abilities_error( 'fuhao_cannot_publish', '当前用户没有发布产品的权限。', 403 );
	}

	$data = array(
		'post_type'   => 'product',
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

	if ( isset( $input['menu_order'] ) ) {
		$data['menu_order'] = (int) $input['menu_order'];
	}

	$result = wp_insert_post( $data, true );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	$post_id = (int) $result;

	if ( isset( $input['categories'] ) ) {
		wp_set_object_terms( $post_id, array_map( 'intval', (array) $input['categories'] ), 'product_cat' );
	}

	if ( isset( $input['tags'] ) ) {
		wp_set_object_terms( $post_id, array_map( 'intval', (array) $input['tags'] ), 'product_tag' );
	}

	if ( ! empty( $input['featured_media'] ) ) {
		$attachment = get_post( (int) $input['featured_media'] );
		if ( $attachment && 'attachment' === $attachment->post_type ) {
			set_post_thumbnail( $post_id, (int) $attachment->ID );
		}
	}

	if ( isset( $input['card_image'] ) ) {
		$card_image = fuhao_abilities_sanitize_image_id( $input['card_image'] );
		if ( is_wp_error( $card_image ) ) {
			return $card_image;
		}
		if ( $card_image ) {
			update_post_meta( $post_id, '_fuhao_card_image', $card_image );
		}
	}

	if ( isset( $input['gallery'] ) ) {
		$gallery = fuhao_abilities_sanitize_gallery( $input['gallery'] );
		if ( is_wp_error( $gallery ) ) {
			return $gallery;
		}
		if ( $gallery ) {
			update_post_meta( $post_id, '_fuhao_gallery', $gallery );
		}
	}

	if ( isset( $input['url_1688'] ) ) {
		$url_1688 = esc_url_raw( trim( (string) $input['url_1688'] ) );
		if ( '' !== $url_1688 ) {
			update_post_meta( $post_id, '_fuhao_url_1688', $url_1688 );
		}
	}

	if ( isset( $input['supply_type'] ) ) {
		$supply = fuhao_abilities_sanitize_supply_type( $input['supply_type'] );
		if ( is_wp_error( $supply ) ) {
			return $supply;
		}
		if ( '' !== $supply ) {
			update_post_meta( $post_id, '_fuhao_supply_type', $supply );
		}
	}

	return fuhao_abilities_product_data( get_post( $post_id ), true );
}

/**
 * 更新产品。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_update_product( $input ) {
	$post = fuhao_abilities_require_product( (int) $input['id'] );

	if ( is_wp_error( $post ) ) {
		return $post;
	}

	if ( isset( $input['status'] ) && 'publish' === $input['status'] && ! current_user_can( 'publish_posts' ) ) {
		return fuhao_abilities_error( 'fuhao_cannot_publish', '当前用户没有发布产品的权限。', 403 );
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

	if ( isset( $input['categories'] ) ) {
		wp_set_object_terms( $post->ID, array_map( 'intval', (array) $input['categories'] ), 'product_cat' );
	}

	if ( isset( $input['tags'] ) ) {
		wp_set_object_terms( $post->ID, array_map( 'intval', (array) $input['tags'] ), 'product_tag' );
	}

	if ( isset( $input['featured_media'] ) ) {
		$featured = (int) $input['featured_media'];
		if ( $featured > 0 ) {
			$attachment = get_post( $featured );
			if ( $attachment && 'attachment' === $attachment->post_type ) {
				set_post_thumbnail( $post->ID, $featured );
			}
		} else {
			delete_post_thumbnail( $post->ID );
		}
	}

	if ( isset( $input['card_image'] ) ) {
		$card_image = fuhao_abilities_sanitize_image_id( $input['card_image'] );
		if ( is_wp_error( $card_image ) ) {
			return $card_image;
		}
		if ( $card_image ) {
			update_post_meta( $post->ID, '_fuhao_card_image', $card_image );
		} else {
			delete_post_meta( $post->ID, '_fuhao_card_image' );
		}
	}

	if ( isset( $input['gallery'] ) ) {
		$gallery = fuhao_abilities_sanitize_gallery( $input['gallery'] );
		if ( is_wp_error( $gallery ) ) {
			return $gallery;
		}
		if ( $gallery ) {
			update_post_meta( $post->ID, '_fuhao_gallery', $gallery );
		} else {
			delete_post_meta( $post->ID, '_fuhao_gallery' );
		}
	}

	if ( isset( $input['url_1688'] ) ) {
		$url_1688 = esc_url_raw( trim( (string) $input['url_1688'] ) );
		if ( '' !== $url_1688 ) {
			update_post_meta( $post->ID, '_fuhao_url_1688', $url_1688 );
		} else {
			delete_post_meta( $post->ID, '_fuhao_url_1688' );
		}
	}

	if ( isset( $input['supply_type'] ) ) {
		$supply = fuhao_abilities_sanitize_supply_type( $input['supply_type'] );
		if ( is_wp_error( $supply ) ) {
			return $supply;
		}
		if ( '' === $supply ) {
			delete_post_meta( $post->ID, '_fuhao_supply_type' );
		} else {
			update_post_meta( $post->ID, '_fuhao_supply_type', $supply );
		}
	}

	return fuhao_abilities_product_data( get_post( $post->ID ), true );
}

/**
 * 删除产品。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_delete_product( $input ) {
	$post = fuhao_abilities_require_product( (int) $input['id'] );

	if ( is_wp_error( $post ) ) {
		return $post;
	}

	$force = ! empty( $input['force'] );
	$done  = $force ? wp_delete_post( $post->ID, true ) : wp_trash_post( $post->ID );

	if ( ! $done ) {
		return fuhao_abilities_error( 'fuhao_delete_failed', '删除产品失败。', 500 );
	}

	return array(
		'id'      => (int) $post->ID,
		'deleted' => true,
		'force'   => $force,
	);
}

/**
 * 列出产品分类或标签。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_list_terms( $input ) {
	$taxonomy = isset( $input['taxonomy'] ) ? sanitize_key( $input['taxonomy'] ) : '';

	if ( ! in_array( $taxonomy, array( 'product_cat', 'product_tag' ), true ) ) {
		return fuhao_abilities_error( 'fuhao_invalid_taxonomy', '分类法无效。' );
	}

	$per_page = isset( $input['per_page'] ) ? (int) $input['per_page'] : 100;
	$per_page = max( 1, min( 200, $per_page ) );

	$args = array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => ! empty( $input['hide_empty'] ),
		'number'     => $per_page,
		'orderby'    => 'term_id',
		'order'      => 'ASC',
	);

	if ( ! empty( $input['search'] ) ) {
		$args['search'] = sanitize_text_field( $input['search'] );
	}

	$terms = get_terms( $args );

	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	$items = array_map(
		static function ( $term ) {
			return array(
				'id'          => (int) $term->term_id,
				'name'        => $term->name,
				'slug'        => $term->slug,
				'description' => $term->description,
				'parent'      => (int) $term->parent,
				'count'       => (int) $term->count,
			);
		},
		$terms
	);

	return array(
		'taxonomy' => $taxonomy,
		'count'    => count( $items ),
		'terms'    => $items,
	);
}

/**
 * 新建产品分类或标签。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_create_term( $input ) {
	$taxonomy = isset( $input['taxonomy'] ) ? sanitize_key( $input['taxonomy'] ) : '';

	if ( ! in_array( $taxonomy, array( 'product_cat', 'product_tag' ), true ) ) {
		return fuhao_abilities_error( 'fuhao_invalid_taxonomy', '分类法无效。' );
	}

	$name = isset( $input['name'] ) ? sanitize_text_field( $input['name'] ) : '';

	if ( '' === $name ) {
		return fuhao_abilities_error( 'fuhao_name_required', '名称不能为空。' );
	}

	$args = array();

	if ( isset( $input['slug'] ) ) {
		$args['slug'] = sanitize_title( $input['slug'] );
	}

	if ( isset( $input['description'] ) ) {
		$args['description'] = sanitize_text_field( $input['description'] );
	}

	if ( ! empty( $input['parent'] ) ) {
		$args['parent'] = (int) $input['parent'];
	}

	$result = wp_insert_term( $name, $taxonomy, $args );

	if ( is_wp_error( $result ) ) {
		if ( 'term_exists' === $result->get_error_code() ) {
			$term_id = (int) $result->get_error_data();
			$term    = get_term( $term_id, $taxonomy );
			return array(
				'id'       => $term_id,
				'name'     => $term instanceof WP_Term ? $term->name : $name,
				'slug'     => $term instanceof WP_Term ? $term->slug : '',
				'existing' => true,
			);
		}

		return $result;
	}

	$term = get_term( (int) $result['term_id'], $taxonomy );

	return array(
		'id'       => (int) $result['term_id'],
		'name'     => $term instanceof WP_Term ? $term->name : $name,
		'slug'     => $term instanceof WP_Term ? $term->slug : '',
		'existing' => false,
	);
}

/**
 * 删除产品分类或标签。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_delete_term( $input ) {
	$taxonomy = isset( $input['taxonomy'] ) ? sanitize_key( $input['taxonomy'] ) : '';

	if ( ! in_array( $taxonomy, array( 'product_cat', 'product_tag' ), true ) ) {
		return fuhao_abilities_error( 'fuhao_invalid_taxonomy', '分类法无效。' );
	}

	$term_id = (int) $input['id'];
	$term    = get_term( $term_id, $taxonomy );

	if ( ! $term || is_wp_error( $term ) ) {
		return fuhao_abilities_error( 'fuhao_term_not_found', '分类或标签不存在。', 404 );
	}

	$done = wp_delete_term( $term_id, $taxonomy );

	if ( ! $done || is_wp_error( $done ) ) {
		return fuhao_abilities_error( 'fuhao_delete_failed', '删除分类或标签失败。', 500 );
	}

	return array(
		'id'      => $term_id,
		'deleted' => true,
	);
}

/**
 * 列出媒体。
 *
 * @param array $input 输入参数。
 * @return array
 */
function fuhao_ability_list_media( $input ) {
	$per_page = isset( $input['per_page'] ) ? (int) $input['per_page'] : 50;
	$per_page = max( 1, min( 200, $per_page ) );

	$args = array(
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'numberposts' => $per_page,
		'orderby'     => 'date',
		'order'       => 'DESC',
	);

	if ( ! empty( $input['search'] ) ) {
		$args['s'] = sanitize_text_field( $input['search'] );
	}

	if ( ! empty( $input['mime'] ) ) {
		$args['post_mime_type'] = sanitize_text_field( $input['mime'] );
	}

	$items = get_posts( $args );

	return array(
		'count' => count( $items ),
		'media' => array_map(
			static function ( $item ) {
				$meta = wp_get_attachment_metadata( $item->ID );

				return array(
					'id'        => (int) $item->ID,
					'title'     => $item->post_title,
					'url'       => wp_get_attachment_url( $item->ID ),
					'mime'      => $item->post_mime_type,
					'width'     => isset( $meta['width'] ) ? (int) $meta['width'] : 0,
					'height'    => isset( $meta['height'] ) ? (int) $meta['height'] : 0,
					'alt'       => (string) get_post_meta( $item->ID, '_wp_attachment_image_alt', true ),
					'parent'    => (int) $item->post_parent,
				);
			},
			$items
		),
	);
}

/**
 * 上传媒体。
 *
 * @param array $input 输入参数。
 * @return array|WP_Error
 */
function fuhao_ability_upload_media( $input ) {
	$filename = isset( $input['filename'] ) ? sanitize_file_name( $input['filename'] ) : '';
	$data     = isset( $input['data'] ) ? (string) $input['data'] : '';

	if ( '' === $filename || '' === $data ) {
		return fuhao_abilities_error( 'fuhao_media_required', '文件名与数据不能为空。' );
	}

	if ( preg_match( '#^data:[^;]+;base64,#', $data, $matches ) ) {
		$data = substr( $data, strlen( $matches[0] ) );
	}

	$raw = base64_decode( preg_replace( '/\s+/', '', $data ), true );

	if ( false === $raw || '' === $raw ) {
		return fuhao_abilities_error( 'fuhao_invalid_base64', '图片数据不是有效的 base64。' );
	}

	if ( strlen( $raw ) > 10 * 1024 * 1024 ) {
		return fuhao_abilities_error( 'fuhao_media_too_large', '图片超过 10MB。' );
	}

	$check = wp_check_filetype( $filename );

	if ( empty( $check['type'] ) || 0 !== strpos( (string) $check['type'], 'image/' ) ) {
		return fuhao_abilities_error( 'fuhao_invalid_media_type', '只支持图片文件。' );
	}

	$upload = wp_upload_bits( $filename, null, $raw );

	if ( ! empty( $upload['error'] ) ) {
		return fuhao_abilities_error( 'fuhao_upload_failed', $upload['error'], 500 );
	}

	$attachment = array(
		'post_mime_type' => $check['type'],
		'post_title'     => isset( $input['title'] ) ? sanitize_text_field( $input['title'] ) : pathinfo( $filename, PATHINFO_FILENAME ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	if ( ! empty( $input['attach_to'] ) ) {
		$attachment['post_parent'] = (int) $input['attach_to'];
	}

	$attachment_id = wp_insert_attachment( $attachment, $upload['file'] );

	if ( is_wp_error( $attachment_id ) ) {
		return $attachment_id;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
	wp_update_attachment_metadata( $attachment_id, $metadata );

	if ( isset( $input['alt'] ) ) {
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $input['alt'] ) );
	}

	return array(
		'id'     => (int) $attachment_id,
		'url'    => wp_get_attachment_url( $attachment_id ),
		'mime'   => $check['type'],
		'width'  => isset( $metadata['width'] ) ? (int) $metadata['width'] : 0,
		'height' => isset( $metadata['height'] ) ? (int) $metadata['height'] : 0,
		'sizes'  => isset( $metadata['sizes'] ) ? array_keys( $metadata['sizes'] ) : array(),
	);
}
