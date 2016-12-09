<?php
/**
 * Post type app-showcase-app
 *
 * @package App_Showcase
 */

/**
 * Class App_Showcase_App
 */
class App_Showcase_App {

	// Be careful max. 20 characters allowed!
	const POST_TYPE = 'app';
	const FIELD_PREFIX = '_app-showcase-app_';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->register_post_type();

		// define backend fields
		add_action( 'cmb2_init', array( $this, 'define_fields' ) );
	}

	/**
	 * Registers the post type.
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Applications', 'ogdch-app' ),
			'singular_name'      => __( 'Application', 'ogdch-app' ),
			'menu_name'          => __( 'Applications', 'ogdch-app' ),
			'name_admin_bar'     => __( 'Applications', 'ogdch-app' ),
			'all_items'          => __( 'All Applications', 'ogdch-app' ),
			'add_new_item'       => __( 'Add New Application', 'ogdch-app' ),
			'add_new'            => __( 'Add New', 'ogdch-app' ),
			'new_item'           => __( 'New Application', 'ogdch-app' ),
			'edit_item'          => __( 'Edit Application', 'ogdch-app' ),
			'update_item'        => __( 'Update Application', 'ogdch-app' ),
			'view_item'          => __( 'View Application', 'ogdch-app' ),
			'search_items'       => __( 'Search Applications', 'ogdch-app' ),
			'not_found'          => __( 'No Applications found', 'ogdch-app' ),
			'not_found_in_trash' => __( 'No Applications found in Trash', 'ogdch-app' ),
		);
		$args = array(
			'label'               => __( 'Applications', 'ogdch-app' ),
			'description'         => __( 'The Application directory', 'ogdch-app' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-smartphone',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'app', 'apps' ),
			'capabilities'        => array(
				'edit_posts'             => 'edit_apps',
				'edit_others_posts'      => 'edit_others_apps',
				'publish_posts'          => 'publish_apps',
				'read_private_posts'     => 'read_private_apps',
				'delete_posts'           => 'delete_apps',
				'delete_private_posts'   => 'delete_private_apps',
				'delete_published_posts' => 'delete_published_apps',
				'delete_others_posts'    => 'delete_others_apps',
				'edit_private_posts'     => 'edit_private_apps',
				'edit_published_posts'   => 'edit_published_apps',
				'create_posts'           => 'create_apps',
				// Meta capabilites assigned by WordPress. Do not give to any role.
				'edit_post'              => 'edit_app',
				'read_post'              => 'read_app',
				'delete_post'            => 'delete_app',
			),
		);
		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Define the custom fields of this post type
	 *
	 * @return void
	 */
	public function define_fields() {
		/* CMB Mainbox */
		$cmb = new_cmb2_box( array(
			'id'           => self::POST_TYPE . '-box',
			'title'        => __( 'Application Information', 'ogdch-app' ),
			'object_types' => array( self::POST_TYPE ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		) );

		// URL
		$cmb->add_field( array(
			'name' => __( 'Landing page', 'ogdch-app' ) . '*',
			'id'   => self::FIELD_PREFIX . 'url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https' ), // Array of allowed protocols
			'attributes'  => array(
				'placeholder' => 'http://myapp.io',
				'rows'        => 3,
				'required'    => 'required',
			),
		) );

		// Author Name
		$cmb->add_field( array(
			'name'       => __( 'Author Name', 'ogdch-app' ) . '*',
			'id'         => self::FIELD_PREFIX . 'author_name',
			'type'       => 'text',
			'attributes'  => array(
				'required'    => 'required',
			),
		) );

		// Author Email
		$cmb->add_field( array(
			'name' => __( 'Author Email', 'ogdch-app' ) . '*',
			'id'   => self::FIELD_PREFIX . 'author_email',
			'type' => 'text_email',
			'attributes'  => array(
				'placeholder' => 'author@app.dev',
				'required'    => 'required',
			),
		) );

		// Dataset relations
		$relations_group = $cmb->add_field( array(
			'id'      => self::FIELD_PREFIX . 'relations',
			'type'    => 'group',
			'options' => array(
				'group_title'   => __( 'Dataset Relation {#}', 'ogdch-app' ),
				'add_button'    => __( 'Add another Dataset Relation', 'ogdch-app' ),
				'remove_button' => __( 'Remove Dataset Relation', 'ogdch-app' ),
			),
		) );

		if ( class_exists( 'Ckan_Backend_Local_Dataset' ) ) {
			$cmb->add_group_field( $relations_group, array(
				'name' => __( 'Dataset', 'ogdch-app' ),
				'id'   => 'dataset_id',
				'type' => 'dataset_search',
			) );
		} else {
			$cmb->add_group_field( $relations_group, array(
				'name' => __( 'Dataset', 'ogdch-app' ),
				'id'   => 'dataset_id',
				'type' => 'text',
			) );
		}
	}
}
