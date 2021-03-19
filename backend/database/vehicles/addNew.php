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
include_once '../objects/typeInfo.php';
include_once '../objects/parkLocation.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));
//TEST
// echo file_get_contents("php://input").'<br>';
//DATA INPUT REQUIREMENTS
if(
    empty($data->vehiclePlate)||
    empty($data->vehicleType)||
    empty($data->vehicleCondition)||
    empty($data->vehicleBrand)||
    empty($data->vehicleModel)||
    empty($data->vehicleMile)||
    empty($data->vehicleYear)||
    empty($data->vehicleLocation)||
    empty($data->vehicleRegistration)

){
    // echo json_encode($data);
    // set response code - 200 bad request
    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
}
else{
  // VALIDATION OF LOCATION ID
  $l = new parkLocation($db);
  $l->locationID = $data->vehicleLocation;
  if(!$l->getNameByID()){
      // echo json_encode($data);
      // set response code - 200 bad request
      http_response_code(200);
      // tell the user
      echo json_encode(array("status" => "fail","message" => "Location Not Exists."));
      return;
  }
  
  // VALIDATION OF TYPE
  $t = new typeInfo($db);
  $t->typeName = $data->vehicleType;
  if(!$t->getIDByName()){
      // echo json_encode($data);
      // set response code - 200 bad request
      http_response_code(200);
      // tell the user
      echo json_encode(array("status" => "fail","message" => "Vehicle type invalid."));
      return;
  }

  $v = new vehicle($db);
  $v->vehiclePlate=$data->vehiclePlate;
  $v->vehicleType=$data->vehicleType;
  $v->vehicleCondition=$data->vehicleCondition;
  $v->vehicleBrand=$data->vehicleBrand;
  $v->vehicleModel=$data->vehicleModel;
  $v->vehicleMile=$data->vehicleMile;
  $v->vehicleYear=$data->vehicleYear;
  $v->vehicleLocation=$data->vehicleLocation;
  $v->vehicleRegistration=$data->vehicleRegistration;


  if($re = $v->addNew()){

    http_response_code(200);
    echo $re;

  }else{
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Function Error."));

  }

}
?>
