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

if(empty($data->order_id)){
  // echo $data->locationID;
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "Data (Order ID) is incomplete."));
  return;
}
$condition = (empty($data->condition))?'':$data->condition;
$comments = (empty($data->comments))?'':$data->comments;
$order = new order($db);
http_response_code(200);
echo $order->dropOff($data->order_id,$condition,$comments);
// if($data == 'success')

?>
