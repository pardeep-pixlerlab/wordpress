<?php
/**
*plugin Name:testing plugin
*plugin url :https://www.pixlerLab.com
*Author:Deep
*Author uri:https://www.pixlerLab.com
*version:0.0.1
*Description:Testing css and js for custom for the site Frountend and backend 
**/
defined('ABSPATH') || die('Nice Try');
add_action('admin_menu', 'test_plugin_setup_menu');
 
function test_plugin_setup_menu(){
    add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', 'test_init' );
}
 
function test_init(){
    echo "<h1>Hello World!</h1>";
}