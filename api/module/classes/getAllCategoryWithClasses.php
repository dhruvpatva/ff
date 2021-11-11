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
          $query = "SELECT id, name, description FROM categories WHERE type='Classes' AND status='1'";
          $query_result = $con->query($query);
          $resulted_data = array();
          while ($rows = $query_result->fetch_assoc()) {
               $rows['name'] = $obj->replaceUnwantedChars($rows['name'], 2);
               $rows['description'] = $obj->replaceUnwantedChars($rows['description'], 2);
               $category_id = $rows['id'];
               $query_class = "SELECT c.`id`,c.`spe_id`,c.`category_id`,c.`name`,c.`description`,c.`timezone`,c.`latitude`,c.`longitude`,c.`address`,c.`city`,c.`state`,c.`country`,c.`zipcode`,c.`is_paid`,c.`price`,c.`image`,c.`banner_image`,c.`discount_type`,c.`discount_amount`,c.`offer_start_date`,c.`offer_end_date`,c.`status`,spe.`name` as spe_name, spe.`phone` as spe_phone, spe.`website` as spe_website, spe.`email` as spe_email, spe.`refund_policy` as spe_refund_policy, spe.`privacy_policy` as spe_privacy_policy, cat.name AS event_category FROM classes AS c LEFT JOIN `spe` ON spe.id=c.spe_id LEFT JOIN `categories` AS cat ON cat.id=c.category_id WHERE c.category_id='".$category_id."' AND c.status='1'";
               $query_result_class = $con->query($query_class);
               while ($row_class = $query_result_class->fetch_assoc()) {
                    $query_in = "SELECT ci.`user_id` as `id`, u.`firstname`, u.`lastname` FROM class_instructors AS ci LEFT JOIN users AS u ON ci.`user_id`=u.`id` WHERE ci.`class_id`='".$row_class['id']."' AND ci.`status`='1';";
                    $query_result_in = $con->query($query_in);
                    $instructor = array();
                    while ($row = $query_result_in->fetch_assoc()) {
                         $row['name'] = $row['firstname'] . " " . $row['lastname'];
                         unset($row['firstname']);
                         unset($row['lastname']);
                         $instructor[] = $row;
                    }
                    $row_class['instructor'] = $instructor;
                    if($row_class['image'] != ""){
                         $row_class['image'] = SITE_ROOT."/uploads/classes/logo/" . $row_class['image'];
                    } else {
                         $row_class['image'] = "";
                    }
                    if($row_class['banner_image'] != ""){
                         $row_class['banner_image'] = SITE_ROOT."/uploads/classes/banner/" . $row_class['banner_image'];
                    } else {
                         $row_class['banner_image'] = "";
                    }
                    $row_class['name'] = $obj->replaceUnwantedChars($row_class['name'], 2);
                    $row_class['description'] = $obj->replaceUnwantedChars($row_class['description'], 2);
                    $row_class['latitude'] = $obj->replaceUnwantedChars($row_class['latitude'], 2);
                    $row_class['longitude'] = $obj->replaceUnwantedChars($row_class['longitude'], 2);
                    $row_class['address'] = $obj->replaceUnwantedChars($row_class['address'], 2);
                    $row_class['city'] = $obj->replaceUnwantedChars($row_class['city'], 2);
                    $row_class['state'] = $obj->replaceUnwantedChars($row_class['state'], 2);
                    $row_class['country'] = $obj->replaceUnwantedChars($row_class['country'], 2);
                    $row_class['zipcode'] = $obj->replaceUnwantedChars($row_class['zipcode'], 2);
                    $row_class['price'] = $obj->replaceUnwantedChars($row_class['price'], 2);
                    $row_class['discount_amount'] = $obj->replaceUnwantedChars($row_class['discount_amount'], 2);
                    if($row_class['offer_start_date'] != "0000-00-00 00:00:00" && $row_class['offer_start_date'] != "1970-01-01 00:00:00"){
                         $row_class['offer_start_date'] = date('Y-m-d H:i:s', strtotime($row_class['offer_start_date']));
                    } else {
                         $row_class['offer_start_date'] = "";
                    }
                    if($row_class['offer_end_date'] != "0000-00-00 00:00:00" && $row_class['offer_end_date'] != "1970-01-01 00:00:00"){
                         $row_class['offer_end_date'] = date('Y-m-d H:i:s', strtotime($row_class['offer_end_date']));
                    } else {
                         $row_class['offer_end_date'] = "";
                    }
                    $rows['classes'][] = $row_class;
               }
               $resulted_data[] = $rows;
          }
          $result['success'] = 1;
          $result['data'] = $resulted_data;
          $result['error'] = 0;
          $result['error_code'] = NULL;
     }
     $result = json_encode($result);
     if (isset($_REQUEST['is_mobile_api']) || isset($_SESSION['user'])) {
          echo $result;
     }
?>
