<?php
require_once(plugin_dir_path(__FILE__) . '../includes/twitch/config.php');
require_once(plugin_dir_path(__FILE__) . '../includes/twitch/twitch-api.php');


function dodocodes_function() { 

    global $twitchLogin;
    if ( isset( $_GET['code'] ) && isset( $_GET['state'] ) && $_GET['state'] == $_SESSION['twitch_state' ]  ) 
    { // user is coming from twitch
		// instantiate new twitch class
		$eciTwitchApi = new eciTwitchApi( TWITCH_CLIENT_ID, TWITCH_CLIENT_SECRET );

        // try and log the user in with twitch
		$twitchLogin = $eciTwitchApi->tryAndLoginWithTwitch( $_GET['code'], TWITCH_REDIRECT_URI );
       $subscriber = $twitchLogin['sub_status'];
       //print_r($twitchLogin);
    }
    
    if ( isset( $twitchLogin['status'] ) && 'fail' != $twitchLogin['status'] ) {

    ob_start();
    ?>
    <center>
    <h3>DazzlingDuckGames Dodo Codes</h3>
    <hr>
    <a href="<?php echo LOGOUT_PAGE ?>"><button style="background-color: turquoise; border: none; border-radius: 5px; color: #333; /* Dark grey */ padding: 15px 32px">Logout</button></a>
    <hr> 
    <p>You are logged in as <?php echo $twitchLogin['username'] ?></p>
    <hr> 
    <p> Please check the dodo codes listed below. Do not share it with anyone else! </p>
    <hr> 
    <?php if($subscriber == 404) { ?>
    <p>You are NOT subscribed to DazzlingDuckGames</p>
    <?php } else if ($subscriber == 200 ) { ?>
    <p>You are subscribed to DazzlingDuckGames</p>
    <?php } else { ?>
        <p>Invalid User Subscription !!!!!!</p>
    <?php } ?>
    <?php if ($subscriber == 404) { ?>
    <hr> 
    <p>Want to see all the codes?</p>
    <br>
    <p><a href="<?php echo TWITCH_SUBSCRIPTION_PAGE ?>">Click Here To Subscribe To DazzlingDuckGames</a> </p>
    <?php } ?>
    <br>
    <table style="width:90%;" border="1">
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
    <td><?php echo $print->dodo_code; } else if($print->protection > 0) { ?></td>
</tr>
<tr>
    <td><?php echo $print->island_name; ?></td> 
    <?php if($subscriber == 404) { ?>
    <td><a href="<?php echo TWITCH_SUBSCRIPTION_PAGE ?>">Subscribe to Reveal</a></td> <?php } else if ($subscriber == 200){?>
        <td><?php echo $print->dodo_code; } } ?></td>
    </tr>
    
        <?php
    }
    ?>

    </table>
    </center>
<?php
$content = ob_get_contents();
ob_end_clean();
return $content;
    
}
else 
{
    echo "<script>location.replace('". constant("LOGOUT_PAGE") ."');</script>";
}

}



add_shortcode('dodocodes', 'dodocodes_function');

?>