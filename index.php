<?php
/*
Plugin Name: About Me Widget by SRC
Plugin URI: https://wordpress.org/plugins/about-me-widget-by-src/
Description: A widget that describe all about yourself.
Version: 1.3
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

/**
 * Load plugin textdomain.
 */
function myplugin_load_textdomain() {
    load_plugin_textdomain( 'aboutwidget', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'myplugin_load_textdomain' );


/**
 * Initialize the plugin tracker
 *
 * @return void
 */
add_action( 'init', function() {

    if ( ! class_exists( 'AppSero\Client' ) ) {
        require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new AppSero\Client(
        'b5631efe-1587-4365-a4e8-31a5ebab2b6d',
        'About Me Widget by SRC',
        __FILE__
    );

    // Active insights
    $client->insights()->init();
} );

/**
 * Load plugin functionality
 */
require_once __DIR__ . '/about-me.php';
