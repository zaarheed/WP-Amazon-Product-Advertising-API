<?php

// register settings
add_action('admin_init', 'zm_apai_admin_init');
function zm_apai_admin_init() {
    
    // some pre-defined values to make this easier
    $page = 'zm_apai-options'; // the Settings page you want your fields to appear
    $group = 'zm_apai-options'; // just keep this the same name as the page
    
    // create a section for 'general settings'
    add_settings_section(
        'zm_apai-general', // name of the section
        'General', // title of the section
        '', // callback to render before the fields (optional)
        $page // page this section appears on
    );
    
    // register the setting field
    register_setting($group, 'zm_apai-show-link'); // must match 'name' field on input element
    register_setting($group, 'zm_apai-aws-access-key'); // must match 'name' field on input element
    register_setting($group, 'zm_apai-aws-secret-key'); // must match 'name' field on input element
    register_setting($group, 'zm_apai-associate-tag'); // must match 'name' field on input element
    
    // add setting field to the section
    add_settings_field(
        'zm_apai-show-link', // name of the section to which you want to add a field
        'Automatically display below post', // title for that settings field
        'zm_apai_show_link_render', // function that will render the field
        $page,
        'zm_apai-general' // section name
    );
    
    add_settings_field(
        'zm_apai-aws-access-key', // name of the section to which you want to add a field
        'AWS Access Key', // title for that settings field
        'zm_apai_aws_access_key_render', // function that will render the field
        $page,
        'zm_apai-general' // section name
    );
    
    add_settings_field(
        'zm_apai-aws-secret-key', // name of the section to which you want to add a field
        'AWS Secret Key', // title for that settings field
        'zm_apai_aws_secret_key_render', // function that will render the field
        $page,
        'zm_apai-general' // section name
    );
    
    add_settings_field(
        'zm_apai-associate-tag', // name of the section to which you want to add a field
        'Associate Tag', // title for that settings field
        'zm_apai_associate_tag_render', // function that will render the field
        $page,
        'zm_apai-general' // section name
    );
}



// renders the input box for 'show link automatically'
function zm_apai_show_link_render() {
    
    // to set the default value to false if the checkbox is left un-checked
    echo '<input type="hidden" name="zm_apai-show-link" value="false" checked="checked" />';
    
    if (esc_attr( get_option("zm_apai-show-link") ) != "false") {
        echo '<input type="checkbox" name="zm_apai-show-link" value="below-post" checked="checked" />';
    }
    else {
        echo '<input type="checkbox" name="zm_apai-show-link" value="below-post" />';
    }
}

// renders the input box for 'aws access key'
function zm_apai_aws_access_key_render() {
    
    if (esc_attr( get_option("zm_apai-aws-access-key") ) != "") {
        echo '<input type="text" name="zm_apai-aws-access-key" value="' . esc_attr( get_option("zm_apai-aws-access-key") ) . '" />';
    }
    else {
        echo '<input type="text" name="zm_apai-aws-access-key" />';
    }
}

// renders the input box for 'aws secret key'
function zm_apai_aws_secret_key_render() {
    
    if (esc_attr( get_option("zm_apai-aws-secret-key") ) != "") {
        echo '<input type="text" name="zm_apai-aws-secret-key" value="' . esc_attr( get_option("zm_apai-aws-secret-key") ) . '" />';
    }
    else {
        echo '<input type="text" name="zm_apai-aws-secret-key" />';
    }
}

// renders the input box for 'associate tag'
function zm_apai_associate_tag_render() {
    
    if (esc_attr( get_option("zm_apai-associate-tag") ) != "") {
        echo '<input type="text" name="zm_apai-associate-tag" value="' . esc_attr( get_option("zm_apai-associate-tag") ) . '" />';
    }
    else {
        echo '<input type="text" name="zm_apai-associate-tag" />';
    }
}

// create custom plugin settings menu
add_action('admin_menu', 'zm_apai_create_menu');
function zm_apai_create_menu() {

	//create new top-level menu

    // create new menu-item under Settings
    add_submenu_page(
        'options-general.php', // parent slug
        'Amazon Product Advertising API', // page_title
        'APAI', // menu_title,
        'administrator', // capability,
        'zm_apai-options', // menu_slug,
        'zm_apai_settings_page' // callable
    );
}

function zm_apai_settings_page() {
?>
<div class="wrap">
<h1>Amazon Product Advertising API</h1>

<form method="post" action="options.php" method="post">
    <?php settings_fields( 'zm_apai-options' ); ?>
    <?php do_settings_sections( 'zm_apai-options' ); ?>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>