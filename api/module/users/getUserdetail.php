<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
$userid = $_REQUEST['userid'];
$checkaccess = true;
if (isset($_REQUEST['is_mobile_api'])) {
        if ($result['success'] == 1) {
                $bydirect = true;
        } else {
                $bydirect = false;
        }
        $params = array();
        $userid = $_REQUEST['userid'];
        $checkaccess = false;
}

if ($bydirect) {
        $condition = "";
        if(isset($_SESSION['user'])){
                if($_SESSION['user']['role_id'] == 0){
                        $condition = "";
                } else if($_SESSION['user']['role_id'] == 1){
                        $condition = "and spe_id=".$_SESSION['user']['spe_id'];
                } 
        }
        
        // Get user detail
        $query = "SELECT * FROM users where role_id != 0 and id=$userid $condition";
        $query_result = $con->query($query);
        if($query_result->num_rows > 0){
                $resulted_data = $query_result->fetch_assoc();
                $resulted_data['aboutus'] = $obj->replaceUnwantedChars( $resulted_data['aboutus'],2);
                $resulted_data['address'] = $obj->replaceUnwantedChars( $resulted_data['address'],2);
                $profileimage = SITE_ROOT."/uploads/users/no-image.png";
                if($resulted_data['profile_image'] != ""){
                     $profileimage = SITE_ROOT."/uploads/users/".$resulted_data['profile_image'];
                }
                $resulted_data['profile_image'] = $profileimage;
                
                $coverlogo = SITE_ROOT."/uploads/users/cover/no-cover-image.png";
                if($resulted_data['profile_cover'] != ""){
                     $coverlogo = SITE_ROOT."/uploads/users/cover/".$resulted_data['profile_cover'];
                }
                
                $resulted_data['profile_cover'] = $coverlogo;
                
                $goals = array();
                $queryspec = "SELECT usergoal.goal_id AS id,g.goalname FROM  users_goals usergoal
                                   left join goals g on g.id = usergoal.goal_id
                                   where usergoal.user_id=$userid";
                $query_result = $con->query($queryspec);
                if($query_result->num_rows > 0){
                     while($row = $query_result->fetch_assoc()){
                          $goals[] = $row;
                     }   
                }
                $resulted_data['goals'] = $goals;
                
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
}else if(isset($_REQUEST['is_mobile_api'])){
        echo $result;
}
?>
