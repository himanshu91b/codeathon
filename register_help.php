<?php


require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['nc_id']) && isset($_POST['provider_id']) && isset($_POST['p_cap']) && isset($_POST['comment']) ) 
{

    // receiving the post params
    $nc_id = $_POST['nc_id'];
    $provider_id = $_POST['provider_id'];
    $p_cap = $_POST['p_cap'];
    $comment = $_POST['comment'];
    

    $result = $db->addhelp($nc_id, $provider_id, $p_cap, $comment);
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

