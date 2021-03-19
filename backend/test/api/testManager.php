<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
require '../../database/config/database.php';
require '../../database/objects/manager.php';

$db_connection = new Database();
$conn = $db_connection->getConnection();

$manager = new manager($conn);

if($result = $manager->getManagers()){
  http_response_code(200);
  echo json_encode($result);
}else{
  http_response_code(503);
  echo json_encode(array("message" => "Failed."));
}
?>
