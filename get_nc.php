<?php


require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

  $user = $db->getnc();
   if ($user != false) {
       
                echo json_encode($user);

    } else {
        
        $response["error"] = TRUE;
        $response["error_msg"] = "Something wrong. Please try again!";
        echo json_encode($response);
    }
?>

