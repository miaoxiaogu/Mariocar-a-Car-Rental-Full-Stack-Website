<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
require '../../database/config/database.php';

$db_connection = new Database();
$conn = $db_connection->getConnection();


$query = "Show tables;" ;

// prepare query statement
$stmt = $conn->prepare($query);

// execute query
$stmt->execute();
$result = array();
while($row = $stmt->fetchColumn()){
  array_push($result,$row);
}
echo json_encode($result);

?>
