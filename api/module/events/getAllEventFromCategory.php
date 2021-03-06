<?php
     require_once '../../cmnvalidate.php';
     $bydirect = true;
     if (isset($_REQUEST['is_mobile_api'])) {
          if ($result['success'] == 1) {
               $bydirect = true;
          } else {
               $bydirect = false;
          }
          $params = array();
          if (isset($_REQUEST['userid'])) {
               $userid = $_REQUEST['userid'];
          }
     }
     if ($bydirect) {
          if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != "") {
               $category_id = $_REQUEST['category_id'];
               $query = "SELECT ev.`id`, ev.`event_name`,ev.`event_description`,ev.`event_timezone`,ev.`event_location`,ev.`event_address`,ev.`event_city`,ev.`event_state`,ev.`event_country`,ev.`event_zipcode`,ev.`booking_price`,ev.`is_paid`,ev.`event_difficulty`,ev.`event_package`,ev.`event_support`,ev.`event_timing`,ev.`event_medals`,ev.`event_start_time`,ev.`event_end_time`,ev.`event_offer_start`,ev.`event_offer_end`,ev.`event_discount_type`,ev.`event_discount_amount`,ev.`event_image`,ev.`status`, ev_c.name AS event_category FROM `events` AS ev LEFT JOIN `categories` AS ev_c ON ev_c.id=ev.event_category_id WHERE ev.event_category_id='".$category_id."' AND ev.status='1'";
               $query_result = $con->query($query);
               $resulted_data = array();
               while ($rows = $query_result->fetch_assoc()) {
                    if($rows['event_image'] != ""){
                         $rows['event_image'] = SITE_ROOT."/uploads/events/" . $rows['event_image'];
                    } else {
                         $rows['event_image'] = "";
                    }
                    $rows['event_name'] = $obj->replaceUnwantedChars($rows['event_name'], 2);
                    $rows['event_description'] = $obj->replaceUnwantedChars($rows['event_description'], 2);
                    $event_location = explode("::", $rows['event_location']);
                    if(isset($event_location[0])){
                         $rows['event_location_lat'] = $obj->replaceUnwantedChars($event_location[0], 2);
                    }
                    if(isset($event_location[1])){
                         $rows['event_location_long'] = $obj->replaceUnwantedChars($event_location[1], 2);
                    }
                    unset($rows['event_location']);
                    $rows['event_address'] = $obj->replaceUnwantedChars($rows['event_address'], 2);
                    $rows['event_city'] = $obj->replaceUnwantedChars($rows['event_city'], 2);
                    $rows['event_state'] = $obj->replaceUnwantedChars($rows['event_state'], 2);
                    $rows['event_country'] = $obj->replaceUnwantedChars($rows['event_country'], 2);
                    $rows['event_zipcode'] = $obj->replaceUnwantedChars($rows['event_zipcode'], 2);
                    $rows['booking_price'] = $obj->replaceUnwantedChars($rows['booking_price'], 2);
                    $rows['signup_more_time'] = $rows['is_paid'];
                    $rows['event_package'] = $obj->replaceUnwantedChars($rows['event_package'], 2);
                    $rows['event_support'] = $obj->replaceUnwantedChars($rows['event_support'], 2);
                    $rows['event_timing'] = $obj->replaceUnwantedChars($rows['event_timing'], 2);
                    $rows['event_medals'] = $obj->replaceUnwantedChars($rows['event_medals'], 2);
                    $rows['event_discount_amount'] = $obj->replaceUnwantedChars($rows['event_discount_amount'], 2);
                    if($rows['event_start_time'] != "0000-00-00 00:00:00" && $rows['event_start_time'] != "1970-01-01 00:00:00"){
                         $rows['event_start_time'] = date('D, M d h:ia', strtotime($rows['event_start_time']));
                    } else {
                         $rows['event_start_time'] = "";
                    }
                    if($rows['event_end_time'] != "0000-00-00 00:00:00" && $rows['event_end_time'] != "1970-01-01 00:00:00"){
                         $rows['event_end_time'] = date('D, M d h:ia', strtotime($rows['event_end_time']));
                    } else {
                         $rows['event_end_time'] = "";
                    }
                    if($rows['event_offer_start'] != "0000-00-00 00:00:00" && $rows['event_offer_start'] != "1970-01-01 00:00:00"){
                         $rows['event_offer_start'] = date('Y-m-d H:i:s', strtotime($rows['event_offer_start']));
                    } else {
                         $rows['event_offer_start'] = "";
                    }
                    if($rows['event_offer_end'] != "0000-00-00 00:00:00" && $rows['event_offer_end'] != "1970-01-01 00:00:00"){
                         $rows['event_offer_end'] = date('Y-m-d H:i:s', strtotime($rows['event_offer_end']));
                    } else {
                         $rows['event_offer_end'] = "";
                    }
                    $resulted_data[] = $rows;
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
     echo $result;
?>
