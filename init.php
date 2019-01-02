<?php 
/*
	Plugin Name: Woocoomerce Paypal INR Support
	Description: This plugin allows you to make use of Paypal as payment gateway while selling products in Indian Rupee (INR) currency. 
	Version: 0.9
*/


add_filter( 'woocommerce_currencies', 'inr_currency' );

function inr_currency( $currencies ) {
    $currencies['INR'] = __( 'Indian Rupees', 'woocommerce' );
    return $currencies;
}

add_filter('woocommerce_currency_symbol', 'inr_currency_symbol', 10, 2);

function inr_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
    case 'INR': $currency_symbol = 'Rs'; break;
	}	
	return $currency_symbol;
}


add_filter("woocommerce_paypal_supported_currencies" , "add_inr_to_paypal_cur");

function add_inr_to_paypal_cur($cur) {
	$cur[] = "INR";
	return $cur;
}

function convert_inr_to_usd($paypal_args){

    if ( $paypal_args['currency_code'] == 'INR'){
        $convert_rate = get_exchange_rate(); 
        //Set converting rate getting call back function
        $count = 1;

        while( isset($paypal_args['amount_' . $count]) ){
            $paypal_args['amount_' . $count] = round( $paypal_args['amount_' . $count] / $convert_rate, 2);
            $count++;
        }
    }
    return $paypal_args;
}
add_filter('woocommerce_paypal_args', 'convert_inr_to_usd');


function get_exchange_rate() {
    $file = 'latest.json';
	$appId = get_option( 'wcinrpaypal_api' );

	// Open CURL session:
	$ch = curl_init("http://openexchangerates.org/api/$file?app_id=".$appId);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Get the data:
	$json = curl_exec($ch);
	curl_close($ch);

	// Decode JSON response:
	$exchangeRates = json_decode($json);

	// Returning Value:
	return $exchangeRates->rates->INR;
}


// ----------------- admin section -----------------

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
		
		$settings_wcpaypalinr[] = array( 'name' => __( 'WC Paypal INR', 'wcinrpaypal' ), 'type' => 'title', 'desc' => __( 'Paypal INR settings', 'wcinrpaypal' ), 'id' => 'wcinrpaypal_title' );
		
		$settings_wcpaypalinr[] = array(
			'name'     => __( 'Openexchangerates API ', 'text-domain' ),
			'desc_tip' => __( 'You can find one for free from here: <a target="_blank" href="https://openexchangerates.org/signup/free">https://openexchangerates.org/signup/free</a>', 'wcinrpaypal' ),
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