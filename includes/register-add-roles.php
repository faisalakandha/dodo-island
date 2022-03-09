<?php


function dodo_add_user_role()
{
    add_role(
        'dodo_user',
        'Dodo User',
        array(
            'read' => true,
            'upload_files' => true,
            'edit_published_posts' => true,
            'delete_published_posts' => true,
            'publish_posts' => true,
            'edit_posts' => true,
            'delete_posts' => true
        )
);

}


function dodo_user_deregister_role()
{

remove_role('dodo_user');

}

//Plugin Activation Hook
register_activation_hook(__FILE__, 'dodo_add_user_role');


// Plugin Deactivation Action
register_deactivation_hook(__FILE__, 'dodo_user_deregister_role');


?>