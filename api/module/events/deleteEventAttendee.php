<?php
     require_once '../../cmnvalidate.php';
     if (isset($_SESSION['user'])) {
          if (($_SESSION['user']['role_id'] == 0 && isset($_REQUEST['id'])) || ($_SESSION['user']['role_id'] == 1 && isset($_REQUEST['id']))) {
               $id = $_REQUEST['id'];
               $queryupdate = "DELETE FROM `events_userbookinglists` WHERE `id` = '" . $id . "'";
               $query_result = $con->query($queryupdate);
               $result['success'] = 1;
               $result['data'] = 'success';
               $result['error'] = 0;
               $result['error_code'] = NULL;
          }
     }
     $result = json_encode($result);
     echo $result;
?>