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
          $userid = $_REQUEST['userid'];
     }
     if ($bydirect) { 
          if ($_SESSION['user']['userRole'] == 'SPEadmin'){
              $query = "SELECT ev.id, ev.event_name, ev_c.name AS event_category, ev.event_start_time, ev.event_end_time, ev.event_image, ev.status FROM `events` AS ev LEFT JOIN `categories` AS ev_c ON ev_c.id=ev.event_category_id WHERE ev.created_by_user_id = '".$_SESSION['user']['user_id']."' ";
          } else {
              $query = "SELECT ev.id, ev.event_name, ev_c.name AS event_category, ev.event_start_time, ev.event_end_time, ev.event_image, ev.status FROM `events` AS ev LEFT JOIN `categories` AS ev_c ON ev_c.id=ev.event_category_id ";
          }
          $query_result = $con->query($query);
          $resulted_data = array();
          while ($rows = $query_result->fetch_assoc()) {
               if($rows['event_image'] != ""){
                    $rows['event_image'] = SITE_ROOT."/uploads/events/" . $rows['event_image'];
               } else {
                    $rows['event_image'] = "";
               }
               $rows['event_name'] = $obj->replaceUnwantedChars($rows['event_name'], 2);
               $rows['event_category'] = $obj->replaceUnwantedChars($rows['event_category'], 2);
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
