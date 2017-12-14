<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;

add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION
// Register Front Sidebar Top
register_sidebar( 
	array (
		'name'          => esc_html__( 'Front Page: Top Area', 'estore' ),
		'id'            => 'estore_sidebar_front_top',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	) 
);

function SS_add_to_cart_validation( $passed, $product_id, $quantity ) { 
    if ( WC()->cart->is_empty() ) return $passed;

    // Get the VendorId of the product being added to the cart.
    $current_vendor = get_wcmp_product_vendors($product_id);

    foreach( WC()->cart->get_cart() as $cart_item ) {
        // Get the vendor Id of the item
        $cart_vendor = get_wcmp_product_vendors($cart_item['product_id']);

        // If two products do not have the same Vendor
        if( $current_vendor->id != $cart_vendor->id ) {
            // We set 'passed' argument to false
            $passed = false ;

            // Displaying a custom message
            $message = __( "You can only buy from one vendor at time.", "woocommerce" );
            wc_add_notice( $message, 'error' );
            // We stop the loop
            break; 
        }
    }
    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'SS_add_to_cart_validation', 10, 3 );

//Change BACS fields based on vendor who the order is for.
function SS_bacs_fields_per_vendor( $array, $order_id ) {
	global $wpdb;

	$order = wc_get_order( $order_id );
	$line_items = $order->get_items();

	foreach ( $line_items as $item ) {
		$product_id = $item->get_product_id();
	}

	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	if ( !empty($vendor_form_data) ) {
		$account_details = array(
			'account_name' 		=> $vendor_form_data[8][value], 
			'account_number'	=> $vendor_form_data[9][value], 
			'sort_code' 		=> $vendor_form_data[7][value],
			'bank_name' 		=> $vendor_form_data[6][value],
			'bic'               => $vendor_form_data[10][value]
		);

		$account_fields = array(
			'Ref' 				=> array(
            	'label' => __( 'Ref', 'woocommerce' ),
            	'value' => $order_id
            ),
            'bank_name'			=> array(
        	    'label'	=> __( 'Bank', 'woocommerce' ),
        	    'value' => $account_details['bank_name']
            ),
            'account_name'  	=> array(
            	'label' => __( 'Account Name', 'woocommerce' ),
            	'value' => $account_details['account_name']
            ),
            'account_number'	=> array(
            	'label' => __( 'Account Number', 'woocommerce' ),
            	'value' => $account_details['account_number']
            ),
            'sort_code' 		=> array(
            	'label' => __( 'Branch Code', 'woocommerce' ),
            	'value' => $account_details['sort_code']
            ),
            'bic' 				=> array(
            	'label' => __( 'Account Type', 'woocommerce' ),
            	'value' => $account_details['bic']
            )
		);

		return $account_fields;
	}
}
add_filter('woocommerce_bacs_account_fields','SS_bacs_fields_per_vendor',10,2);

//Display the payment gateway that the vendor accepts.
function SS_change_wc_gateway_per_vendor( $allowed_gateways ){
 
	foreach( WC()->cart->get_cart() as $cart_item ) {
		$product_id = $cart_item['product_id'];
	}

	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	if ( !empty($vendor_form_data) ) {
		$payment_gateways = $vendor_form_data[0][value];
		$allowed_gateways = array();
		$all_gateways = WC()->payment_gateways->payment_gateways();
	    
	    foreach ($payment_gateways as $payment_gateway) {
		    if ($payment_gateway == "bacs") {
		      $allowed_gateways['bacs'] = $all_gateways  ['bacs'];
		    } elseif ($payment_gateway == "payfast") {
		        echo $payment_gateway;
		         $allowed_gateways['payfast'] = $all_gateways  ['payfast'];
		    }
		}
	}
 
	return $allowed_gateways;
}
add_filter('woocommerce_available_payment_gateways','SS_change_wc_gateway_per_vendor', 10, 1 );

//Display the delivery methods that the vendor supports.
function SS_show_wc_shipping_per_vendor( $rates ) {
    $allowed_shipping = array();
    
    foreach( WC()->cart->get_cart() as $cart_item ) {
		$product_id = $cart_item['product_id'];
	}

	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);

	$vendor = get_wcmp_product_vendors($product_id);
	$vendor_form_id = (int)get_user_meta($vendor->id, 'wcmp_vendor_registration_form_id', true);
	$vendor_form_data = get_post_meta($vendor_form_id, 'wcmp_vendor_fields', true);
	
	$shipping_methods = $vendor_form_data[12][value];
	
	foreach ($shipping_methods as $shipping_method) {
        foreach ( $rates as $rate_id => $rate ) {
            if ( $shipping_method === $rate->method_id ) {
                $allowed_shipping[ $rate_id ] = $rate;
                break;
            }
        }
    }
    return ! empty( $allowed_shipping ) ? $allowed_shipping : $rates;

}
add_filter( 'woocommerce_package_rates', 'SS_show_wc_shipping_per_vendor', 10 );
