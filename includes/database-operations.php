<?php

function createOrUpdateDatabase($island, $code)
{
    global $wpdb;     
    $table_name = $wpdb->prefix . 'dodo_island_manager';  

    $result = $wpdb->get_row ( "
    SELECT * 
    FROM  {$wpdb->prefix}dodo_island_manager
        WHERE island_name = '{$island}' " );

    if(strcmp($result->island_name,$island) == 0)
    {
        if(strcmp($result->dodo_code, $code) != 0)
        {
            $sql = "UPDATE {$table_name} SET dodo_code = '{$code}' WHERE dodo_code = '{$result->dodo_code}';";     
            $wpdb->query($sql); 
        }

        return;

    }

    $date = date ('Y-m-d H:i:s');   
    $sql = "INSERT INTO {$table_name} (time,island_name, dodo_code, protection) VALUES ('{$date}', '{$island}', '{$code}', 0);";     
    $wpdb->query($sql); 
}

?>