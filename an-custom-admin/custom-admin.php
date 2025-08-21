<?php
/**
 * Plugin Name: Custom Admin Page
 * Description: A simple plugin to customize the WordPress login page.
 * Version: 1.0
 * Author: Andrew Nguyen
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue custom styles
function custom_login_styles() {
    $logo_url = plugin_dir_url( __FILE__ ) . 'images/kac-logo.png';
    echo '<style>
        #login h1 a {
            background-image: url(' . $logo_url . ');
            background-size: contain;
            width: 100%;
            height: 80px;
        }
        body.login {
            background-color: #f1f1f1;
        }
    </style>';
    echo '<p>Logo URL: ' . $logo_url . '</p>'; // Output the URL for debugging
}
add_action( 'login_enqueue_scripts', 'custom_login_styles' );

// Change the login logo URL
function custom_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'custom_login_logo_url' );

// Change the login logo title
function custom_login_logo_title() {
    return 'Your Site Name';
}
add_filter( 'login_headertitle', 'custom_login_logo_title' );
