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
                if(isset($_REQUEST['email']) && $_REQUEST['email'] != ""){
                    $updatevalues .= "email='".$_REQUEST['email']."' ,";
                }
                if(isset($_REQUEST['dob']) && $_REQUEST['dob'] != ""){
                     $dob = date('Y-m-d',  strtotime($_REQUEST['dob']));
                     $updatevalues .= "dob='".$dob."' ,";
                }
                if(isset($_REQUEST['gender']) && $_REQUEST['gender'] != ""){
                     $updatevalues .= "gender='".$_REQUEST['gender']."' ,";
                }
                if(isset($_REQUEST['timezone']) && $_REQUEST['timezone'] != ""){
                     $updatevalues .= "timezone='".$_REQUEST['timezone']."' ,";
                }
                if(isset($_REQUEST['latitude']) && $_REQUEST['latitude'] != ""){
                     $latitude = $_REQUEST['latitude']; 
                     $updatevalues .= "latitude='".$latitude."' ,";
                }
                if(isset($_REQUEST['longitude']) && $_REQUEST['longitude'] != ""){
                     $longitude = $_REQUEST['longitude']; 
                     $updatevalues .= "longitude='".$longitude."' ,";
                }
                if(isset($_REQUEST['aboutus']) && $_REQUEST['aboutus'] != ""){
                     $aboutus = $obj->replaceUnwantedChars($_REQUEST['aboutus'],1); 
                     $updatevalues .= "aboutus='".$aboutus."' ,";
                }
                if(isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != ""){
                     $mobile = $_REQUEST['mobile']; 
                     $updatevalues .= "mobile='".$mobile."' ,";
                }
                if(isset($_REQUEST['address']) && $_REQUEST['address'] != ""){
                     $address = $obj->replaceUnwantedChars($_REQUEST['address'],1); 
                     $updatevalues .= "address='".$address."' ,";
                }
                if(isset($_REQUEST['city']) && $_REQUEST['city'] != ""){
                     $city = $obj->replaceUnwantedChars($_REQUEST['city'],1); 
                     $updatevalues .= "city='".$city."' ,";
                }
                if(isset($_REQUEST['state']) && $_REQUEST['state'] != ""){
                     $state = $obj->replaceUnwantedChars($_REQUEST['state'],1); 
                     $updatevalues .= "state='".$state."' ,";
                }
                if(isset($_REQUEST['country']) && $_REQUEST['country'] != ""){
                     $country = $obj->replaceUnwantedChars($_REQUEST['country'],1); 
                     $updatevalues .= "country='".$country."' ,";
                }
                if(isset($_REQUEST['zipcode']) && $_REQUEST['zipcode'] != ""){
                     $updatevalues .= "zipcode='".$_REQUEST['zipcode']."' ,";
                }
                if(isset($_REQUEST['status']) && $_REQUEST['status'] != ""){
                     $updatevalues .= "status='".$_REQUEST['status']."' ,";
                }
                
                $updatevalues = rtrim($updatevalues,",");
                //if (empty($_REQUEST['firstname']) || empty(str_replace(' ', '', $_REQUEST['firstname']))) {
                if (empty($_REQUEST['firstname'])) {
                    $validation_flag = 1;
                    $validation_error_code = 'FirstName is required';
                }  else if (!preg_match("/^[a-zA-Z]*$/", $_REQUEST['firstname'])) {
                    $validation_flag = 1;
                    $validation_error_code = 'FirstName is Invalid';
                } else if (empty($_REQUEST['lastname'])) {
                //} else if (empty($_REQUEST['lastname']) || empty(str_replace(' ', '', $_REQUEST['lastname']))) {
                    $validation_flag = 1;
                    $validation_error_code = 'LastName is required';
                } else if (!preg_match("/^[a-zA-Z]*$/", $_REQUEST['lastname'])) {
                    $validation_flag = 1;
                    $validation_error_code = 'LastName is Invalid';
                } else if (empty($_REQUEST['email'])) {
                //} else if (empty($_REQUEST['email']) || empty(str_replace(' ', '', $_REQUEST['email']))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Email is required';
                } else if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
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
                    $query = "UPDATE `users` SET $updatevalues WHERE id = '$userid' ";
                    $query_result = $con->query($query);
                    if(!empty($_REQUEST['specialities']) && !empty($userid)){
                            $specialities = $_REQUEST['specialities'];
                            $query = "SELECT spec_id FROM users_specialities WHERE user_id = '$userid' ";
                            $result = $con->query($query);
                            $result = $result->fetch_all();
                            $result = array_map('current', $result);
                            $remains = array_diff($result,$specialities);
                            foreach($specialities as $key=>$value){
                                  if(!in_array($value,$result)){
                                    $query = "INSERT INTO users_specialities(`user_id`,`spec_id`,`status`) VALUES ('$userid','$value','1')";
                                    $query_result = $con->query($query);
                                  }
                            }
                            $query = "DELETE FROM users_specialities WHERE user_id = '$userid' AND spec_id IN (" . implode(',', $remains) . ") ";
                            $con->query($query);
                      }
                      if(!empty($_REQUEST['education']) && !empty($userid)){
                            $educations = $_REQUEST['education'];
                            $query = "SELECT id FROM users_education WHERE user_id = '$userid' ";
                            $result = $con->query($query);
                            $result = $result->fetch_all();
                            $result = array_map('current', $result);
                            foreach($educations as $key=>$value){ 
                                $coursename = $value['coursename'];
                                $universityname = $value['universityname'];
                                $startyear = $value['startyear'];
                                $endyear = $value['endyear'];
                                if($value['eid'] == 'undefined'){
                                    $query = "INSERT INTO users_education(`user_id`,`coursename`,universityname,startyear,endyear,`status`) VALUES ('$userid','$coursename','$universityname','$startyear','$endyear','1')";
                                    $query_result = $con->query($query);
                                } else if(in_array($value['eid'], $result)){
                                    $eids[] = $value['eid'];
                                    $query = "UPDATE users_education SET `coursename` = '$coursename', `universityname` = '$universityname', `startyear` = '$startyear', endyear = '$endyear' WHERE id = '".$value['eid']."' ";
                                    $query_result = $con->query($query); 
                                }
                            }
                            $remains = array_diff($result,$eids);
                            $query = "DELETE FROM users_education WHERE user_id = '$userid' AND id IN (" . implode(',', $remains) . ") ";
                            $con->query($query);
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