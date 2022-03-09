<?php

function dotiavatar_function() {
    return ' <h1>Hello this is a shortcode avatar</h1> <br> <img src="http://dayoftheindie.com/wp-content/uploads/avatar-simple.png" 
   alt="doti-avatar" width="96" height="96" class="left-align" />';
}

add_shortcode('dotiavatar', 'dotiavatar_function');


?>