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
    empty($data->email)
){
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Data (email) is incomplete."));
    return;
}

if(
    empty($data->newFirst) &&
      empty($data->newLast)
){
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Data (first|last) is incomplete."));
    return;
}


  //TODO CHECK EMAIL EXISTANCE
  $user = new user($db);
  $user->userEmail = $data->email;
  if(!$user->getUserByEmail()){
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "User Not Exists."));
        return;
  }
  $first = (empty($data->newFirst)) ? '0' : $data->newFirst;
  $last = (empty($data->newLast)) ? '0' : $data->newLast;

  $result = array('status'=>'success');
  $result['message']=array();
  // echo "-$first-$last-";
  if($first){
    //TODO CHANGE FIRST NAME
    array_push($result['message'],json_decode($user->updateFirstName($first),true));
  }
  if($last){
    //TODO CHANGE LAST NAME
    array_push($result['message'],json_decode($user->updateLastName($last),true));
  }

  http_response_code(200);
  echo json_encode($result);
    // echo json_encode(array("status" => "fail","message" => "Function Error."));



?>
