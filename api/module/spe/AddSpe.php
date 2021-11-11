<?php
require_once '../../cmnvalidate.php';
require_once '../../libs/image/ImageManipulator.php';
$bydirect = true;

if ($bydirect){ 
    if (isset($_REQUEST['name']) && isset($_REQUEST['aboutus']) && isset($_REQUEST['address']) && isset($_REQUEST['latitude']) && isset($_REQUEST['longitude']) && isset($_REQUEST['city'])&& isset($_REQUEST['state'])&& isset($_REQUEST['country'])) {
        $validation_flag = 0;
        $validation_error_code = NULL;
        $name = $obj->replaceUnwantedChars($_REQUEST['name'],1);
        $aboutus = $obj->replaceUnwantedChars($_REQUEST['aboutus'],1); 
        $email = $_REQUEST['email']; 
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
        $status = 1;
        if(isset($_REQUEST['status'])){
             $status = $_REQUEST['status']; 
        }
        $amenities = explode(",",$_REQUEST['amenities']);
        $logo = "no-image.png";
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
        
        $coverlogo = "no-cover-image.png";
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
            $query = "SELECT `id` FROM `spe` WHERE `name` = '$name'";
            $query_result = $con->query($query);
            if ($query_result->num_rows == 0) {
                        $secretkey = substr(sha1(mt_rand()), 0, 22);  
                        $role_id = 1;
                        $query = "INSERT INTO `spe`(`name`,`aboutus`,`email`,`logo`,`coverlogo`,`address`,`city`,`state`,`country`,`zipcode`,`latitude`,`longitude`,`phone`,`website`,`timezone`,`open_day`,`close_day`,`open_time`,`close_time`,`refund_policy`,`privacy_policy`,`spe_type`,`status`) 
                                  VALUES ('$name','$aboutus','$email','$logo','$coverlogo','$address','$city','$state','$country','$zipcode','$latitude','$longitude','$phone','$website','$timezone','NULL','NULL','NULL','NULL','$refund_policy','$privacy_policy','NULL','$status'); ";
                        $query_result = $con->query($query);
                        $speid = $con->insert_id;
                        if(!empty($amenities) && !empty($speid)){
                              foreach($amenities as $key=>$value){
                                    $amid = $value;
                                    $query = "INSERT INTO spe_amenities(`spe_id`,`amenity_id`,`status`) VALUES ('$speid','$amid','1')";
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
                $result['error_code'] = 'Name Already Registered';
            }
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
        $result['error_code'] = 'Required Parameter Are Missing';
    }
}
$result = json_encode($result);
echo $result;
?>