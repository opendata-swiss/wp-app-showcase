<?php
/**
 * Plugin Name: App showcase
 * Description: Plugin to create app showcase of opendata-projects.
 * Author: Team Jazz <juerg.hunziker@liip.ch>
 * Version: 1.0
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
			add_action( 'init', array( $this, 'bootstrap' ), 0 );
			add_action( 'admin_init', array( $this, 'add_scripts' ) );
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
		}

		/**
		 * Bootstrap all post types.
		 *
		 * @return void
		 */
		public function bootstrap() {
			$this->load_dependencies();

			new App_Showcase_App();
		}

		/**
		 * Add scripts and styles.
		 *
		 * @return void
		 */
		public function add_scripts() {
			wp_register_style( self::$plugin_slug . '-base', plugins_url( 'assets/css/base.css', __FILE__ ) );
			wp_enqueue_style( self::$plugin_slug . '-base' );
		}

		/**
		 * Load all the dependencies.
		 *
		 * @return void
		 */
		protected function load_dependencies() {
			require_once plugin_dir_path( __FILE__ ) . 'post-types/app-showcase-app.php';
		}

		/**
		 * Activate the plugin.
		 *
		 * @return void
		 */
		public function activate() {
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

	}

	App_Showcase::initiate();
}
