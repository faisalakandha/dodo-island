<?php

require_once(plugin_dir_path(__FILE__) . '/includes/database-operations.php');

add_action('rest_api_init', function () {

    register_rest_route( 'dodo/v1', 'codes', array(
        'methods' => 'POST',
        'callback' => 'create_dodo_codes_from_data',
        'permission_callback' => '__return_true'
    ));
});

function create_dodo_codes_from_data($req) {
   
    $parameters = $req->get_params();

    $island = $parameters['island'];
    $code = $parameters['code'];
    $username = $parameters['username'];
    $password = $parameters['password'];

    if(isset($island) && isset($code))
    {
        $userdata = get_user_by('login', $username);
        $passdata = wp_check_password($password, $userdata->user_pass, $userdata->ID);

        if($userdata && $passdata)
        {
        createOrUpdateDatabase($island, $code);

        return new WP_REST_Response(
            array(
              'status' => 200,
              'response' => "Successfully Posted",
              'body_response' => $island . ' '. $code
            )
          );
        }
        
        else 
        {
            return new WP_REST_Response(
                array(
                  'status' => 401,
                  'response' => "No Authentication",
                  'body_response' => "You don't have permission here !"
                )
              );
        }
    }

    else 
    {
        return new WP_REST_Response(
            array(
              'status' => 500,
              'response' => "Warning Error !",
              'body_response' => NULL
            )
          );
        
    }
  
}

?>