<?php
require_once '../../cmnvalidate.php';
$bydirect = true;

if ($bydirect) {
     if (isset($_REQUEST['id']) && isset($_REQUEST['goalname'])) {
        $id = $_REQUEST['id'];
        $goalname = $obj->replaceUnwantedChars($_REQUEST['goalname'],1);
        // Get user detail
        $query = "SELECT id FROM goals where id!=$id and goalname='$goalname'";
        $query_result = $con->query($query);
        if($query_result->num_rows == 0){
                $validation_flag = 0;
                $validation_error_code = NULL;
                $updatevalues = "";
                if(isset($_REQUEST['goalname']) && $_REQUEST['goalname'] != ""){
                     $updatevalues .= "goalname='".$goalname."' ,";
                }
                if(isset($_REQUEST['description']) && $_REQUEST['description'] != ""){
                     $description= $obj->replaceUnwantedChars($_REQUEST['description'],1);
                     $updatevalues .= "description='".$description."' ,";
                }
                if(isset($_REQUEST['status']) && $_REQUEST['status'] != ""){
                     $updatevalues .= "status='".$_REQUEST['status']."' ,";
                }
                $updatevalues = rtrim($updatevalues,",");
                if ($validation_flag == 0) {
                    $query = "UPDATE `goals` SET $updatevalues WHERE id = '$id' ";
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
if(isset($_SESSION['user'])){
        echo $result;
}else if(isset($_REQUEST['is_mobile_api'])){
        echo $result;
}
?>