<?php
/*
 * SETTING UP METABOX
 */
 
/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'zm_apai_meta_boxes_setup' );
add_action( 'load-post-new.php', 'zm_apai_meta_boxes_setup' );

/* Meta box setup function. */
function zm_apai_meta_boxes_setup() {
    /* Add meta boxes on the 'add_meta_boxes' hook. */
    add_action( 'add_meta_boxes', 'zm_apai_add_post_meta_boxes' );
    
    /* Save post meta on the 'save_post' hook. */
    add_action( 'save_post', 'zm_apai_save_post_zm_apai_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function zm_apai_add_post_meta_boxes() {
  add_meta_box(
    'zm_apai-meta',      // Unique ID
    'Amazon Product Advertising API',    // Title
    'zm_apai_meta_meta_box',   // Callback function
    'post',         // Screen
    'side',         // Context
    'default'         // Priority
  );
}

/* Display the post meta box. */
function zm_apai_meta_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'zm_apai-meta_nonce' ); ?>

  <p>
      <label for="zm_apai-meta-duration">ASIN</label>
      <input class="widefat" type="text" name="zm_apai-meta-asin" id="zm_apai-meta-asin" value="<?php echo esc_attr( get_post_meta( $object->ID, 'apai_asin', true ) ); ?>" size="30" />
  </p>
<?php }

/*
 * SAVE METABOX
 */
/* Save the meta box's post metadata. */
function zm_apai_save_post_zm_apai_meta( $post_id, $post ) {
    
    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['zm_apai-meta_nonce'] ) || !wp_verify_nonce( $_POST['zm_apai-meta_nonce'], basename( __FILE__ ) ) )
            return $post_id;
    
    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );
    
    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;
    
    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value_asin = ( isset( $_POST['zm_apai-meta-asin'] ) ? $_POST['zm_apai-meta-asin'] : '' );
    
    /* Get the meta key. */
    $meta_key_asin = 'apai_asin';
    
    /* Get the meta value of the custom field key. */
    $meta_value_asin = get_post_meta( $post_id, $meta_key_asin, true );
    
    
    
    // Update ASIN Meta [text]
    if ( $new_meta_value_asin && '' == $meta_value_asin ) {
        // If a new meta value was added and there was no previous value, add it. 
        add_post_meta( $post_id, $meta_key_asin, $new_meta_value_asin, true );
    }
    elseif ( $new_meta_value_asin && $new_meta_value_asin != $meta_value_asin ) { 
        // If the new meta value does not match the old value, update it.
        update_post_meta( $post_id, $meta_key_asin, $new_meta_value_asin );
    }
    elseif ( '' == $new_meta_value_asin && $meta_value_asin ) { 
        // If there is no new meta value but an old value exists, delete it.
        delete_post_meta( $post_id, $meta_key_asin, $meta_value_asin );
    }
}
?>