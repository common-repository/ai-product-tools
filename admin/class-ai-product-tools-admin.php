<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://aiforproducts.org
 * @since      1.0.0
 *
 * @package    Ai_Product_Tools
 * @subpackage Ai_Product_Tools/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ai_Product_Tools
 * @subpackage Ai_Product_Tools/admin
 * @author     Dogu Pekgoz <aipostpix@gmail.com>
 */
class AIPT_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array($this, 'aipt_add_admin_menu'));
		add_action('admin_init', array($this, 'aipt_register_settings'));
		add_action('admin_init', array($this, 'aipt_options_update'));
		add_action('admin_notices', array($this, 'aipt_woocommerce_notice_global'));
	}


	function aipt_add_admin_menu() {
		add_menu_page(
			'AI Product Tools',
			'AI Product Tools',
			'manage_options',
			$this->plugin_name,
			array($this, 'aipt_admin_page'),
			plugins_url('img/icon.png', dirname(__FILE__)),
			56
		);
	}

	function aipt_admin_page() {
		// Check if WooCommerce is active
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			include_once ('partials/ai-product-woocommerce-notice.php');
		} else {
			include_once ('partials/ai-product-tools-admin-display.php');
		}
	}

	function aipt_woocommerce_notice_global() {
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			echo '<div class="notice notice-error is-dismissible"><p>Please install and activate Woocommerce first. You can install it <a href="' . esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '">here</a>.</p></div>';
		}
	}

	public function aipt_register_settings() {
		static $already_called = false;
		if ($already_called) {
			return;
		}
		$already_called = true;
		if (isset($_POST['aipt_settings_submit'])) {  // Form gönderme kontrolü
			// Nonce ve yetki kontrolleri
			if (!isset($_POST['aipt_settings_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aipt_settings_nonce'])), 'aipt_settings_action') || !current_user_can('manage_options')) {
				add_settings_error('aipt_settings', 'unauthorized', 'Unauthorized operation.', 'error');
			} else {
				update_option('aipt_openai_api', sanitize_text_field($_POST['aipt_openai_api']));
				update_option('aipt_temperature', floatval(max(0, min(2, floatval($_POST['aipt_temperature'])))));
				update_option('aipt_frequency_penalty', floatval(max(0, min(2, floatval($_POST['aipt_frequency_penalty'])))));
				update_option('aipt_presence_penalty', floatval(max(0, min(2, floatval($_POST['aipt_presence_penalty'])))));
				update_option('aipt_top_p', floatval(max(0, min(1, floatval($_POST['aipt_top_p'])))));
				update_option('aipt_best_of', absint(max(1, min(20, absint($_POST['aipt_best_of'])))));
				update_option('aipt_openai_model', sanitize_text_field($_POST['aipt_openai_model']));
				update_option('aipt_writing_style', sanitize_text_field($_POST['aipt_writing_style']));
				update_option('aipt_descgen_language', sanitize_text_field($_POST['aipt_descgen_language']));

				add_settings_error('aipt_settings', 'aipt_settings_updated', 'Settings updated.', 'updated');
			}
		}
	}


	public function aipt_options_update() {
		register_setting('aipt_settings', 'aipt_openai_api', 'sanitize_text_field');
		register_setting('aipt_settings', 'aipt_openai_model', 'sanitize_text_field');
		register_setting('aipt_settings', 'aipt_writing_style', 'sanitize_text_field');
		register_setting('aipt_settings', 'aipt_descgen_language', 'sanitize_text_field');
		register_setting('aipt_settings', 'aipt_temperature', function ($value) {
			return floatval(max(0, min(2, $value)));
		});
		register_setting('aipt_settings', 'aipt_frequency_penalty', function ($value) {
			return floatval(max(0, min(2, $value)));
		});
		register_setting('aipt_settings', 'aipt_presence_penalty', function ($value) {
			return floatval(max(0, min(2, $value)));
		});
		register_setting('aipt_settings', 'aipt_top_p', function ($value) {
			return floatval(max(0, min(1, $value)));
		});
		register_setting('aipt_settings', 'aipt_best_of', function ($value) {
			return absint(max(1, min(20, $value)));
		});
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ai_Product_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ai_Product_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	
		}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ai_Product_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ai_Product_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

}
