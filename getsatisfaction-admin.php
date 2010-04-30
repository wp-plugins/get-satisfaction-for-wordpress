<?php
include_once('getsatisfaction-config.php');
include_once('getsatisfaction-templatetags.php');
include_once('classes/Satisfaction.php');

// Process Option Form
if (isset($_POST['api_key']) && isset($_POST['api_secret'])) {
    
    update_option('gs.api_key', $_POST['api_key'], $deprecated = '', 'yes');
    update_option('gs_api_secret', $_POST['api_secret'], $deprecated = '', 'yes');
    
    $consumer_data = array(
  		'key' => $_POST['api_key'],
  		'secret' => $_POST['api_secret']
    );
    
    // generate oauth request token
    $token = get_oauth_request_token($consumer_data);

    setcookie("oauth_request_token_key", $token[0]);
    setcookie("oauth_request_token_secret", $token[1]);
    
    $callback_url = get_bloginfo('wpurl').'/wp-admin/options-general.php?page=get-satisfaction-for-wordpress/getsatisfaction-admin.php&updated=true';
    $auth_url = oauth_authorization_url($token[0], $callback_url);

    // Redirect to authorization page
    Header("Location: $auth_url");    
}

// Process Callback
if (array_key_exists('updated', $_GET)) {

    $_COOKIE['oauth_token'] = $_REQUEST['oauth_token'];

    // save api user information
    update_option('gs.api_user_name', $_COOKIE['user_name'], $deprecated = '', 'yes');
    update_option('gs.api_user_nick', $_COOKIE['user_nick'], $deprecated = '', 'yes');
    update_option('gs.api_canonical_name', $_COOKIE['canonical_name'], $deprecated = '', 'yes');
    update_option('gs.api_avatar_url', $_COOKIE['avatar_url'], $deprecated = '', 'yes');
    
    $api_key = get_option('gs.api_key');
    $api_secret = get_option('gs_api_secret');
    
    $consumer_data = array (
    	'key' => $api_key,
    	'secret' => $api_secret
    );
    
    $request_token = $_COOKIE['oauth_request_token_key'];
    $request_token_secret = $_COOKIE['oauth_request_token_secret'];
    
    // generate oauth access token
    $access_token = get_oauth_access_token($consumer_data, $request_token, $request_token_secret);

    // save oauth access token for the duration of 
    setcookie("oauth_access_token_key", $access_token[0]);
    setcookie("oauth_access_token_secret", $access_token[1]);    
}


function get_satisfaction_add_option_page()
{
    $icon_url = get_bloginfo('wpurl') .'/wp-content/plugins/get-satisfaction-for-wordpress/images/gs-icon.png';
    add_menu_page('Get Satisfaction', 'Get Satisfaction', 8, __FILE__, 'get_satisfaction_option_page', $icon_url);
}

// Register Option Pages
add_action('admin_menu', 'get_satisfaction_add_option_page');

function get_satisfaction_option_page() {        
    include('views/options.php');
}
?>