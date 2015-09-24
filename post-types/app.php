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

		// add custom CMB2 field type dataset_search
		add_action( 'cmb2_render_dataset_search', array( $this, 'cmb2_render_callback_dataset_search' ), 10, 5 );
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
	 * Renders CMB2 field of type dataset_identifier
	 *
	 * @param CMB2_Field $field The passed in `CMB2_Field` object.
	 * @param mixed      $escaped_value The value of this field escaped. It defaults to `sanitize_text_field`.
	 * @param int        $object_id The ID of the current object.
	 * @param string     $object_type The type of object you are working with.
	 * @param CMB2_Types $field_type_object This `CMB2_Types` object.
	 */
	public function cmb2_render_callback_dataset_search( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		$options = array(
			'ajax' => array(
				'url' => CKAN_SEARCH_API_ENDPOINT,
				'dataType' => 'json',
                'delay' => 250,
			),
			//'tags' => true,
			'minimumInputLength' => 3,
			'placeholder' => __( 'Datensatz suchen…', 'ogdch' ),
			'allowClear'  => true,
		);
		?>
		<select class="search-box"
		        style="width: 50%"
		        name="<?php esc_attr_e( $field->args['_name'] ); ?>"
		        id="<?php esc_attr_e( $field->args['_id'] ); ?>">
			<?php if ($escaped_value) : ?>
				<?php $title = Ckan_Backend_Helper::get_dataset_title( $escaped_value ); ?>
				<option selected="selected" value="<?php esc_attr_e( $escaped_value )?>"><?php esc_html_e( $title ); ?></option>
			<?php endif; ?>
		</select>
		<script type="text/javascript">
			(function($) {
				var options = <?php echo json_encode( $options ) . ";\n"; ?>
				options.ajax.data = dataFn;
				options.ajax.processResults = resultFn;
				options.templateResult = format;
				options.templateSelection = formatSelection;
				options.escapeMarkup = escapeFn;
				options.formatNoMatches = noMatchFn;
				options.language = {
					inputTooLong: function (args) {
						return '<?php _e('Bitte weniger Zeichen eingeben', 'ogdch'); ?>';
					},
					inputTooShort: function (args) {
						return '<?php _e('Bitte mehr Zeichen eingeben', 'ogdch'); ?>';
					},
					noResults: function () {
						return '<?php _e('Keine Treffer gefunden', 'ogdch'); ?>';
					},
					searching: function () {
						return '<?php _e('Search'); ?>…';
					}
				};

				$("[name='<?php echo $field->args['_name']; ?>'").select2(options);

				var fieldGroupId     = '_app-showcase-app_relations';
				var fieldGroupTable = $( document.getElementById( fieldGroupId + '_repeat' ) );
				fieldGroupTable
					.on( 'cmb2_add_row', function(event, row) {
						var name = $(row).find('.search-box')[0].name;
						//remove the previous select2 rendering, as CMB2 copies everything
						$("[name='" + name + "'] + .select2-container").remove();
						var new_select = $("[name='" + name + "'").select2(options);
						// select empty value as original is copied
						new_select.val("").trigger("change");
					})
			})( jQuery );
		</script>

		<?php
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
			'name' => __( 'Landing page', 'ogdch' ),
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
			'id'   => self::FIELD_PREFIX . 'author_email',
			'type' => 'text_email',
			'attributes'  => array(
				'placeholder' => 'author@app.dev',
				'required'    => 'required',
			),
		) );

		// Icon
		$cmb->add_field( array(
			'name'       => __( 'Icon', 'ogdch' ),
			'id'         => self::FIELD_PREFIX . 'icon',
			'type'       => 'file',
		) );

		$cmb->add_field( array(
			'name' => __( 'Dataset', 'ogdch' ),
			'id'   => self::FIELD_PREFIX .  'dataset_id[]',
			'type' => 'dataset_search',
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
			'type' => 'dataset_search',
		) );
	}
}
