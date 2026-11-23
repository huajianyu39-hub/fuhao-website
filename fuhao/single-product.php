<?php
/**
 * 产品详情页：左侧产品图，右侧信息与参数摘要，下方完整参数。
 *
 * @package Fuhao
 */

get_header();
?>
<main id="main">
	<?php
	while ( have_posts() ) :
		the_post();
		$fuhao_terms   = get_the_terms( get_the_ID(), 'product_cat' );
		$fuhao_supply  = fuhao_get_supply_type();
		$fuhao_content = get_the_content();
		$fuhao_facts   = array();

		if ( preg_match( '/<table[^>]*>.*?<\/table>/s', $fuhao_content, $fuhao_table_match ) ) {
			$fuhao_dom = new DOMDocument();
			libxml_use_internal_errors( true );
			$fuhao_dom->loadHTML( '<?xml encoding="utf-8" ?><html><body>' . $fuhao_table_match[0] . '</body></html>' );
			libxml_clear_errors();

			$fuhao_xpath = new DOMXPath( $fuhao_dom );
			$fuhao_heads = $fuhao_xpath->query( '//table//thead//th' );
			$fuhao_rows  = $fuhao_xpath->query( '//table//tbody/tr' );

			if ( $fuhao_heads && $fuhao_heads->length && $fuhao_rows && $fuhao_rows->length ) {
				$fuhao_first_cells = $fuhao_rows->item( 0 )->getElementsByTagName( 'td' );

				for ( $fuhao_i = 1; $fuhao_i < $fuhao_heads->length && $fuhao_i < $fuhao_first_cells->length; $fuhao_i++ ) {
					$fuhao_label = trim( $fuhao_heads->item( $fuhao_i )->textContent );
					$fuhao_value = trim( $fuhao_first_cells->item( $fuhao_i )->textContent );

					if ( in_array( $fuhao_label, array( '尺寸', '容量', '单重', '毛重' ), true ) && '' !== $fuhao_value && '—' !== $fuhao_value ) {
						$fuhao_facts[ $fuhao_label ] = $fuhao_value;
					}
				}

				$fuhao_sizes = array();
				foreach ( $fuhao_rows as $fuhao_row ) {
					$fuhao_cells = $fuhao_row->getElementsByTagName( 'td' );
					if ( $fuhao_cells->length ) {
						$fuhao_size = trim( $fuhao_cells->item( 0 )->textContent );
						if ( '' !== $fuhao_size ) {
							$fuhao_sizes[] = $fuhao_size;
						}
					}
				}

				if ( count( $fuhao_sizes ) > 3 ) {
					$fuhao_facts = array( '可选规格' => sprintf( __( '共 %d 个规格，详见下表', 'fuhao' ), count( $fuhao_sizes ) ) ) + $fuhao_facts;
				} elseif ( count( $fuhao_sizes ) > 1 ) {
					$fuhao_facts = array( '可选尺码' => implode( ' / ', $fuhao_sizes ) ) + $fuhao_facts;
				}
			}
		}

		if ( preg_match( '/面料：([^<。]+)/u', $fuhao_content, $fuhao_material_match ) ) {
			$fuhao_facts['面料'] = trim( $fuhao_material_match[1] );
		}

		$fuhao_gallery = fuhao_get_product_gallery_ids();
		$fuhao_main_id = (int) get_post_thumbnail_id();

		if ( ! $fuhao_main_id && $fuhao_gallery ) {
			$fuhao_main_id = $fuhao_gallery[0];
		}

		$fuhao_images = $fuhao_main_id ? array_merge( array( $fuhao_main_id ), $fuhao_gallery ) : $fuhao_gallery;
		$fuhao_images = array_slice( array_values( array_unique( $fuhao_images ) ), 0, FUHAO_GALLERY_LIMIT + 1 );
		$fuhao_1688   = fuhao_get_product_1688_url();
		$fuhao_wechat = fuhao_get( 'wechat_qr' );
		$fuhao_link   = get_permalink();
		?>
		<section class="page-band page-band--slim">
			<div class="container">
				<?php
				fuhao_breadcrumb(
					array(
						array(
							'label' => __( '产品中心', 'fuhao' ),
							'url'   => fuhao_product_archive_url(),
						),
						array( 'label' => get_the_title() ),
					)
				);
				?>
			</div>
		</section>

		<div class="page-main">
			<div class="container">
				<article <?php post_class( 'product-page' ); ?>>
					<div class="product-layout">
						<div class="product-gallery">
							<div class="product-gallery__stage">
								<?php if ( $fuhao_main_id ) : ?>
									<?php echo wp_get_attachment_image( $fuhao_main_id, 'large', false, array( 'class' => 'product-gallery__image', 'loading' => 'eager' ) ); ?>
								<?php else : ?>
									<?php fuhao_placeholder( 'backpack', __( '产品图片', 'fuhao' ) ); ?>
								<?php endif; ?>
							</div>

							<?php if ( count( $fuhao_images ) > 1 ) : ?>
								<div class="product-gallery__thumbs" aria-label="<?php esc_attr_e( '产品图片', 'fuhao' ); ?>">
									<?php foreach ( $fuhao_images as $fuhao_index => $fuhao_image_id ) : ?>
										<?php
										$fuhao_full = wp_get_attachment_image_url( $fuhao_image_id, 'full' );
										if ( ! $fuhao_full ) {
											continue;
										}
										?>
										<button type="button" class="product-gallery__thumb<?php echo 0 === $fuhao_index ? ' is-active' : ''; ?>" data-full="<?php echo esc_url( $fuhao_full ); ?>" aria-pressed="<?php echo 0 === $fuhao_index ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( sprintf( __( '查看第 %d 张图片', 'fuhao' ), $fuhao_index + 1 ) ); ?>">
											<?php echo wp_get_attachment_image( $fuhao_image_id, 'medium', false, array( 'loading' => 'lazy' ) ); ?>
										</button>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<div class="product-info">
							<div class="product-detail__meta">
								<?php if ( $fuhao_terms && ! is_wp_error( $fuhao_terms ) ) : ?>
									<?php foreach ( $fuhao_terms as $fuhao_term ) : ?>
										<?php $fuhao_term_link = get_term_link( $fuhao_term ); ?>
										<?php if ( ! is_wp_error( $fuhao_term_link ) ) : ?>
											<a class="chip" href="<?php echo esc_url( $fuhao_term_link ); ?>"><?php echo esc_html( $fuhao_term->name ); ?></a>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php if ( ! empty( $fuhao_supply['label'] ) ) : ?>
									<span class="chip chip--supply"><?php echo esc_html( $fuhao_supply['label'] ); ?></span>
								<?php endif; ?>
							</div>

							<h1><?php the_title(); ?></h1>

							<?php if ( has_excerpt() ) : ?>
								<p class="product-info__summary"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>

							<?php if ( $fuhao_facts ) : ?>
								<dl class="product-facts">
									<?php foreach ( $fuhao_facts as $fuhao_fact_label => $fuhao_fact_value ) : ?>
										<div>
											<dt><?php echo esc_html( $fuhao_fact_label ); ?></dt>
											<dd><?php echo esc_html( $fuhao_fact_value ); ?></dd>
										</div>
									<?php endforeach; ?>
								</dl>
							<?php endif; ?>

							<div class="product-actions">
								<a class="btn btn--accent" href="<?php echo esc_url( fuhao_page_url( 'customization' ) . '#quote' ); ?>"><?php esc_html_e( '获取报价', 'fuhao' ); ?></a>
								<a class="btn btn--outline" href="<?php echo esc_url( fuhao_product_archive_url() ); ?>"><?php esc_html_e( '返回产品中心', 'fuhao' ); ?></a>
							</div>

							<ul class="product-assure">
								<li><?php esc_html_e( '支持来样、来图与参考款定制', 'fuhao' ); ?></li>
								<li><?php esc_html_e( '支持 LOGO 印刷与颜色定制', 'fuhao' ); ?></li>
								<li><?php esc_html_e( '起订量与交期按项目确认', 'fuhao' ); ?></li>
							</ul>
						</div>
					</div>

					<section class="find-product" aria-labelledby="find-product-title">
						<div class="find-product__intro">
							<p class="eyebrow"><?php esc_html_e( '快速联系', 'fuhao' ); ?></p>
							<h2 id="find-product-title"><?php esc_html_e( '找到这款产品', 'fuhao' ); ?></h2>
							<p class="find-product__text"><?php esc_html_e( '扫码保存本页，或通过 1688、微信直接联系我们确认同款、起订量与交期。', 'fuhao' ); ?></p>
						</div>

						<div class="find-product__grid">
							<?php if ( $fuhao_1688 ) : ?>
								<div class="find-card">
									<h3><?php esc_html_e( '1688 同款', 'fuhao' ); ?></h3>
									<p><?php esc_html_e( '在线查看同款详情与店铺报价。', 'fuhao' ); ?></p>
									<div class="find-card__qr" data-fuhao-qr="<?php echo esc_url( $fuhao_1688 ); ?>" data-fuhao-qr-alt="<?php esc_attr_e( '1688 商品二维码', 'fuhao' ); ?>"></div>
									<a class="btn btn--accent btn--sm" href="<?php echo esc_url( $fuhao_1688 ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( '前往 1688', 'fuhao' ); ?></a>
								</div>
							<?php endif; ?>

							<div class="find-card">
								<h3><?php esc_html_e( '本页二维码', 'fuhao' ); ?></h3>
								<div class="find-card__qr" data-fuhao-qr="<?php echo esc_url( $fuhao_link ); ?>"></div>
								<button class="btn btn--outline btn--sm" type="button" data-fuhao-copy="<?php echo esc_url( $fuhao_link ); ?>"><?php esc_html_e( '复制本页链接', 'fuhao' ); ?></button>
								<p class="find-card__hint" aria-live="polite"></p>
								<noscript><a href="<?php echo esc_url( $fuhao_link ); ?>"><?php esc_html_e( '打开本页链接', 'fuhao' ); ?></a></noscript>
							</div>

							<div class="find-card">
								<h3><?php esc_html_e( '联系业务', 'fuhao' ); ?></h3>
								<p><?php esc_html_e( '询价、起订量与交期，到联系我们页面留言或直接致电。', 'fuhao' ); ?></p>
								<a class="btn btn--outline btn--sm" href="<?php echo esc_url( fuhao_page_url( 'contact' ) ); ?>"><?php esc_html_e( '联系我', 'fuhao' ); ?></a>
							</div>

							<?php if ( $fuhao_wechat ) : ?>
								<div class="find-card">
									<h3><?php esc_html_e( '微信咨询', 'fuhao' ); ?></h3>
									<img class="find-card__qr-img" src="<?php echo esc_url( $fuhao_wechat ); ?>" alt="<?php esc_attr_e( '微信二维码', 'fuhao' ); ?>" loading="lazy">
									<p><?php esc_html_e( '扫码添加微信，直接沟通。', 'fuhao' ); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</section>

					<?php if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
						<div class="product-content entry-content">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
				</article>
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
