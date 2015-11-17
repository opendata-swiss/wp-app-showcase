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
			add_action( 'init', array( $this, 'bootstrap' ), 0 );
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
