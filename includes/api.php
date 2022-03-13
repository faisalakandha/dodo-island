<?php

//require_once(plugin_dir_path(__FILE__) . 'database-manager.php');

add_action('rest_api_init', function () {
    register_rest_route( 'dodo/v1', 'codes', array(
        'methods' => 'POST',
        'callback' => 'create_dodo_codes_from_data'
    ));
});
function create_dodo_codes_from_data($req) {
    $response['island'] = $req['island'];
    $response['code'] = $req['code'];
    $response['protection'] = $req['protection'];

    $res = new WP_REST_Response($response);

    if(is_wp_error($res))
    {
        $error_message = $response->get_error_message();
        echo 'Something went wrong ' + $error_message;
        $res->set_status(500);
    }

    else 
    {
        //createOrUpdateDatabase($response);
        $res->set_status(200);
        return ['req' => $res];
    }

    return;

}

?>