<?php

add_action('rest_api_init', function () {
    register_rest_route( 'dodo/v1', 'codes', array(
        'methods' => 'POST',
        'callback' => 'create_dodo_codes_from_data'
    ));
});
function create_dodo_codes_from_data($req) {
    $response['island'] = $req['name'];
    $response['code'] = $req['population'];

    $res = new WP_REST_Response($response);
    $res->set_status(200);

    return ['req' => $res];
}

?>