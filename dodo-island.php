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




// Initial Activation 
activation_initial_functions();

//Plugin Activation Hook
register_activation_hook(__FILE__, 'dodo_add_user_role');


// Plugin Deactivation Action
register_deactivation_hook(__FILE__, 'dodo_user_deregister_role');

?>