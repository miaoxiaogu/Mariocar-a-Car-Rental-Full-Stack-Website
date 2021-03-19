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
// echo json_encode($data);
//DATA INPUT REQUIREMENTS
if(
    empty($data->email) ||
    empty($data->driver_license_number) ||
    empty($data->date_of_birth) ||
    empty($data->driver_license_state) ||
    empty($data->drive_license_expire_date) ||
    empty($data->address_street) ||
    empty($data->address_state) ||
    empty($data->address_city) ||
    empty($data->address_zipcode)

){
    // echo empty($data->email) ;
    // echo empty($data->driver_license_number) ;
    // echo empty($data->date_of_birth) ;
    // echo empty($data->driver_license_state) ;
    // echo empty($data->drive_license_expire_date) ;
    // echo empty($data->address_street) ;
    // echo empty($data->address_state) ;
    // echo empty($data->address_city) ;
    // echo empty($data->address_zipcode);
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

    $newdriver = new driverLicense($db);
    $newdriver->licenseNumber = $data->driver_license_number;
    $newdriver->licenseState =  $data->driver_license_state;
    $newdriver->birthDate = $data->date_of_birth ;
    $newdriver->expDate = $data->drive_license_expire_date ;
    $newdriver->addrState = $data->address_state ;
    $newdriver->addrCity = $data->address_city ;
    $newdriver->addrStreet = $data->address_street ;
    $newdriver->addrZip = $data->address_zipcode ;


    http_response_code(200);
    echo $newdriver->update($id);
}
?>
