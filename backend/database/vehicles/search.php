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
include_once '../objects/vehicle.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));


// GET SEARCH TYPE
if(
    empty($data->search_type)
){
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
  return;
}

// SEARCH BY LOCATION
if($data->search_type=='location'){
    if(empty($data->location_state) ||
        empty($data->location_city)
    ){
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
        return;
    }
    $result = array();
    $result['status']='success';
    $result['vehicle_list'] = array();
    $query = 'SELECT LOCATION_STREET,LOCATION_CITY,LOCATION_STATE,LOCATION_ZIPCODE,TYPE_NAME,VEHICLE_ID,VEHICLE_BRAND,VEHICLE_MODEL,TYPE_RATE FROM VEHICLE_INFO,TYPE_INFO,PARK_LOCATION WHERE VEHICLE_INFO.VEHICLE_TYPE=TYPE_INFO.TYPE_NAME AND LOCATION_ID = VEHICLE_LOCATION AND VEHICLE_STATUS = "AVAILABLE" AND
              LOCATION_CITY= "'.$data->location_city.'"
              AND
              LOCATION_STATE = "'.$data->location_state.'"
              ORDER BY LOCATION_ID ;';
    $stmt = $db->prepare($query);
    if($stmt->execute()){
        while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($result['vehicle_list'],$rows);
        }
        echo json_encode($result);
        return;
    }else{
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "Connection Error."));
        return;
    }

//SEARCH BY VEHICLE TYPE
}else if($data->search_type=='type'){
    if(empty($data->type_name)){
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
        return;
    }
    $result = array();
    $result['status']='success';
    $result['vehicle_list'] = array();
    $query = 'SELECT LOCATION_STREET,LOCATION_CITY,LOCATION_STATE,LOCATION_ZIPCODE,TYPE_NAME,VEHICLE_ID,VEHICLE_BRAND,VEHICLE_MODEL,TYPE_RATE FROM VEHICLE_INFO,TYPE_INFO,PARK_LOCATION WHERE VEHICLE_INFO.VEHICLE_TYPE=TYPE_INFO.TYPE_NAME AND LOCATION_ID = VEHICLE_LOCATION AND VEHICLE_STATUS = "AVAILABLE" AND
              VEHICLE_TYPE = "'.$data->type_name.'"
              ORDER BY LOCATION_ID ;';
    $stmt = $db->prepare($query);
    if($stmt->execute()){
        while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($result['vehicle_list'],$rows);
        }
        echo json_encode($result);
        return;
    }else{
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "Connection Error."));
        return;
    }


}else if($data->search_type=='both'){
    if(empty($data->type_name)||
        empty($data->location_state)||
        empty($data->location_city)
      ){
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
        return;
    }
    $result = array();
    $result['status']='success';
    $result['vehicle_list'] = array();
    $query = 'SELECT LOCATION_STREET,LOCATION_CITY,LOCATION_STATE,LOCATION_ZIPCODE,TYPE_NAME,VEHICLE_ID,VEHICLE_BRAND,VEHICLE_MODEL,TYPE_RATE FROM VEHICLE_INFO,TYPE_INFO,PARK_LOCATION WHERE VEHICLE_INFO.VEHICLE_TYPE=TYPE_INFO.TYPE_NAME AND LOCATION_ID = VEHICLE_LOCATION AND VEHICLE_STATUS = "AVAILABLE" AND
              VEHICLE_TYPE = "'.$data->type_name.'"
              AND
              LOCATION_CITY= "'.$data->location_city.'"
              AND
              LOCATION_STATE = "'.$data->location_state.'"
              ORDER BY LOCATION_ID ;';
    $stmt = $db->prepare($query);
    if($stmt->execute()){
        while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($result['vehicle_list'],$rows);
        }
        echo json_encode($result);
        return;
    }else{
        http_response_code(200);
        echo json_encode(array("status" => "fail","message" => "Connection Error."));
        return;
    }

}else{
  http_response_code(200);
  echo json_encode(array("status" => "fail","message" => "Search Type Undefined."));
  return;
}
?>
