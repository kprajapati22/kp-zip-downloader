<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Create required class for functionality.
 */
class KP_Zip_Downloader {

    /**
     * Initialize the plugin hooks.
     */
    public function init() {
        add_filter( 'plugin_action_links', [ $this, 'add_plugin_download_link' ], 10, 4 );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_init', [ $this, 'handle_download_request' ] );
    }

	/**
     * Enqueue JavaScript for adding download links to themes.
     */
    public function enqueue_scripts() {
        if ( 'themes' === get_current_screen()->id ) {
            wp_enqueue_script( 'kp-zip-downloader', KP_ZIP_DOWNLOADER_URL . 'assets/js/kp-zip-downloader.js', [ 'jquery' ], KP_ZIP_DOWNLOADER_VERSION, true );
            wp_localize_script( 'kp-zip-downloader', 'kp_zip_downloader', [
                'themes_url' => admin_url(),
                'nonce' => [
                    'param' => '_wpnonce',
                    'value' => wp_create_nonce( 'kp_download_nonce' ),
                ],
                'download_link_text' => esc_html__( 'Download ZIP', 'kp-zip-downloader' ),
                'theme_name' => get_option( 'stylesheet' ),
            ] );
        }
    }

    /**
     * Add download link to plugins page.
     */
    public function add_plugin_download_link( $actions, $plugin_file, $plugin_data, $context ) {
        if ( 'all' === $context ) {
            $download_url = add_query_arg([
                'kp_download' => 'plugin',
                'plugin'      => $plugin_file,
				'_wpnonce'    => wp_create_nonce( 'kp_download_nonce' )
            ], admin_url() );

            $actions['download_zip'] = '<a href="' . esc_url( $download_url ) . '">' . esc_html__( 'Download ZIP', 'kp-zip-downloader' ) . '</a>';
        }
        return $actions;
    }

    /**
     * Add download link to themes page.
     */
    public function add_theme_download_link( $actions, $stylesheet, $theme ) {
        $download_url = add_query_arg([
            'kp_download' => 'theme',
            'theme'       => $stylesheet,
			'_wpnonce'    => wp_create_nonce( 'kp_download_nonce' )
        ], admin_url() );

        $actions['download_zip'] = '<a href="' . esc_url( $download_url ) . '">' . esc_html__( 'Download ZIP', 'kp-zip-downloader' ) . '</a>';

        return $actions;
    }

    /**
     * Handle the download request.
     */
    public function handle_download_request() {
        if ( isset( $_GET['kp_download'] ) && current_user_can( 'manage_options' ) ) {
            if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'kp_download_nonce' ) ) {
                wp_die( esc_html__( 'Invalid nonce.', 'kp-zip-downloader' ) );
            }

            $type = sanitize_text_field( wp_unslash( $_GET['kp_download'] ) );

            if ( 'plugin' === $type && isset( $_GET['plugin'] ) ) {
                $this->download_plugin_zip( sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) );
            } elseif ( 'theme' === $type && isset( $_GET['theme'] ) ) {
                $this->download_theme_zip( sanitize_text_field( wp_unslash( $_GET['theme'] ) ) );
            }
        }
    }

    /**
     * Download the plugin as a ZIP file.
     */
    private function download_plugin_zip( $plugin_file ) {
        $plugin_dir = WP_PLUGIN_DIR . '/' . dirname( $plugin_file );

        if ( ! is_dir( $plugin_dir ) ) {
            wp_die( esc_html__( 'Invalid plugin directory.', 'kp-zip-downloader' ) );
        }

        $this->create_and_send_zip( $plugin_dir, dirname( $plugin_file ) . '.zip' );
    }

    /**
     * Download the theme as a ZIP file.
     */
    private function download_theme_zip( $theme ) {
        $theme_dir = get_theme_root( $theme ) . '/' . $theme;

        if ( ! is_dir( $theme_dir ) ) {
            wp_die( esc_html__( 'Invalid theme directory.', 'kp-zip-downloader' ) );
        }

        $this->create_and_send_zip( $theme_dir, $theme . '.zip' );
    }

    /**
     * Create a ZIP file and send it to the browser.
     */
    private function create_and_send_zip( $source, $zip_name ) {
        if ( ! class_exists( 'ZipArchive' ) ) {
            wp_die( esc_html__( 'ZipArchive is not available.', 'kp-zip-downloader' ) );
        }

        $zip = new ZipArchive();
        $zip_path = wp_tempnam( $zip_name );

        if ( $zip->open( $zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
            wp_die( esc_html__( 'Failed to create ZIP file.', 'kp-zip-downloader' ) );
        }

        $source = realpath( $source );
        if ( is_dir( $source ) ) {
            $files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $source ), RecursiveIteratorIterator::LEAVES_ONLY );

            foreach ( $files as $name => $file ) {
                if ( ! $file->isDir() ) {
                    $file_path = $file->getRealPath();
                    $relative_path = substr( $file_path, strlen( $source ) + 1 );
                    $zip->addFile( $file_path, $relative_path );
                }
            }
        } else {
            $zip->addFile( $source, basename( $source ) );
        }

        $zip->close();

        header( 'Content-Type: application/zip' );
        header( 'Content-Disposition: attachment; filename="' . basename( $zip_name ) . '"' );
        header( 'Content-Length: ' . filesize( $zip_path ) );

        // Stream the file
		readfile( $zip_path );

		// Clean up the temporary file
		wp_delete_file( $zip_path );

        exit;
    }
}
