<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
if ($bydirect) {
     if (isset($_REQUEST['id']) && isset($_REQUEST['title']) && isset($_REQUEST['subtitle'])) {
        $id = $_REQUEST['id'];
        $title = $_REQUEST['title']; 
        // Get user detail
        $query = "SELECT id FROM active_level where id!=$id and title='$title'";
        $query_result = $con->query($query);
        if($query_result->num_rows == 0){
                $validation_flag = 0;
                $validation_error_code = NULL;
                $updatevalues = "";
                if(isset($_REQUEST['title']) && $_REQUEST['title'] != ""){
                     $updatevalues .= "title='".$_REQUEST['title']."' ,";
                }
                if(isset($_REQUEST['subtitle']) && $_REQUEST['subtitle'] != ""){
                     $updatevalues .= "subtitle='".$_REQUEST['subtitle']."' ,";
                }
                if(isset($_REQUEST['status']) && $_REQUEST['status'] != ""){
                     $updatevalues .= "status='".$_REQUEST['status']."' ,";
                }
                $updatevalues = rtrim($updatevalues,",");
                if ($validation_flag == 0) {
                    $query = "UPDATE `active_level` SET $updatevalues WHERE id = '$id' ";
                    $query_result = $con->query($query);
                    $result['success'] = 1;
                    $result['data'] = 'success';
                    $result['error'] = 0;
                    $result['error_code'] = NULL;
                } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = $validation_error_code;
                }
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
if(isset($_SESSION['user'])){
        echo $result;
}else if(isset($_REQUEST['is_mobile_api'])){
        echo $result;
}
?>