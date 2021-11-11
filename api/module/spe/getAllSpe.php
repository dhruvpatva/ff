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
     $limit = 10;
     $pagination = $_REQUEST['pagination'];
     $start = $pagination * $limit;
}

if ($bydirect) {
     if (isset($_REQUEST['is_mobile_api'])) {
          $query = "SELECT * FROM spe where status=1 limit $start,$limit";
     } else {
          $query = "SELECT * FROM spe where status=1";
     }
     $query_result = $con->query($query);
     $resulted_data = array();
     while ($rows = $query_result->fetch_assoc()) {
          $speimage = SITE_ROOT . "/uploads/spe/no-image-spe.png";
          $rows['address'] = $obj->replaceUnwantedChars( $rows['address'],2);
          $rows['refund_policy'] = $obj->replaceUnwantedChars( $rows['refund_policy'],2);
          $rows['privacy_policy'] = $obj->replaceUnwantedChars( $rows['privacy_policy'],2);
          if (isset($rows['logo']) && $rows['logo'] != "") {
               $speimage = SITE_ROOT . "/uploads/spe/" . $rows['logo'];
          }
          $coverlogo = SITE_ROOT."/uploads/spe/coverlogo/no-cover-image.png";
          if($rows['coverlogo'] != ""){
                $coverlogo = SITE_ROOT."/uploads/spe/coverlogo/".$rows['coverlogo'];
          }
          $resulted_data[] = array(
              'id' => $rows['id'],
              'name' => $rows['name'],
              'email' => $rows['email'],
              'logoimage' => $speimage,
              'coverlogo' => $coverlogo,
              'address' => $rows['address'],
              'latitude' => $rows['latitude'],
              'longitude' => $rows['longitude'],
              'phone' => $rows['phone'],
              'website' => $rows['website'],
              'city' => $rows['city'],
              'state' => $rows['state'],
              'country' => $rows['country'],
              'zip' => $rows['zipcode'],
              'refund_policy' => $rows['refund_policy'],
              'privacy_policy' => $rows['privacy_policy'],
              'spe_type' => $rows['spe_type'],
              'created' => $rows['created'],
              'status' => $rows['status'],
              'deals' => 1,
              'offers' => 5
          );
     }
     $result['success'] = 1;
     $result['data'] = $resulted_data;
     $result['error'] = 0;
     $result['error_code'] = NULL;
}
$result = json_encode($result);
if (isset($_SESSION['user'])){
     echo $result;
} else if (isset($_REQUEST['is_mobile_api'])) {
     echo $result;
}
?>
