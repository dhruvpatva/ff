<?php
    require_once '../../cmnvalidate.php';
    $bydirect = true;
    if ($bydirect) {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "" && isset($_REQUEST['contact_number']) && $_REQUEST['contact_number'] != "" && isset($_REQUEST['slot_id']) && $_REQUEST['slot_id'] != "" && isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != "" && isset($_REQUEST['is_mobile_api'])) {
            $id = $_REQUEST['id'];
            $userid = $_REQUEST['userid'];
            $contact_number = $_REQUEST['contact_number'];
            $slot_id = $_REQUEST['slot_id'];
            $class_id = $_REQUEST['class_id'];
            $status = 1;
            if (isset($_REQUEST['status'])) {
                $status = $_REQUEST['status'];
            }
            $validation_flag = 0;
            $validation_error_code = NULL;
            if ($validation_flag == 0) {
                $query = "SELECT id FROM class_user_bookings WHERE user_id='" . $userid . "' AND class_id='" . $class_id . "' AND slot_id='" . $slot_id . "' AND slot_time_id='" . $id . "' AND status='1'";
                $query_result = $con->query($query);
                if ($query_result->num_rows == 0) {
                   $query = "INSERT INTO class_user_bookings (class_id, slot_id, slot_time_id, user_id, contact_number, attend_status, status, created, created_by) VALUES ('" . $class_id . "', '" . $slot_id . "', '" . $id . "', '" . $userid . "', '" . $contact_number . "', '0', '" . $status . "', '" . date("Y-m-d H:i:s") . "', '" . $userid . "')";
                   $query_result = $con->query($query);
                   $result['success'] = 1;
                   $result['data'] = 'success';
                   $result['error'] = 0;
                   $result['error_code'] = NULL;
                } else {
                   $result['success'] = 0;
                   $result['data'] = NULL;
                   $result['error'] = 1;
                   $result['error_code'] = 'Class Booking Already Exists For This User';
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
    if (isset($_REQUEST['is_mobile_api'])) {
        echo $result;
    }
?>
