<?php

require_once '../../cmnvalidate.php';
$bydirect = true;
if ($bydirect) {
     if (isset($_REQUEST['title']) && isset($_REQUEST['subtitle'])) {
          $title = $obj->replaceUnwantedChars($_REQUEST['title'],1);
          $subtitle = $obj->replaceUnwantedChars($_REQUEST['subtitle'],1);
         
          $status = 1;
          if (isset($_REQUEST['status'])){
               $status = $_REQUEST['status'];
          }
          $query = "SELECT id FROM active_level where title='$title'";
          $query_result = $con->query($query);
          if ($query_result->num_rows == 0) {
               $query = "INSERT INTO active_level(title,subtitle,status) VALUES ('$title','$subtitle','$status')";
               $query_result = $con->query($query);
               $result['success'] = 1;
               $result['data'] = 'success';
               $result['error'] = 0;
               $result['error_code'] = NULL;
          } else {
               $result['success'] = 0;
               $result['data'] = NULL;
               $result['error'] = 1;
               $result['error_code'] = 'Activity Level Already Exists';
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
} else if (isset($_REQUEST['is_mobile_api'])) {
     echo $result;
}
?>