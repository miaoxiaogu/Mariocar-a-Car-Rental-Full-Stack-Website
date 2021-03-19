<?php
// required headers
ini_set('display_errors',1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Headers: x-requested-with, content-type');

// get database connection
include_once '../config/database.php';
include_once '../objects/parkLocation.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));
//TEST
// echo file_get_contents("php://input").'<br>';
//DATA INPUT REQUIREMENTS
if(
    empty($data->locationID)
){
    // echo json_encode($data);
    // set response code - 200 bad request
    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "Data (location ID) is incomplete."));
    return;
}
else{

  $location = new parkLocation($db);
  // $location->typeName = $location->typeName ;
  http_response_code(200);
  echo $location->delete($data->locationID);
}
?>
