<?php
defined('ABSPATH') ||die('Not Access');
class css_js_addon {
    public static function register_post_type() {
        $menu = array(
            'name'          => 'Custom Code', 'post type general name', 'add-css-js',
            'singular_name' => 'Custom Code', 'post type singular name', 'add-css-js',
            'menu_name'     => 'All Css & js', 'admin menu', 'add-css-js',
            'name_admin_bar'=> 'Custom for css & js', 'add new on admin bar', 'add-css-js',
            'add_new'           => 'Add Custom Code', 'add new', 'add-css-js',
            'edit_item'         =>  'Edit Custom Code', 'add-css-js',
            'view_item'         =>  'View Custom Code', 'add-css-js',
            'all_items'         =>  'All Custom Code', 'add-css-js',
            'search_items'      =>  'Search Custom Code', 'add-css-js',
        );

        $manageoptions = 'custom_css';
        $bilities = array(
            'edit_post'              => "edit_{$manageoptions}",
            'read_post'              => "read_{$manageoptions}",
            'delete_post'            => "delete_{$manageoptions}",
            'edit_others_posts'      => "edit_others_{$manageoptions}",
            'read'                   => "read",
            'delete_posts'           => "delete_{$manageoptions}",
            'delete_published_posts' => "delete_published_{$manageoptions}",
            'delete_others_posts'    => "delete_others_{$manageoptions}",
            'edit_published_posts'   => "edit_published_{$manageoptions}",
            'create_posts'           => "edit_{$manageoptions}",
        );

        $args = array(
            'labels'                 => $menu,
            'description'            => 'Custom CSS and JS code', 'add_css_js',
            'show_ui'                => true,
            'show_in_menu'           => true,
            'menu_icon'              => 'dashicons-admin-plugins',
            'rewrite'                => array( 'slug' => 'add_css_js' ),
            'capability_type'        => $manageoptions,
            'capabilities'           => $bilities, 
            'exclude_from_search'    => true,
            'menu_position'          => 100,
            'supports'               => array( 'title' )
        );
        register_post_type( 'add_css_js', $args );
    }
}
add_action('admin_init','testccs');
function testccs(){
	add_submenu_page('add-css-js','test css','test css','manage-options','add-css','testyourcss',$icon_url='',100);
}
?>
