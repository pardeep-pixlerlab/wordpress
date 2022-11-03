<?php
defined('ABSPATH')  || die('Nice Try');
// add css file in plugin 
add_action( 'admin_enqueue_scripts', 'ci_add_cssjs_styles' );
function ci_add_cssjs_styles() {

  wp_register_style( 'ci-comment-custom-styles', plugins_url( '/', __FILE__ ) . '../asserts/allcssfile/addcss.css' );
  
  wp_enqueue_style( 'dashicons' );
  wp_enqueue_style( 'ci-comment-custom-styles' );
}

add_action('admin_enqueue_scripts','plugin_scripts');
function plugin_scripts(){
  wp_enqueue_script('ci-comment-custom-scripts', plugins_url( '/', __FILE__ ) . '../asserts/alljsfile/addjs.js' ,array('jquery'),false,true);

}
class mainclasscssjs_Admin {

	/**
	 * Default options for a new page
	 */
	private $default_options = array(
		'type'     => 'header',
		'linking'  => 'internal',
		'side'     => 'frontend',
		'language' => 'css',
	);
	
	/**
	 * Array with the options for a specific add_css_js post
	 */
	private $options = array();

//start of admin ediitor add in admin dasboard------------------------------------------------------
	public function __construct() {

		$this->add_functions();
	}

	/**
	 * Add actions and filters
	 */
	function add_functions() {

		// Add filters
		$filters = array(
			'manage_add_css_js_posts_columns' => 'manage_custom_posts_columns',
		);
		foreach ( $filters as $_key => $_value ) {
			add_filter( $_key, array( $this, $_value ) );
		}

		// Add actions
		$actions = array(
			'admin_menu'                 => 'admin_menu',
			'admin_enqueue_scripts'      => 'admin_enqueue_scripts',
			'current_screen'             => 'current_screen',
			'admin_notices'              => 'create_uploads_directory',
			'edit_form_after_title'      => 'allcssfile_editor',
			'add_meta_boxes'             => 'add_meta_boxes',
			'save_post'                  => 'options_save_meta_box_data',
			'trashed_post'               => 'trash_post',
			'untrashed_post'             => 'trash_post',
			'wp_ajax_ccj_active_code'    => 'wp_ajax_ccj_active_code',
			'wp_ajax_ccj_permalink'      => 'wp_ajax_ccj_permalink',
			'post_submitbox_start'       => 'post_submitbox_start',
			'restrict_manage_posts'      => 'restrict_manage_posts',
			'load-post.php'              => 'contextual_help',
			'load-post-new.php'          => 'contextual_help',
			'edit_form_before_permalink' => 'edit_form_before_permalink',
			'before_delete_post'         => 'before_delete_post',
		);
		foreach ( $actions as $_key => $_value ) {
			add_action( $_key, array( $this, $_value ) );
		}

		// Add some custom actions/filters
		add_action( 'manage_add_css_js_posts_custom_column', array( $this, 'manage_posts_columns' ), 10, 2 );
		add_filter( 'manage_edit-add_css_js_sortable_columns', array( $this, 'manage_edit_posts_sortable_columns' ) );
		add_action( 'posts_orderby', array( $this, 'posts_orderby' ), 10, 2 );
		add_action( 'posts_join_paged', array( $this, 'posts_join_paged' ), 10, 2 );
		add_action( 'posts_where_paged', array( $this, 'posts_where_paged' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
		add_filter( 'parse_query', array( $this, 'parse_query' ), 10 );

		add_action( 'current_screen', array( $this, 'current_screen_2' ), 100 );

	}

//end of admin ediitor add in admin dasboard!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	/**
	 * Add submenu pages
	 */
	function admin_menu() {
		$menu_slug    = 'edit.php?post_type=add_css_js';
		$submenu_slug = 'post-new.php?post_type=add_css_js';
	
		remove_submenu_page( $menu_slug, $submenu_slug );

		$title = __( 'Add CSS', 'add_css_js' );
		add_submenu_page( $menu_slug, $title, $title, 'publish_custom_csss', $submenu_slug . '&#038;language=css' );

		$title = __( 'Add JS', 'add_css_js' );
		add_submenu_page( $menu_slug, $title, $title, 'publish_custom_csss', $submenu_slug . '&#038;language=js' );

		

	}
// end of add submenu in dasboard !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	/**
	 * Enqueue the scripts and styles
	 */
	public function admin_enqueue_scripts( $hook ) {		
	}


	/**
	 * Send variables to the addcss.js script
	 */
	public function cm_localize() {

		$settings = get_option( 'ccj_settings' );

		$vars = array(
			'autocomplete'   => isset( $settings['ccj_autocomplete'] ) && ! $settings['ccj_autocomplete'] ? false : true,
			'active'         => __( 'Active', 'add_css_js' ),
			'inactive'       => __( 'Inactive', 'add_css_js' ),
			'activate'       => __( 'Activate', 'add_css_js' ),
			'deactivate'     => __( 'Deactivate', 'add_css_js' ),
			'active_title'   => __( 'The code is active. Click to deactivate it', 'add_css_js' ),
			'deactive_title' => __( 'The code is inactive. Click to activate it', 'add_css_js' ),

			/* CodeMirror options */
			'allcssfile' => array(
				'indentUnit'       => 4,
				'indentWithTabs'   => true,
				'inputStyle'       => 'contenteditable',
				'lineNumbers'      => true,
				'lineWrapping'     => true,
				'styleActiveLine'  => true,
				'continueComments' => true,
				'extraKeys'        => array(
					'Ctrl-Space' => 'autocomplete',
					'Cmd-Space'  => 'autocomplete',
					'Ctrl-/'     => 'toggleComment',
					'Cmd-/'      => 'toggleComment',
					'Alt-F'      => 'findPersistent',
					'Ctrl-F'     => 'findPersistent',
					'Cmd-F'      => 'findPersistent',
					'Ctrl-J'     =>  'toMatchingTag',
				),
				'direction'        => 'ltr', // Code is shown in LTR even in RTL languages.
				'gutters'          => array( 'allcssfile-lint-markers' ),
				'matchBrackets'    => true,
				'matchTags'        => array( 'bothTags' => true ),
				'autoCloseBrackets' => true,
				'autoCloseTags'    => true,
			)
		);

		return apply_filters( 'ccj_code_editor_settings', $vars);
	}

	public function add_meta_boxes() {
		add_meta_box( 'custom-code-options', __( 'Options', 'add_css_js' ), array( $this, 'custom_code_options_meta_box_callback' ), 'add_css_js', 'side', 'low' );

		remove_meta_box( 'slugdiv', 'add_css_js', 'normal' );
	}



	/**
	 * Get options for a specific add_css_js post
	 */
	private function get_options( $post_id ) {
		if ( isset( $this->options[ $post_id ] ) ) {
			return $this->options[ $post_id ];
		}

		$options = get_post_meta( $post_id );
		if ( empty( $options ) || ! isset( $options['options'][0] ) ) {
			$this->options[ $post_id ] = $this->default_options;
			return $this->default_options;
		}

		$options                   = unserialize( $options['options'][0] );
		$this->options[ $post_id ] = $options;
		return $options;
	}


	/**
	 * Reformat the `edit` or the `post` screens
	 */
	function current_screen( $current_screen ) {

		if ( $current_screen->post_type != 'add_css_js' ) {
			return false;
		}

		if ( $current_screen->base == 'post' ) {
			add_action( 'admin_head', array( $this, 'current_screen_post' ) );
		}

		if ( $current_screen->base == 'edit' ) {
			add_action( 'admin_head', array( $this, 'current_screen_edit' ) );
		}

		wp_deregister_script( 'autosave' );
	}



	/**
	 * Add the buttons in the `edit` screen
	 */
	function add_new_buttons() {
		$current_screen = get_current_screen();

		if ( ( isset( $current_screen->action ) && $current_screen->action == 'add' ) || $current_screen->post_type != 'add_css_js' ) {
			return false;
		}
		?>
	<div class="updated buttons">
	<a href="post-new.php?post_type=add_css_js&language=css" class="custom-btn custom-css-btn"><?php _e( 'Add CSS code', 'add_css_js' ); ?></a>
	<a href="post-new.php?post_type=add_css_js&language=js" class="custom-btn custom-js-btn"><?php _e( 'Add JS code', 'add_css_js' ); ?></a>
	<a href="post-new.php?post_type=add_css_js&language=html" class="custom-btn custom-js-btn"><?php _e( 'Add HTML code', 'add_css_js' ); ?></a>
		<!-- a href="post-new.php?post_type=add_css_js&language=php" class="custom-btn custom-php-btn">Add PHP code</a -->
	</div>
		<?php
	}



	/**
	 * Add new columns in the `edit` screen
	 */
	function manage_custom_posts_columns( $columns ) {
		return array(
			'cb'        => '<input type="checkbox" />',
			'active'    => '<span class="ccj-dashicons dashicons dashicons-star-empty" title="' . __( 'Active', 'add_css_js' ) . '"></span>',
			'type'      => __( 'Type', 'add_css_js' ),
			'title'     => __( 'Title' ),
			'author'    => __( 'Author' ),
			
		);
	}


	/**
	 * Fill the data for the new added columns in the `edit` screen
	 */
	function manage_posts_columns( $column, $post_id ) {

		if ( 'type' === $column ) {
			$options = $this->get_options( $post_id );
			echo '<span class="language language-' . $options['language'] . '">' . $options['language'] . '</span>';
		}

		

		if ( 'active' === $column ) {
			$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=ccj_active_code&code_id=' . $post_id ), 'ccj-active-code-' . $post_id );
			if ( $this->is_active( $post_id ) ) {
				$active_title = __( 'The code is active. Click to deactivate it', 'add_css_js' );
				$active_icon  = 'dashicons-star-filled';
			} else {
				$active_title = __( 'The code is inactive. Click to activate it', 'add_css_js' );
				$active_icon  = 'dashicons-star-empty ccj_row';
			}
			echo '<a href="' . esc_url( $url ) . '" class="ccj_activate_deactivate" data-code-id="' . $post_id . '" title="' . $active_title . '">' .
				'<span class="dashicons ' . $active_icon . '"></span>' .
				'</a>';
		}
	}


	/**
	 * Make the 'Modified' column sortable
	 */
	function manage_edit_posts_sortable_columns( $columns ) {
		$columns['active']    = 'active';
		$columns['type']      = 'type';
		$columns['modified']  = 'modified';
		$columns['published'] = 'published';
		return $columns;

	}


	/**
	 * List table: Change the query in order to filter by code type
	 */
	function parse_query( $query ) {
		global $wpdb;
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return $query;
		}

		if ( ! isset( $query->query['post_type'] ) ) {
			return $query;
		}

		if ( 'add_css_js' !== $query->query['post_type'] ) {
			return $query;
		}

		$filter = filter_input( INPUT_GET, 'language_filter' );
		if ( ! is_string( $filter ) || strlen( $filter ) == 0 ) {
			return $query;
		}
		$filter = '%' . $wpdb->esc_like( $filter ) . '%';

		$post_id_query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value LIKE %s";
		$post_ids      = $wpdb->get_col( $wpdb->prepare( $post_id_query, 'options', $filter ) );
		if ( ! is_array( $post_ids ) || count( $post_ids ) == 0 ) {
			$post_ids = array( -1 );
		}
		$query->query_vars['post__in'] = $post_ids;

		return $query;
	}


	/**
	 * List table: add a filter by code type
	 */
	function restrict_manage_posts( $post_type ) {
		if ( 'add_css_js' !== $post_type ) {
			return;
		}

		$languages = array(
			'css'  => __( 'CSS Codes', 'custom-cs-js' ),
			'js'   => __( 'JS Codes', 'add_css_js' ),
			'html' => __( 'HTML Codes', 'add_css_js' ),
		);

		echo '<label class="screen-reader-text" for="add_css_js-filter">' . esc_html__( 'Filter Code Type', 'add_css_js' ) . '</label>';
		echo '<select name="language_filter" id="add_css_js-filter">';
		echo '<option  value="">' . __( 'All Codes', 'add_css_js' ) . '</option>';
		foreach ( $languages as $_lang => $_label ) {
			$selected = selected( filter_input( INPUT_GET, 'language_filter' ), $_lang, false );
			echo '<option ' . $selected . ' value="' . $_lang . '">' . $_label . '</option>';
		}
		echo '</select>';
	}


	/**
	 * Order table by Type and Active columns
	 */
	function posts_orderby( $orderby, $query ) {
		if ( ! is_admin() ) {
			return $orderby;
		}
		global $wpdb;

		if ( 'add_css_js' === $query->get( 'post_type' ) && 'type' === $query->get( 'orderby' ) ) {
			$orderby = "REGEXP_SUBSTR( {$wpdb->prefix}postmeta.meta_value, 'js|html|css') " . $query->get( 'order' );
		}
		if ( 'add_css_js' === $query->get( 'post_type' ) && 'active' === $query->get( 'orderby' ) ) {
			$orderby = "coalesce( postmeta1.meta_value, 'p' ) " . $query->get( 'order' );
		}
		return $orderby;
	}


	/**
	 * Order table by Type and Active columns
	 */
	function posts_join_paged( $join, $query ) {
		if ( ! is_admin() ) {
			return $join;
		}
		global $wpdb;

		if ( 'add_css_js' === $query->get( 'post_type' ) && 'type' === $query->get( 'orderby' ) ) {
			$join = "LEFT JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id";
		}

		if ( 'add_css_js' === $query->get( 'post_type' ) && 'active' === $query->get( 'orderby' ) ) {
			$join = "LEFT JOIN (SELECT post_id AS ID, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '_active' ) as postmeta1 USING( ID )";
		}
		return $join;
	}


	/**
	 * Order table by Type and Active columns
	 */
	function posts_where_paged( $where, $query ) {
		if ( ! is_admin() ) {
			return $where;
		}
		global $wpdb;

		if ( 'add_css_js' === $query->get( 'post_type' ) && 'type' === $query->get( 'orderby' ) ) {
			$where .= " AND {$wpdb->prefix}postmeta.meta_key = 'options'";
		}
		return $where;
	}


	/**
	 * Activate/deactivate a code
	 *
	 * @return void
	 */
	function wp_ajax_ccj_active_code() {
		if ( ! isset( $_GET['code_id'] ) ) {
			die();
		}

		$code_id = absint( $_GET['code_id'] );

		$response = 'error';
		if ( check_admin_referer( 'ccj-active-code-' . $code_id ) ) {

			if ( 'add_css_js' === get_post_type( $code_id ) ) {
				$active = get_post_meta( $code_id, '_active', true );
				$active = ( $active !== 'no' ) ? $active = 'yes' : 'no';

				update_post_meta( $code_id, '_active', $active === 'yes' ? 'no' : 'yes' );
				$this->build_search_tree();
			}
		}
		echo $active;

		die();
	}

	/**
	 * Check if a code is active
	 *
	 * @return bool
	 */
	function is_active( $post_id ) {
		return get_post_meta( $post_id, '_active', true ) !== 'no';
	}

	/**
	 * Reformat the `edit` screen
	 */
	function current_screen_edit() {
		
	}


	/**
	 * Reformat the `post` screen
	 */
	function current_screen_post() {

		$this->remove_unallowed_metaboxes();

		$strings = array(
			'Add CSS Code'   => __( 'Add CSS Code', 'add_css_js' ),
			'Add JS Code'    => __( 'Add JS Code', 'add_css_js' ),
			'Add HTML Code'  => __( 'Add HTML Code', 'add_css_js' ),
			'Edit CSS Code'  => __( 'Edit CSS Code', 'add_css_js' ),
			'Edit JS Code'   => __( 'Edit JS Code', 'add_css_js' ),
			'Edit HTML Code' => __( 'Edit HTML Code', 'add_css_js' ),
		);

		if ( isset( $_GET['post'] ) ) {
			$action  = 'Edit';
			$post_id = esc_attr( $_GET['post'] );
		} else {
			$action  = 'Add';
			$post_id = false;
		}
		$language = $this->get_language( $post_id );

		$title = $action . ' ' . strtoupper( $language ) . ' Code';
		$title = ( isset( $strings[ $title ] ) ) ? $strings[ $title ] : $strings['Add CSS Code'];

	

		?>
		<style type="text/css">
			#post-body-content, .edit-form-section { position: static !important; }
			#ed_toolbar { display: none; }
			#postdivrich { display: none; }
		</style>
		<script type="text/javascript">
			 /* <![CDATA[ */
			jQuery(window).ready(function($){
				$("#wpbody-content h1").html('<?php echo $title; ?>');
				$("#message.updated.notice").html('<p><?php _e( 'Code updated', 'add_css_js' ); ?></p>');

				var from_top = -$("#normal-sortables").height();
				if ( from_top != 0 ) {
					$(".ccj_only_premium-first").css('margin-top', from_top.toString() + 'px' );
				} else {
					$(".ccj_only_premium-first").hide();
				}
			});
			/* ]]> */
		</script>
		<?php
	}


	/**
	 * Remove unallowed metaboxes from add_css_js edit page
	 *
	 * Use the add_css_js-meta-boxes filter to add/remove allowed metaboxdes on the page
	 */
	function remove_unallowed_metaboxes() {
		global $wp_meta_boxes;

		// Side boxes
		$allowed = array( 'submitdiv', 'custom-code-options' );

		$allowed = apply_filters( 'add_css_js-meta-boxes', $allowed );

		foreach ( $wp_meta_boxes['add_css_js']['side'] as $_priority => $_boxes ) {
			foreach ( $_boxes as $_key => $_value ) {
				if ( ! in_array( $_key, $allowed ) ) {
					unset( $wp_meta_boxes['add_css_js']['side'][ $_priority ][ $_key ] );
				}
			}
		}

		// Normal boxes
		$allowed = array( 'slugdiv', 'previewdiv', 'url-rules', 'revisionsdiv' );

		$allowed = apply_filters( 'add_css_js-meta-boxes-normal', $allowed );

		foreach ( $wp_meta_boxes['add_css_js']['normal'] as $_priority => $_boxes ) {
			foreach ( $_boxes as $_key => $_value ) {
				if ( ! in_array( $_key, $allowed ) ) {
					unset( $wp_meta_boxes['add_css_js']['normal'][ $_priority ][ $_key ] );
				}
			}
		}

		unset( $wp_meta_boxes['add_css_js']['advanced'] );
	}



	/**
	 * Add the codemirror editor in the `post` screen
	 */
	public function allcssfile_editor( $post ) {

		$current_screen = get_current_screen();

		if ( $current_screen->post_type != 'add_css_js' ) {
			return false;
		}

		if ( empty( $post->title ) && empty( $post->post_content ) ) {
			$new_post = true;
			$post_id  = false;
		} else {
			$new_post = false;
			if ( ! isset( $_GET['post'] ) ) {
				$_GET['post'] = $post->id;
			}
			$post_id = esc_attr( $_GET['post'] );
		}
		$language = $this->get_language( $post_id );

		$settings = get_option( 'ccj_settings' );

		// Replace the htmlentities (https://wordpress.org/support/topic/annoying-bug-in-text-editor/), but only selectively
		if ( isset( $settings['ccj_htmlentities'] ) && $settings['ccj_htmlentities'] == 1 && strstr( $post->post_content, '&' ) ) {

			// First the ampresands
			$post->post_content = str_replace( '&amp', htmlentities( '&amp' ), $post->post_content );

			// Then the rest of the entities
			$html_flags = defined( 'ENT_HTML5' ) ? ENT_QUOTES | ENT_HTML5 : ENT_QUOTES;
			$entities   = get_html_translation_table( HTML_ENTITIES, $html_flags );
			unset( $entities[ array_search( '&amp;', $entities ) ] );
			$regular_expression = str_replace( ';', '', '/(' . implode( '|', $entities ) . ')/i' );
			preg_match_all( $regular_expression, $post->post_content, $matches );
			if ( isset( $matches[0] ) && count( $matches[0] ) > 0 ) {
				foreach ( $matches[0] as $_entity ) {
					$post->post_content = str_replace( $_entity, htmlentities( $_entity ), $post->post_content );
				}
			}
		}
		switch ( $language ) {
			case 'js':
				
				$code_mirror_mode   = 'text/javascript';
				$code_mirror_before = '<script type="text/javascript">';
				$code_mirror_after  = '</script>';
				break;
			case 'php':
				if ( $new_post ) {
					$post->post_content = '/* The following will be executed as if it were written in functions.php. */' . PHP_EOL . PHP_EOL;
				}
				$code_mirror_mode   = 'php';
				$code_mirror_before = '<?php';
				$code_mirror_after  = '?>';

				break;
			default:
				$code_mirror_mode   = 'text/css';
				$code_mirror_before = '<style type="text/css">';
				$code_mirror_after  = '</style>';
		}

		?>

				<div class="code-mirror-buttons">
				<div class="button-left"><span rel="tipsy" original-title="<?php _e( 'Beautify Code', 'add_css_js-pro' ); ?>"><button type="button" tabindex="-1" id="ccj-beautifier"><i class="ccj-i-beautifier"></i></button></span></div>
				<!--div class="button-left"><span rel="tipsy" original-title="<?php _e( 'Editor Settings', 'add_css_js-pro' ); ?>"><button type="button" tabindex="-1" id="ccj-settings"><i class="ccj-i-settings"></i></button></span></div -->
				<div class="button-right" id="ccj-fullscreen-button" alt="<?php _e( 'Distraction-free writing mode', 'add_css_js-pro' ); ?>"><span rel="tipsy" original-title="<?php _e( 'Fullscreen', 'add_css_js-pro' ); ?>"><button role="presentation" type="button" tabindex="-1"><i class="ccj-i-fullscreen"></i></button></span></div>
<input type="hidden" name="fullscreen" id="ccj-fullscreen-hidden" value="false" />
<!-- div class="button-right" id="ccj-search-button" alt="Search"><button role="presentation" type="button" tabindex="-1"><i class="ccj-i-find"></i></button></div -->

				</div>

				<div class="code-mirror-before"><div><?php echo htmlentities( $code_mirror_before ); ?></div><textarea class="setcolortext"   mode="<?php echo htmlentities( $code_mirror_mode ); ?>" name="content" autofocus><?php echo $post->post_content; ?></textarea></div>
				
				<div class="code-mirror-after"><div><?php echo htmlentities( $code_mirror_after ); ?></div></div>

				<table id="post-status-info"><tbody><tr>
					<td class="autosave-info">
					<span class="autosave-message">&nbsp;</span>
				<?php
				if ( 'auto-draft' != $post->post_status ) {
					echo '<span id="last-edit">';
					if ( $last_user = get_userdata( get_post_meta( $post->ID, '_edit_last', true ) ) ) {
						printf( __( 'Last edited by %1$s on %2$s at %3$s', 'add_css_js-pro' ), esc_html( $last_user->display_name ), mysql2date( get_option( 'date_format' ), $post->post_modified ), mysql2date( get_option( 'time_format' ), $post->post_modified ) );
					} else {
						printf( __( 'Last edited on %1$s at %2$s', 'add_css_js-pro' ), mysql2date( get_option( 'date_format' ), $post->post_modified ), mysql2date( get_option( 'time_format' ), $post->post_modified ) );
					}
					echo '</span>';
				}
				?>
					</td>
				</tr></tbody></table>


				<input type="hidden" id="update-post_<?php echo $post->ID; ?>" value="<?php echo wp_create_nonce( 'update-post_' . $post->ID ); ?>" />
		<?php

	}



	/**
	 * Show the options form in the `post` screen
	 */
	function custom_code_options_meta_box_callback( $post ) {

			$options = $this->get_options( $post->ID );
		if ( ! isset( $options['preprocessor'] ) ) {
			$options['preprocessor'] = 'none';
		}

		if ( isset( $_GET['language'] ) ) {
			$options['language'] = $this->get_language();
		}

			$meta = $this->get_options_meta();
		if ( $options['language'] == 'html' ) {
			$meta = $this->get_options_meta_html();
		}

		$options['multisite'] = false;

		wp_nonce_field( 'options_save_meta_box_data', 'add_css_js_meta_box_nonce' );

		?>
			<div class="options_meta_box">
			<?php

			$output = '';

			foreach ( $meta as $_key => $a ) {
				$close_div = false;

				if ( ( $_key == 'preprocessor' && $options['language'] == 'css' ) ||
					( $_key == 'linking' && $options['language'] == 'html' ) ||
					in_array( $_key, ['priority', 'minify', 'multisite' ] ) ) {
					$close_div = true;
					$output   .= '<div class="ccj_opaque">';
				}

				// Don't show Pre-processors for JavaScript Codes
				if ( $options['language'] == 'js' && $_key == 'preprocessor' ) {
					continue;
				}

				$output .= '<h3>' . esc_attr( $a['title'] ) . '</h3>' . PHP_EOL;

				$output .= $this->render_input( $_key, $a, $options );

				if ( $close_div ) {
					$output .= '</div>';
				}
			}

			echo $output;

			?>

			<input type="hidden" name="custom_code_language" value="<?php echo $options['language']; ?>" />
			<h2><label class="setcolortitle" >Select Editor Color</label></h2><br>
			<input type="radio" onclick='allgreen()' class="age1" name="age" value="30"><b>Maroon</b>
			<input type="radio" onclick='allred()' class="age2" name="age" value="60"><b>Gray</b>
			<input type="radio" onclick='allblue()' class="age3" name="age" value="100"><b>ForestGreen</b>
			<h2><label class="setcolortitle">Select Editor Font Size</label></h2><br>
			<input type="radio" onclick='allfont1()' class="font1" name="font" value="16"><b>16Px</b>
			<input type="radio" onclick='allfont2()' class="font2" name="font" value="24"><b>24Px</b>
			<input type="radio" onclick='allfont3()' class="font3" name="font" value="28"><b>28PX</b>
			<input type="radio" onclick='allfont4()' class="font4" name="font" value="35"><b>35PX</b>
			
			<div style="clear: both;"></div>

			</div>

			<div class="ccj_only_premium ccj_only_premium-right">
				
			</div>


			<?php
	}


	/**
	 * Get an array with all the information for building the code's options
	 */
	function get_options_meta() {
		$options = array(
			'type'         => array(
				'title'   => __( 'Where on page', 'add_css_js' ),
				'type'    => 'radio',
				'default' => 'header',
				'values'  => array(
					'header' => array(
						'title'    => __( 'In the <head> element', 'add_css_js' ),
						'dashicon' => 'arrow-up-alt2',
					),
					'footer' => array(
						'title'    => __( 'In the <footer> element', 'add_css_js' ),
						'dashicon' => 'arrow-down-alt2',
					),
				),
			),
			'side'         => array(
				'title'   => __( 'Where in site', 'add_css_js' ),
				'type'    => 'radio',
				'default' => 'frontend',
				'values'  => array(
					'frontend' => array(
						'title'    => __( 'In Frontend', 'add_css_js' ),
						'dashicon' => 'tagcloud',
					),
					'admin'    => array(
						'title'    => __( 'In Admin', 'add_css_js' ),
						'dashicon' => 'id',
					),
					
				),
			),
			
			
		);

		if ( is_multisite() && is_super_admin() && is_main_site() ) {
			$options['multisite'] = array(
				'title'    => __( 'Apply network wide', 'add_css_js-pro' ),
				'type'     => 'checkbox',
				'default'  => false,
				'dashicon' => 'admin-multisite',
				'disabled' => true,
			);
		}

		return $options;
	}


	/**
	 * Get an array with all the information for building the code's options
	 */
	function get_options_meta_html() {
		$options = array(
			'type'     => array(
				'title'   => __( 'Where on page', 'add_css_js' ),
				'type'    => 'radio',
				'default' => 'header',
				'values'  => array(
					'header' => array(
						'title'    => __( 'Header', 'add_css_js' ),
						'dashicon' => 'arrow-up-alt2',
					),
					'footer' => array(
						'title'    => __( 'Footer', 'add_css_js' ),
						'dashicon' => 'arrow-down-alt2',
					),
				),
			),
			'side'     => array(
				'title'   => __( 'Where in site', 'add_css_js' ),
				'type'    => 'radio',
				'default' => 'frontend',
				'values'  => array(
					'frontend' => array(
						'title'    => __( 'In Frontend', 'add_css_js' ),
						'dashicon' => 'tagcloud',
					),
					'admin'    => array(
						'title'    => __( 'In Admin', 'add_css_js' ),
						'dashicon' => 'id',
					),
				),
			),
			'linking'  => array(
				'title'    => __( 'On which device', 'add_css_js' ),
				'type'     => 'radio',
				'default'  => 'both',
				'dashicon' => '',
				'values'   => array(
					'desktop' => array(
						'title'    => __( 'Desktop', 'add_css_js' ),
						'dashicon' => 'desktop',
					),
					'mobile'  => array(
						'title'    => __( 'Mobile', 'add_css_js' ),
						'dashicon' => 'smartphone',
					),
					'both'    => array(
						'title'    => __( 'Both', 'add_css_js' ),
						'dashicon' => 'tablet',
					),
				),
				'disabled' => true,
			),
			'priority' => array(
				'title'    => __( 'Priority', 'add_css_js' ),
				'type'     => 'select',
				'default'  => 5,
				'dashicon' => 'sort',
				'values'   => array(
					1  => _x( '1 (highest)', '1 is the highest priority', 'add_css_js' ),
					2  => '2',
					3  => '3',
					4  => '4',
					5  => '5',
					6  => '6',
					7  => '7',
					8  => '8',
					9  => '9',
					10 => _x( '10 (lowest)', '10 is the lowest priority', 'add_css_js' ),
				),
				'disabled' => true,
			),

		);

		if ( function_exists( 'wp_body_open' ) ) {
			$tmp = $options['type']['values'];
			unset( $options['type']['values'] );
			$options['type']['values']['header']    = $tmp['header'];
			$options['type']['values']['body_open'] = array(
				'title'    => __( 'After &lt;body&gt; tag', 'add_css_js' ),
				'dashicon' => 'editor-code',
			);
			$options['type']['values']['footer']    = $tmp['footer'];
		}

		if ( is_multisite() && is_super_admin() && is_main_site() ) {
			$options['multisite'] = array(
				'title'    => __( 'Apply network wide', 'add_css_js-pro' ),
				'type'     => 'checkbox',
				'default'  => false,
				'dashicon' => 'admin-multisite',
				'disabled' => true,
			);
		}

		return $options;
	}


	/**
	 * Save the post and the metadata
	 */
	function options_save_meta_box_data( $post_id ) {

		// The usual checks
		if ( ! isset( $_POST['add_css_js_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['add_css_js_meta_box_nonce'], 'options_save_meta_box_data' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'add_css_js' != $_POST['post_type'] ) {
			return;
		}

		// Update the post's meta
		$defaults = array(
			'type'     => 'header',
			'linking'  => 'internal',
			'priority' => 5,
			'side'     => 'frontend',
			'language' => 'css',
		);

		if ( $_POST['custom_code_language'] == 'html' ) {
			$defaults = array(
				'type'     => 'header',
				'linking'  => 'both',
				'side'     => 'frontend',
				'language' => 'html',
				'priority' => 5,
			);
		}

		foreach ( $defaults as $_field => $_default ) {
			$options[ $_field ] = isset( $_POST[ 'custom_code_' . $_field ] ) ? esc_attr( strtolower( $_POST[ 'custom_code_' . $_field ] ) ) : $_default;
		}

		$options['language'] = in_array( $options['language'], array( 'html', 'css', 'js' ), true ) ? $options['language'] : $defaults['language'];

		update_post_meta( $post_id, 'options', $options );

		if ( $options['language'] == 'html' ) {
			$this->build_search_tree();
			return;
		}

		if ( $options['language'] == 'js' ) {
			// Replace the default comment
			if ( preg_match( '@/\* Add your JavaScript code here[\s\S]*?End of comment \*/@im', $_POST['content'] ) ) {
				$_POST['content'] = preg_replace( '@/\* Add your JavaScript code here[\s\S]*?End of comment \*/@im', '/* Default comment here */', $_POST['content'] );
			}

			// For other locales remove all the comments
			if ( substr( get_locale(), 0, 3 ) !== 'en_' ) {
				$_POST['content'] = preg_replace( '@/\*[\s\S]*?\*/@', '', $_POST['content'] );
			}
		}

		// Save the Custom Code in a file in `wp-content/uploads/add_css_js`
		if ( $options['linking'] == 'internal' ) {

			$before = '<!-- start Simple Custom CSS and JS -->' . PHP_EOL;
			$after  = '<!-- end Simple Custom CSS and JS -->' . PHP_EOL;
			if ( $options['language'] == 'css' ) {
				$before .= '<style type="text/css">' . PHP_EOL;
				$after   = '</style>' . PHP_EOL . $after;
			}
			if ( $options['language'] == 'js' ) {
				if ( ! preg_match( '/<script\b[^>]*>([\s\S]*?)<\/script>/im', $_POST['content'] ) ) {
					$before .= '<script type="text/javascript">' . PHP_EOL;
					$after   = '</script>' . PHP_EOL . $after;
				} else {
					// the content has a <script> tag, then remove the comments so they don't show up on the frontend
					$_POST['content'] = preg_replace( '@/\*[\s\S]*?\*/@', '', $_POST['content'] );
				}
			}
		}

		if ( $options['linking'] == 'external' ) {
			$before = '/******* Do not edit this file *******' . PHP_EOL .
			'Simple Custom CSS and JS - by Silkypress.com' . PHP_EOL .
			'Saved: ' . date( 'M d Y | H:i:s' ) . ' */' . PHP_EOL;
			$after  = '';
		}

		if ( wp_is_writable( CCJ_UPLOAD_DIR ) ) {
			$file_name    = $post_id . '.' . $options['language'];
			$file_content = $before . stripslashes( $_POST['content'] ) . $after;
			@file_put_contents( CCJ_UPLOAD_DIR . '/' . $file_name, $file_content );

			// save the file as the Permalink slug
			$slug = get_post_meta( $post_id, '_slug', true );
			if ( $slug ) {
				@file_put_contents( CCJ_UPLOAD_DIR . '/' . sanitize_file_name( $slug ) . '.' . $options['language'], $file_content );
			}
		}

		$this->build_search_tree();
	}

	/**
	 * Create the add_css_js dir in uploads directory
	 *
	 * Show a message if the directory is not writable
	 *
	 * Create an empty index.php file inside
	 */
	function create_uploads_directory() {
		$current_screen = get_current_screen();

		// Check if we are editing a add_css_js post
		if ( $current_screen->base != 'post' || $current_screen->post_type != 'add_css_js' ) {
			return false;
		}

		$dir = CCJ_UPLOAD_DIR;

		// Create the dir if it doesn't exist
		if ( ! file_exists( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		// Show a message if it couldn't create the dir
		if ( ! file_exists( $dir ) ) :
			?>
			 <div class="notice notice-error is-dismissible">
			 <p><?php printf( __( 'The %s directory could not be created', 'add_css_js' ), '<b>add_css_js</b>' ); ?></p>
			 <p><?php _e( 'Please run the following commands in order to make the directory', 'add_css_js' ); ?>: <br /><strong>mkdir <?php echo $dir; ?>; </strong><br /><strong>chmod 777 <?php echo $dir; ?>;</strong></p>
			</div>
			<?php
			return;
endif;

		// Show a message if the dir is not writable
		if ( ! wp_is_writable( $dir ) ) :
			?>
			 <div class="notice notice-error is-dismissible">
			 <p><?php printf( __( 'The %s directory is not writable, therefore the CSS and JS files cannot be saved.', 'add_css_js' ), '<b>' . $dir . '</b>' ); ?></p>
			 <p><?php _e( 'Please run the following command to make the directory writable', 'add_css_js' ); ?>:<br /><strong>chmod 777 <?php echo $dir; ?> </strong></p>
			</div>
			<?php
			return;
endif;

		// Write a blank index.php
		if ( ! file_exists( $dir . '/index.php' ) ) {
			$content = '<?php' . PHP_EOL . '// Silence is golden.';
			@file_put_contents( $dir . '/index.php', $content );
		}
	}


	/**
	 * Build a tree where you can quickly find the needed add_css_js posts
	 *
	 * @return void
	 */
	private function build_search_tree() {

		// Retrieve all the add_css_js codes
		$posts = query_posts( 'post_type=add_css_js&post_status=publish&nopaging=true' );

		$tree = array();
		foreach ( $posts as $_post ) {
			if ( ! $this->is_active( $_post->ID ) ) {
				continue;
			}

			$options = $this->get_options( $_post->ID );

			// Get the branch name, example: frontend-css-header-external
			$tree_branch = $options['side'] . '-' . $options['language'] . '-' . $options['type'] . '-' . $options['linking'];

			$filename = $_post->ID . '.' . $options['language'];

			if ( $options['linking'] == 'external' ) {
				$filename .= '?v=' . rand( 1, 10000 );
			}

			// Add the code file to the tree branch
			$tree[ $tree_branch ][] = $filename;

			// Mark to enqueue the jQuery library, if necessary
			if ( $options['language'] === 'js' ) {
				$_post->post_content = preg_replace( '@/\* Add your JavaScript code here[\s\S]*?End of comment \*/@im', '/* Default comment here */', $_post->post_content );
				if ( preg_match( '/jquery\s*(\(|\.)/i', $_post->post_content ) && ! isset( $tree['jquery'] ) ) {
					$tree['jquery'] = true;
				}
			}

		}

		// Save the tree in the database
		update_option( 'add_css_js-tree', $tree );
	}

	/**
	 * Rebuilt the tree when you trash or restore a custom code
	 */
	function trash_post( $post_id ) {
		$this->build_search_tree();
	}

	/**
	 * Render the checkboxes, radios, selects and inputs
	 */
	function render_input( $_key, $a, $options ) {
		$name   = 'custom_code_' . $_key;
		$output = '';

		// Show radio type options
		if ( $a['type'] === 'radio' ) {
			$output .= '<div class="radio-group">' . PHP_EOL;
			foreach ( $a['values'] as $__key => $__value ) {
				$selected  = '';
				$id        = $name . '-' . $__key;
				$dashicons = isset( $__value['dashicon'] ) ? 'dashicons-before dashicons-' . $__value['dashicon'] : '';
				if ( isset( $a['disabled'] ) && $a['disabled'] ) {
					$selected = ' disabled="disabled"';
				}
				$selected .= ( $__key == $options[ $_key ] ) ? ' checked="checked" ' : '';
				$output   .= '<input type="radio" ' . $selected . 'value="' . $__key . '" name="' . $name . '" id="' . $id . '">' . PHP_EOL;
				$output   .= '<label class="' . $dashicons . '" for="' . $id . '"> ' . esc_attr( $__value['title'] ) . '</label><br />' . PHP_EOL;
			}
			$output .= '</div>' . PHP_EOL;
		}

		// Show checkbox type options
		if ( $a['type'] == 'checkbox' ) {
			$dashicons = isset( $a['dashicon'] ) ? 'dashicons-before dashicons-' . $a['dashicon'] : '';
			$selected  = ( isset( $options[ $_key ] ) && $options[ $_key ] == '1' ) ? ' checked="checked" ' : '';
			if ( isset( $a['disabled'] ) && $a['disabled'] ) {
				$selected .= ' disabled="disabled"';
			}
			$output .= '<div class="radio-group">' . PHP_EOL;
			$output .= '<input type="checkbox" ' . $selected . ' value="1" name="' . $name . '" id="' . $name . '">' . PHP_EOL;
			$output .= '<label class="' . $dashicons . '" for="' . $name . '"> ' . esc_attr( $a['title'] ) . '</label>';
			$output .= '</div>' . PHP_EOL;
		}

		// Show select type options
		if ( $a['type'] == 'select' ) {
			$output .= '<div class="radio-group">' . PHP_EOL;
			$output .= '<select name="' . $name . '" id="' . $name . '">' . PHP_EOL;
			foreach ( $a['values'] as $__key => $__value ) {
				$selected = ( isset( $options[ $_key ] ) && $options[ $_key ] == $__key ) ? ' selected="selected"' : '';
				$output  .= '<option value="' . $__key . '"' . $selected . '>' . esc_attr( $__value ) . '</option>' . PHP_EOL;
			}
			$output .= '</select>' . PHP_EOL;
			$output .= '</div>' . PHP_EOL;
		}

		return $output;

	}


	/**
	 * Get the language for the current post
	 */
	function get_language( $post_id = false ) {
		if ( $post_id !== false ) {
			$options  = $this->get_options( $post_id );
			$language = $options['language'];
		} else {
			$language = isset( $_GET['language'] ) ? esc_attr( strtolower( $_GET['language'] ) ) : 'css';
		}
		if ( ! in_array( $language, array( 'css', 'js', 'html' ) ) ) {
			$language = 'css';
		}

		return $language;
	}


	/**
	 * Show the activate/deactivate link in the row's action area
	 */
	function post_row_actions( $actions, $post ) {
		if ( 'add_css_js' !== $post->post_type ) {
			return $actions;
		}

		$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=ccj_active_code&code_id=' . $post->ID ), 'ccj-active-code-' . $post->ID );
		if ( $this->is_active( $post->ID ) ) {
			$active_title = __( 'The code is active. Click to deactivate it', 'add_css_js' );
			$active_text  = __( 'Deactivate', 'add_css_js' );
		} else {
			$active_title = __( 'The code is inactive. Click to activate it', 'add_css_js' );
			$active_text  = __( 'Activate', 'add_css_js' );
		}
		$actions['activate'] = '<a href="' . esc_url( $url ) . '" title="' . $active_title . '" class="ccj_activate_deactivate" data-code-id="' . $post->ID . '">' . $active_text . '</a>';

		return $actions;
	}


	/**
	 * Show the activate/deactivate link in admin.
	 */
	public function post_submitbox_start() {
		global $post;

		if ( ! is_object( $post ) ) {
			return;
		}

		if ( 'add_css_js' !== $post->post_type ) {
			return;
		}

		if ( ! isset( $_GET['post'] ) ) {
			return;
		}

		$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=ccj_active_code&code_id=' . $post->ID ), 'ccj-active-code-' . $post->ID );

		if ( $this->is_active( $post->ID ) ) {
			$text   = __( 'Active', 'add_css_js' );
			$action = __( 'Deactivate', 'add_css_js' );
		} else {
			$text   = __( 'Inactive', 'add_css_js' );
			$action = __( 'Activate', 'add_css_js' );
		}
		?>
		<div id="activate-action"><span style="font-weight: bold;"><?php echo $text; ?></span>
		(<a class="ccj_activate_deactivate" data-code-id="<?php echo $post->ID; ?>" href="<?php echo esc_url( $url ); ?>"><?php echo $action; ?></a>)
		</div>
		<?php
	}


	/**
	 * Show the Permalink edit form
	 */
	function edit_form_before_permalink( $filename = '', $permalink = '', $filetype = 'css' ) {
		if ( isset( $_GET['language'] ) ) {
			$filetype = strtolower(trim($_GET['language']));
		}

		if ( ! in_array( $filetype, array( 'css', 'js' ) ) ) {
			return;
		}

		if ( ! is_string( $filename ) ) {
			global $post;
			if ( ! is_object( $post ) ) {
				return;
			}
			if ( 'add_css_js' !== $post->post_type ) {
				return;
			}

			$post    = $filename;
			$slug    = get_post_meta( $post->ID, '_slug', true );
			$options = get_post_meta( $post->ID, 'options', true );

			if ( isset( $options['language'] ) ) {
				$filetype = $options['language'];
			}
			if ( $filetype === 'html' ) {
				return;
			}
			if ( ! @file_exists( CCJ_UPLOAD_DIR . '/' . $slug . '.' . $filetype ) ) {
				$slug = false;
			}
			$filename = ( $slug ) ? $slug : $post->ID;
		}

		if ( empty( $permalink ) ) {
			$permalink = CCJ_UPLOAD_URL . '/' . $filename . '.' . $filetype;
		}

		?>
		<div class="inside">
			<div id="edit-slug-box" class="hide-if-no-js">
				<strong>Permalink:</strong>
				<span id="sample-permalink"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( CCJ_UPLOAD_URL ) . '/'; ?><span id="editable-post-name"><?php echo esc_html( $filename ); ?></span>.<?php echo esc_html( $filetype ); ?></a></span>
				&lrm;<span id="ccj-edit-slug-buttons"><button type="button" class="ccj-edit-slug button button-small hide-if-no-js" aria-label="Edit permalink">Edit</button></span>
				<span id="editable-post-name-full" data-filetype="<?php echo $filetype; ?>"><?php echo esc_html( $filename ); ?></span>
			</div>
			<?php wp_nonce_field( 'ccj-permalink', 'ccj-permalink-nonce' ); ?>
		</div>
		<?php
	}


	/**
	 * AJAX save the Permalink slug
	 */
	function wp_ajax_ccj_permalink() {

		if ( ! isset( $_POST['ccj_permalink_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ccj_permalink_nonce'], 'ccj-permalink' ) ) {
			return;
		}

		$code_id   = isset( $_POST['code_id'] ) ? intval( $_POST['code_id'] ) : 0;
		$permalink = isset( $_POST['permalink'] ) ? $_POST['permalink'] : null;
		$slug      = isset( $_POST['new_slug'] ) ? trim( sanitize_file_name( $_POST['new_slug'] ) ) : null;
		$filetype  = isset( $_POST['filetype'] ) ? $_POST['filetype'] : 'css';
		if ( empty( $slug ) ) {
			$slug = (string) $code_id;
		} else {
			update_post_meta( $code_id, '_slug', $slug );
		}
		$this->edit_form_before_permalink( $slug, $permalink, $filetype );

		wp_die();
	}


	/**
	 * Show contextual help for the Custom Code edit page
	 */
	public function contextual_help() {
		$screen = get_current_screen();

		if ( $screen->id != 'add_css_js' ) {
			return;
		}

		$screen->add_help_tab(
			array(
				'id'      => 'ccj-editor_shortcuts',
				'title'   => __( 'Editor Shortcuts', 'add_css_js-pro' ),
				'content' =>
							  '<p><table>
            <tr><td><strong>Auto Complete</strong></td><td> <code>Ctrl</code> + <code>Space</code></td></tr>
            <tr><td><strong>Find</strong></td><td> <code>Ctrl</code> + <code>F</code></td></tr>
            <tr><td><strong>Replace</strong></td><td> <code>Shift</code> + <code>Ctrl</code> + <code>F</code></td></tr>
            <tr><td><strong>Save</strong></td><td> <code>Ctrl</code> + <code>S</code></td></tr>
            <tr><td><strong>Comment line/block</strong></td><td> <code>Ctrl</code> + <code>/</code></td></tr>
            <tr><td><strong>Code folding</strong></td><td> <code>Ctrl</code> + <code>Q</code></td></tr>
            </table></p>',
			)
		);

	}


	/**
	 * Remove the JS/CSS file from the disk when deleting the post
	 */
	


	/**
	 * Fix for bug: white page Edit Custom Code for WordPress 5.0 with Classic Editor
	 */
	function current_screen_2() {
		$screen = get_current_screen();

		if ( $screen->post_type != 'add_css_js' ) {
			return false;
		}

		remove_filter( 'use_block_editor_for_post', array( 'Classic_Editor', 'choose_editor' ), 100, 2 );
		add_filter( 'use_block_editor_for_post', '__return_false', 100 );
		add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
	}

}
return new mainclasscssjs_Admin();

