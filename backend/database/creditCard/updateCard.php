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
    empty($data->email) ||
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

    //USER REGISTER
    $user = new user($db);
    $user->userEmail=$data->email;
    $id = $user->getUserByEmail();
    if(empty($id)){
      http_response_code(200);
      echo json_encode(array("status" => "fail","message" => "User Email not Exists."));
      return;
    }

    $newCard = new creditCard($db);
    $newCard->cardNumber = $data->card_number;
    $newCard->expDate= $data->card_expire_date;
    $newCard->cardCVI= $data->card_cvi;
    $newCard->cardZIP= $data->card_zipcode;
    $newCard->billStreet= $data->billing_street;
    $newCard->billCity= $data->billing_city;
    $newCard->billState= $data->billing_state;
    $newCard->billZip= $data->billing_zipcode;

    http_response_code(200);
    echo $newCard->update($id);
}
?>
