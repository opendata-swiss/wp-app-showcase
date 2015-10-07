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
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
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

		/**
		 * Add scripts and styles.
		 *
		 * @return void
		 */
		public function add_scripts() {
			wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js', null, null, false );
			wp_enqueue_script( 'dataset-search', plugins_url( 'assets/javascript/dataset_search.js', __FILE__ ), array( 'select2' ), null, false );
			wp_enqueue_style( 'select2-style', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css' );
			if( function_exists( 'pll_current_language' ) ) {
				$current_language = pll_current_language();
			} else {
				$current_language = 'en';
			}
			wp_localize_script( 'app-config', 'ogdConfig',
				array(
					'lang' => $current_language,
				)
			);
		}

	}

	App_Showcase::initiate();
}
