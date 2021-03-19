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
include_once '../objects/order.php';
include_once '../objects/user.php';
include_once '../objects/vehicle.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(empty($data->user_email)||
    empty($data->regist_start_time)||
    empty($data->regist_end_time)||
    empty($data->vehicle_id)
){
  // echo $data->locationID;
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
  return;
}
//CHECK IF USER Exists
$user = new user($db);
$user->userEmail = $data->user_email;
$user_id = $user->getUserByEmail();
if(empty($user_id)){
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "User Email not exists."));
  return;
}
//CHECK IF VEHICLE Exists
$vehicle = new vehicle($db);
$vehicle_plate = $vehicle->getPlateByID($data->vehicle_id);
if(empty($vehicle_plate)){
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "Vehicle not exists."));
  return;
}
//GET USER ID
//$user_id;
//GET VEHICLE LICENSE PLATE
//$vehicle_plate;
//GET VEHICLE LOCATION
$location_id = $vehicle->getLocation($data->vehicle_id);

$order = new order($db);
$order->register_start =$data->regist_start_time;
$order->register_end =$data->regist_end_time;
$order->user_ID = $user_id;
$order->license_plate = $vehicle_plate;
$order->park_location = $location_id;
http_response_code(200);
echo $order->addNew();



?>
