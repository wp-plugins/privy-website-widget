<?php
/*
Plugin Name: Privy Website Widget
Plugin URI: http://blog.privy.com/blog/2015/5/how-to-install-the-privy-wordpress-plugin
Description: Simple website banners and exit intent popups to grow your email list.
Version: 2.0
Author: Privy Inc.
Author URI: http://privy.com/
License: MIT
*/

add_action('admin_menu', 'privy_create_settings_page');

function privy_create_settings_page() {
	add_options_page('Privy Website Widget', 'Privy Website Widget', 'manage_options', 'privy_settings_page', 'privy_settings_page');
}

add_action( 'admin_init', 'register_privy_settings' );

function register_privy_settings() {
	register_setting( 'privy-settings-group', 'account_identifier', 'privy_settings_validate' );
}

function privy_settings_page() {
  if (!current_user_can('manage_options'))
  {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  include(sprintf('%s/templates/settings.php', dirname(__FILE__)));
}

function privy_settings_validate($input) {
	$newinput = $input;
	if(!preg_match('/^[a-z0-9]{24}$/i', $newinput)) {
		$newinput = '';
	}
	return $newinput;
}

function privy_widget() {
	wp_enqueue_script('privy-marketing-widget', plugins_url('privy-marketing-widget.js', __FILE__));
	$params = array('account_identifier' => get_option('account_identifier'));
	wp_localize_script('privy-marketing-widget', 'PrivyWebsiteWidgetParams', $params);
}

add_action('wp_footer', 'privy_widget');

?>