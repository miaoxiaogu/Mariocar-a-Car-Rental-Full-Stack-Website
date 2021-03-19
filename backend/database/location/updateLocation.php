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
    !isset($data->locationID)
){
  http_response_code(200);
  // tell the user
  echo json_encode(array("status" => "fail","message" => "Data (location ID) is incomplete."));
  return;
}
if(
    !isset($data->newCapacity) &&
    !isset($data->newName)
){
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Data (capacity | name) is incomplete."));
}
else{
  $name = null;
  $cap = null;
  //GET NEW CAPACITY , STAYS NULL IF NOT FOUND.
  if(isset($data->newCapacity) && $data->newCapacity!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newCapacity)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Capacity Invalid. Must be Integer"));
          return;
    }else if(is_int($data->newCapacity+0) && $data->newCapacity>=0){
      $cap = $data->newCapacity;
    }else if($data->newCapacity<0){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Capacity Invalid. Must be positive number."));
          return;
    }
  }
  //GET NEW Name

  if(isset($data->newName) && $data->newName!==''){
    $name = $data->newName;
  }

  $park = new parkLocation($db);
  $park->locationID = $data->locationID;
  if($re = $park->updateLocation($name,$cap)){
    echo $re;

  }else{
    http_response_code(200);

    echo json_encode(array("status" => "fail","message" => "Function Error."));

  }

}
?>
