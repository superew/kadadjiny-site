<?php
/*
 Plugin Name: WP2Social Auto Publish
Plugin URI: https://xyzscripts.com/wordpress-plugins/facebook-auto-publish/
Description:   Publish posts automatically from your blog to Facebook social media. You can publish your posts to Facebook as simple text message, text message with image or as attached link to your blog. The plugin supports filtering posts by custom post-types and categories.
Version: 2.4.7
Author: xyzscripts.com
Author URI: https://xyzscripts.com/
License: GPLv2 or later
Text Domain: facebook-auto-publish
Domain Path: /languages/
*/

/*
 This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if( !defined('ABSPATH') ){ exit();}
if ( !function_exists( 'add_action' ) ) {
	_e('Hi there!  I'.'m just a plugin, not much I can do when called directly.','facebook-auto-publish');
	exit;
}
function plugin_load_fbaptextdomain() {
    load_plugin_textdomain( 'facebook-auto-publish', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'plugin_load_fbaptextdomain' );

//error_reporting(E_ALL);
define('XYZ_FBAP_PLUGIN_FILE',__FILE__);
define('XYZ_FBAP_FB_API_VERSION','v20.0');
if (!defined('XYZ_SMAP_SOLUTION_AUTH_URL'))
define('XYZ_SMAP_SOLUTION_AUTH_URL','https://authorize.smapsolutions.com/');
if (!defined('XYZ_SMAP_SOLUTION_PUBLISH_URL'))
define('XYZ_SMAP_SOLUTION_PUBLISH_URL','https://free-publish.smapsolutions.com/');

include_once(ABSPATH.'wp-includes/version.php');
global $wp_version;
define('XYZ_WP_FBAP_WP_VERSION',$wp_version);
global $wpdb;
if(isset($_POST) && isset($_POST['fb_auth'] ) ||(isset($_GET['page']) && ($_GET['page']=='facebook-auto-publish-suggest-feature'))|| (isset($_GET['page']) && ($_GET['page']=='facebook-auto-publish-settings')) || (isset($_GET['page']) && ($_GET['page']=='facebook-auto-publish-manage-authorizations')))
{
	ob_start();
}
require_once( dirname( __FILE__ ) . '/admin/install.php' );
require_once( dirname( __FILE__ ) . '/xyz-functions.php' );
require_once( dirname( __FILE__ ) . '/admin/menu.php' );
require_once( dirname( __FILE__ ) . '/admin/destruction.php' );
if (version_compare(PHP_VERSION, '5.4.0', '>'))
{
	require_once( dirname( __FILE__ ) . '/admin/publish.php' );
}
require_once( dirname( __FILE__ ) . '/admin/ajax-backlink.php' );
require_once( dirname( __FILE__ ) . '/admin/metabox.php' );
require_once( dirname( __FILE__ ) . '/admin/admin-notices.php' );
if(get_option('xyz_credit_link')=="fbap"){

	add_action('wp_footer', 'xyz_fbap_credit');

}
function xyz_fbap_credit() {
	$content = '<div style="clear:both;width:100%;text-align:center; font-size:11px; "><a target="_blank" title="WP2Social Auto Publish" href="https://xyzscripts.com/wordpress-plugins/facebook-auto-publish/compare" >WP2Social Auto Publish</a> Powered By : <a target="_blank" title="PHP Scripts & Programs" href="http://www.xyzscripts.com" >XYZScripts.com</a></div>';
	echo $content;
}
if(!function_exists('get_post_thumbnail_id'))
	add_theme_support( 'post-thumbnails' );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'xyz_fbap_add_action_links' );
function xyz_fbap_add_action_links( $links ) {
	$xyz_fbap_links = array(
			'<a href="' . admin_url( 'admin.php?page=facebook-auto-publish-settings' ) . '">Settings</a>',
	);
	return array_merge( $links, $xyz_fbap_links);
}
add_action('admin_init', 'xyz_fbap_check_and_upgrade_plugin_version');
function xyz_fbap_check_and_upgrade_plugin_version() {
	$current_version = xyz_fbap_plugin_get_version();
	$saved_version   = get_option('xyz_fbap_free_version');
	if ($saved_version === false) {
		add_option('xyz_fbap_free_version', $current_version);
	} elseif (version_compare($current_version, $saved_version, '>')) {
		xyz_fbap_run_upgrade_routines();
		update_option('xyz_fbap_free_version', $current_version);
	}
}
?>
