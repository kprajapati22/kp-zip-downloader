<?php
/**
 * Plugin Name: KP Zip Downloader
 * Plugin URI: https://wordpress.org/plugins/kp-zip-downloader
 * Description: A WordPress plugin to download installed plugins and themes as zip files from the admin dashboard.
 * Version: 1.0.2
 * Author: Kalpesh Prajapati
 * Author URI: https://profiles.wordpress.org/kprajapati22/
 * License: GPLv2 or later
 * Text Domain: kp-zip-downloader
 * Domain Path: /languages
 * Requires at least:  5.0
 * Requires PHP: 7.4
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Define constants.
define( 'KP_ZIP_DOWNLOADER_VERSION', '1.0.2' );
define( 'KP_ZIP_DOWNLOADER_DIR', plugin_dir_path( __FILE__ ) );
define( 'KP_ZIP_DOWNLOADER_URL', plugin_dir_url( __FILE__ ) );

require_once KP_ZIP_DOWNLOADER_DIR . 'includes/class-kp-zip-downloader.php';

function kp_zip_downloader_init() {
    $kp_zip_downloader = new KP_Zip_Downloader();
    $kp_zip_downloader->init();
}
add_action( 'plugins_loaded', 'kp_zip_downloader_init' );