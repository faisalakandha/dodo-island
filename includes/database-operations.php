<?php

function createOrUpdateDatabase($island, $code)
{
    global $wpdb;     
    $table_name = $wpdb->prefix . 'dodo_island_manager';  
    $date = date ('Y-m-d H:i:s');   
    $sql = "INSERT INTO {$table_name} (time,island_name, dodo_code, protection) VALUES ('{$date}', '{$island}', '{$code}', 0);";     
    $wpdb->query($sql); 
}

?>