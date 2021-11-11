<?php

require_once '../../cmnvalidate.php';
$bydirect = true;
if ($bydirect) {
     if (isset($_REQUEST['goalname'])) {
          $goalname = $obj->replaceUnwantedChars($_REQUEST['goalname'],1);
          $description = $obj->replaceUnwantedChars($_REQUEST['description'],1);
         
          $status = 1;
          if (isset($_REQUEST['status'])){
               $status = $_REQUEST['status'];
          }
          $query = "SELECT id FROM goals where goalname='$goalname'";
          $query_result = $con->query($query);
          if ($query_result->num_rows == 0) {
               $query = "INSERT INTO goals(goalname,description,status) VALUES ('$goalname','$description','$status')";
               $query_result = $con->query($query);
               $result['success'] = 1;
               $result['data'] = 'success';
               $result['error'] = 0;
               $result['error_code'] = NULL;
          } else {
               $result['success'] = 0;
               $result['data'] = NULL;
               $result['error'] = 1;
               $result['error_code'] = 'Goal Already Exists';
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