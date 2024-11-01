<?php

/*
  Plugin Name: WP TowTruck
  Plugin URI: http://www.ilsitodiluca.it/plugins/wp-towtruck/
  Description: This plugin enables Mozilla TowTruck in your blog.
  Version: 1.1
  Author: totojack
  Author URI: http://www.ilsitodiluca.it
  License: GPLv2
 */

/*  Copyright 2013  totojack  (email : info@ilsitodiluca.it)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WP_TOWTRUCK_VERSION', '1.1');
define('WP_TOWTRUCK_DIR', WP_PLUGIN_DIR . '/wp-towtruck');
define('WP_TOWTRUCK_URL', WP_PLUGIN_URL . '/wp-towtruck');

//default options
$wptowtruck_default_options['activate'] = '0';
$wptowtruck_default_options['loggedusers'] = '0';
$wptowtruck_default_options['analytics'] = '0';
$wptowtruck_default_options['style'] = '';
$wptowtruck_default_options['button-text'] = 'TowTruck On/Off';

$wptowtruck_user_is_logged = false;

add_action('init', 'wptowtruck_init');
function wptowtruck_init() {
	global $wptowtruck_user_is_logged;
	$wptowtruck_user_is_logged = is_user_logged_in(); 
}

if (is_admin()) {
    add_action('admin_menu', 'wptowtruck_admin_menu');

    function wptowtruck_admin_menu() {
        add_options_page('WP TowTruck', 'WP TowTruck', 'manage_options', 'wp-towtruck/options.php');
    }
}

//load button only if activated
$wptowtruck_options = get_option('wptowtruck');
if ($wptowtruck_options['activate'] == 1) {
	wptowtruck_add_action(); 	
}

function wptowtruck_add_stylesheet() {
	global $wptowtruck_options;
	
	//we load button base style only if there isn't a custom style
	if (empty($wptowtruck_options['style']))
	{
		wp_register_style('wptowtruck-style', plugins_url('style.css', __FILE__), array(), WP_TOWTRUCK_VERSION);
		wp_enqueue_style('wptowtruck-style');
	}	
}

function wptowtruck_admin_init() {
	global $wptowtruck_options;
	
	if (strpos($_GET['page'], 'wp-towtruck/') === 0) {
		wp_enqueue_script('jquery-ui-tabs');
	}
	
	//we load button base style only if there isn't a custom style
	if (empty($wptowtruck_options['style']))
	{
		wp_register_style('wptowtruck-style-admin', plugins_url('style.css', __FILE__), array(), WP_TOWTRUCK_VERSION);
		wp_enqueue_style('wptowtruck-style-admin');
	}
}

function wptowtruck_add_action() {
	add_action('wp_head', 'wptowtruck_wp_head');
	add_action('admin_head', 'wptowtruck_admin_head');
	add_action('wp_enqueue_scripts', 'wptowtruck_add_stylesheet');
	add_action('the_content', 'wptowtruck_the_content');
	add_action('in_admin_footer', 'wptowtruck_admin_footer');
	add_action('admin_init', 'wptowtruck_admin_init');
}

function wptowtruck_wp_head() {
	global $wptowtruck_options;
	
	//is there a custom user style? add to <head> section
	if (!empty($wptowtruck_options['style'])) 
	{
		$result = "<style type='text/css'>\n";
		$result .= $wptowtruck_options['style'];
		$result .= "</style>";
		echo $result;
	}	
}

function wptowtruck_admin_head() {
	global $wptowtruck_options;
	
	if (strpos($_GET['page'], 'wp-towtruck/') === 0) {
		echo '<link type="text/css" rel="stylesheet" href="' . WP_TOWTRUCK_URL . '/admin.css?' . WP_TOWTRUCK_VERSION . '"/>';
		echo '<script src="' . WP_TOWTRUCK_URL . '/admin.js?' . WP_TOWTRUCK_VERSION . '"></script>';
	}
	
	//is there a custom user style? add to <head> section
	if (!empty($wptowtruck_options['style']))
	{
		$result = "<style type='text/css'>\n";
		$result .= $wptowtruck_options['style'];
		$result .= "</style>";
		echo $result;
	}
}

function wptowtruck_the_content($content) {
    return $content . wptowtruck_show();
}

function wptowtruck_show() {
	global $wptowtruck_options,$wptowtruck_user_is_logged;	
	
	//check if we must show button for logged in users
	if ($wptowtruck_options['loggedusers'] == 1 && $wptowtruck_user_is_logged == false) return;
	
	echo wp_towtruck_get_button_html();
}

function wptowtruck_admin_footer() {
	global $wptowtruck_options,$wptowtruck_user_is_logged;
	
	//check if we must show button for logged in users
	if ($wptowtruck_options['loggedusers'] == 1 && $wptowtruck_user_is_logged == false) return;
		
	echo wp_towtruck_get_button_html();
}

/**
 * Returns html code to be included in page
 */
function wp_towtruck_get_button_html() {
	global $wptowtruck_options;
	
	$custom_text = empty($wptowtruck_options['button-text']) ? $wptowtruck_default_options['button-text'] : $wptowtruck_options['button-text'];
	
	//load Mozilla TowTruck scripts
	$result = "<script src='https://towtruck.mozillalabs.com/towtruck.js'></script>
	    <button id='start-towtruck' type='button' onclick='TowTruck(this); return false' data-end-towtruck-html='End TowTruck'>".$custom_text."</button>";
	
	if ($wptowtruck_options['analytics'] == '1') $result = "<script>TowTruckConfig_enableAnalytics = true;</script>" . $result;
	else $result = "<script>TowTruckConfig_enableAnalytics = false;</script>" . $result;
	
	return $result;
}
