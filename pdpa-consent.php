<?php
/**
 * @package PDPAConsent
 */
/*
Plugin Name: PDPA Consent
Description: PDPA Consent allows you to notify to the user to accept privacy terms. Comply with Thailand PDPA law.
Version: 1.0.0
Author: Apinan Woratrakun, Aeknarin Sirisub
Author URI: https://www.ioblog.me
Plugin URI: http://www.ioblog.me/pdpa-consent-plugin
License: GNU License
License URI: https://opensource.org/licenses/lgpl-3.0.html
Text Domain: pdpa-consent
Domain Path: /languages
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( '_PATH', plugin_dir_path( __FILE__ ) );

// Check get_plugin_data exists.
if( !function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if(!class_exists('PDPA_Consent')){
    class PDPA_Consent {
        private $ver;
        private $show_popup = true;
        private $consent_popup_title = '';
        private $consent_popup_button_text = '';
        private $plugin_info;

        public function __construct () {
            $this->plugin_info = get_plugin_data( _PATH . 'pdpa-consent.php' );
            $this->initial();
        }

        public function initial() {
            add_filter( 'body_class', array( $this, 'change_body_class' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'pdpa_enqueue_scripts' ) );
        }

        public function pdpa_enqueue_scripts() {
            wp_enqueue_script( 'pdpa-consent', plugins_url( 'assets/pdpa-consent.js', __FILE__ ), array(), $this->plugin_info['Version'] );
            wp_enqueue_style( 'pdpa-consent', plugins_url( 'assets/pdpa-consent.css', __FILE__ ), array(), $this->plugin_info['Version'] );
        }

        public function pdpa_cookies_set() {
            return apply_filters( 'cn_is_cookie_set', isset( $_COOKIE['pdpa_accepted'] ) );
        }

        public static function pdpa_cookies_accepted() {
            return apply_filters( 'cn_is_cookie_accepted', isset( $_COOKIE['pdpa_accepted'] ) && $_COOKIE['pdpa_accepted'] === 'true' );
        }

        public function change_body_class( $classes ) {
            if ( is_admin() )
                return $classes;

            if ( $this->pdpa_cookies_set() ) {
                $classes[] = 'pdpa-set';

                if ( $this->pdpa_cookies_accepted() )
                    $classes[] = 'pdpa-accepted';
                else
                    $classes[] = 'pdpa-refused';
            } else
                $classes[] = 'pdpa-not-set';

            return $classes;
        }

        public function create_notice() {

        }

        private function pdpa_admin_menu() {

        }

        private function pdpa_admin_option() {

        }


    }
    new PDPA_Consent;
}