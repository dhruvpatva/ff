<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
if ($bydirect) {
     if (isset($_REQUEST['event_id']) && isset($_REQUEST['registration_date']) && isset($_REQUEST['contact_number']) && isset($_REQUEST['user_id'])) {
          $validation_flag = 0;
          $validation_error_code = NULL;
          $contact_number = $obj->replaceUnwantedChars($_REQUEST['contact_number'], 1);
          $event_id = $_REQUEST['event_id'];
          $user_id = $_REQUEST['user_id'];
          $date = explode('-', $_REQUEST['registration_date']);
          if(isset($date[0]) && isset($date[1]) && isset($date[2])){
               $registration_date = date('Y-m-d H:i:s', strtotime($date[1]."-".$date[0]."-".$date[2]));
          } else {
               $registration_date = "0000-00-00 00:00:00";
          }
          $status = 1;
          if (isset($_REQUEST['status'])){
               $status = $_REQUEST['status'];
          }
          if ($validation_flag == 0 && ($registration_date == '0000-00-00 00:00:00' || empty($registration_date) || empty(str_replace(' ', '', $registration_date)))) {
               $validation_flag = 1;
               $validation_error_code = 'Registration Date Is Required.';
          }
          
          if ($validation_flag == 0) {
               $query = "SELECT id FROM events_userbookinglists WHERE event_id='".$event_id."' AND user_id='".$user_id."'";
               $query_result = $con->query($query);
               if ($query_result->num_rows == 0) {
                    $query = "INSERT INTO events_userbookinglists (event_id, user_id, contact_number, registration_date, attend_status, status, created) VALUES ('".$event_id."', '".$user_id."', '".$contact_number."', '".$registration_date."', '0', '".$status."', '".date("Y-m-d H:i:s")."')";
                    $query_result = $con->query($query);
                    $result['success'] = 1;
                    $result['data'] = 'success';
                    $result['error'] = 0;
                    $result['error_code'] = NULL;
               } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = 'Event Booking Already Exists For This User';
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
if (isset($_SESSION['user']) || isset($_REQUEST['is_mobile_api'])) {
     echo $result;
}
?>