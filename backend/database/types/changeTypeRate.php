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
include_once '../objects/typeInfo.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));
//TEST
// echo file_get_contents("php://input").'<br>';
//DATA INPUT REQUIREMENTS
if(
    empty($data->typeName)||
    empty($data->newRate)

){
    // echo json_encode($data);
    // set response code - 200 bad request
    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
}
else{
  if($data->newRate<0){

    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "New Rate is Invalid."));
    return;
  }




  $type = new typeInfo($db);
  $type->typeName = $data->typeName;
  $newRate = $data->newRate;

  if(!$type->getIDByName()){

    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "Vehicle type Not Exists."));
    return;
  }
  if($result  = $type->changeRate($newRate)){
    http_response_code(200);
    echo $result;
    return;
  }
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Function Error."));

}
?>
