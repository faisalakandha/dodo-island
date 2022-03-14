<?php
/**
* Plugin Name: Dodo Island
* Plugin URI: https://dazzlingduckgames.com
* Description: A plugin for treasure island game
* Version: 1.0.0
* Author: H.A.B.M. Faisal Akandha
* Author URI: https://dazzlingduckgames.com
* License: GPL2
**/

require_once(plugin_dir_path(__FILE__) . 'public\shortcode.php');
require_once(plugin_dir_path(__FILE__) . 'includes\register-add-roles.php');
require_once(plugin_dir_path(__FILE__) . 'admin\add-admin-menu.php');
require_once(plugin_dir_path(__FILE__) . 'includes\database-manager.php');
require_once(plugin_dir_path(__FILE__) . 'includes\api.php');
require_once(plugin_dir_path(__FILE__) . 'init.php');


defined('ABSPATH') or die('Unauthorized Access');

/**
 * Register a custom post type called "Dodo".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_Dodo_init() {
    $labels = array(
        'name'                  => _x( 'Dodos', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Dodo', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Dodos', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Dodo', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Dodo', 'textdomain' ),
        'new_item'              => __( 'New Dodo', 'textdomain' ),
        'edit_item'             => __( 'Edit Dodo', 'textdomain' ),
        'view_item'             => __( 'View Dodo', 'textdomain' ),
        'all_items'             => __( 'All Dodos', 'textdomain' ),
        'search_items'          => __( 'Search Dodos', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Dodos:', 'textdomain' ),
        'not_found'             => __( 'No Dodos found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No Dodos found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Dodo Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Dodo archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into Dodo', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Dodo', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter Dodos list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Dodos list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Dodos list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'Dodo' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );
 
    register_post_type( 'Dodo', $args );
}
 
add_action( 'init', 'wpdocs_codex_Dodo_init' );


// Initial Activation 
activation_initial_functions();

//Plugin Activation Hook
register_activation_hook(__FILE__, 'dodo_add_user_role');


// Plugin Deactivation Action
register_deactivation_hook(__FILE__, 'dodo_user_deregister_role');

?>