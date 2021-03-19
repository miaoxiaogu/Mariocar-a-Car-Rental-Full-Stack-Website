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

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));
//TEST
// echo file_get_contents("php://input").'<br>';
//DATA INPUT REQUIREMENTS
if(
    empty($data->email) ||
    empty($data->oldPass) ||
    empty($data->newPass)
){
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Data (email|old Pass|new Pass) is incomplete."));
    return;
}



  //TODO CHECK EMAIL EXISTANCE
  $user = new user($db);
  $user->userEmail = $data->email;
  $user->password = $data->oldPass;
  if(!$user->getUserByEmail()){
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "User Not Exists."));
        return;
  }
  if(json_decode($user->validation(),true)['status']=='fail'){
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" =>'Password wrong.'));
        return;
  }
  http_response_code(200);
  echo $user->changePass($data->newPass);



?>
