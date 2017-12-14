<?php
/*
 * Template Name: WooCommerce Category List
 *
 * @package ThemeGrill
 * @subpackage eStore
 * @since eStore 1.0
 */

	get_header();

	$estore_layout = estore_layout_class();

	$taxonomy     = 'product_cat';
	$orderby      = 'name';  
	$show_count   = 0;      // 1 for yes, 0 for no
	$pad_counts   = 0;      // 1 for yes, 0 for no
	$hierarchical = 1;      // 1 for yes, 0 for no  
	$title        = '';  
	$empty        = $empty;

	$category_args= array(
		 'taxonomy'     => $taxonomy,
		 'orderby'      => $orderby,
		 'show_count'   => $show_count,
		 'pad_counts'   => $pad_counts,
		 'hierarchical' => $hierarchical,
		 'title_li'     => $title,
		 'hide_empty'   => $empty
	);

	$woo_categories = get_categories( $category_args );
	
?>
	<div id="content" class="site-content">
		<!-- #content.site-content -->
		<main id="main" class="clearfix <?php echo esc_attr($estore_layout); ?>">
			<div class="tg-container">
				<div id="primary">
					<div class="entry-content-text-wrapper clearfix">
						<div class="entry-content-wrapper">
<?php
	foreach ($woo_categories as $cat) {
		if($cat->category_parent == 0) {
			$category_id = $cat->term_id;
?>
							<div class="entry-content" style="text-align: left">
								<div class="tg-column-wrapper">
									<div class="tg-column-1">
<?php 
			echo '<h4><a href="http://mall.redlorry.co.za/stores/?vendor_sort_type=category&vendor_sort_category=' . $category_id . '">' . $cat->name . '</a></h4>';
?>
									</div>
								</div>
								<div class="tg-column-wrapper">
<?php
			$sub_category_args = array(
				'taxonomy'     => $taxonomy,
				'child_of'     => 0,
				'parent'       => $category_id,
				'orderby'      => $orderby,
				'show_count'   => $show_count,
				'pad_counts'   => $pad_counts,
				'hierarchical' => $hierarchical,
				'title_li'     => $title,
				'hide_empty'   => $empty
			);

			$woo_sub_categories = get_categories( $sub_category_args );
			if($woo_sub_categories) {
				$c = 0;
				$started = 0;
				foreach($woo_sub_categories as $sub_cat) {
					$sub_cat_id = $sub_cat->term_id;
					if ( $c % 4 == 0 && $started == 1 ) {
						echo '</div><div class="tg-column-wrapper"><div class="tg-column-4"><a href="http://mall.redlorry.co.za/stores/?vendor_sort_type=category&vendor_sort_category=' . $sub_cat_id . '"> - '. $sub_cat->name .'</a></div>';
					}
					else {
						echo '<div class="tg-column-4"><a href="http://mall.redlorry.co.za/stores/?vendor_sort_type=category&vendor_sort_category=' . $sub_cat_id . '"> - '. $sub_cat->name .'</a></div>';
					}
					$c++;	
					$started = 1;
				}   
			}
?>  
								</div>
						
<?php							
		}
?>

				<?php wp_reset_postdata(); ?>
				<?php } // endforeach ?>
							</div>
						</div>
					</div> <!-- Primary end -->
			<?php
			/**
			 * woocommerce_sidebar hook
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			do_action( 'woocommerce_sidebar' );
			?>
		 		</div>
		 	</div>
		</main>
   <!-- #content .site-content -->

<?php get_footer(); ?>
