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
               $query = "SELECT c.`id`,c.`spe_id`,c.`category_id`,c.`name`,c.`description`,c.`timezone`,c.`latitude`,c.`longitude`,c.`address`,c.`city`,c.`state`,c.`country`,c.`zipcode`,c.`is_paid`,c.`price`,c.`image`,c.`banner_image`,c.`discount_type`,c.`discount_amount`,c.`offer_start_date`,c.`offer_end_date`,c.`status`,spe.`name` as spe_name, spe.`phone` as spe_phone, spe.`website` as spe_website, spe.`email` as spe_email, spe.`refund_policy` as spe_refund_policy, spe.`privacy_policy` as spe_privacy_policy, cat.name AS event_category FROM classes AS c LEFT JOIN `spe` ON spe.id=c.spe_id LEFT JOIN `categories` AS cat ON cat.id=c.category_id WHERE c.category_id='".$category_id."' AND c.status='1'";
               $query_result = $con->query($query);
               $resulted_data = array();
               while ($rows = $query_result->fetch_assoc()) {
                    $query_in = "SELECT ci.`user_id` as `id`, u.`firstname`, u.`lastname` FROM class_instructors AS ci LEFT JOIN users AS u ON ci.`user_id`=u.`id` WHERE ci.`class_id`='".$rows['id']."' AND ci.`status`='1';";
                    $query_result_in = $con->query($query_in);
                    $instructor = array();
                    while ($row = $query_result_in->fetch_assoc()) {
                         $row['name'] = $row['firstname'] . " " . $row['lastname'];
                         unset($row['firstname']);
                         unset($row['lastname']);
                         $instructor[] = $row;
                    }
                    $rows['instructor'] = $instructor;
                    if($rows['image'] != ""){
                         $rows['image'] = SITE_ROOT."/uploads/classes/logo/" . $rows['image'];
                    } else {
                         $rows['image'] = "";
                    }
                    if($rows['banner_image'] != ""){
                         $rows['banner_image'] = SITE_ROOT."/uploads/classes/banner/" . $rows['banner_image'];
                    } else {
                         $rows['banner_image'] = "";
                    }
                    $rows['name'] = $obj->replaceUnwantedChars($rows['name'], 2);
                    $rows['description'] = $obj->replaceUnwantedChars($rows['description'], 2);
                    $rows['latitude'] = $obj->replaceUnwantedChars($rows['latitude'], 2);
                    $rows['longitude'] = $obj->replaceUnwantedChars($rows['longitude'], 2);
                    $rows['address'] = $obj->replaceUnwantedChars($rows['address'], 2);
                    $rows['city'] = $obj->replaceUnwantedChars($rows['city'], 2);
                    $rows['state'] = $obj->replaceUnwantedChars($rows['state'], 2);
                    $rows['country'] = $obj->replaceUnwantedChars($rows['country'], 2);
                    $rows['zipcode'] = $obj->replaceUnwantedChars($rows['zipcode'], 2);
                    $rows['price'] = $obj->replaceUnwantedChars($rows['price'], 2);
                    $rows['discount_amount'] = $obj->replaceUnwantedChars($rows['discount_amount'], 2);
                    if($rows['offer_start_date'] != "0000-00-00 00:00:00" && $rows['offer_start_date'] != "1970-01-01 00:00:00"){
                         $rows['offer_start_date'] = date('Y-m-d H:i:s', strtotime($rows['offer_start_date']));
                    } else {
                         $rows['offer_start_date'] = "";
                    }
                    if($rows['offer_end_date'] != "0000-00-00 00:00:00" && $rows['offer_end_date'] != "1970-01-01 00:00:00"){
                         $rows['offer_end_date'] = date('Y-m-d H:i:s', strtotime($rows['offer_end_date']));
                    } else {
                         $rows['offer_end_date'] = "";
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
     if (isset($_REQUEST['is_mobile_api']) || isset($_SESSION['user'])) {
          echo $result;
     }
?>
