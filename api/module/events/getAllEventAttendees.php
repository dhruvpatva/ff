<?php
     require_once '../../cmnvalidate.php';
     $bydirect = true;
     $query;
     if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
          $id = $_REQUEST['id'];
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
               if ($_SESSION['user']['userRole'] == 'admin' || $_SESSION['user']['userRole'] == 'SPEadmin') { 
                    $query = "SELECT evub.id, usr.firstname, usr.lastname, evub.registration_date, evub.status FROM events_userbookinglists AS evub LEFT JOIN users AS usr ON usr.id=evub.user_id WHERE evub.event_id='".$id."'";
               }
               $query_result = $con->query($query);
               $resulted_data = array();
               while ($rows = $query_result->fetch_assoc()) {
                    $resulted_data[] = $rows;
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
     $result = json_encode($result);
     if ($_SESSION['user']['userRole'] == 'admin' || $_SESSION['user']['userRole'] == 'SPEadmin') {
          echo $result;
     }
?>
