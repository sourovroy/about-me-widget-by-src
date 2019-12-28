<?php
/*
Plugin Name: About Me Widget by SRC
Plugin URI: https://wordpress.org/plugins/about-me-widget-by-src/
Description: A widget that describe all about yourself.
Version: 1.4.1
Author: Sourov Roy
Author URI: https://github.com/sourovroy
Text Domain: aboutwidget
Domain Path: /languages/
License: GPLv2
*/

/**
 * About Me Widget by SRC is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * About Me Widget by SRC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with About Me Widget by SRC. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

final class About_Me_Widget_By_Src {

    /**
     * Hold the class instance.
     *
     * @var null|About_Me_Widget_By_Src
     */
    private static $instance = null;

    /**
     * The constructor
     */
    private function __construct() {
        // Init tracker
        $this->includes();

        add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );

        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

        add_action( 'widgets_init', [ $this, 'widgets_init' ] );

        add_action( 'admin_footer', [ $this, 'admin_footer' ], 20 );

        // Init tracker
        $this->plugin_tracker();
    }

    /**
     * The object is created from within the class itself
     * only if the class has no instance.
     */
    public static function init() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * After all plugins loaded
     */
    public function plugins_loaded() {
        // Load plugin textdomain
        load_plugin_textdomain( 'aboutwidget', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /**
     * Initialize the plugin tracker
     *
     * @return void
     */
    public function plugin_tracker() {
        if ( ! class_exists( 'Appsero\Client' ) ) {
            require_once __DIR__ . '/lib/appsero/Client.php';
        }

        $client = new Appsero\Client(
            'b5631efe-1587-4365-a4e8-31a5ebab2b6d',
            'About Me Widget by SRC',
            __FILE__
        );

        // Active insights
        $client->insights()->init();
    }

    /**
     * Require necessary files
     */
    private function includes() {
        require __DIR__ . '/includes/About_Me_Widget.php';
    }

    /**
    * Admin enqueue scripts
    */
    public function admin_enqueue_scripts() {
        global $pagenow;

        if ( 'widgets.php' == $pagenow ) {
            // WP default media upload files
            wp_enqueue_media();
        }
    }

    /**
     * Active Widget
     */
    public function widgets_init() {
        register_widget( 'Amws\About_Me_Widget' );
    }

    /**
     * Admin footer
     * JS for add or remove widget image
     */
    public function admin_footer() {
        global $pagenow;

        if ( 'widgets.php' == $pagenow ) {
            ?>
            <script type="text/javascript">
                jQuery(document).on("click", '.wgt_src_upload_image', function(event) {
                    event.preventDefault();
                    var image = wp.media({
                        title: 'Upload Image',
                        multiple: false
                    }).open();
                    image.on('select', function() {
                        var uploaded_image = image.state().get('selection').first().toJSON();
                        jQuery('.wgt_src_uploaded_image_source').val(uploaded_image.url);
                        jQuery('.wgt_src_upload_image').text("Change Image");
                        jQuery('.wgt_src_remove_image').removeAttr("disabled");
                        jQuery('.wgt_src_image_priview').html(
                            '<img src="'+uploaded_image.url+'" alt="" style="max-width:150px; height:auto; margin: 10px 0px;">'
                        );
                        jQuery(event.target).parents('.widget').find('input[name="savewidget"]').removeAttr("disabled");
                    });
                });

                jQuery(document).on("click", '.wgt_src_remove_image', function(e) {
                    jQuery('.wgt_src_image_priview').html("");
                    jQuery('.wgt_src_remove_image').attr("disabled", "disabled");
                    jQuery('.wgt_src_upload_image').text("Upload");
                    jQuery('.wgt_src_uploaded_image_source').val("");
                    jQuery(this).parents('.widget').find('input[name="savewidget"]').removeAttr("disabled");
                });
            </script>
            <?php
        }
    }

}

/**
 * Run widget functionality
 */
About_Me_Widget_By_Src::init();
