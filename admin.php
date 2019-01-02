<?php 
add_filter( 'woocommerce_get_sections_products', 'woocompaypal_inr_add_section' );
function woocompaypal_inr_add_section( $sections ) {
	
	$sections['wcinrpaypal'] = 'Paypal INR';
	return $sections;
	
}

add_filter( 'woocommerce_get_settings_products', 'woocompaypal_inr_all_settings', 10, 2 );
function woocompaypal_inr_all_settings( $settings, $current_section ) {
	/**
	 * Check the current section is what we want
	 **/
	if ( $current_section == 'wcinrpaypal' ) {
		$settings_wcpaypalinr = array();
		
		$settings_wcpaypalinr[] = array( 'name' => __( 'WC Paypal INR', 'wcinrpaypal' ), 'type' => 'title', 'desc' => __( 'Paypal INR settings', 'wcinrpaypal' ), 'id' => 'wcinrpaypal' );
		
		$settings_slider[] = array(
			'name'     => __( 'Openexchangerates API ', 'text-domain' ),
			'desc_tip' => __( 'You can find one for free from here: <a href="https://openexchangerates.org/signup/free">https://openexchangerates.org/signup/free</a>', 'wcinrpaypal' ),
			'id'       => 'wcinrpaypal_api',
			'type'     => 'text',
			'desc'     => __( 'You can find one for free from here: <a href="https://openexchangerates.org/signup/free">https://openexchangerates.org/signup/free</a>', 'wcinrpaypal' ),
		);

		
		return $settings_wcpaypalinr;
	/**
	 * If not, return the standard settings
	 **/
	} else {
		return $settings;
	}
}