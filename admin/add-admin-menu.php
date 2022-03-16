<?php

/**
 * Register a custom menu admin page
 */
function register_my_custom_menu_page()
{

    add_menu_page(

        __('Dodo Codes Settings', 'textdomain'),
        'Dodo Manager',
        'manage_options',
        'dodo-island.php',
        'show_table',
        'dashicons-code-standards',
        85
    );
}
    add_action('admin_menu', 'register_my_custom_menu_page');

    function show_table()
    {

        ob_start();
        ?>
        
        <h1>Dodo Codes Manager Dashboard</h1>
        <h2 style="text-align:center;"><u>Dodo Codes Currently Live</u></h2>
        <table style="  margin-left: auto; margin-right: auto; margin-top: 40px; border-spacing: 20px" border="2">
        <tr>
        <th>ID</th>
        <th>Island Name</th>
        <th>Dodo Code</th>
        <th>Protection</th>
        <th>Modify</th>
        <th>Remove</th>
        </tr>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "dodo_island_manager";
        $result = $wpdb->get_results ( "SELECT * FROM {$table_name}" );
        foreach ( $result as $print ) {
        ?>
        <tr>
        <td><?php echo $print->id;?></td>
        <td><?php echo $print->island_name;?></td>
        <td><?php echo $print->dodo_code; ?></td>
        <td><?php echo $print->protection; ?></td>
        <td style="text-align:center" width='25%'><a href='admin.php?page=dodo-island.php&upt=<?php echo $print->id ?>'><button type='button'>UPDATE</button></a></td>
        <td style="text-align:center" width='25%'><a href='admin.php?page=dodo-island.php&del= <?php echo $print->id ?>'><button type='button'>DELETE</button></a></td>
        </tr>
        <?php
        }
        ?>
        </table>
        <br><br>

        <!-- Delete a Dodo Code -->
    
    <?php if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $wpdb->query("DELETE FROM $table_name WHERE id='$del_id'");
    echo "<script>location.replace('admin.php?page=dodo-island.php');</script>";
  } ?>

<!-- Update Function -->
<?php
  if (isset($_GET['upt'])) {
    $upt_id = $_GET['upt'];
    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id'");
    foreach($result as $print) {
      $island = $print->island_name;
      $dodo = $print->dodo_code;
      $protection = $print->protection;
    }
    echo "
    <center><h2><u>Update Dodo Code</u></h2></center>

    <table class='wp-list-table widefat striped'>
      <thead>
        <tr>
          <th width='25%'>ID</th>
          <th width='25%'>Island Name</th>
          <th width='25%'>Dodo Code</th>
          <th width='25%'>Protection</th>
        </tr>
      </thead>
      <tbody>
        <form action='' method='post'>
          <tr>
            <td width='25%'>$print->id <input type='hidden' id='uptid' name='uptid' value='$print->id'></td>
            <td width='25%'><input type='text' id='uptisland' name='uptisland' value='$island'></td>
            <td width='25%'><input type='text' id='uptdodocode' name='uptdodocode' value='$dodo'></td>
            <td width='25%'><input type='text' id='uptprotection' name='uptprotection' value='$protection'></td>
            <td width='25%'><button id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=dodo-island.php'><button type='button'>CANCEL</button></a></td>
          </tr>
        </form>
      </tbody>
    </table>
    
    ";
  }

  if (isset($_POST['uptsubmit'])) {
    $id_s = $_POST['uptid'];
    $island_s = $_POST['uptisland'];
    $dodo_s = $_POST['uptdodocode'];
    $protection_s = $_POST['uptprotection'];
    $wpdb->query("UPDATE $table_name SET id='$id_s',island_name='$island_s', dodo_code='$dodo_s', protection='$protection_s' WHERE id='$id_s'");
    
    echo "<script>location.replace('admin.php?page=dodo-island.php');</script>";
  }

?>



        <!-- Create a new Dodo Code -->
        <h2 style="text-align:center;"><u>Create a new dodo code </u></h2>
        <table style="margin-left: auto; margin-right: auto;">
        <thead>
        <tr>
          <th width='25%'>Island Name</th>
          <th width='25%'>Dodo Code</th>
          <th width='25%'>Protection</th>
        </tr>
      </thead>
      <tbody>
        <form style="margin-left: auto; margin-right: auto;" action="" method="post">
    <tr>
    <td><input type="text"  id="island_name" name="island_name"></td>
    <td><input type="text"  id="dodo_code" name="dodo_code"></td>
    <td><input type="text"  id="protection" name="protection"></td>
    <td><button id="newsubmit" name="newsubmit" type="submit">INSERT</button></td>
  </tr>
</form>
    </tbody>
    </table>

<?php
if (isset($_POST['newsubmit'])) {
  $island = $_POST['island_name'];
  $dodo = $_POST['dodo_code'];
  $protection = $_POST['protection'];

  $wpdb->query("INSERT INTO $table_name(island_name,dodo_code, protection) VALUES('$island','$dodo', '$protection')");
  
  echo "<script>location.replace('admin.php?page=dodo-island.php');</script>";
}

?>

    <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;

    }

?>