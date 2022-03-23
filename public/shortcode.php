<?php
require_once(plugin_dir_path(__FILE__) . 'includes/twitch/config.php');
require_once(plugin_dir_path(__FILE__) . 'includes/twitch/twitch-api.php');


function dotiavatar_function() { 

    $twitchLogin;
    if ( isset( $_GET['code'] ) && isset( $_GET['state'] ) && $_GET['state'] == $_SESSION['twitch_state' ]  ) { // user is coming from twitch
		// instantiate new twitch class
		$eciTwitchApi = new eciTwitchApi( TWITCH_CLIENT_ID, TWITCH_CLIENT_SECRET );

        // try and log the user in with twitch
		$twitchLogin = $eciTwitchApi->tryAndLoginWithTwitch( $_GET['code'], TWITCH_REDIRECT_URI );
  //  }
    
   // if ( isset( $twitchLogin['status'] ) && 'fail' == $twitchLogin['status'] ) {
    //    echo $twitchLogin['message']; 
    
    ob_start();
    ?>
    <table border="1">
    <tr>
    <th>Island Name</th>
    <th>Dodo Code</th>
    </tr>
    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . "dodo_island_manager";
    $result = $wpdb->get_results ( "SELECT * FROM {$table_name}" );
    foreach ( $result as $print ) {
    ?>
    <tr>
    <?php if($print->protection == 0) { ?>    
    <td><?php echo $print->island_name;?></td>
    <td><?php echo $print->dodo_code; } else {?></td>
    <td><?php echo $print->island_name;?></td>
    <td><a href="">Click to subscribe and show the dodo code</a> <?php } ?></td>
    </tr>
    <?php
    }
    ?>
    </table>
<?php
$content = ob_get_contents();
ob_end_clean();
return $content;
    }

else 
{
    echo "<script>location.replace('http://localhost/sample-page/');</script>";

}

}



add_shortcode('dotiavatar', 'dotiavatar_function');

?>