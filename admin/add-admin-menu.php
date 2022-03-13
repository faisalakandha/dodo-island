<?php

/**
 * Register a custom menu admin page
 */
function register_my_custom_menu_page()
{

    add_menu_page(

        __('Dodo Codes Settings', 'textdomain'),
        'Dodo Manager',
        'manage_options',
        'dodo-island.php',
        'get_send_data',
        'dashicons-code-standards',
        85
    );
}
    add_action('admin_menu', 'register_my_custom_menu_page');

    function get_send_data()
    {

        global $wpdb;

        $result = $wpdb->get_results ( "
            SELECT * 
            FROM  {$wpdb->prefix}dodo_island_manager
                WHERE island_name = 'James'
        " );
        
        foreach ( $result as $page )
        {
           echo $page->island_name.'<br/>';
        }
        echo '<h1>Dodo Codes Admin</h1>';

    }

?>