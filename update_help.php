<?php


require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['help_id'])) {

    // receiving the post params
    $help_id = $_POST['help_id'];
   
   
        $help = $db->update($help_id);
        if ($help) {
            // user stored successfully
            $response["error"] = FALSE;
             echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred!";
            echo json_encode($response);
        }
    }

?>

