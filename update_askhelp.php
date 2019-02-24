<?php


require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['ask_id'])) {

    // receiving the post params
    $ask_id = $_POST['ask_id'];
   
   
        $ask = $db->update_ask($ask_id);
        if ($ask) {
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

