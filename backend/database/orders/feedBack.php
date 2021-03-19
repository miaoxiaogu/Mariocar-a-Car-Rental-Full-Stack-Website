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


$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(empty($data->order_id) ||
    empty($data->condition)||
    empty($data->comments)
){
  // echo $data->locationID;
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "Data (Order ID) is incomplete."));
  return;
}
$query = 'UPDATE ORDER_INFO SET COMMENTS = "'.$data->comments.'", VEHICLE_CONDITION="'.$data->condition.'" WHERE ORDER_ID = "'.$data->order_id.'" ;';
$stmt = $db->prepare($query);
if($stmt->execute()){

  http_response_code(200);
  echo  json_encode(array('status'=>'success','action'=>'Update Feedbacks'));
  return;
}
http_response_code(200);
echo  json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>'Update Feedbacks'));
return;
// if($data == 'success')

?>
