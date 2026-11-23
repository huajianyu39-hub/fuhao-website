<?php
/**
 * 首页版块顺序：后台拖动排序、显示/隐藏。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 内置首页版块。
 *
 * @return array
 */
function fuhao_home_builtin_sections() {
	return array(
		'cooperation' => __( '合作方式', 'fuhao' ),
		'products'    => __( '产品分类', 'fuhao' ),
		'oem'         => __( '定制服务（深色）', 'fuhao' ),
		'process'     => __( '制造流程', 'fuhao' ),
		'stock'       => __( '现货供应', 'fuhao' ),
		'industries'  => __( '服务行业', 'fuhao' ),
		'about'       => __( '工厂实力', 'fuhao' ),
		'contact'     => __( '询盘表单', 'fuhao' ),
	);
}

/**
 * 已发布的宣传版块。
 *
 * @return WP_Post[]
 */
function fuhao_home_block_posts() {
	return get_posts(
		array(
			'post_type'        => 'fuhao_block',
			'post_status'      => 'publish',
			'numberposts'      => -1,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'suppress_filters' => false,
		)
	);
}

/**
 * 全部可排序项目：token => 后台名称。
 *
 * @return array
 */
function fuhao_home_all_items() {
	$items = fuhao_home_builtin_sections();

	foreach ( fuhao_home_block_posts() as $block ) {
		$items[ 'block:' . $block->ID ] = sprintf(
			/* translators: %s: 宣传版块在后台的标题。 */
			__( '宣传版块：%s', 'fuhao' ),
			get_the_title( $block )
		);
	}

	return $items;
}

/**
 * 合并用户排序与新增项目。
 *
 * @return array
 */
function fuhao_home_order() {
	$items = fuhao_home_all_items();
	$saved = get_option( 'fuhao_home_order', array() );
	$order = array();

	foreach ( (array) $saved as $token ) {
		if ( isset( $items[ $token ] ) && ! in_array( $token, $order, true ) ) {
			$order[] = $token;
		}
	}

	$missing = array_values( array_diff( array_keys( $items ), $order ) );

	if ( $missing ) {
		$contact_at = array_search( 'contact', $order, true );

		if ( false !== $contact_at ) {
			array_splice( $order, $contact_at, 0, $missing );
		} else {
			$order = array_merge( $order, $missing );
		}
	}

	return $order;
}

/**
 * 被隐藏的版块 token。
 *
 * @return array
 */
function fuhao_home_hidden() {
	$hidden = get_option( 'fuhao_home_hidden', array() );

	return is_array( $hidden ) ? $hidden : array();
}

/**
 * 后台菜单：外观 → 首页版块排序。
 */
function fuhao_home_order_menu() {
	add_theme_page(
		__( '首页版块排序', 'fuhao' ),
		__( '首页版块排序', 'fuhao' ),
		'edit_theme_options',
		'fuhao-home-order',
		'fuhao_home_order_page'
	);
}
add_action( 'admin_menu', 'fuhao_home_order_menu' );

/**
 * 渲染排序页面。
 */
function fuhao_home_order_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$items  = fuhao_home_all_items();
	$order  = fuhao_home_order();
	$hidden = fuhao_home_hidden();
	$notice = isset( $_GET['updated'] ) ? sanitize_key( wp_unslash( $_GET['updated'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<div class="wrap fuhao-home-order">
		<h1><?php esc_html_e( '首页版块排序', 'fuhao' ); ?></h1>

		<?php if ( '1' === $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( '排序已保存。', 'fuhao' ); ?></p></div>
		<?php endif; ?>

		<p><?php esc_html_e( '按住左侧手柄上下拖动即可调整整块顺序；取消勾选“显示”即可隐藏该版块，内容不会被删除。主视觉轮播固定在首页第一位，不在此列表中。', 'fuhao' ); ?></p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="fuhao_save_home_order">
			<?php wp_nonce_field( 'fuhao_save_home_order', 'fuhao_home_order_nonce' ); ?>
			<input type="hidden" name="fuhao_home_order" value="<?php echo esc_attr( implode( ',', $order ) ); ?>" data-fuhao-order-input>

			<ul class="fuhao-home-order__list" data-fuhao-order-list>
				<?php foreach ( $order as $token ) : ?>
					<?php if ( ! isset( $items[ $token ] ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<li class="fuhao-home-order__item" data-token="<?php echo esc_attr( $token ); ?>">
						<span class="fuhao-home-order__handle" aria-hidden="true">≡</span>
						<span class="fuhao-home-order__label"><?php echo esc_html( $items[ $token ] ); ?></span>
						<?php if ( 0 === strpos( $token, 'block:' ) ) : ?>
							<a class="fuhao-home-order__edit" href="<?php echo esc_url( get_edit_post_link( (int) substr( $token, 6 ) ) ); ?>"><?php esc_html_e( '编辑内容', 'fuhao' ); ?></a>
						<?php endif; ?>
						<label class="fuhao-home-order__toggle">
							<input type="checkbox" name="fuhao_home_hidden[]" value="<?php echo esc_attr( $token ); ?>"<?php checked( ! in_array( $token, $hidden, true ) ); ?>>
							<?php esc_html_e( '显示', 'fuhao' ); ?>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>

			<p class="description"><?php esc_html_e( '“询盘表单”建议放在最后；技术上可以拖到任意位置。', 'fuhao' ); ?></p>

			<p>
				<?php submit_button( __( '保存排序', 'fuhao' ), 'primary', 'submit', false ); ?>
				<a class="button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=fuhao_block' ) ); ?>"><?php esc_html_e( '新建宣传版块', 'fuhao' ); ?></a>
			</p>
		</form>
	</div>
	<?php
}

/**
 * 保存排序与显示状态。
 */
function fuhao_save_home_order() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( '没有权限修改首页版块。', 'fuhao' ) );
	}

	check_admin_referer( 'fuhao_save_home_order', 'fuhao_home_order_nonce' );

	$items = fuhao_home_all_items();
	$raw   = isset( $_POST['fuhao_home_order'] ) ? sanitize_text_field( wp_unslash( $_POST['fuhao_home_order'] ) ) : '';
	$order = array();

	foreach ( explode( ',', $raw ) as $token ) {
		$token = trim( $token );

		if ( '' !== $token && isset( $items[ $token ] ) && ! in_array( $token, $order, true ) ) {
			$order[] = $token;
		}
	}

	$visible = array();

	if ( isset( $_POST['fuhao_home_hidden'] ) ) {
		foreach ( (array) wp_unslash( $_POST['fuhao_home_hidden'] ) as $token ) {
			if ( ! is_string( $token ) ) {
				continue;
			}

			$token = sanitize_text_field( $token );

			if ( isset( $items[ $token ] ) ) {
				$visible[] = $token;
			}
		}
	}

	$hidden = array_values( array_diff( array_keys( $items ), $visible ) );

	update_option( 'fuhao_home_order', $order );
	update_option( 'fuhao_home_hidden', $hidden );

	wp_safe_redirect( add_query_arg( 'updated', '1', admin_url( 'themes.php?page=fuhao-home-order' ) ) );
	exit;
}
add_action( 'admin_post_fuhao_save_home_order', 'fuhao_save_home_order' );

/**
 * 排序页面脚本与样式。
 *
 * @param string $hook 当前后台页面。
 */
function fuhao_home_order_assets( $hook ) {
	if ( 'appearance_page_fuhao-home-order' !== $hook ) {
		return;
	}

	$js_path  = get_template_directory() . '/assets/js/admin-home.js';
	$css_path = get_template_directory() . '/assets/css/admin-home.css';

	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script(
		'fuhao-admin-home',
		get_template_directory_uri() . '/assets/js/admin-home.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		file_exists( $js_path ) ? filemtime( $js_path ) : FUHAO_VERSION,
		true
	);
	wp_enqueue_style(
		'fuhao-admin-home',
		get_template_directory_uri() . '/assets/css/admin-home.css',
		array(),
		file_exists( $css_path ) ? filemtime( $css_path ) : FUHAO_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'fuhao_home_order_assets' );
