<?php
     require_once '../../cmnvalidate.php';
     if (isset($_SESSION['user'])) {
          if ($_SESSION['user']['role_id'] == 0 || $_SESSION['user']['role_id'] == 1) {
               $id = $_REQUEST['id'];
               $updated = date('Y-m-d H:i:s');
               $updated_by = $_SESSION['user']['user_id'];
               $queryupdate = "UPDATE `classes` SET status='2',`updated`='" . $updated . "',`updated_by`='" . $updated_by . "' WHERE `id` = '" . $id . "'";
               if($con->query($queryupdate)) {
                    $queryupdate_in = "UPDATE `class_instructors` SET status='2',`updated`='" . $updated . "',`updated_by`='" . $updated_by . "' WHERE `class_id` = '" . $id . "'";
                    $con->query($queryupdate_in);
                    $queryupdate_st = "UPDATE `class_slot_timings` SET status='2',`updated`='" . $updated . "',`updated_by`='" . $updated_by . "' WHERE `class_id` = '" . $id . "'";
                    $con->query($queryupdate_st);
                    $queryupdate_s = "UPDATE `class_slots` SET status='2',`updated`='" . $updated . "',`updated_by`='" . $updated_by . "' WHERE `class_id` = '" . $id . "'";
                    $con->query($queryupdate_s);
                    $queryupdate_ub = "UPDATE `class_user_bookings` SET status='2',`updated`='" . $updated . "',`updated_by`='" . $updated_by . "' WHERE `class_id` = '" . $id . "'";
                    $con->query($queryupdate_ub);
                    $result['success'] = 1;
                    $result['data'] = 'success';
                    $result['error'] = 0;
                    $result['error_code'] = NULL;
               } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = 'Error In Query';
               }
          }
     }
     $result = json_encode($result);
     echo $result;
?>