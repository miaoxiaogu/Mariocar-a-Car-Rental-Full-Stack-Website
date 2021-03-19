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
include_once '../objects/systemInfo.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));

if(
    !isset($data->newMemberFee) &&
    !isset($data->newTimePoint1) &&
    !isset($data->newTimePoint2) &&
    !isset($data->newDiscount1) &&
    !isset($data->newDiscount2) &&
    !isset($data->newDiscount3) &&
    !isset($data->newOverAge)
){
    http_response_code(200);
    echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
}
else{

  $info = new systemInfo($db);
  // echo $info->checkExists();
  if($info->checkExists()!=='true'){
    http_response_code(200);
    echo json_encode(array("status"=>"fail","message"=>"System Data Not Exists."));
    return;
  }


  $fee = null;
  $time1 = null;
  $time2 = null;
  $discount1 = null;
  $discount2 = null;
  $discount3 = null;
  $overAge = null;

  //NEW MEMBER FEE.
  if(isset($data->newMemberFee) && $data->newMemberFee!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newMemberFee)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Member Fee Value Invalid. Must be Integer"));
          return;
    }else if(is_int($data->newMemberFee+0) && $data->newMemberFee>=0){
      $fee = $data->newMemberFee;
    }else {
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Member Fee Value Invalid."));
          return;
    }
  }

  // NEW TIME POINT 1
  if(isset($data->newTimePoint1) && $data->newTimePoint1!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newTimePoint1)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Time Point_1 value Invalid. Must be Integer"));
          return;
    }else if(is_int($data->newTimePoint1+0) && $data->newTimePoint1>=0){
      $time1 = $data->newTimePoint1;
    }else{
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Time Point_1 value Invalid. Must be positive number."));
          return;
    }
  }

  //NEW TIME POINT 2
  if(isset($data->newTimePoint2) && $data->newTimePoint2!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newTimePoint2)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Time Point_2 value Invalid. Must be Integer"));
          return;
    }else if(is_int($data->newTimePoint2+0) && $data->newTimePoint2>=0){
      $time2 = $data->newTimePoint2;
    }else if($data->newTimePoint2<0){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Time Point_2 value Invalid. Must be positive number."));
          return;
    }
  }

  // NEW TIME RANGE DISCOUNT 1
  if(isset($data->newDiscount1) && $data->newDiscount1!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newDiscount1)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Time Discount_1 value Invalid. Must be Number"));
          return;
    }else {
      $discount1 = $data->newDiscount1;
    }
  }

  // DISCOUNT 2
  if(isset($data->newDiscount2) && $data->newDiscount2!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newDiscount2)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Time Discount_2 value Invalid. Must be Number"));
          return;
    }else {
      $discount2 = $data->newDiscount2;
    }
  }

  // DISCOUNT 3
  if(isset($data->newDiscount3) && $data->newDiscount3!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newDiscount3)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Time Discount_3 value Invalid. Must be Number"));
          return;
    }else {
      $discount3 = $data->newDiscount3;
    }
  }

  // DISCOUNT OVER 25
  if(isset($data->newOverAge) && $data->newOverAge!==''){
    // echo is_numeric("0");
    if(!is_numeric($data->newOverAge)){
          http_response_code(200);
          echo json_encode(array("status" => "fail","message" => "Over Age Discount value Invalid. Must be Number"));
          return;
    }else {
      $overAge = $data->newOverAge;
    }
  }

  // if($time1>=$time2){
  //   http_response_code(200);
  //   echo json_encode(array("status" => "fail","message" => "Time Point 2 Must Greater than Time Point 1"));
  // }


  if($re = $info->modify($fee,$time1,$time2,$discount1,$discount2,$discount3,$overAge)){
    echo $re;

  }else{
    http_response_code(200);

    echo json_encode(array("status" => "fail","message" => "Function Error."));

  }

}
?>
