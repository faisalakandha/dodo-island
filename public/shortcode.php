<?php

function dotiavatar_function() { 
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

add_shortcode('dotiavatar', 'dotiavatar_function');


?>