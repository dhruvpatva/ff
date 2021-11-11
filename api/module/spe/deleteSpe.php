<?php
require_once '../../cmnvalidate.php';
if(isset($_SESSION['user'])){   
           if($_SESSION['user']['role_id'] == 0){
               if (isset($_REQUEST['speid'])) {
                    $speid = $_REQUEST['speid'];
                    $querydelete= "delete from `spe` WHERE `id` = '" . $speid . "'";
                    $query_result = $con->query($querydelete);
                    $result['success'] = 1;
                    $result['data'] = 'success';
                    $result['error'] = 0;
                    $result['error_code'] = NULL;
               }
           }
}
$result = json_encode($result);
echo $result;
?>