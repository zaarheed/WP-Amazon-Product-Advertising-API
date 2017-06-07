<?php
/*
	Plugin Name: Amazon Product Advertising API
	Description: Interacts with the Amazon Product Advertising API via values stored in Custom Fields
	Author: Zahid Mahmood
	Version: 1.0
	Author URI: http://www.zahidmahmood.co.uk
*/

// Include plugin files
require_once dirname( __FILE__ ) .'/zm_apai-functions.php';
require_once dirname( __FILE__ ) .'/zm_apai-options.php';
require_once dirname( __FILE__ ) .'/zm_apai-metabox.php';

function zm_apai_get_product_by_asin($asin) {
	
	// get signed URL
	$url = zm_apai_create_signed_url($asin);
	
	// parse XML from Amazon API
	$xml = zm_apai_parse_xml($url);
	
	// build product object
	$product = array(
		'name' => (string)$xml->Items->Item[0]->ItemAttributes->Title,
		'fprice' => (string)$xml->Items->Item[0]->ItemAttributes->ListPrice->FormattedPrice,
		'url' => (string)$xml->Items->Item[0]->DetailPageURL
		);
		
	return $product;
}

// executed when plugin is de-activated
register_deactivation_hook( __FILE__, 'zm_apai_deactivate' );
function zm_apai_deactivate() {
    
    // unregister setting fields
    unregister_setting( 'zm_apai-options', 'zm_apai-show-link');
}