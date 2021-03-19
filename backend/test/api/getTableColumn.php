<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
require '../../database/config/database.php';

$db_connection = new Database();
$conn = $db_connection->getConnection();

$data = json_decode(file_get_contents("php://input"));
if(empty($data->table_name)){
    http_response_code(400);
    echo json_encode(array("message" => "Data incomplete."));
}else{
  $sql = "SELECT COLUMN_NAME
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE TABLE_NAME = N'".$data->table_name."';";
  // echo $sql;
  $stmt = $conn->prepare($sql);

  $stmt->execute();

  $result = array();

  while($row = $stmt->fetchColumn()){
    array_push($result,$row);
  }
  echo json_encode($result);

}
?>
