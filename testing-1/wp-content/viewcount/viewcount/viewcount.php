<?php
/* Plugin name: View Count
*plugin url:https://www.pixlerLab.com
*Author :Deep
*Author uri:https://www.pixlerlab.com
*Description:Check Viewer in site
*/
defined('ABSPATH') ||die('Nice Try');


//-----------------------------------------------------------------------------//


// add menu and submenu in plugin adminmenu
add_action('admin_menu', 'view_count_admin_1');
 
function view_count_admin_1(){
    add_menu_page( 'Viewer Count', 'Viewer Count', 'manage_options', 'viewer-count','view_count_admin_2','dashicons-admin-users',100);// main menu
    add_submenu_page('viewer-count','Viewer Count 2','Viewer Count 2','manage_options','viewer-count-2','viewer_count_2');// submenu in the plugin
}  
//main menu function 
function view_count_admin_2(){
    include('includes/count.php');
}
// submenu function
function viewer_count_2(){
    include('includes/count2.php');
}


//-------------------------------------------------------------------------------//


//plugin activation and deactivatio table create and delete 
 register_activation_hook( __FILE__, 'viewr_create_table' );
 function viewr_create_table()
 {      
   global $wpdb; 
   $db_table_name = $wpdb->prefix . 'viewer_count';  
   $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $db_table_name (
                 `pageid` int(11) NOT NULL,
                `pagetitle` varchar(255) NOT NULL,
                `viewer` bigint(20) NOT NULL,
                `count` int(11) NOT NULL
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    add_option( 'test_db_version', $test_db_version );
 } 
 // table delate in database for plugin deactivation
 register_deactivation_hook( __FILE__, 'viewr_delete_table' );
 function viewr_delete_table(){
    global $wpdb; 
    $db_table_name = $wpdb->prefix . 'viewer_count'; 
    $sql = "drop table $db_table_name"; 
    $wpdb->query($sql);
 }

 //-------------------------------------------------------------------------------//

 
// add css and js in plugin 
 add_action( 'admin_enqueue_scripts', 'ci_add_cssjs_styles' );
 function ci_add_cssjs_styles() {
   wp_register_style( 'ci-comment-custom-styles', plugins_url( '/', __FILE__ ) . 'assets/style.css' );
   wp_enqueue_style( 'dashicons' );
   wp_enqueue_style( 'ci-comment-custom-styles' );
 }
 add_action('wp_enqueue_scripts','plugin_scripts');
 function plugin_scripts(){
   wp_enqueue_script('mypluginscript',plugins_url( '/',__FILE__).'assets/app.js',array('jquery'),false,false);
 }
 // get data throw api  in plugin
// $url='http://localhost/wordpress_new/';

//   function wp_remote_get( $url,register_post_type( $post_type:string,[
//     'label' =>
//     'public' =>
//     'capability' =>
//   ] 
//     )
// ) 
// {
//  	$http = _wp_http_get_object();
//  	return $http->get( $url, $args );
//  }

