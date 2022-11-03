<?php
/**
*plugin Name:Viewer Count
*plugin Uri:https://pixerlab.com
*Author:By Deep
*Author Uri: https://pixerlab.com
*Description:Check User Activate In Your Site And Most User Used Your Site
*version:0.0.1
*/
defined('ABSPATH') ||die('Nice Try');//protect your site for direct access
if(! class_exists('user_information')){
    class user_information{
        function __construct(){
          add_action('admin_menu', 'view_count_admin_1');
            
          function view_count_admin_1(){
              add_menu_page( 'Viewer Count', 'Viewer Count', 'manage_options', 'viewer-count','view_count_admin_2','dashicons-admin-users',100);// main menu
          }  
          //main menu function 
          function view_count_admin_2(){
              include('includes/table_template.php');
          }
        }
        function activation_deactivatio(){
         //plugin activation and deactivatio table create and delete 
            register_activation_hook( __FILE__, 'viewr_create_table' ); // create table in database  prefix viewer_count
            function viewr_create_table()
            {      
            global $wpdb; 
            $db_table_name = $wpdb->prefix . 'viewer_count';  // add prefixed and create viewer_count
            $charset_collate = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE " . $db_table_name . " (
                    id int(11) NOT NULL auto_increment,
                        `pagetitle` varchar(255) NOT NULL,
                        `viewer` bigint(20) NOT NULL,
                        `country` varchar(200) NOT NULL,
                            `city` varchar(200) NOT NULL,
                            `zip` bigint(20) NOT NULL,
                            `ipaddress` varchar(200) NOT NULL,
                            PRIMARY KEY (id)
                    ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql);
            add_option( 'test_db_version', $test_db_version );
            } 
            // table delate in database for plugin deactivation
            register_deactivation_hook( __FILE__, 'viewr_delete_table' ); // drop table in databse 
            function viewr_delete_table(){
                global $wpdb; 
                $db_table_name = $wpdb->prefix . 'viewer_count'; 
                $sql = "drop table $db_table_name"; 
                $wpdb->query($sql);
            }
        }
        function enqueue_script_plugin(){
            // add css and js in plugin 
            add_action( 'admin_enqueue_scripts', 'ci_add_cssjs_styles' );
            function ci_add_cssjs_styles() {
            wp_register_style( 'ci-comment-custom-styles', plugins_url( '/', __FILE__ ) . 'assets/style.css' );
            wp_register_style( 'ci-comment-custom-bootstrap', plugins_url( '/', __FILE__ ) . 'assets/bootstrap.min.css' );
            wp_enqueue_style( 'dashicons' );
            wp_enqueue_style( 'ci-comment-custom-styles' );
            wp_enqueue_style( 'ci-comment-custom-bootstrap' );// bootstarp add in plugin 
            }
            // add script file in plugin 
            add_action('admin_enqueue_scripts','plugin_scripts');
            function plugin_scripts(){
            wp_enqueue_script('ci-comment-custom-scripts', plugins_url( '/', __FILE__ ) . 'assets/app.js' ,array('jquery'),false,true);
            wp_enqueue_script('ci-comment-custom-slim', plugins_url( '/', __FILE__ ) . 'assets/slim.min.js' ,false,true);// bootstarp jquery add in plugin
            wp_enqueue_script('ci-comment-custom-popper', plugins_url( '/', __FILE__ ) . 'assets/popper.min.js' ,false,true);// bootstarp jquery add in plugin
            wp_enqueue_script('ci-comment-custom-bootstrap', plugins_url( '/', __FILE__ ) . 'assets/bootstrap.min.js' ,false,true);// bootstarp jquery add in plugin

            }
        }
        function insert_data_on_load(){
            // Get IP Details from user 
            loadIp();// load function on activation plugin hit
            function loadIp(){ 
            $date = basename($_SERVER['PHP_SELF']);// return path of file in plugin
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,"http://ip-api.com/json");
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $result=curl_exec($ch);
            $result = json_decode($result);
            // insert data into table onload  and update data in database
            if($result->status == 'success' ){  
            global $wpdb; 
            $db_table_name = $wpdb->prefix . 'viewer_count';
            $wpdb->query("INSERT INTO  $db_table_name(pagetitle,country,city,zip,ipaddress)VALUES('$date','$result->country','$result->city','$result->zip','$result->query')");
            // update for count in databse
            $wpdb->query("UPDATE  $db_table_name set viewer=viewer+1");// update query
            }
            }
        }
    }
}
$user_information = new user_information;