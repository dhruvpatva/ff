<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
$amenities = explode(",",$_REQUEST['amenities']);
$oldamenities = explode(",",$_REQUEST['oldamenities']);
$amenflag = false;
foreach($oldamenities as $key=>$value){
     $check = array_search($value, $amenities);
     if(!$check){
          $amenflag = true;
     } 
}
if ($bydirect) {
     if (!empty($_REQUEST['name']) && !empty($_REQUEST['email']) && !empty($_REQUEST['address']) && !empty($_REQUEST['latitude']) && !empty($_REQUEST['longitude'])) {
        $id = $_REQUEST['id'];
        $name = $obj->replaceUnwantedChars($_REQUEST['name'],1);
        $aboutus = $obj->replaceUnwantedChars($_REQUEST['aboutus'],1); 
        $email = $_REQUEST['email']; 
        $logo = $_REQUEST['logo']; 
        $address = $obj->replaceUnwantedChars($_REQUEST['address'],1); 
        $city = $obj->replaceUnwantedChars($_REQUEST['city'],1); 
        $state = $obj->replaceUnwantedChars($_REQUEST['state'],1); 
        $country = $obj->replaceUnwantedChars($_REQUEST['country'],1); 
        $zipcode = $_REQUEST['zipcode'];
        $latitude = $_REQUEST['latitude'];
        $longitude = $_REQUEST['longitude'];
        $phone = $_REQUEST['phone'];
        $website = $_REQUEST['website'];
        $timezone = $_REQUEST['timezone'];
        $refund_policy = $obj->replaceUnwantedChars($_REQUEST['refund_policy'],1); 
        $privacy_policy = $obj->replaceUnwantedChars($_REQUEST['privacy_policy'],1); 
        $status = $_REQUEST['status'];
        if(isset($_FILES['logo'])){ 
               $file_name = time() . $_FILES['logo']['name'];
               $file_size = $_FILES['logo']['size'];
               $file_tmp = $_FILES['logo']['tmp_name'];
               $file_type = $_FILES['logo']['type'];
               $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
               if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                    $response = 'Invalid file extension.';
                    $validation_flag = 1;
                    $validation_error_code = 'Invalid file extension.';
               } else {
                    $manipulator = new ImageManipulator($_FILES['logo']['tmp_name']);
                    $newImage = $manipulator->resample(400, 400);
                    $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/spe/" . $file_name);
                    $logo = $file_name;
               }
        }
        
        if(isset($_FILES['coverlogo'])){ 
               $file_name = time() . $_FILES['coverlogo']['name'];
               $file_size = $_FILES['coverlogo']['size'];
               $file_tmp = $_FILES['coverlogo']['tmp_name'];
               $file_type = $_FILES['coverlogo']['type'];
               $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
               if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                    $response = 'Invalid file extension.';
                    $validation_flag = 1;
                    $validation_error_code = 'Invalid file extension.';
               } else {
                    $manipulator = new ImageManipulator($_FILES['logo']['tmp_name']);
                    $newImage = $manipulator->resample(400, 400);
                    $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/spe/coverlogo/" . $file_name);
                    $coverlogo = $file_name;
               }
        }
        
        $query = "SELECT id FROM spe where id!=$id and email='$name'";
        $query_result = $con->query($query);
        if($query_result->num_rows == 0){
                $validation_flag = 0;
                $validation_error_code = NULL;
                $updatevalues = "";
                if(!empty($_REQUEST['name'])){ $updatevalues .= "name='".$name."' ,"; }
                if(!empty($_REQUEST['aboutus'])){ $updatevalues .= "aboutus='".$aboutus."' ,"; }
                if(!empty($_REQUEST['email'])){ $updatevalues .= "email='".$email."' ,"; }
                if(!empty($_REQUEST['address'])){ $updatevalues .= "address='".$address."' ,"; }
                if(!empty($_REQUEST['city'])){ $updatevalues .= "city='".$city."' ,"; }
                if(!empty($_REQUEST['state'])){ $updatevalues .= "state='".$state."' ,"; }
                if(!empty($_REQUEST['country'])){ $updatevalues .= "country='".$country."' ,"; }
                if(!empty($_REQUEST['zipcode'])){ $updatevalues .= "zipcode='".$zipcode."' ,"; }
                if(!empty($_REQUEST['latitude'])){ $updatevalues .= "latitude='".$latitude."' ,"; }
                if(!empty($_REQUEST['longitude'])){ $updatevalues .= "longitude='".$longitude."' ,"; }
                if(!empty($_REQUEST['phone'])){ $updatevalues .= "phone='".$phone."' ,"; }
                if(!empty($_REQUEST['website'])){ $updatevalues .= "website='".$website."' ,"; }
                if(!empty($_REQUEST['timezone'])){ $updatevalues .= "timezone='".$timezone."' ,"; }
                if(!empty($_REQUEST['refund_policy'])){ $updatevalues .= "refund_policy='".$refund_policy."' ,"; }
                if(!empty($_REQUEST['privacy_policy'])){ $updatevalues .= "privacy_policy='".$privacy_policy."' ,"; }
                if(!empty($_REQUEST['status'])){ $updatevalues .= "status='".$status."' ,"; }
                if(isset($_FILES['logo'])){ $updatevalues .= "logo='".$logo."' ,"; }
                if(isset($_FILES['coverlogo'])){ $updatevalues .= "coverlogo='".$coverlogo."' ,"; }
                
                $updatevalues = rtrim($updatevalues,",");
                if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
                    $validation_flag = 1;
                    $validation_error_code = 'Email is Invalid';
                } else if (!preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $latitude)) {
                    $validation_flag = 1;
                    $validation_error_code = 'Latitude is Invalid';
                } else if (!preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $longitude)) {
                    $validation_flag = 1;
                    $validation_error_code = 'Longitude is Invalid';
                } 
                
                if ($validation_flag == 0) {
                    $query = "UPDATE `spe` SET $updatevalues WHERE id = '$id' ";
                    $query_result = $con->query($query);
                    if($amenflag){
                         $deletequery = "delete from spe_amenities WHERE spe_id = '$id' ";
                         $query_result = $con->query($deletequery);
                              foreach($amenities as $key=>$value){
                                    $amid = $value;
                                    $query = "INSERT INTO spe_amenities(`spe_id`,`amenity_id`,`status`) VALUES ('$id','$amid','1')";
                                    $query_result = $con->query($query);
                              }
                    }
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
               $result['error_code'] = 'Name Already Exists';     
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
}
?>