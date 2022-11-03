<?php

// delete data  data in database  
 if (isset($_GET['delete'])) {
    global $wpdb; 
     $db_table_name = $wpdb->prefix . 'viewer_count';
    $delete = $_GET['delete'];
    $wpdb->query("DELETE FROM $db_table_name WHERE id = $delete");// delete query 
    echo "<script>location.replace('admin.php?page=viewer-count');</script>";
    
  }
 // edit data in database
  if (isset($_POST['data_update_in_database'])) {
    $edit = $_GET['edit'];
    $pagetitle = $_POST['pagetitle'];
    $page_count = $_POST['page_count'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $ipaddress = $_POST['ipaddress'];
    global $wpdb; 
    $db_table_name = $wpdb->prefix . 'viewer_count';
    $wpdb->query("UPDATE  $db_table_name set pagetitle='$pagetitle',viewer='$page_count',country='$country',city='$city',zip='$zip',ipaddress='$ipaddress'  WHERE id = $edit");
    echo "<script>location.replace('admin.php?page=viewer-count');</script>";
   
  }
?>
<html>
<head>
<body>
    <table class="table text-center mt-3">
        <tr>
            <th>USER ID</th>
            <th>PAGE TITLE</th>
            <th>TOTAL_PAGES_VIEW</th>
            <th>COUNTRY</th>
            <th>CITY</th>
            <th>ZIP</th>
            <th>IP ADRESS</th>
            <th>ACTION</th>
        </tr>
        <?php
        // select all data into database
        global $wpdb; 
        $db_table_name = $wpdb->prefix . 'viewer_count';
        $result = $wpdb->get_results("SELECT * FROM $db_table_name");
        if($result){
        foreach ($result as $print) {
     echo"<tr>
                <td>$print->id</td>
                <td>$print->pagetitle</td>
                <td>$print->viewer</td>
                <td>$print->country</td>
                <td>$print->city</td>
                <td>$print->zip</td>
                <td>$print->ipaddress</td>
                <td><a href='admin.php?page=viewer-count&edit=$print->id'><button type='button' class='btn btn-success showdiv'>Edit</button></a><a href='admin.php?page=viewer-count&delete=$print->id'><button type='button' class='btn btn-success ml-3' >Delete</button></a></td>
            </tr>";
        }}else{
            echo "<tr class='text-center text-success '>
                    No Recoured Found
                    </tr>";
        }?>
    </table>
    <div id="model">
        <div id="model-edit"  class="form-group">
        <div id="close">X</div>
            <form method='post'>
                <?php
                 $edit = $_GET['edit'];
                 global $wpdb; 
                 $db_table_name = $wpdb->prefix . 'viewer_count';
                 $result = $wpdb->get_results("SELECT * FROM $db_table_name WHERE id = $edit");
                 foreach ($result as $print) {
                echo "
                <div class='form-group row'>
                <label for='pageid' class='col-sm-3 col-form-label'>USER ID</label>
                <div class='col-sm-9'>
                  <input type='text' value='$print->id' name='pageid'  disabled class='form-control' >
                  <input type='text' value='$print->id' name='stid' hidden class='form-control' >
                </div>
              </div>
              <div class='form-group row'>
                <label for='pagetitle' class='col-sm-3 col-form-label'>PAGE TITLE</label>
                <div class='col-sm-9'>
                  <input type='text' class='form-control' name='pagetitle' value='$print->pagetitle'  >
                </div>
              </div>
              <div class='form-group row'>
                <label for='viewer' class='col-sm-3 col-form-label'>PAGES_VIEW</label>
                <div class='col-sm-9'>
                  <input type='text' class='form-control' name='page_count' value='$print->viewer'>
                </div>
              </div>
              <div class='form-group row'>
                <label for='country' class='col-sm-3  ' col-form-label'>COUNTRY</label>
                <div class='col-sm-9'>
                  <input type='' class='form-control' name='country'  value='$print->country'   s>
                </div>
              </div>
              <div class='form-group row'>
                <label for='city' class='col-sm-3 col-form-label'>CITY</label>
                <div class='col-sm-9'>
                  <input type='text' class='form-control'  name='city' value='$print->city'  s>
                </div>
              </div>
              <div class='form-group row'>
                <label for='zip' class='col-sm-3 col-form-label'>ZIP</label>
                <div class='col-sm-9'>
                  <input type='text' class='form-control'  name='zip'  value='$print->zip'   disabled s>
                </div>
              </div>
              <div class='form-group row'>
                <label for='ipaddress' class='col-sm-3 col-form-label'>IP ADDRESS</label>
                <div class='col-sm-9'>
                  <input type='text' class='form-control' name='ipaddress'  value='$print->ipaddress'  disabled>
                </div>
              </div>
                    <button type='submit' class='btn btn-success' name='data_update_in_database' style='margin-left:145px;'>UPDATE</button>";
                 }?>
            </form>
        </div>
    </div>
</body>
</head>
</html>
