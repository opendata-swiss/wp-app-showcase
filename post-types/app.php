<?php
/**
 * Post type app-showcase-app
 *
 * @package App_Showcase
 */

/**
 * Class Ckan_Backend_Local_Dataset
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
			'name'               => __( 'Apps', 'ogdch' ),
			'singular_name'      => __( 'App', 'ogdch' ),
			'menu_name'          => __( 'Apps', 'ogdch' ),
			'name_admin_bar'     => __( 'Apps', 'ogdch' ),
			'parent_item_colon'  => __( 'Parent App:', 'ogdch' ),
			'all_items'          => __( 'All Apps', 'ogdch' ),
			'add_new_item'       => __( 'Add New App', 'ogdch' ),
			'add_new'            => __( 'Add New', 'ogdch' ),
			'new_item'           => __( 'New App', 'ogdch' ),
			'edit_item'          => __( 'Edit App', 'ogdch' ),
			'update_item'        => __( 'Update App', 'ogdch' ),
			'view_item'          => __( 'View App', 'ogdch' ),
			'search_items'       => __( 'Search Apps', 'ogdch' ),
			'not_found'          => __( 'Not found', 'ogdch' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'ogdch' ),
		);
		$args = array(
			'label'               => __( 'Apps', 'ogdch' ),
			'description'         => __( 'The App directory', 'ogdch' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'          => array( 'post_tag' ),
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
			'capability_type'     => 'app',
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
			'title'        => __( 'App Information', 'ogdch' ),
			'object_types' => array( self::POST_TYPE ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		) );

		// URL
		$cmb->add_field( array(
			'name' => __( 'URL', 'ogdch' ),
			'desc' => __( 'Landing page of app', 'ogdch' ),
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
			'name'       => __( 'Author Name', 'ogdch' ),
			'id'         => self::FIELD_PREFIX . 'author_name',
			'type'       => 'text',
			'attributes'  => array(
				'required'    => 'required',
			),
		) );

		// Author Email
		$cmb->add_field( array(
			'name' => __( 'Author Email', 'ogdch' ),
			'desc' => __( 'Email address of author', 'ogdch' ),
			'id'   => self::FIELD_PREFIX . 'author_email',
			'type' => 'text_email',
			'attributes'  => array(
				'placeholder' => 'author@app.dev',
				'required'    => 'required',
			),
		) );

		// Version
		$cmb->add_field( array(
			'name'       => __( 'Version', 'ogdch' ),
			'id'         => self::FIELD_PREFIX . 'version',
			'type'       => 'text_small',
			'attributes'  => array(
				'placeholder' => '1.2.0',
			),
		) );

		// Icon
		$cmb->add_field( array(
			'name'       => __( 'Icon', 'ogdch' ),
			'id'         => self::FIELD_PREFIX . 'icon',
			'type'       => 'file',
		) );

		// Dataset relations
		$relations_group = $cmb->add_field( array(
			'id'      => self::FIELD_PREFIX . 'relations',
			'type'    => 'group',
			'options' => array(
				'group_title'   => __( 'Dataset Relation {#}', 'ogdch' ),
				'add_button'    => __( 'Add another Dataset Relation', 'ogdch' ),
				'remove_button' => __( 'Remove Dataset Relation', 'ogdch' ),
			),
		) );

		$cmb->add_group_field( $relations_group, array(
			'name' => __( 'Dataset', 'ogdch' ),
			'id'   => 'dataset_id',
			'type' => 'text',
		) );
	}
}
