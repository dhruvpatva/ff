<?php
    require_once '../../cmnvalidate.php';
    $bydirect = true;
    if ($bydirect) { //print_r($_SESSION['user_id']); print_r($_SESSION); exit;
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "" && isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] != "") { 
            $id = $_REQUEST['id'];
            $userid = $_SESSION['user']['user_id'];
            $query = "SELECT ev.`id`, ev.`spe_id`, spe.`name` as spe_name, spe.`phone` as spe_phone, spe.`website` as spe_website, spe.`email` as spe_email, spe.`refund_policy` as spe_refund_policy, spe.`privacy_policy` as spe_privacy_policy,ev.event_category_id, ev.`event_name`,ev.`event_description`,ev.`event_timezone`,ev.`event_location`,ev.`event_address`,ev.`event_city`,ev.`event_state`,ev.`event_country`,ev.`event_zipcode`,ev.`booking_price`,ev.`is_paid`,ev.`event_difficulty`,ev.`event_package`,ev.`event_support`,ev.`event_timing`,ev.`event_medals`,ev.`event_start_time`,ev.`event_end_time`,ev.`event_offer_start`,ev.`event_offer_end`,ev.`event_discount_type`,ev.`event_discount_amount`,ev.`event_image`,ev.`status`, ev_c.name AS event_category FROM `events` AS ev LEFT JOIN `categories` AS ev_c ON ev_c.id=ev.event_category_id LEFT JOIN `spe` ON spe.id=ev.spe_id WHERE ev.id=$id";
            $query_result = $con->query($query);
            if ($query_result->num_rows > 0) {
                $resulted_data = $query_result->fetch_assoc();
                if ($resulted_data['event_image'] != "") {
                    $resulted_data['event_image'] = SITE_ROOT . "/uploads/events/" . $resulted_data['event_image'];
                } else {
                    $resulted_data['event_image'] = "";
                }
                $resulted_data['event_name'] = $obj->replaceUnwantedChars($resulted_data['event_name'], 2);
                $resulted_data['event_description'] = $obj->replaceUnwantedChars($resulted_data['event_description'], 2);
                $event_location = explode("::", $resulted_data['event_location']);
                if (isset($event_location[0])) {
                    $resulted_data['event_location_lat'] = $obj->replaceUnwantedChars($event_location[0], 2);
                }
                if (isset($event_location[1])) {
                    $resulted_data['event_location_long'] = $obj->replaceUnwantedChars($event_location[1], 2);
                }
                $resulted_data['event_address'] = $obj->replaceUnwantedChars($resulted_data['event_address'], 2);
                $resulted_data['event_city'] = $obj->replaceUnwantedChars($resulted_data['event_city'], 2);
                $resulted_data['event_state'] = $obj->replaceUnwantedChars($resulted_data['event_state'], 2);
                $resulted_data['event_country'] = $obj->replaceUnwantedChars($resulted_data['event_country'], 2);
                $resulted_data['event_zipcode'] = $obj->replaceUnwantedChars($resulted_data['event_zipcode'], 2);
                $resulted_data['booking_price'] = $obj->replaceUnwantedChars($resulted_data['booking_price'], 2);
                $resulted_data['signup_more_time'] = $resulted_data['is_paid'];
                $resulted_data['event_package'] = $obj->replaceUnwantedChars($resulted_data['event_package'], 2);
                $resulted_data['event_support'] = $obj->replaceUnwantedChars($resulted_data['event_support'], 2);
                $resulted_data['event_timing'] = $obj->replaceUnwantedChars($resulted_data['event_timing'], 2);
                $resulted_data['event_medals'] = $obj->replaceUnwantedChars($resulted_data['event_medals'], 2);
                $resulted_data['event_discount_amount'] = $obj->replaceUnwantedChars($resulted_data['event_discount_amount'], 2);
                if (isset($_REQUEST['is_mobile_api'])) {
                    $query_att = "SELECT id,contact_number,registration_date FROM events_userbookinglists WHERE event_id='" . $id . "' AND user_id='" . $userid . "' AND status='1'";
                    $query_att_result = $con->query($query_att);
                    if ($query_att_result->num_rows == 0) {
                        $resulted_data['is_join'] = 0;
                        $resulted_data['booking_details'] = array("id" => "", "contact_number" => "", "registration_date" => "");
                    } else {
                        $query_att_data = $query_att_result->fetch_assoc();
                        $resulted_data['is_join'] = 1;
                        $resulted_data['booking_details'] = $query_att_data;
                    }
                    if ($resulted_data['event_start_time'] != "0000-00-00 00:00:00" && $resulted_data['event_start_time'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_start_time'] = date('Y-m-d H:i:s', strtotime($resulted_data['event_start_time']));
                    } else {
                        $resulted_data['event_start_time'] = "";
                    }
                    if ($resulted_data['event_end_time'] != "0000-00-00 00:00:00" && $resulted_data['event_end_time'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_end_time'] = date('Y-m-d H:i:s', strtotime($resulted_data['event_end_time']));
                    } else {
                        $resulted_data['event_end_time'] = "";
                    }
                    if ($resulted_data['event_offer_start'] != "0000-00-00 00:00:00" && $resulted_data['event_offer_start'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_offer_start'] = date('Y-m-d H:i:s', strtotime($resulted_data['event_offer_start']));
                    } else {
                        $resulted_data['event_offer_start'] = "";
                    }
                    if ($resulted_data['event_offer_end'] != "0000-00-00 00:00:00" && $resulted_data['event_offer_end'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_offer_end'] = date('Y-m-d H:i:s', strtotime($resulted_data['event_offer_end']));
                    } else {
                        $resulted_data['event_offer_end'] = "";
                    }
                } else {
                    $resulted_data['selectedspe'] = array("id" => $resulted_data['spe_id'], "name" => $resulted_data['spe_name']);
                    if ($resulted_data['event_start_time'] != "0000-00-00 00:00:00" && $resulted_data['event_start_time'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_start_time'] = date('m-d-Y h:i A', strtotime($resulted_data['event_start_time']));
                    } else {
                        $resulted_data['event_start_time'] = "";
                    }
                    if ($resulted_data['event_end_time'] != "0000-00-00 00:00:00" && $resulted_data['event_end_time'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_end_time'] = date('m-d-Y h:i A', strtotime($resulted_data['event_end_time']));
                    } else {
                        $resulted_data['event_end_time'] = "";
                    }
                    if ($resulted_data['event_offer_start'] != "0000-00-00 00:00:00" && $resulted_data['event_offer_start'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_offer_start'] = date('m-d-Y h:i A', strtotime($resulted_data['event_offer_start']));
                    } else {
                        $resulted_data['event_offer_start'] = "";
                    }
                    if ($resulted_data['event_offer_end'] != "0000-00-00 00:00:00" && $resulted_data['event_offer_end'] != "1970-01-01 00:00:00") {
                        $resulted_data['event_offer_end'] = date('m-d-Y h:i A', strtotime($resulted_data['event_offer_end']));
                    } else {
                        $resulted_data['event_offer_end'] = "";
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
    if (isset($_SESSION['user']) || isset($_REQUEST['is_mobile_api'])) {
        echo $result;
    }
?>
