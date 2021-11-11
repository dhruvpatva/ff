<?php
     require_once '../../cmnvalidate.php';
     require_once '../../libs/image/ImageManipulator.php';
     $bydirect = true;
     if ($bydirect) {
          if (isset($_POST['event_name'])) {
               $validation_flag = 0;
               $validation_error_code = NULL;
               $event_category_id = "";
               $event_name = "";
               $event_description = "";
               $event_timezone = "";
               $event_location_lat = "";
               $event_location_lng = "";
               $event_location = "";
               $event_address = "";
               $event_city = "";
               $event_state = "";
               $event_country = "";
               $event_zipcode = "";
               $booking_price = 0;
               $is_paid = "";
               $event_difficulty = "";
               $event_package = "";
               $event_support = "";
               $event_timing = "";
               $event_medals = "";
               $event_start_time = "";
               $event_end_time = "";
               $event_offer_start = "NULL";
               $event_offer_end = "NULL";
               $event_discount_type = "";
               $event_discount_amount = "0";
               $status = 1;
               $created = date('Y-m-d H:i:s');
               $logo = "";
               $spe_id = "";
               $created_by_user_id = $_SESSION['user']['user_id'];
               if (isset($_POST['event_category_id'])) {
                    $event_category_id = $_POST['event_category_id'];
               }
               if (isset($_POST['event_name'])) {
                    $event_name = $obj->replaceUnwantedChars($_POST['event_name'], 1);
               }
               if (isset($_POST['event_description'])) {
                    $event_description = $obj->replaceUnwantedChars($_POST['event_description'], 1);
               }
               if (isset($_POST['event_timezone'])) {
                    $event_timezone = $_POST['event_timezone'];
               }
               if (isset($_POST['event_location_lat'])) {
                    $event_location_lat = $obj->replaceUnwantedChars($_POST['event_location_lat'], 1);
               }
               if (isset($_POST['event_location_long'])) {
                    $event_location_lng = $obj->replaceUnwantedChars($_POST['event_location_long'], 1);
               }
               if (isset($_POST['event_address'])) {
                    $event_address = $obj->replaceUnwantedChars($_POST['event_address'], 1);
               }
               if (isset($_POST['event_city'])) {
                    $event_city = $obj->replaceUnwantedChars($_POST['event_city'], 1);
               }
               if (isset($_POST['event_state'])) {
                    $event_state = $obj->replaceUnwantedChars($_POST['event_state'], 1);
               }
               if (isset($_POST['event_country'])) {
                    $event_country = $obj->replaceUnwantedChars($_POST['event_country'], 1);
               }
               if (isset($_POST['event_zipcode'])) {
                    $event_zipcode = $obj->replaceUnwantedChars($_POST['event_zipcode'], 1);
               }
               if (isset($_POST['booking_price'])) {
                    $booking_price = $obj->replaceUnwantedChars($_POST['booking_price'], 1);
               }
               if (isset($_POST['signup_more_time'])) {
                    $is_paid = $_POST['signup_more_time'];
               }
               if (isset($_POST['event_difficulty'])) {
                    $event_difficulty = $_POST['event_difficulty'];
               }
               if (isset($_POST['event_package'])) {
                    $event_package = $obj->replaceUnwantedChars($_POST['event_package'], 1);
               }
               if (isset($_POST['event_support'])) {
                    $event_support = $obj->replaceUnwantedChars($_POST['event_support'], 1);
               }
               if (isset($_POST['event_timing'])) {
                    $event_timing = $obj->replaceUnwantedChars($_POST['event_timing'], 1);
               }
               if (isset($_POST['event_medals'])) {
                    $event_medals = $obj->replaceUnwantedChars($_POST['event_medals'], 1);
               }
               if (isset($_POST['spe_id'])) {
                    $spe_id = $_POST['spe_id'];
               }
               if (isset($_POST['event_start_time'])) {
                    $date = explode('-', $_POST['event_start_time']);
                    if(isset($date[0]) && isset($date[1]) && isset($date[2])){
                         $event_start_time = date('Y-m-d H:i:s', strtotime($date[1]."-".$date[0]."-".$date[2]));
                    } else {
                         $event_start_time = "0000-00-00 00:00:00";
                    }
               }
               if (isset($_POST['event_end_time'])) {
                    $date = explode('-', $_POST['event_end_time']);
                    if(isset($date[0]) && isset($date[1]) && isset($date[2])){
                         $event_end_time = date('Y-m-d H:i:s', strtotime($date[1]."-".$date[0]."-".$date[2]));
                    } else {
                         $event_end_time = "0000-00-00 00:00:00";
                    }
               }
               if (isset($_POST['event_offer_start'])) {
                    $date = explode('-', $_POST['event_offer_start']);
                    if(isset($date[0]) && isset($date[1]) && isset($date[2])){
                         $event_offer_start = "'".date('Y-m-d H:i:s', strtotime($date[1]."-".$date[0]."-".$date[2]))."'";
                    } else {
                         $event_offer_start = "NULL";
                    }
               }
               if (isset($_POST['event_offer_end'])) {
                    $date = explode('-', $_POST['event_offer_end']);
                    if(isset($date[0]) && isset($date[1]) && isset($date[2])){
                         $event_offer_end = "'".date('Y-m-d H:i:s', strtotime($date[1]."-".$date[0]."-".$date[2]))."'";
                    } else {
                         $event_offer_end = "NULL";
                    }
               }
               if (isset($_POST['event_discount_type'])) {
                    $event_discount_type = $_POST['event_discount_type'];
               }
               if (isset($_POST['event_discount_amount'])) {
                    $event_discount_amount = $obj->replaceUnwantedChars($_POST['event_discount_amount'], 1);
               }
               if (isset($_POST['status'])) {
                    $status = $_POST['status'];
               }
               
               if ($validation_flag == 0 && (empty($event_name) || empty(str_replace(' ', '', $event_name)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Name Is Required.';
               }
               if ($validation_flag == 0 && (empty($event_category_id) || empty(str_replace(' ', '', $event_category_id)) || $event_category_id == '0')) {
                    $validation_flag = 1;
                    $validation_error_code = 'Please Select Event Category.';
               }
               if ($validation_flag == 0 && (empty($event_timezone) || empty(str_replace(' ', '', $event_timezone)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Please Select Event Timezone.';
               }
               if ($validation_flag == 0 && (empty($event_timezone) || empty(str_replace(' ', '', $event_timezone)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Please Select Event Timezone.';
               }
               if ($validation_flag == 0 && (empty($event_location_lat) || empty(str_replace(' ', '', $event_location_lat)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Location Is Required.';
               } else {
                    if($validation_flag == 0 && !preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $event_location_lat)){
                         $validation_flag = 1;
                         $validation_error_code = 'Enter Valid Latitude.';
                    }
               }
               if ($validation_flag == 0 && (empty($event_location_lng) || empty(str_replace(' ', '', $event_location_lng)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Location Is Required.';
               } else {
                    if($validation_flag == 0 && !preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $event_location_lng)){
                         $validation_flag = 1;
                         $validation_error_code = 'Enter Valid Longitude.';
                    }
               }
               if ($validation_flag == 0 && (empty($event_address) || empty(str_replace(' ', '', $event_address)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Address Is Required.';
               }
               if ($validation_flag == 0 && (empty($event_city) || empty(str_replace(' ', '', $event_city)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event City Is Required.';
               }
               if ($validation_flag == 0 && (empty($event_state) || empty(str_replace(' ', '', $event_state)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event State Is Required.';
               }
               if ($validation_flag == 0 && (empty($event_country) || empty(str_replace(' ', '', $event_country)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Country Is Required.';
               }
               if ($validation_flag == 0 && (empty($event_zipcode) || empty(str_replace(' ', '', $event_zipcode)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Zipcode Is Required.';
               }
               if ($validation_flag == 0 && ($event_start_time == '0000-00-00 00:00:00' || empty($event_start_time) || empty(str_replace(' ', '', $event_start_time)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Start Date Is Required.';
               }
               if ($validation_flag == 0 && ($event_end_time == '0000-00-00 00:00:00' || empty($event_end_time) || empty(str_replace(' ', '', $event_end_time)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event End Date Is Required.';
               }
               if ($is_paid == '1' && $validation_flag == 0 && (empty($booking_price) || empty(str_replace(' ', '', $booking_price)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Booking Price Is Required If Is Paid Event.';
               } else {
                    $booking_price = 0;
               }
               if ($validation_flag == 0 && (empty($spe_id) || empty(str_replace(' ', '', $spe_id)))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event SPE Is Required.';
               }
               
               if ($validation_flag == 0) {
                    if (isset($_FILES['logo'])) {
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
                              $newImage = $manipulator->resample(600, 600);
                              $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/events/" . $file_name);
                              $logo = $file_name;
                         }
                    }
               }

               if ($validation_flag == 0) {
                    $event_location = $event_location_lat."::".$event_location_lng;
                    $query = "INSERT INTO `events` (`spe_id`,`created_by_user_id`,`event_category_id`,`event_name`,`event_description`,`event_timezone`,`event_location`,`event_address`,`event_city`,`event_state`,`event_country`,`event_zipcode`,`booking_price`,`is_paid`,`event_difficulty`,`event_package`,`event_support`,`event_timing`,`event_medals`,`event_start_time`,`event_end_time`,`event_offer_start`,`event_offer_end`,`event_discount_type`,`event_discount_amount`,`event_image`,`status`,`created`) VALUES ( '".$spe_id."', '" . $created_by_user_id . "','" . $event_category_id . "','" . $event_name . "','" . $event_description . "','" . $event_timezone . "','" . $event_location . "','" . $event_address . "','" . $event_city . "','" . $event_state . "','" . $event_country . "','" . $event_zipcode . "','" . $booking_price . "','" . $is_paid . "','" . $event_difficulty . "','" . $event_package . "','" . $event_support . "','" . $event_timing . "','" . $event_medals . "','" . $event_start_time . "','" . $event_end_time . "'," . $event_offer_start . "," . $event_offer_end . ",'" . $event_discount_type . "','" . $event_discount_amount . "','" . $logo . "','" . $status . "','" . $created . "');";
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
               $result['error_code'] = 'Required Parameter Are Missing';
          }
     }
     $result = json_encode($result);
     if (isset($_SESSION['user'])) {
          echo $result;
     }
?>