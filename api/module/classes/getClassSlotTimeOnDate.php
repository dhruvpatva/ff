<?php
    require_once '../../cmnvalidate.php';
    $bydirect = true;
    if ($bydirect) {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "" && isset($_REQUEST['date']) && $_REQUEST['date'] != "" && isset($_REQUEST['is_mobile_api'])) {
            $id = $_REQUEST['id'];
            $date = $_REQUEST['date'];
            $resulted_data = array();
            $slot_time_sql = "SELECT `id`, `class_id`, `slot_id`, `start_date`, `end_date`, `interval` FROM `class_slot_timings` WHERE `class_id`='".$id."' AND DATE_FORMAT(`start_date`,'%m-%d-%Y') = '".$date."' AND `status`='1' ORDER BY `start_date` ASC";
            $slot_time_sql_result = $con->query($slot_time_sql);
            while ($slot_time = $slot_time_sql_result->fetch_assoc()) {
                $resulted_data[] = $slot_time;
            }
            $result['success'] = 1;
            $result['data'] = $resulted_data;
            $result['error'] = 0;
            $result['error_code'] = NULL;
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
