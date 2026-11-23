<?php
/**
 * 询盘表单处理：校验、发送邮件、返回首页联系版块。
 *
 * @package Fuhao
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 按字符截断文本，避免依赖 mbstring。
 *
 * @param string $text 文本。
 * @param int    $limit 最大长度。
 * @return string
 */
function fuhao_trim_text( $text, $limit ) {
	if ( function_exists( 'mb_substr' ) ) {
		return mb_substr( $text, 0, $limit );
	}

	return substr( $text, 0, $limit );
}

/**
 * 处理询盘提交。
 */
function fuhao_handle_inquiry() {
	$referer  = wp_get_referer();
	$redirect = $referer ? $referer : home_url( '/' );
	$redirect = preg_replace( '/#.*$/', '', $redirect );
	$redirect = remove_query_arg( 'inquiry', $redirect );

	if ( ! $redirect ) {
		$redirect = home_url( '/' );
	}

	$nonce = isset( $_POST['fuhao_inquiry_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['fuhao_inquiry_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'fuhao_inquiry' ) ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'error', $redirect ) . '#contact' );
		exit;
	}

	// 蜜罐字段：正常用户看不到，机器人填写后静默丢弃。
	if ( ! empty( $_POST['fuhao_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'success', $redirect ) . '#contact' );
		exit;
	}

	$name         = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$company      = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$product_type = isset( $_POST['product_type'] ) ? sanitize_text_field( wp_unslash( $_POST['product_type'] ) ) : '';
	$quantity     = isset( $_POST['quantity'] ) ? sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) : '';
	$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$consent      = isset( $_POST['consent'] ) ? sanitize_text_field( wp_unslash( $_POST['consent'] ) ) : '';

	$name     = fuhao_trim_text( $name, 100 );
	$company  = fuhao_trim_text( $company, 150 );
	$phone    = fuhao_trim_text( $phone, 50 );
	$quantity = fuhao_trim_text( $quantity, 100 );
	$message  = fuhao_trim_text( $message, 4000 );

	$type_options = array( '现货供应', '现货定制', '批量定制 / OEM', 'ODM 设计开发', '其他' );
	if ( ! in_array( $product_type, $type_options, true ) ) {
		$product_type = '';
	}

	if ( '1' !== $consent || '' === $name || ! is_email( $email ) || '' === $message ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'error', $redirect ) . '#contact' );
		exit;
	}

	$to = sanitize_email( fuhao_get( 'inquiry_email' ) );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$type_label = '' !== $product_type ? $product_type : '未填写';
	$subject    = sprintf( '[网站询盘] %s - %s', $name, $type_label );
	$subject    = fuhao_trim_text( $subject, 120 );

	$lines = array(
		'姓名：' . $name,
		'公司：' . ( '' !== $company ? $company : '未填写' ),
		'邮箱：' . $email,
		'电话 / WhatsApp：' . ( '' !== $phone ? $phone : '未填写' ),
		'合作方式：' . $type_label,
		'预计数量：' . ( '' !== $quantity ? $quantity : '未填写' ),
		'',
		'需求说明：',
		$message,
		'',
		'—— 来自 ' . home_url( '/' ),
	);

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	$cc = array();
	foreach ( fuhao_contact_emails() as $fuhao_email ) {
		if ( $fuhao_email !== $to ) {
			$cc[] = $fuhao_email;
		}
	}

	if ( $cc ) {
		$headers[] = 'Cc: ' . implode( ', ', array_unique( $cc ) );
	}

	$safe_name = str_replace( array( '"', ',', '<', '>', "\r", "\n" ), '', $name );
	if ( '' !== $safe_name ) {
		$headers[] = 'Reply-To: ' . $safe_name . ' <' . $email . '>';
	}

	$sent = wp_mail( $to, $subject, implode( "\n", $lines ), $headers );

	wp_safe_redirect( add_query_arg( 'inquiry', $sent ? 'success' : 'error', $redirect ) . '#contact' );
	exit;
}
add_action( 'admin_post_fuhao_inquiry', 'fuhao_handle_inquiry' );
add_action( 'admin_post_nopriv_fuhao_inquiry', 'fuhao_handle_inquiry' );
