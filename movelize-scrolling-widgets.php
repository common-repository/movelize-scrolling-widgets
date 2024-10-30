<?php
/**
 * Plugin Name: Movelize Scrolling Widgets
 * Description: Elementor columns as scrolling containers
 * Version:     1.0
 * Author:      Movelize
 * Author URI:  https://movelize.com
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: movsw
 */

if ( ! defined( 'ABSPATH' ) )
    exit;


/**
 * Main Plugin Class
 *
 * The init class that runs the plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 */
final class Movelize_Scrolling_Widgets {

    /** Plugin version */
	const VERSION = '1.0';

    /** Minimum required Elementor version */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /** Minimum required PHP version */
	const MINIMUM_PHP_VERSION = '7.0';

    /** Constructor function */
	public function __construct() {

        // Load translation
		add_action( 'init', array( $this, 'i18n' ) );

        // Initialize the plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

    /** Load the text domain */
	public function i18n() {
        load_plugin_textdomain( 'movsw', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

    /**
     * Initializing the plugin
     *
     * Validates that Elementor is already loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed include the plugin settings functions.
     */
	public function init() {

        // Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

        // Check for the required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

        // Check for the required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

        // Once we have passed all validation checks we can include the plugin and load the necessary asset files
		require_once( 'settings.php' );
	}

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
			__( '"%1$s" requires "%2$s" to be installed and activated.', 'movsw' ),
			'<strong>' . __( 'Movelize Scrolling Widgets', 'movsw' ) . '</strong>',
			'<strong>' . __( 'Elementor', 'movsw' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
			__( '"%1$s" requires "%2$s" version %3$s or greater.', 'movsw' ),
			'<strong>' . __( 'Movelize Scrolling Widgets', 'movsw' ) . '</strong>',
			'<strong>' . __( 'Elementor', 'movsw' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
			__( '"%1$s" requires "%2$s" version %3$s or greater.', 'movsw' ),
			'<strong>' . __( 'Movelize Scrolling Widgets', 'movsw' ) . '</strong>',
			'<strong>' . __( 'PHP', 'movsw' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate the Movelize_Scrolling_Widgets class
new Movelize_Scrolling_Widgets();
?>