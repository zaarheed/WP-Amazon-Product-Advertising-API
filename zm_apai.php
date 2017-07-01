<?php
/*
	Plugin Name: Amazon Product Advertising API
	Description: Interacts with the Amazon Product Advertising API via values stored in Custom Fields
	Author: Zahid Mahmood
	Version: 0.2
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
		'isEligibleForPrime' => (boolean)$xml->Items->Item[0]->Offers->Offer->OfferListing->IsEligibleForPrime,
		'name' => (string)$xml->Items->Item[0]->ItemAttributes->Title,
		'fprice' => (string)$xml->Items->Item[0]->Offers->Offer->OfferListing->Price->FormattedPrice,
		'url' => (string)$xml->Items->Item[0]->DetailPageURL
		);
		
	return $product;
}

// executed when plugin is de-activated
register_deactivation_hook( __FILE__, 'zm_apai_deactivate' );
function zm_apai_deactivate() {
    
    // unregister setting fields
    unregister_setting( 'zm_apai-options', 'zm_apai-show-link');
    unregister_setting( 'zm_apai-options', 'zm_apai-aws-access-key');
    unregister_setting( 'zm_apai-options', 'zm_apai-aws-secret-key');
    unregister_setting( 'zm_apai-options', 'zm_apai-associate-tag');
}


if (esc_attr( get_option("zm_apai-show-link") ) == "below-post") {
	add_filter('the_content', 'zm_apai_append_content');
	function zm_apai_append_content($content) {
		
		global $post; // need this so we can access custom fields
		
		$asin = get_post_meta( $post->ID, 'apai_asin', true );
		
		if ($asin != '' && function_exists('zm_apai_get_product_by_asin')) {
		
			$product = zm_apai_get_product_by_asin($asin);
			
			// only return purchase link if the product offer is eligible for Prime
			if ($product['isEligibleForPrime'] == true) {
				$aftercontent = zm_apai_get_html_after_post($product);
				
				$fullcontent = $content . $aftercontent;
				
				return $fullcontent;
			}
			else {
				return $content;
			}
			
		}
	}
}