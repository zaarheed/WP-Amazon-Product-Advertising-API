Amazon Product Advertising API (APAI)
=========================

A dead-simple and lightweight plugin which allows interaction with the Amazon Product Advertising API. Designed for posts which review products and wish to earn affiliate comission via Amazon by promoting sales links.

Features
--------
* Super simple and lightweight
* Direct integration with the Amazon Product Advertising API (no external libraries)
* Perfect balance of user-friendly and developer-friendly
* Actively used by myself so features in the pipeline reflect real-world needs

Installation
-------------------------
This plugin has not yet been published to the WordPress plugin repository.

1. Clone this repository in to your `wp-content/plugins` directory
2. Activate the plugin
3. Navigate to the plugin settings page at Settings > APAI
4. Enter your AWS Access Key, AWS Secret Key and Amazon Associate Tag ID and click Save Changes.

Usage
-------------------------
From the plugin settings page enable the option to 'Automatically display below post'. Then enter the ASIN in the metabox on the post editor page and links will automatically appear at the bottom of each post. The link will contain your Amazon Associate Tag as entered in the plugin settings page.

Developers
-------------------------
The automatic link at the bottom of the post content is currently non-configurable (nor is it well-styled in all honesty!). Developers may prefer to make modifications to their theme files to display the link to their preference.

The following example code can be added to `single.php`. A full API documentation will be added here soon.

`
<?php
    if (get_post_meta( $post->ID, 'apai_asin', true ) != '' && function_exists('zm_apai_get_product_by_asin')) {
    
        $apai_product = zm_apai_get_product_by_asin(get_post_meta( $post->ID, 'apai_asin', true )); ?>
        
        <div class="buyonamazon">
            <a class="btn btn-buyonamazon btn-md btn-block" href="<?php echo $apai_product['url']; ?>">
               Buy on Amazon for <?php echo $apai_product['fprice']; ?>
            </a>
        </div>
                    
<?php } ?>
`