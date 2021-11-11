<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
$userid = @$_REQUEST['id'];
$checkaccess = true;
if (isset($_REQUEST['is_mobile_api'])) {
        if ($result['success'] == 1) {
                $bydirect = true;
        } else {
                $bydirect = false;
        }
        $params = array();
        $userid = $_REQUEST['userid'];
        $checkaccess = false;
}

if ($bydirect) {
     if (isset($userid) && isset($_REQUEST['firstname']) && isset($_REQUEST['lastname']) && isset($_REQUEST['email']) && isset($_REQUEST['dob'])) {
        $email = $_REQUEST['email'];
        // Get user detail
        $query = "SELECT id FROM users where id!=$userid and email='$email'";
        $query_result = $con->query($query);
        if($query_result->num_rows == 0){
                $validation_flag = 0;
                $validation_error_code = NULL;
                $updatevalues = "";
                if(isset($_REQUEST['firstname']) && $_REQUEST['firstname'] != ""){
                     $updatevalues .= "firstname='".$_REQUEST['firstname']."' ,";
                }
                if(isset($_REQUEST['lastname']) && $_REQUEST['lastname'] != ""){
                     $updatevalues .= "lastname='".$_REQUEST['lastname']."' ,";
                }
                if(isset($_REQUEST['dob']) && $_REQUEST['dob'] != ""){
                     $dob = date('Y-m-d',  strtotime($_REQUEST['dob']));
                     $updatevalues .= "dob='".$dob."' ,";
                }
                if(isset($_REQUEST['gender']) && $_REQUEST['gender'] != ""){
                     $updatevalues .= "gender='".$_REQUEST['gender']."' ,";
                }
                
                if(isset($_REQUEST['height']) && $_REQUEST['height'] != ""){
                     $updatevalues .= "height='".$_REQUEST['height']."' ,";
                }
                if(isset($_REQUEST['weight']) && $_REQUEST['weight'] != ""){
                     $updatevalues .= "weight='".$_REQUEST['weight']."' ,";
                }
                if(isset($_REQUEST['active_level']) && $_REQUEST['active_level'] != ""){
                     $updatevalues .= "active_level='".$_REQUEST['active_level']."' ,";
                }
                if(isset($_REQUEST['timezone']) && $_REQUEST['timezone'] != ""){
                     $updatevalues .= "timezone='".$_REQUEST['timezone']."' ,";
                }
                if(isset($_REQUEST['status']) && $_REQUEST['status'] != ""){
                     $updatevalues .= "status='".$_REQUEST['status']."' ,";
                }
                if(isset($_REQUEST['ispasswordchange']) && $_REQUEST['ispasswordchange']==1){
                     $password = $obj->encryptIt($_REQUEST['password']);
                     $updatevalues .= "password='".$password."' ,";
                }
                $updatevalues = rtrim($updatevalues,",");
                if (empty($email) || empty(str_replace(' ', '', $email))) {
                    $validation_flag = 1;
                    $validation_error_code = 'ER0006';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validation_flag = 1;
                    $validation_error_code = 'ER0007';
                }
                if(isset($_REQUEST['ispasswordchange']) && $_REQUEST['ispasswordchange'] == 1){
                    if (empty($_REQUEST['password'])  && empty($_REQUEST['cpassword'])) {
                         $validation_flag = 1;
                         $validation_error_code = 'Password Field Missing';
                    } else if (@$_REQUEST['password'] != @$_REQUEST['cpassword']) {
                        $validation_flag = 1;
                         $validation_error_code = 'Password Not Match';
                    } 
                } 
                if ($validation_flag == 0) {
                    $query = "UPDATE `users` SET $updatevalues WHERE id = '$userid' ";
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
               $result['error_code'] = 'Email Already Exists';     
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