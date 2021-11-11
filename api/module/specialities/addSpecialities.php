<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
if ($bydirect) {
     if (isset($_REQUEST['name'])) {
          $name = $obj->replaceUnwantedChars($_REQUEST['name'],1);
          $description = "";
          if(isset($_REQUEST['description']) && $_REQUEST['description'] != ""){
               $description = $obj->replaceUnwantedChars($_REQUEST['description'],1);
          }
          $status = 1;
          if (isset($_REQUEST['status'])){
               $status = $_REQUEST['status'];
          }
          $query = "SELECT id FROM specialities where name='$name'";
          $query_result = $con->query($query);
          if ($query_result->num_rows == 0) {
               $query = "INSERT INTO specialities(name,description,status) VALUES ('$name','$description','$status')";
               $query_result = $con->query($query);
               $result['success'] = 1;
               $result['data'] = 'success';
               $result['error'] = 0;
               $result['error_code'] = NULL;
          } else {
               $result['success'] = 0;
               $result['data'] = NULL;
               $result['error'] = 1;
               $result['error_code'] = 'Amenities Already Exists';
          }
     } else {
          $result['success'] = 0;
          $result['data'] = NULL;
          $result['error'] = 1;
          $result['error_code'] = 'Required Parameter Are Missing';
     }
}
$result = json_encode($result);
if (isset($_SESSION['user'])) {
     echo $result;
}
?>