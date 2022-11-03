 add_action( 'admin_enqueue_scripts', 'ci_add_cssjs_styles' );
 function ci_add_cssjs_styles() {
   wp_register_style( 'ci-comment-custom-styles', plugins_url( '/', __FILE__ ) . 'assets/style.css' );
   wp_enqueue_style( 'dashicons' );
   wp_enqueue_style( 'ci-comment-custom-styles' );
 }
 add_action('admin_enqueue_scripts','plugin_scripts');
 function plugin_scripts(){
   wp_enqueue_script('ci-comment-custom-scripts', plugins_url( '/', __FILE__ ) . 'assets/app.js' ,array('jquery'),false,true);
 
 }