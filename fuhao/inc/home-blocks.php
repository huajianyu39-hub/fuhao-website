<?php
/**
 * 宣传版块：首页可自由添加的自定义版块。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 注册宣传版块内容类型。
 */
function fuhao_register_block_type() {
	register_post_type(
		'fuhao_block',
		array(
			'labels'              => array(
				'name'          => __( '宣传版块', 'fuhao' ),
				'singular_name' => __( '宣传版块', 'fuhao' ),
				'add_new'       => __( '新建宣传版块', 'fuhao' ),
				'add_new_item'  => __( '新建宣传版块', 'fuhao' ),
				'edit_item'     => __( '编辑宣传版块', 'fuhao' ),
				'new_item'      => __( '新宣传版块', 'fuhao' ),
				'search_items'  => __( '搜索宣传版块', 'fuhao' ),
				'not_found'     => __( '暂无宣传版块', 'fuhao' ),
				'menu_name'     => __( '宣传版块', 'fuhao' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-layout',
			'menu_position'       => 21,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			'show_in_rest'        => false,
		)
	);
}
add_action( 'init', 'fuhao_register_block_type' );

/**
 * 字段写入权限。
 *
 * @param bool   $allowed  默认是否允许。
 * @param string $meta_key 元数据键。
 * @param int    $post_id  版块 ID。
 * @return bool
 */
function fuhao_block_meta_auth( $allowed, $meta_key, $post_id ) {
	return current_user_can( 'edit_post', $post_id );
}

/**
 * 注册宣传版块字段。
 */
function fuhao_register_block_meta() {
	$fields = array(
		'_fuhao_block_eyebrow'  => 'sanitize_text_field',
		'_fuhao_block_title'    => 'sanitize_text_field',
		'_fuhao_block_text'     => 'sanitize_textarea_field',
		'_fuhao_block_btn_text' => 'sanitize_text_field',
		'_fuhao_block_btn_url'  => 'esc_url_raw',
	);

	foreach ( $fields as $key => $sanitize ) {
		register_post_meta(
			'fuhao_block',
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'show_in_rest'      => false,
				'sanitize_callback' => $sanitize,
				'auth_callback'     => 'fuhao_block_meta_auth',
			)
		);
	}
}
add_action( 'init', 'fuhao_register_block_meta', 20 );

/**
 * 添加字段面板。
 */
function fuhao_add_block_fields_box() {
	add_meta_box(
		'fuhao_block_fields',
		__( '版块内容', 'fuhao' ),
		'fuhao_render_block_fields_box',
		'fuhao_block',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'fuhao_add_block_fields_box' );

/**
 * 渲染字段面板。
 *
 * @param WP_Post $post 当前版块。
 */
function fuhao_render_block_fields_box( $post ) {
	wp_nonce_field( 'fuhao_block_fields', 'fuhao_block_fields_nonce' );

	$eyebrow  = (string) get_post_meta( $post->ID, '_fuhao_block_eyebrow', true );
	$title    = (string) get_post_meta( $post->ID, '_fuhao_block_title', true );
	$text     = (string) get_post_meta( $post->ID, '_fuhao_block_text', true );
	$btn_text = (string) get_post_meta( $post->ID, '_fuhao_block_btn_text', true );
	$btn_url  = (string) get_post_meta( $post->ID, '_fuhao_block_btn_url', true );
	?>
	<p class="description"><?php esc_html_e( '标题栏的名称只用于后台识别；前台显示下面填写的小标签和大标题。配图请用右侧“特色图片”。', 'fuhao' ); ?></p>

	<p>
		<label for="fuhao_block_eyebrow"><strong><?php esc_html_e( '小标签', 'fuhao' ); ?></strong></label><br>
		<input type="text" class="widefat" name="fuhao_block_eyebrow" id="fuhao_block_eyebrow" value="<?php echo esc_attr( $eyebrow ); ?>" placeholder="<?php esc_attr_e( '如：工厂产能', 'fuhao' ); ?>">
	</p>

	<p>
		<label for="fuhao_block_title"><strong><?php esc_html_e( '大标题', 'fuhao' ); ?></strong></label><br>
		<input type="text" class="widefat" name="fuhao_block_title" id="fuhao_block_title" value="<?php echo esc_attr( $title ); ?>">
	</p>

	<p>
		<label for="fuhao_block_text"><strong><?php esc_html_e( '说明文字', 'fuhao' ); ?></strong></label><br>
		<textarea class="widefat" rows="5" name="fuhao_block_text" id="fuhao_block_text"><?php echo esc_textarea( $text ); ?></textarea>
	</p>

	<p>
		<label for="fuhao_block_btn_text"><strong><?php esc_html_e( '按钮文字', 'fuhao' ); ?></strong></label><br>
		<input type="text" class="widefat" name="fuhao_block_btn_text" id="fuhao_block_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" placeholder="<?php esc_attr_e( '留空则不显示按钮', 'fuhao' ); ?>">
	</p>

	<p>
		<label for="fuhao_block_btn_url"><strong><?php esc_html_e( '按钮链接', 'fuhao' ); ?></strong></label><br>
		<input type="url" class="widefat" name="fuhao_block_btn_url" id="fuhao_block_btn_url" value="<?php echo esc_attr( $btn_url ); ?>" placeholder="https://">
	</p>
	<?php
}

/**
 * 保存字段面板。
 *
 * @param int $post_id 版块 ID。
 */
function fuhao_save_block_fields( $post_id ) {
	if ( ! isset( $_POST['fuhao_block_fields_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['fuhao_block_fields_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'fuhao_block_fields' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_fuhao_block_eyebrow'  => array( 'fuhao_block_eyebrow', 'sanitize_text_field' ),
		'_fuhao_block_title'    => array( 'fuhao_block_title', 'sanitize_text_field' ),
		'_fuhao_block_text'     => array( 'fuhao_block_text', 'sanitize_textarea_field' ),
		'_fuhao_block_btn_text' => array( 'fuhao_block_btn_text', 'sanitize_text_field' ),
		'_fuhao_block_btn_url'  => array( 'fuhao_block_btn_url', 'esc_url_raw' ),
	);

	foreach ( $fields as $meta_key => $field ) {
		if ( ! isset( $_POST[ $field[0] ] ) ) {
			continue;
		}

		$value = call_user_func( $field[1], wp_unslash( $_POST[ $field[0] ] ) );
		$value = is_string( $value ) ? trim( $value ) : '';

		if ( '' !== $value ) {
			update_post_meta( $post_id, $meta_key, $value );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}
}
add_action( 'save_post_fuhao_block', 'fuhao_save_block_fields' );
