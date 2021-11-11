<?php
require_once '../../cmnvalidate.php';
if (isset($_SESSION['user'])) {
        $userid = $_REQUEST['userid'];
        // Get user detail
        $query = "SELECT users.*,spe.id as speid,spe.name FROM users 
                 left join spe on spe.id = users.spe_id   
                 where users.role_id = 1 and users.id=$userid";
        $query_result = $con->query($query);
        if($query_result->num_rows > 0){
                $resulted_data = $query_result->fetch_assoc();
                $profileimage = SITE_ROOT."/uploads/users/no-image.png";
                if($resulted_data['profile_image'] != ""){
                     $profileimage = SITE_ROOT."/uploads/users/".$resulted_data['profile_image'];
                }
                $resulted_data['profile_image'] = $profileimage;
                $result['success'] = 1;
                $result['data'] = $resulted_data;
                $result['error'] = 0;
                $result['error_code'] = NULL;
        } else {
                $result['success'] = 0;
                $result['data'] = NULL;
                $result['error'] = 1;
                $result['error_code'] = "User Not Found";
        }
}
$result = json_encode($result);
if(isset($_SESSION['user'])){
        echo $result;
}
?>