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


// MAKE SQL QUERY
// GET ALL TABLES NAME
$sql = "SHOW TABLES;";

$stmt = $conn->prepare($sql);

$stmt->execute();

if($stmt->rowCount() > 0){
    // CREATE TABLE ARRAY
    $tables_array = [];

    while($table_name = $stmt->fetch()[0]){
      // echo $table_name[0];
      $items = ['tableName' => $table_name];
      $items['data'] = array();
      $items_data = ['colunm'=> 'c1'];
      array_push($items["data"],$items_data);
      array_push($tables_array, $items);
    }
    //SHOW POST/POSTS IN JSON FORMAT
    echo json_encode($tables_array);


}
else{
    //IF THER IS NO POST IN OUR DATABASE
    echo json_encode(['message'=>'No post found']);
}
?>
