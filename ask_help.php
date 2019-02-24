<?php


require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['nc_id']) && isset($_POST['provider_id']) && isset($_POST['comment']) ) 
{

    // receiving the post params
    $nc_id = $_POST['nc_id'];
    $provider_id = $_POST['provider_id'];
    $comment = $_POST['comment'];
    

    $result = $db->askhelp($nc_id, $provider_id, $comment);
        if ($result) {
            // user stored successfully
            $response["error"] = FALSE;
            echo json_encode($response);
        
        
        } else 
            {
            $response["error"] = TRUE;
            $response["error_msg"] = "Something wrong";
            echo json_encode($response);
    }
}
?>

