<?php
    require_once '../../cmnvalidate.php';
    $bydirect = true;
    if ($bydirect) {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
            $id = $_REQUEST['id'];
            $query = "SELECT c.`id`,c.`spe_id`,c.`category_id`,c.`name`,c.`description`,c.`timezone`,c.`latitude`,c.`longitude`,c.`address`,c.`city`,c.`state`,c.`country`,c.`zipcode`,c.`is_paid`,c.`price`,c.`image`,c.`banner_image`,c.`discount_type`,c.`discount_amount`,c.`offer_start_date`,c.`offer_end_date`,c.`status`,spe.`name` as spe_name, spe.`phone` as spe_phone, spe.`website` as spe_website, spe.`email` as spe_email, spe.`refund_policy` as spe_refund_policy, spe.`privacy_policy` as spe_privacy_policy FROM classes AS c LEFT JOIN `spe` ON spe.id=c.spe_id WHERE c.id=$id";
            $query_result = $con->query($query);
            if ($query_result->num_rows > 0) {
                $query_in = "SELECT ci.`user_id` as `id`, u.`firstname`, u.`lastname`, u.`profile_image` FROM class_instructors AS ci LEFT JOIN users AS u ON ci.`user_id`=u.`id` WHERE ci.`class_id`=$id AND ci.`status`='1';";
                $query_result_in = $con->query($query_in);
                $instructor = array();
                while ($row = $query_result_in->fetch_assoc()) {
                    $row['name'] = $row['firstname'] . " " . $row['lastname'];
                    if ($row['profile_image'] != "") {
                        $row['profile_image'] = SITE_ROOT . "/uploads/users/" . $row['profile_image'];
                    }
                    unset($row['firstname']);
                    unset($row['lastname']);
                    $instructor[] = $row;
                }
                $resulted_data = $query_result->fetch_assoc();
                if ($resulted_data['image'] != "") {
                    $resulted_data['image'] = SITE_ROOT . "/uploads/classes/logo/" . $resulted_data['image'];
                } else {
                    $resulted_data['image'] = "";
                }
                if ($resulted_data['banner_image'] != "") {
                    $resulted_data['banner_image'] = SITE_ROOT . "/uploads/classes/banner/" . $resulted_data['banner_image'];
                } else {
                    $resulted_data['banner_image'] = "";
                }
                $resulted_data['name'] = $obj->replaceUnwantedChars($resulted_data['name'], 2);
                $resulted_data['description'] = $obj->replaceUnwantedChars($resulted_data['description'], 2);
                $resulted_data['latitude'] = $obj->replaceUnwantedChars($resulted_data['latitude'], 2);
                $resulted_data['longitude'] = $obj->replaceUnwantedChars($resulted_data['longitude'], 2);
                $resulted_data['address'] = $obj->replaceUnwantedChars($resulted_data['address'], 2);
                $resulted_data['city'] = $obj->replaceUnwantedChars($resulted_data['city'], 2);
                $resulted_data['state'] = $obj->replaceUnwantedChars($resulted_data['state'], 2);
                $resulted_data['country'] = $obj->replaceUnwantedChars($resulted_data['country'], 2);
                $resulted_data['zipcode'] = $obj->replaceUnwantedChars($resulted_data['zipcode'], 2);
                $resulted_data['price'] = $obj->replaceUnwantedChars($resulted_data['price'], 2);
                $resulted_data['discount_amount'] = $obj->replaceUnwantedChars($resulted_data['discount_amount'], 2);
                $resulted_data['instructor'] = $instructor;
                
                if (isset($_REQUEST['is_mobile_api'])) {
                    $slot_type = 0;
                    $recurring_type = 0;
                    $recurring_weeks = array();
                    $recurring_months_base = 1;
                    $recurring_month_date = array();
                    $recurring_month_every = array();
                    $recurring_month_day = array();
                    $slot_time_sql = "SELECT `id`, `slot_id`, `start_date`, `end_date`, `interval`, COUNT(`id`) as total_time_slots FROM `class_slot_timings` WHERE `class_id`='".$id."' AND (`start_date` >= '".date('Y-m-d H:i:s')."' OR `end_date` >= '".date('Y-m-d H:i:s')."') AND `status`='1' GROUP BY `slot_id`,DATE(`start_date`) ORDER BY `start_date` ASC";
                    $slot_time_sql_result = $con->query($slot_time_sql);
                    $dates = array();
                    $total_spots = 0;
                    $interval = 0;
                    while ($slot_time = $slot_time_sql_result->fetch_assoc()) {
                        if(empty($dates)){
                            $slot_sql = "SELECT `id`,`start_date`,`end_date`,`start_time`,`end_time`,`timezone`,`slot_type`,`attendee_type`,`attendee_limit`,`recurring_type`,`recurring_weeks`,`recurring_months_base`,`recurring_month_date`,`recurring_month_every`,`recurring_month_day`,`registration_cut_off` FROM `class_slots` WHERE `id`='".$slot_time['slot_id']."'";
                            $slot_sql_result = $con->query($slot_sql);
                            $slot = $slot_sql_result->fetch_assoc();
                            $dates[] = date('m-d-Y', strtotime($slot_time['start_date']));
                            $interval = $slot_time['interval'];
                            if($slot['attendee_type'] == '0'){
                                $total_spots = -1;
                            } else{
                                $total_spots = $slot_time['total_time_slots'] * $slot['attendee_limit'];
                            }
                            $slot_type = $slot['slot_type'];
                            if($slot_type == 1 || $slot_type == 2) {
                                $recurring_type = $slot['recurring_type'];
                                if($recurring_type == 2 || $recurring_type == 3) {
                                    if($slot['recurring_type'] == 2){
                                        $recurring_weeks = explode(",", $slot['recurring_weeks']);
                                    } else {
                                        $recurring_months_base = $slot['recurring_months_base'];
                                        if($recurring_months_base == 1){
                                            $recurring_month_date = explode(",", $slot['recurring_month_date']);
                                        } else {
                                            $recurring_month_every = explode(",", $slot['recurring_month_every']);
                                            $recurring_month_day = explode(",", $slot['recurring_month_day']);
                                        }
                                    }
                                } else {
                                    $recurring_weeks = array("1","2","3","4","5","6","7");
                                }
                            }
                        } else if(in_array(date('m-d-Y', strtotime($slot_time['start_date'])), $dates)){
                            if(array_search(date('m-d-Y', strtotime($slot_time['start_date'])), $dates) == 0) {
                                $slot_sql = "SELECT `id`,`start_date`,`end_date`,`start_time`,`end_time`,`timezone`,`slot_type`,`slot_interval`,`attendee_type`,`attendee_limit`,`recurring_type`,`recurring_weeks`,`recurring_months_base`,`recurring_month_date`,`recurring_month_every`,`recurring_month_day`,`registration_cut_off` FROM `class_slots` WHERE `id`='".$slot_time['slot_id']."'";
                                $slot_sql_result = $con->query($slot_sql);
                                $slot = $slot_sql_result->fetch_assoc();
                                if($slot['attendee_type'] == '0'){
                                    $total_spots = -1;
                                } else{
                                    $total_spots = $total_spots + ($slot_time['total_time_slots'] * $slot['attendee_limit']);
                                }
                            }
                        } else {
                            $dates[] = date('m-d-Y', strtotime($slot_time['start_date']));
                        }
                    }
                    $resulted_data['dates'] = $dates;
                    $resulted_data['total_spots'] = $total_spots;
                    $filled_spots = 0;
                    $time_available = array();
                    if(!empty($dates)){
                        $get_slots = "SELECT `id`, `start_date`, `end_date`, (SELECT COUNT(class_user_bookings.`id`) FROM class_user_bookings WHERE class_user_bookings.`slot_time_id`=`class_slot_timings`.`id` GROUP BY class_user_bookings.`slot_time_id`) as `user_booking` FROM `class_slot_timings` WHERE `class_id`='".$id."' AND DATE_FORMAT(`start_date`,'%m-%d-%Y') = '".$dates[0]."' AND `status`='1'";
                        $get_slots_result = $con->query($get_slots);
                        while ($slot_book = $get_slots_result->fetch_assoc()) {
                            $filled_spots = $filled_spots + $slot_book['user_booking'];
                            $time_available[] = date('H:i', strtotime($slot_book['start_date']))." - ".date('H:i', strtotime($slot_book['end_date']));
                        }
                    }
                    $resulted_data['filled_spots'] = $filled_spots;
                    $resulted_data['time_available'] = $time_available;
                    $resulted_data['interval'] = $interval;
                    if ($resulted_data['offer_start_date'] != "0000-00-00 00:00:00" && $resulted_data['offer_start_date'] != "1970-01-01 00:00:00") {
                        $resulted_data['offer_start_date'] = date('Y-m-d H:i:s', strtotime($resulted_data['offer_start_date']));
                    } else {
                        $resulted_data['offer_start_date'] = "";
                    }
                    if ($resulted_data['offer_end_date'] != "0000-00-00 00:00:00" && $resulted_data['offer_end_date'] != "1970-01-01 00:00:00") {
                        $resulted_data['offer_end_date'] = date('Y-m-d H:i:s', strtotime($resulted_data['offer_end_date']));
                    } else {
                        $resulted_data['offer_end_date'] = "";
                    }
                    $resulted_data['slot_type'] = $slot_type;
                    $resulted_data['recurring_type'] = $recurring_type;
                    $resulted_data['recurring_weeks'] = $recurring_weeks;
                    $resulted_data['recurring_months_base'] = $recurring_months_base;
                    $resulted_data['recurring_month_date'] = $recurring_month_date;
                    $resulted_data['recurring_month_every'] = $recurring_month_every;
                    $resulted_data['recurring_month_day'] = $recurring_month_day;
                } else {
                    $resulted_data['selectedspe'] = array("id" => $resulted_data['spe_id'], "name" => $resulted_data['spe_name']);
                    if ($resulted_data['offer_start_date'] != "0000-00-00 00:00:00" && $resulted_data['offer_start_date'] != "1970-01-01 00:00:00") {
                        $resulted_data['offer_start_date'] = date('m-d-Y h:i A', strtotime($resulted_data['offer_start_date']));
                    } else {
                        $resulted_data['offer_start_date'] = "";
                    }
                    if ($resulted_data['offer_end_date'] != "0000-00-00 00:00:00" && $resulted_data['offer_end_date'] != "1970-01-01 00:00:00") {
                        $resulted_data['offer_end_date'] = date('m-d-Y h:i A', strtotime($resulted_data['offer_end_date']));
                    } else {
                        $resulted_data['offer_end_date'] = "";
                    }
                }
                $result['success'] = 1;
                $result['data'] = $resulted_data;
                $result['error'] = 0;
                $result['error_code'] = NULL;
            }
        } else {
            $result['success'] = 0;
            $result['data'] = NULL;
            $result['error'] = 1;
            $result['error_code'] = 'Required Parameter Are Missing';
        }
    }
    $result = json_encode($result);
    if (isset($_REQUEST['is_mobile_api']) || isset($_SESSION['user'])) {
        echo $result;
    }
?>
