<?php
/**
 * 404 页面。
 *
 * @package Fuhao
 */

get_header();
?>
<main id="main">
	<div class="page-main">
		<div class="container">
			<div class="empty-state">
				<p class="big">404</p>
				<h1><?php esc_html_e( '页面没有找到', 'fuhao' ); ?></h1>
				<p><?php esc_html_e( '链接可能已失效，或内容已经调整。你可以返回首页，或直接浏览我们的产品。', 'fuhao' ); ?></p>
				<div class="empty-state__actions">
					<a class="btn btn--accent" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '返回首页', 'fuhao' ); ?></a>
					<a class="btn btn--outline" href="<?php echo esc_url( fuhao_product_archive_url() ); ?>"><?php esc_html_e( '浏览产品', 'fuhao' ); ?></a>
					<a class="btn btn--outline" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系我们', 'fuhao' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
