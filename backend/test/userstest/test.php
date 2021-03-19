<!-- <?php echo exec('whoami'); ?> -->
<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../database/config/setting.php';
require_once '../../database/config/database.php';
require_once '../../database/objects/user.php';
require_once '../../database/objects/creditCard.php';
require_once '../../database/objects/driverLicense.php';
require_once '../../database/objects/vehicle.php';
require_once '../../database/objects/typeInfo.php';
require_once '../../database/objects/parkLocation.php';
require_once '../../database/objects/systemInfo.php';
require_once '../../database/objects/order.php';
$curr_url = $root_url . "/test";
//GET ALL TABLES NAME
// $table_names = file_get_contents($curr_url."/api/getTableName.php");

$database = new Database();
$db = $database->getConnection();

// echo 'users test for getMembership() <br>';
$newuser = new user($db);
$newuser->userEmail = 'driverTest@test.com';
$newuser->password = '1111';
$newuser->userfirst = 'f1';
$newuser->userlast = 'l2';

$newdriver = new driverLicense($db);
$newdriver->licenseNumber = 'newDriver123';
$newdriver->licenseState = 'test' ;
$newdriver->birthDate = '1996-01-01' ;
$newdriver->expDate = '1996-01-01' ;
$newdriver->addrState = 'test' ;
$newdriver->addrCity = 'test' ;
$newdriver->addrStreet = 'test' ;
$newdriver->addrZip = '1234' ;

$newCard = new creditCard($db);
$newCard->cardNumber = '2222';
$newCard->expDate= '2020-10-10';
$newCard->cardCVI= '123456789';
$newCard->cardZIP= '123456789';
$newCard->billStreet= '123456789';
$newCard->billCity= '123456789';
$newCard->billState= '123456789';
$newCard->billZip= '123456789';

$newVehicle = new vehicle($db);
$newVehicle->vehiclePlate = 'SF0001';
$newVehicle->vehicleRegistration = 'cerkjg1243523423';

$newPark = new parkLocation($db);
$newPark->locationName = 'newName';
$newPark->locationStreet = 'newStreet1';
$newPark->locationCity = 'city';
$newPark->locationState = 'state';
$newPark->locationZip = '1234';

$system = new systemInfo($db);
// echo $system->modify('999',23,null,null,null,null,null);
$timezone = 'America/Los_Angeles';

$curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles') );
$end_time = new DateTime("now", new DateTimeZone('America/Los_Angeles') );
date_modify($end_time,'+6 months');

// echo $curr_time->format('Y-m-d h:i');
// echo '<br>';
// echo $end_time->format('Y-m-d h:i');
// echo $newPark->getInfoByID(1);
// echo $newuser->register();
$newOrder = new order($db);
$newOrder->register_start = '2012-10-10';
$newOrder->register_end = '2012-11-10';
$newOrder->user_ID = '1';
$newOrder->license_plate = 'ASD';
$newOrder->park_location = '1';
// echo $newOrder->addNew();
// echo $newOrder->cancel(33);
// echo $newOrder->dropOff(32,'','');
// echo $newOrder->dropOff(28,"condition","comments");
$date1 ="2018-09-01 10:10:00";
$date2 ="2018-09-01 11:40:01";

// $diff = $date2->diff($date1);

// $hours = $diff->h;
// $hours = $hours + ($diff->days*24);
// $minut = $diff->m;
// echo $hours+' hour '+$minut;
// echo timeDiffInHours($date1,$date2);

echo $newOrder->getPrice(33);
// echo age('19960506');
//if return negative: time1 is later than time2;
//return in hours
function timeDiffInHours($str_time1,$str_time2){
  $date1 = new DateTime($str_time1);
  $date2 = new DateTime($str_time2);
  $factor = 1;
  if($date1>$date2){
    $factor = -1;
  }
  $diff = $date2->diff($date1);
  $hours = $diff->h;
  $hours = $hours + ($diff->days*24);
  $minut = $diff->i;
  $hours = $hours + ($minut/60);
  return $hours*$factor;
}

function age($str_time1){
  $curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
  $date1 = new DateTime($str_time1);

  $diff = $date1->diff($curr_time);
  $y = $diff->y;
  return $y;
}

// echo $newCard->getDataByID(1);
// echo $newCard->setOwner(3);
// $t = new typeInfo($db);
// $t->typeName = 'minivan';
// $t->typeRate = '13';
// echo $t->getIDByName();

// echo $newVehicle->getVehicleInfo( '2',false);
// echo $newVehicle->getVehicleInfo('2', '2');
// echo "=>".$newCard->setOwner($newuser->getUserByEmail())."<=";
// echo $newuser->validation();
// echo $newuser->changePass('1111');
// echo $newuser->updateLastName('thisisnewlast');



// $driver_res = $newdriver->register();
// $user_res = $newuser->register();
// $new_driver_number = json_decode($driver_res,true)['number'];
// $insertDriver_res = $newuser->setDriverLicense($new_driver_number);


// echo "<br>driver_res => $driver_res<br>";
// echo "<br>user_res => $user_res<br>";
// echo "<br>new_driver_number => $new_driver_number<br>";
// echo "<br>insertDriver_res => $insertDriver_res<br>";

?>
