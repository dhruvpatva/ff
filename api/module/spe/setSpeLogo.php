<?php

require_once '../../cmnvalidate.php';
require_once '../../libs/image/ImageManipulator.php';
$bydirect = true;
if (isset($_SESSION['user'])) {
     if ($bydirect) {
          if (isset($_REQUEST['speid'])) {
               $speid = $_REQUEST['speid'];
               $validation_flag = 0;
               $validation_error_code = NULL;

               if (isset($_FILES['logo'])) {
                    $file_name = time() . $_FILES['logo']['name'];
                    $file_size = $_FILES['logo']['size'];
                    $file_tmp = $_FILES['logo']['tmp_name'];
                    $file_type = $_FILES['logo']['type'];
                    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                         $response = 'Invalid file extension.';
                         $validation_flag = 1;
                         $validation_error_code = 'Invalid file extension.';
                    } else {
                         $manipulator = new ImageManipulator($_FILES['logo']['tmp_name']);
                         // resizing to 200x200
                         $newImage = $manipulator->resample(400, 400);
                         $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/spe/" . $file_name);
                         $logo = $file_name;
                    }
               }
               if ($validation_flag == 0 && $logo != "") {
                    $query = "UPDATE spe SET  logo='$logo' WHERE id = '$speid' ";
                    $query_result = $con->query($query);
                    $logoimage = SITE_ROOT . "/uploads/spe/" . $logo;
                    $result['success'] = 1;
                    $result['data'] = $logoimage;
                    $result['error'] = 0;
                    $result['error_code'] = NULL;
               } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = $validation_error_code;
               }
          } else {
               $result['success'] = 0;
               $result['data'] = NULL;
               $result['error'] = 1;
               $result['error_code'] = 'Required Parameter Are Missing';
          }
     }
     $result = json_encode($result);
     echo $result;
}
?>