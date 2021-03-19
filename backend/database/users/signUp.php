<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: x-requested-with, content-type');

// get database connection
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/driverLicense.php';
include_once '../objects/creditCard.php';

$database = new Database();
$db = $database->getConnection();


// get posted data
$data = json_decode(file_get_contents("php://input"));

//DATA INPUT REQUIREMENTS
if(
    //USER INFO PART
    empty($data->first_name) ||
    empty($data->last_name) ||
    empty($data->password) ||
    empty($data->email) ||
    //DRIVER LICENSE PART
    empty($data->driver_license_number) ||
    empty($data->date_of_birth) ||
    empty($data->driver_license_state) ||
    empty($data->drive_license_expire_date) ||
    empty($data->address_street) ||
    empty($data->address_state) ||
    empty($data->address_city) ||
    empty($data->address_zipcode) ||
    //CREDIT CARD PART
    empty($data->card_number) ||
    empty($data->card_expire_date) ||
    empty($data->card_cvi) ||
    empty($data->card_zipcode) ||
    empty($data->billing_street) ||
    empty($data->billing_state) ||
    empty($data->billing_city) ||
    empty($data->billing_zipcode)

){
    // echo json_encode($data);
    // set response code - 200 bad request
    http_response_code(200);
    // tell the user
    echo json_encode(array("status" => "fail","message" => "Data is incomplete."));
}
else{



    //DATA RESULT
    $result=array();
    $result["user_reg"] = array();
    $result["driver_reg"] = array();
    $result["card_reg"] = array();
    $result["license_set_user"] = array();
    $result["card_set_owner"] = array();

    //USER REGISTER
    $newuser = new user($db);
    $newuser->userEmail = $data->email;
    $newuser->password = $data->password;
    $newuser->userfirst = $data->first_name;
    $newuser->userlast = $data->last_name;

    $newdriver = new driverLicense($db);
    $newdriver->licenseNumber = $data->driver_license_number;
    $newdriver->licenseState =  $data->driver_license_state;
    $newdriver->birthDate = $data->date_of_birth ;
    $newdriver->expDate = $data->drive_license_expire_date ;
    $newdriver->addrState = $data->address_state ;
    $newdriver->addrCity = $data->address_city ;
    $newdriver->addrStreet = $data->address_street ;
    $newdriver->addrZip = $data->address_zipcode ;

    $newCard = new creditCard($db);
    $newCard->cardNumber = $data->card_number;
    $newCard->expDate= $data->card_expire_date;
    $newCard->cardCVI= $data->card_cvi;
    $newCard->cardZIP= $data->card_zipcode;
    $newCard->billStreet= $data->billing_street;
    $newCard->billCity= $data->billing_city;
    $newCard->billState= $data->billing_state;
    $newCard->billZip= $data->billing_zipcode;

    //CHECK FOR VALIDATION
    $temp_error = array();
    $fail = false;
    $temp_error['status']='fail';
    $temp_error['message'] = array();

    if($newuser->getUserByEmail()){
      $fail = true;
      array_push($temp_error['message'],'User Email Already Exists.');
    }
    if($newdriver->getIDbyNumber()){
      $fail = true;
      array_push($temp_error['message'],'Driver License Number Already Exists.');
    }
    if($newCard->getIDbyNumber()){
      $fail = true;
      array_push($temp_error['message'],'Credit Card Already Exists.');
    }
    if($fail){
      http_response_code(200);
      echo json_encode($temp_error);
      return;
    }


    //register new user with basic info
    $temp_result = $newuser->register();
    if(json_decode($temp_result,true)['status']=='fail'){
      http_response_code(200);
      echo json_encode(array("status" => "fail",  "message" => "Failed on register user.","function"=>json_decode($temp_result,true)));
      return;
    }
    array_push($result["user_reg"],json_decode($temp_result));


    // REGISTER FOR DRIVER
    $temp_result = $newdriver->register();
    if(json_decode($temp_result,true)['status']=='fail'){
      http_response_code(200);
      echo json_encode(array("status" => "fail",  "message" => "Failed on register driver license.","function"=>json_decode($temp_result,true)));
      return;
    }
    array_push($result["driver_reg"],json_decode($temp_result));

    // INSERT DRIVER INTO USER
    // $temp_result = $newuser->setDriverLicense($data->driver_license_number);
    // if(json_decode($temp_result,true)['status']=='fail'){
    //   http_response_code(200);
    //   echo json_encode(array("status" => "fail",  "message" => "Failed on set driver license on user.","function"=>json_decode($temp_result,true)));
    //   return;
    // }
    // array_push($result["user_set_license"],json_decode($temp_result));

    //TODO SET USER ID OF DRIVER
    $temp_result = $newdriver->setUserID($newuser->getUserByEmail());
    if(json_decode($temp_result,true)['status']=='fail'){
      http_response_code(200);
      echo json_encode(array("status" => "fail",  "message" => "Failed on set owner of driver license.","function"=>json_decode($temp_result,true)));
      return;
    }
    array_push($result["license_set_user"],json_decode($temp_result));

    // http_response_code(200);
    // echo json_encode(array("status"=>"success","message"=>$result));


    // REGISTER FOR CREDIT CARD
    $temp_result = $newCard->register();
    if(json_decode($temp_result,true)['status']=='fail'){
      http_response_code(200);
      echo json_encode(array("status" => "fail",  "message" => "Failed on register new card.","function"=>json_decode($temp_result,true)));
      return;
    }
    array_push($result["card_reg"],json_decode($temp_result));

    // INSERT USER ID INTO CREDIT CARD
    $temp_result = $newCard->setOwner($newuser->getUserByEmail());
    if(json_decode($temp_result,true)['status']=='fail'){
      http_response_code(200);
      echo json_encode(array("status" => "fail",  "message" => "Failed on set owner of new card.","function"=>json_decode($temp_result,true)));
      return;
    }
    array_push($result["card_set_owner"],json_decode($temp_result));

    http_response_code(200);
    echo json_encode(array("status"=>"success","message"=>$result));

}
?>
