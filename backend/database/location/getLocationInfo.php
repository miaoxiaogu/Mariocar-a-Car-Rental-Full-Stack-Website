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

$data = json_decode(file_get_contents("php://input"));

if(empty($data->locationID)){
  // echo $data->locationID;
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "Data (location ID) is incomplete."));
  return;
}
$location = new parkLocation($db);

http_response_code(200);
echo $location->getInfoByID($data->locationID);
// if($data == 'success')

?>
