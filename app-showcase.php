<?php
/**
 * Plugin Name: App Showcase
 * Description: Plugin to create app showcase of opendata projects.
 * Author: Team Jazz <jazz@liip.ch>
 * Version: 1.0.0
 * Date: 17.08.2015
 *
 * @package AppShowcase
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'App_Showcase', false ) ) {

	/**
	 * Class App_Showcase
	 */
	class App_Showcase {

		/**
		 * Slug of this plugin.
		 * @var string
		 */
		public static $plugin_slug = 'app-showcase';

		/**
		 * Version number of this plugin.
		 * @var string
		 */
		public static $version = '1.0.0';

		/**
		 * Single instance of the App_Showcase object
		 *
		 * @var App_Showcase
		 */
		public static $single_instance = null;

		/**
		 * Creates/returns the single instance App_Showcase object
		 *
		 * @return App_Showcase Single instance object
		 */
		public static function initiate() {
			if ( null === self::$single_instance ) {
				self::$single_instance = new self();
			}

			return self::$single_instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			register_activation_hook( __FILE__,  array( $this, 'activate_plugin' ) );
			add_action( 'init', array( $this, 'bootstrap' ), 0 );
		}

		/**
		 * Activation hook, check if we can actually use this plugin
		 *
		 * @return void
		 */
		public function activate_plugin() {
			// Require CMB2 plugin
			if ( ! is_plugin_active( 'cmb2/init.php' ) && current_user_can( 'activate_plugins' ) ) {
				// Stop activation redirect and show error
				wp_die( 'Sorry, but this plugin requires CMB2 to be installed and active.' );
			}
			// initialize capabilities
			$this->init_caps();
		}

		/**
		 * Initializes capabilities used in this plugin
		 */
		public function init_caps() {
			$post_types = array(
				'apps',
			);
			// Add all capabilities of plugin to administrator role (save in database) to make them visible in backend.
			$admin_role = get_role( 'administrator' );
			if ( is_object( $admin_role ) ) {
				foreach ( $post_types as $post_type ) {
					$admin_role->add_cap( 'edit_' . $post_type );
					$admin_role->add_cap( 'edit_others_' . $post_type );
					$admin_role->add_cap( 'publish_' . $post_type );
					$admin_role->add_cap( 'read_private_' . $post_type );
					$admin_role->add_cap( 'delete_' . $post_type );
					$admin_role->add_cap( 'delete_private_' . $post_type );
					$admin_role->add_cap( 'delete_published_' . $post_type );
					$admin_role->add_cap( 'delete_others_' . $post_type );
					$admin_role->add_cap( 'edit_private_' . $post_type );
					$admin_role->add_cap( 'edit_published_' . $post_type );
					$admin_role->add_cap( 'create_' . $post_type );
				}
			}
		}

		/**
		 * Bootstrap all post types.
		 *
		 * @return void
		 */
		public function bootstrap() {
			// Load translation
			load_plugin_textdomain( 'ogdch-app', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			$this->load_dependencies();

			new App_Showcase_App();
		}

		/**
		 * Load all the dependencies.
		 *
		 * @return void
		 */
		protected function load_dependencies() {
			require_once plugin_dir_path( __FILE__ ) . 'post-types/app.php';
		}

	}

	App_Showcase::initiate();
}
