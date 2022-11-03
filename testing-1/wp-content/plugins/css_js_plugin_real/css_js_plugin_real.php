<?php
/**
*plugin Name:Real custom css js 
*plugin url :https://pixlerLab
*Author:Deep
*Author uri:https://pixlerLab
*version:0.0.1
*Description:Testing css and js for custom for the site Frountend and backend 
**/
defined('ABSPATH') || die('Nice Try');
register_activation_hook(__FILE__,'add_directory_plugin');
function add_directory_plugin(){
	echo 'sdtdfh';
}
if ( ! class_exists( 'mainclasscssjs' ) ) :
	/**
	 * Main mainclasscssjs Class
	 *
	 * @class mainclasscssjs
	 */
	final class mainclasscssjs {

		public $search_tree         = false;
		protected static $_instance = null;
		private $settings           = array();


		/**
		 * Main mainclasscssjs Instance
		 *
		 * Ensures only one instance of mainclasscssjs is loaded or can be loaded
		 *
		 * @static
		 * @return mainclasscssjs - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		
	
		
		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
		}

		/**
		 * mainclasscssjs Constructor
		 *
		 * @access public
		 */
		
		public function __construct() {

			include_once 'includes/cssjsadmin.php';
			
			add_action( 'init', array( 'css_js_addon', 'register_post_type' ) );

			$this->set_constants();

			if ( is_admin() ) {
				add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
				include_once 'includes/admin-css-js.php';
				
			}

			$this->search_tree = get_option( 'add_css_js-tree' );
			$this->settings    = get_option( 'ccj_settings' );
			if ( ! isset( $this->settings['remove_comments'] ) ) {
				$this->settings['remove_comments'] = false;
			}

			if ( ! $this->search_tree || count( $this->search_tree ) == 0 ) {
				return false;
			}
			if ( is_null( self::$_instance ) ) {
				$this->print_code_actions();
				if ( isset ( $this->search_tree['jquery'] ) && true === $this->search_tree['jquery'] ) {
					add_action( 'wp_enqueue_scripts', 'mainclasscssjs::wp_enqueue_scripts' );
				}
			}
		}

		/**
		 * Add the appropriate wp actions
		 */
		public function print_code_actions() {
			foreach ( $this->search_tree as $_key => $_value ) {
				$action = 'wp_';
				if ( strpos( $_key, 'admin' ) !== false ) {
					$action = 'admin_';
				}
				if ( strpos( $_key, 'login' ) !== false ) {
					$action = 'login_';
				}
				if ( strpos( $_key, 'header' ) !== false ) {
					$action .= 'head';
				} elseif ( strpos( $_key, 'body_open' ) !== false ) {
					$action .= 'body_open';
				} else {
					$action .= 'footer';
				}

				$priority = ( 'wp_footer' === $action ) ? 40 : 10;

				add_action( $action, array( $this, 'print_' . $_key ), $priority );
			}
		}

		/**
		 * Print the custom code.
		 */
		public function __call( $function, $args ) {

			if ( strpos( $function, 'print_' ) === false ) {
				return false;
			}

			$function = str_replace( 'print_', '', $function );

			if ( ! isset( $this->search_tree[ $function ] ) ) {
				return false;
			}

			$args = $this->search_tree[ $function ];

			if ( ! is_array( $args ) || count( $args ) == 0 ) {
				return false;
			}

			$where = strpos( $function, 'external' ) !== false ? 'external' : 'internal';
			$type  = strpos( $function, 'css' ) !== false ? 'css' : '';
			$type  = strpos( $function, 'js' ) !== false ? 'js' : $type;
			$type  = strpos( $function, 'html' ) !== false ? 'html' : $type;
			$tag   = array( 'css' => 'style', 'js' => 'script' );

			$type_attr = ( 'js' === $type && ! current_theme_supports( 'html5', 'script' ) ) ? ' type="text/javascript"' : '';
			$type_attr = ( 'css' === $type && ! current_theme_supports( 'html5', 'style' ) ) ? ' type="text/css"' : $type_attr;

			//$upload_url = str_replace( array( 'https://', 'http://' ), '//', CCJ_UPLOAD_URL ) . '/';

			if ( 'internal' === $where ) {

				$before = $this->settings['remove_comments'] ? '' : '<!-- start Simple Custom CSS and JS -->' . PHP_EOL;
				$after  = $this->settings['remove_comments'] ? '' : '<!-- end Simple Custom CSS and JS -->' . PHP_EOL;

				if ( 'css' === $type || 'js' === $type ) {
					$before .= '<' . $tag[ $type ] . ' ' . $type_attr . '>' . PHP_EOL;
					$after   = '</' . $tag[ $type ] . '>' . PHP_EOL . $after;
				}

			}

			foreach ( $args as $_filename ) {

				if ( 'internal' ===  $where && ( strstr( $_filename, 'css' ) || strstr( $_filename, 'js' ) ) ) {
					if ( $this->settings['remove_comments'] || empty( $type_attr ) ) {
						$custom_code = @file_get_contents( CCJ_UPLOAD_DIR . '/' . $_filename );
						if ( $this->settings['remove_comments'] ) {
								$custom_code = str_replace( array( 
										'<!-- start Simple Custom CSS and JS -->' . PHP_EOL, 
										'<!-- end Simple Custom CSS and JS -->' . PHP_EOL 
								), '', $custom_code );
						}
						if ( empty( $type_attr ) ) {
							$custom_code = str_replace( array( ' type="text/javascript"', ' type="text/css"' ), '', $custom_code );
						}
						echo $custom_code;
					} else {
						echo @file_get_contents( CCJ_UPLOAD_DIR . '/' . $_filename );
					}
				}

				if ( 'internal' === $where && ! strstr( $_filename, 'css' ) && ! strstr( $_filename, 'js' ) ) {
					$post = get_post( $_filename );
					echo $before . $post->post_content . $after;
				}

				if ( 'external' === $where && 'js' === $type ) {
					echo PHP_EOL . "<script{$type_attr} src='{$upload_url}{$_filename}'></script>" . PHP_EOL;
				}

				if ( 'external' === $where && 'css' === $type ) {
					$shortfilename = preg_replace( '@\.css\?v=.*$@', '', $_filename );
					echo PHP_EOL . "<link rel='stylesheet' id='{$shortfilename}-css' href='{$upload_url}{$_filename}'{$type_attr} media='all' />" . PHP_EOL;
				}

				if ( 'external' === $where && 'html' === $type ) {
					$_filename = str_replace( '.html', '', $_filename );
					$post      = get_post( $_filename );
					echo $post->post_content . PHP_EOL;
				}
			}
		}


		/**
		 * Enqueue the jQuery library, if necessary
		 */
		public static function wp_enqueue_scripts() {
			wp_enqueue_script( 'jquery' );
		}


		/**
		 * Set constants for later use
		 */
		public function set_constants() {
			$dir       = wp_upload_dir(); 
			$constants = array(
				'CCJ_UPLOAD_DIR'  => $dir['basedir'] . '/add_css_js',
				'CCJ_UPLOAD_URL'  => $dir['baseurl'] . '/add_css_js',
				'CCJ_PLUGIN_FILE' => __FILE__,
			);
			foreach ( $constants as $_key => $_value ) {
				if ( ! defined( $_key ) ) {
					define( $_key, $_value );
				}
			}
		}

		/**
		 * Loads a plugin’s translated strings.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'add_css_js', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}

endif;

if ( ! function_exists( 'mainclasscssjs' ) ) {
	/**
	 * Returns the main instance of mainclasscssjs
	 *
	 * @return mainclasscssjs
	 */
	function mainclasscssjs() {
		return mainclasscssjs::instance();
	}

	mainclasscssjs();
}


if ( ! function_exists( 'custom_css_js_quads_pro_compat' ) ) {
	/**
	 * Compatibility with the WP Quads Pro plugin,
	 * otherwise on a Custom Code save there is a
	 * "The link you followed has expired." page shown.
	 *
	 * @param array $post_types The Post types.
	 * @return array The Post types.
	 */
	function custom_css_js_quads_pro_compat( $post_types ) {
		$match = array_search( 'add_css_js', $post_types, true );
		if ( $match ) {
			unset( $post_types[ $match ] );
		}
		return $post_types;
	}
	add_filter( 'quads_meta_box_post_types', 'custom_css_js_quads_pro_compat', 20 );
}
	

