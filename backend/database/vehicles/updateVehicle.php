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
include_once '../objects/vehicle.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));
//TEST
// echo file_get_contents("php://input").'<br>';
//DATA INPUT REQUIREMENTS
if(
    empty($data->vehiclePlate)
){
  http_response_code(200);
  // tell the user
  echo json_encode(array("status" => "fail","message" => "Data (plate number) is incomplete."));
  return;
}
if(
    empty($data->vehicleCondition) &&
    empty($data->vehicleLocation) &&
    empty($data->vehicleMile) &&
    empty($data->lastServicedTime)

){
    // echo json_encode($data);
    // set response code - 200 bad request
    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
}
else{
  // $plate = (empty($data->vehiclePlate)) ? 'false' : $data->vehiclePlate;
  $condition = (empty($data->vehicleCondition)) ? '' : $data->vehicleCondition;
  $location = (empty($data->vehicleLocation)) ? '' : $data->vehicleLocation;
  $mile = (empty($data->vehicleMile)) ? '' : $data->vehicleMile;
  $lastuse = (empty($data->lastServicedTime)) ? '' : $data->lastServicedTime;

  $v = new vehicle($db);
  $v->vehiclePlate = $data->vehiclePlate;
  // $result = $v->getVehicleInfo($plate,$tag);
  // if($result){}
  if($re = $v->update($condition,$location,$lastuse,$mile)){
    echo $re;

  }else{
    echo json_encode(array("status" => "fail","message" => "Function Error."));

  }

}
?>
