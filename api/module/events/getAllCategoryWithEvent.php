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
          $query = "SELECT id, name, description FROM categories WHERE type='Events' AND status='1'";
          $query_result = $con->query($query);
          $resulted_data = array();
          while ($rows = $query_result->fetch_assoc()) {
               $rows['name'] = $obj->replaceUnwantedChars($rows['name'], 2);
               $rows['description'] = $obj->replaceUnwantedChars($rows['description'], 2);
               $query_ev = "SELECT ev.`id`, ev.`event_name`,ev.`event_description`,ev.`event_timezone`,ev.`event_location`,ev.`event_address`,ev.`event_city`,ev.`event_state`,ev.`event_country`,ev.`event_zipcode`,ev.`booking_price`,ev.`is_paid`,ev.`event_difficulty`,ev.`event_package`,ev.`event_support`,ev.`event_timing`,ev.`event_medals`,ev.`event_start_time`,ev.`event_end_time`,ev.`event_offer_start`,ev.`event_offer_end`,ev.`event_discount_type`,ev.`event_discount_amount`,ev.`event_image`,ev.`status`, ev_c.name AS event_category FROM `events` AS ev LEFT JOIN `categories` AS ev_c ON ev_c.id=ev.event_category_id WHERE ev.event_category_id='".$rows['id']."' AND ev.status='1'";
               $query_result_ev = $con->query($query_ev);
               $ev_data = array();
               while ($rows_ev = $query_result_ev->fetch_assoc()) {
                    if($rows_ev['event_image'] != ""){
                         $rows_ev['event_image'] = SITE_ROOT."/uploads/events/" . $rows_ev['event_image'];
                    } else {
                         $rows_ev['event_image'] = "";
                    }
                    $rows_ev['event_name'] = $obj->replaceUnwantedChars($rows_ev['event_name'], 2);
                    $rows_ev['event_description'] = $obj->replaceUnwantedChars($rows_ev['event_description'], 2);
                    $event_location = explode("::", $rows_ev['event_location']);
                    if(isset($event_location[0])){
                         $rows_ev['event_location_lat'] = $obj->replaceUnwantedChars($event_location[0], 2);
                    }
                    if(isset($event_location[1])){
                         $rows_ev['event_location_long'] = $obj->replaceUnwantedChars($event_location[1], 2);
                    }
                    unset($rows_ev['event_location']);
                    $rows_ev['event_address'] = $obj->replaceUnwantedChars($rows_ev['event_address'], 2);
                    $rows_ev['event_city'] = $obj->replaceUnwantedChars($rows_ev['event_city'], 2);
                    $rows_ev['event_state'] = $obj->replaceUnwantedChars($rows_ev['event_state'], 2);
                    $rows_ev['event_country'] = $obj->replaceUnwantedChars($rows_ev['event_country'], 2);
                    $rows_ev['event_zipcode'] = $obj->replaceUnwantedChars($rows_ev['event_zipcode'], 2);
                    $rows_ev['booking_price'] = $obj->replaceUnwantedChars($rows_ev['booking_price'], 2);
                    $rows_ev['signup_more_time'] = $rows_ev['is_paid'];
                    $rows_ev['event_package'] = $obj->replaceUnwantedChars($rows_ev['event_package'], 2);
                    $rows_ev['event_support'] = $obj->replaceUnwantedChars($rows_ev['event_support'], 2);
                    $rows_ev['event_timing'] = $obj->replaceUnwantedChars($rows_ev['event_timing'], 2);
                    $rows_ev['event_medals'] = $obj->replaceUnwantedChars($rows_ev['event_medals'], 2);
                    $rows_ev['event_discount_amount'] = $obj->replaceUnwantedChars($rows_ev['event_discount_amount'], 2);
                    if($rows_ev['event_start_time'] != "0000-00-00 00:00:00" && $rows_ev['event_start_time'] != "1970-01-01 00:00:00"){
                         $rows_ev['event_start_time'] = date('D, M d h:ia', strtotime($rows_ev['event_start_time']));
                    } else {
                         $rows_ev['event_start_time'] = "";
                    }
                    if($rows_ev['event_end_time'] != "0000-00-00 00:00:00" && $rows_ev['event_end_time'] != "1970-01-01 00:00:00"){
                         $rows_ev['event_end_time'] = date('D, M d h:ia', strtotime($rows_ev['event_end_time']));
                    } else {
                         $rows_ev['event_end_time'] = "";
                    }
                    if($rows_ev['event_offer_start'] != "0000-00-00 00:00:00" && $rows_ev['event_offer_start'] != "1970-01-01 00:00:00"){
                         $rows_ev['event_offer_start'] = date('Y-m-d H:i:s', strtotime($rows_ev['event_offer_start']));
                    } else {
                         $rows_ev['event_offer_start'] = "";
                    }
                    if($rows_ev['event_offer_end'] != "0000-00-00 00:00:00" && $rows_ev['event_offer_end'] != "1970-01-01 00:00:00"){
                         $rows_ev['event_offer_end'] = date('Y-m-d H:i:s', strtotime($rows_ev['event_offer_end']));
                    } else {
                         $rows_ev['event_offer_end'] = "";
                    }
                    $ev_data[] = $rows_ev;
               }
               $rows['events'] = $ev_data;
               $resulted_data[] = $rows;
          }
          $result['success'] = 1;
          $result['data'] = $resulted_data;
          $result['error'] = 0;
          $result['error_code'] = NULL;
     }
     $result = json_encode($result);
     echo $result;
?>
