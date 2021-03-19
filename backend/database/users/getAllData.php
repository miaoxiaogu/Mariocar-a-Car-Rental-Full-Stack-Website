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
include_once '../objects/user.php';
include_once '../objects/creditCard.php';
include_once '../objects/driverLicense.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));
//TEST
// echo file_get_contents("php://input").'<br>';
//DATA INPUT REQUIREMENTS
if(
    empty($data->email)
){
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Data (email) is incomplete."));
    return;
}
  $user = new user($db);
  $card = new creditCard($db);
  $driver = new driverLicense($db);
  $result = array();
  $result['status']='success';
  // $result['CreditCard'];


  $user->userEmail = $data->email;
  $id = $user->getUserByEmail();
  if(empty($id)){
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "User Email Not Exists."));
        return;
  }
  $result['id']=$id;
  // array_push($result['CreditCard'], json_decode($card->getDataByID($id),true));
  $result['User'] = json_decode($user->getDataByID($id),true);

  $result['CreditCard'] = json_decode($card->getDataByID($id),true);
  $result['DriverLicense'] = json_decode($driver->getDataByID($id),true);
  http_response_code(200);
  echo json_encode($result);



?>
