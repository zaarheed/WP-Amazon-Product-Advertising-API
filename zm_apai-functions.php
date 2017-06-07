<?php

function zm_apai_create_signed_url($asin) {
    
    $aws_access_key_id = "AKIAJX5X5MWUBSDCHPVA";
    $aws_secret_key = "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";
    $aws_associate_tag = "zb00-21";
    

    // The region you are interested in
    $endpoint = "webservices.amazon.co.uk";

    $uri = "/onca/xml";

    $params = array(
        "Service" => "AWSECommerceService",
        "Operation" => "ItemLookup",
        "AWSAccessKeyId" => $aws_access_key_id,
        "AssociateTag" => $aws_associate_tag,
        "ItemId" => $asin,
        "IdType" => "ASIN",
        "ResponseGroup" => "Images,ItemAttributes,Offers"
    );

    // Set current timestamp if not set
    if (!isset($params["Timestamp"])) {
        $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
    }

    // Sort the parameters by key
    ksort($params);

    $pairs = array();

    foreach ($params as $key => $value) {
        array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
    }

    // Generate the canonical query
    $canonical_query_string = join("&", $pairs);

    // Generate the string to be signed
    $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

    // Generate the signature required by the Product Advertising API
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));
    

    // Generate the signed URL
    $apai_signed_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
    
    return $apai_signed_url;
}

function zm_apai_parse_xml($url) {
    $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

    $xml = file_get_contents($url, false, $context);
    
    $xml = simplexml_load_string($xml);
    
    return $xml;
}

function zm_apai_get_html_after_post($product) {
    return '<div><a href="' . $product['url'] .'">Buy ' . $product['name'] . 'on Amazon for' . $product['fprice'] .'</a>';
}