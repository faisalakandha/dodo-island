<?php require_once(plugin_dir_path(__FILE__) . 'includes/twitch/twitch-api.php'); 
require_once(plugin_dir_path(__FILE__) . 'includes/twitch/config.php');


function twitch_dodo () { 

$eciTwitchApi = new eciTwitchApi(TWITCH_CLIENT_ID, TWITCH_CLIENT_SECRET);
$twitchLoginUrl = $eciTwitchApi->getLoginUrl(TWITCH_REDIRECT_URI);

    ob_start();
    ?>
<head>
    <style>
        .a-twitch {
	text-decoration: none;
}

.twitch-button-container {
	padding: 10px;
	color: #fff;
	border-radius: 5px;
	background: #9147ff;
}

.twitch-button-container:hover {
	background: #772ce8;
}

.section-action-container {
	margin-top: 20px;
	text-align: center;
    width: 20%;
}
    </style>
</head>

<body>

<div class="section-action-container">
							<a href="<?php echo $twitchLoginUrl; ?>" class="a-twitch">
								<div class="twitch-button-container">
									Login with Twitch
								</div>
							</a>
						</div>

</body>

<?php
$content = ob_get_contents();
ob_end_clean();
return $content;

}

add_shortcode('twitch_dodo', 'twitch_dodo');

?>