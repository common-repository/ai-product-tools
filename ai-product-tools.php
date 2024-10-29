<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://aiforproducts.org
 * @since             1.0.0
 * @package           Ai_Product_Tools
 *
 * @wordpress-plugin
 * Plugin Name:       AI-Powered Product Description Generator - AI Product Tools for WooCommerce
 * Plugin URI:        https://aiforproducts.org
 * Description:       Boost your WooCommerce Products with AI Product Tools: The AI-powered assistant for your products descriptions.
 * Version:           1.0.1
 * Author:            Dogu Pekgoz
 * Author URI:        https://aiforproducts.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ai-product-tools
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AIPT_VERSION', '1.0.1' );
if ( !function_exists( 'aipt_fs' ) ) {
    // Create a helper function for easy SDK access.
    function aipt_fs() {
        global $aipt_fs;
        if ( !isset( $aipt_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $aipt_fs = fs_dynamic_init( array(
                'id'             => '15570',
                'slug'           => 'ai-product-tools',
                'type'           => 'plugin',
                'public_key'     => 'pk_bebac4a4b8beed031101136867398',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => false,
                'menu'           => array(
                    'slug'    => 'ai-product-tools',
                    'account' => false,
                    'support' => false,
                ),
                'is_live'        => true,
            ) );
        }
        return $aipt_fs;
    }

    // Init Freemius.
    aipt_fs();
    // Signal that SDK was initiated.
    do_action( 'aipt_fs_loaded' );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ai-product-tools-activator.php
 */
function AIPT_activate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-tools-activator.php';
    AIPT_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ai-product-tools-deactivator.php
 */
function AIPT_deactivate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-tools-deactivator.php';
    AIPT_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'AIPT_activate' );
register_deactivation_hook( __FILE__, 'AIPT_deactivate' );
function aipt_fs_uninstall_cleanup() {
    if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        die;
    }
}

aipt_fs()->add_action( 'after_uninstall', 'aipt_fs_uninstall_cleanup' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-tools.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function AIPT_run() {
    $plugin = new AIPT();
    $plugin->run();
}

AIPT_run();
require_once __DIR__ . '/ai-product-tools-extra.php';