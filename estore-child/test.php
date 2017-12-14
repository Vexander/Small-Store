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
	
	foreach( WC()->cart->get_cart() as $cart_item ) {
		$product_id = $cart_item['product_id'];
	}

	//Get the product vendor based on the $product_id.
	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	if ( !empty($vendor_form_data) ) {
		$payment_gateways = $vendor_form_data[0][value]
		$allowed_gateways = array();
		$all_gateways = WC()->payment_gateways->payment_gateways();
		$allowed_gateways['paypal_pro_payflow'] = $all_gateways['paypal_pro_payflow'];
		$allowed_gateways['paypal_pro_payflow'] = $all_gateways['paypal_pro_payflow'];
	}
	foreach ($payment_gateways as $gateway) {
		
	}
	
	/*
	$order = wc_get_order( 409 );
	$line_items = $order->get_items();

	foreach ( $line_items as $item ) {
  		// This will be a product
	   $product_id = $item->get_product_id();
	}

   //Get the product vendor based on the $product_id.
   $vendor = get_wcmp_product_vendors($product_id);
   //Get the form_id of the vendor.
   $vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
   //Get the vendors form meta data.
   $vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	//Get the product vendor based on the $product_id.
	$vendor = get_wcmp_product_vendors($product_id);
	//Get the form_id of the vendor.
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	//Get the vendors form meta data.
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	

        $account_fields = array(
            'bank_name'			=> array(
        	    'label'	=> 'Bank',
        	    'value' => $account_details[0]['bank_name']
            ),
            'account_name'  	=> array(
            	'label' => 'Account Name',
            	'value' => $account_details[0]['account_name']
            ),
            'account_number'	=> array(
            	'label' => 'Account Number',
            	'value' => $account_details[0]['account_number']
            ),
            'sort_code' 		=> array(
            	'label' => 'Sort Code',
            	'value' => $account_details[0]['sort_code']
            ),
            'bic' 				=> array(
            	'label' => 'BIC',
            	'value' => $account_details[0]['bic']
            )
        );
	} //end if
	*/
?>
	<div id="content" class="site-content">
		<!-- #content.site-content -->
		<main id="main" class="clearfix <?php echo esc_attr($estore_layout); ?>">
			<div class="tg-container">
				<div id="primary">
					<div class="entry-content-text-wrapper clearfix">
						<div class="entry-content-wrapper">
<?php
	print_r($payment_gateways)							
	/*
	echo "<br />\n";
    echo $account_details['account_number'];
    echo "<br />\n";
    echo $account_details['sort_code'];
    echo "<br />\n";
    echo $account_details['bank_name'];
    echo "<br />\n";
    echo $account_details['bic'];
	*/
?>
					    </div>
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
		</main>
   <!-- #content .site-content -->

<?php get_footer(); ?>
