<?php
// required headers
// ini_set('display_errors',1);
// error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Headers: x-requested-with, content-type');

// get database connection
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));

//DATA INPUT REQUIREMENTS
if(
    empty($data->email) ||
    empty($data->password)

){
    // echo json_encode($data);
    // set response code - 200 bad request
    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
}
else{
    //CHECK USER_INFO TABLE
    $query = 'SELECT USER_EMAIL,USER_PASSWORD FROM USER_INFO WHERE USER_EMAIL="'.$data->email.'";';
    // echo $query;
    // echo '<br>';
    $stmt = $db->prepare($query);
    $result=$stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row && count($row)==2){
      // echo "checking user;";
      //CHCK PASSWORD FOR USER
      if($data->password == $row['USER_PASSWORD']){
        // echo "CORRECT PASSWORD FOR USER";
        http_response_code(200);
        echo json_encode(array("status" => "success", "role" => "user"));
        return;
      }else{
        http_response_code(200);
        echo json_encode(array("status" => "fail", "role" => "user", "message" => "Wrong pass."));
        return;
      }
    }else{
      $query = 'SELECT MANAGER_EMAIL,MANAGER_PASSWORD FROM MANAGER_INFO WHERE MANAGER_EMAIL="'.$data->email.'";';
      // echo $query;
      // echo '<br>';
      $stmt = $db->prepare($query);
      $result=$stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      // echo json_encode($row);
      if($row && count($row)==2){
        // echo "checking managers;";
        //CHECK PASS FOR MANAGER
        if($data->password == $row['MANAGER_PASSWORD']){
          // echo "CORRECT PASSWORD FOR MANAGER";
          http_response_code(200);
          echo json_encode(array("status" => "success", "role" => "manager"));
          return;
        }else{
          http_response_code(200);
          echo json_encode(array("status" => "fail", "role" => "manager", "message" => "Wrong pass."));
          return;
        }
      }else{
        // echo 'NOT MANAGER';
        http_response_code(200);
        // tell the user
        echo json_encode(array("status" => "fail", "role" => "none", "message" => "Email not exist."));
      }
    }
}
?>
