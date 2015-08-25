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
			require_once plugin_dir_path( __FILE__ ) . 'post-types/app.php';
		}

	}

	App_Showcase::initiate();
}