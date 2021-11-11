<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
$checkaccess = true;
if (isset($_REQUEST['is_mobile_api'])) {
        if ($result['success'] == 1) {
                $bydirect = true;
        } else {
                $bydirect = false;
        }
        $params = array();
        $userid = $_REQUEST['userid'];
}
$allamenities = array();
if ($bydirect) {
        // Get SPE detail
        $speid = @$_REQUEST['speid'];
        $query = "SELECT * FROM spe where id=$speid";
        $query_result = $con->query($query);
        if($query_result->num_rows > 0){
                $resulted_data = $query_result->fetch_assoc();
                $profileimage = SITE_ROOT."/uploads/spe/no-image.png";
                if($resulted_data['logo'] != ""){
                     $profileimage = SITE_ROOT."/uploads/spe/".$resulted_data['logo'];
                }
                $resulted_data['logo'] = $profileimage;
                $coverlogo = SITE_ROOT."/uploads/spe/coverlogo/no-cover-image.png";
                if($resulted_data['coverlogo'] != ""){
                     $coverlogo = SITE_ROOT."/uploads/spe/coverlogo/".$resulted_data['coverlogo'];
                }
                
                $resulted_data['coverlogo'] = $coverlogo;
                $resulted_data['name'] = $obj->replaceUnwantedChars($resulted_data['name'],2);
                $resulted_data['aboutus'] = $obj->replaceUnwantedChars( $resulted_data['aboutus'],2);
                $resulted_data['address'] =  $obj->replaceUnwantedChars( $resulted_data['address'],2);
                $resulted_data['refund_policy'] = $obj->replaceUnwantedChars( $resulted_data['refund_policy'],2);
                $resulted_data['privacy_policy'] = $obj->replaceUnwantedChars( $resulted_data['privacy_policy'],2);
                
                $queryamenities = "SELECT spa.amenity_id AS id,am.name FROM spe_amenities  spa
                                   left join amenities am on am.id = spa.amenity_id 
                                   where spa.spe_id=$speid";
                $query_result = $con->query($queryamenities);
                if($query_result->num_rows > 0){
                     while($row = $query_result->fetch_assoc()){
                          $allamenities[] = $row;
                     }   
                }
                $resulted_data['amenities'] = $allamenities;
                $result['success'] = 1;
                $result['data'] = $resulted_data;
                $result['error'] = 0;
                $result['error_code'] = NULL;
        } else {
                $result['success'] = 0;
                $result['data'] = NULL;
                $result['error'] = 1;
                $result['error_code'] = "SPE Profile Not Found";
        }
}
$result = json_encode($result);
if(isset($_SESSION['user'])){
        echo $result;
}else if(isset($_REQUEST['is_mobile_api'])){
        echo $result;
}
?>
