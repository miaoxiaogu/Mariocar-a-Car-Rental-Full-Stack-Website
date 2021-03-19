
<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
  ini_set('display_errors',1);
  error_reporting(E_ALL);
  require_once '../../database/config/setting.php';
  $api_url = $root_url . "/database/users";
  if(isset($_POST['submit']))
  {
     sign_up();
  }

  function sign_up(){
    global $api_url;
    $data = array();
    $data['first_name']=$_POST['first'];
    $data['last_name']=$_POST['last'];
    $data['password']=$_POST['pass'];
    $data['email']=$_POST['email'];
    $data['driver_license_number']=$_POST['driver_number'];
    $data['date_of_birth']=$_POST['driver_dob'];
    $data['driver_license_state']=$_POST['driver_states'];
    $data['drive_license_expire_date']=$_POST['driver_exp'];
    $data['address_street']=$_POST['driver_street'];
    $data['address_state']=$_POST['driver_state'];
    $data['address_city']=$_POST['driver_city'];
    $data['address_zipcode']=$_POST['driver_zip'];

    $data['card_number']=$_POST['card_number'];
    $data['card_expire_date']=$_POST['card_exp'];
    $data['card_cvi']=$_POST['card_cvi'];
    $data['card_zipcode']=$_POST['card_zip'];
    $data['billing_street']=$_POST['bill_street'];
    $data['billing_state']=$_POST['bill_state'];
    $data['billing_city']=$_POST['bill_city'];
    $data['billing_zipcode']=$_POST['bill_zip'];




    $result = callAPI("POST",$api_url.'/signUp.php',json_encode($data));

    echo "<br>".$result."<br>";
  }


  function callAPI($method, $url, $data){
     $curl = curl_init($url);
     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method );
     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
     curl_setopt($curl, CURLOPT_HEADER, 0);
     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept:application/json', 'Content-Type:application/json'));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     $result = curl_exec($curl);
     // echo $result;
     // echo "<br>";
     if(!$result){die("Connection Failure");}
     curl_close($curl);
     // echo json_decode($result,true)[];
     return $result;
  }
 ?>
 <html>
 <body>

 <form action="registertest.php" method="post">
   <pre style="border:2px solid Black; color:red;text-align:center;margin-left:10%;margin-right:10%;margin-top:15px;padding:1%">
 First Name:     <input type="text" name="first"><br>
 Last Name:      <input type="text" name="last"><br>
 password:       <input type="text" name="pass"><br>
 email:          <input type="text" name="email"><br>
 </pre>
 <pre style="border:2px solid Black; color:blue;text-align:center;margin-left:10%;margin-right:10%;margin-top:15px;padding:1%">
   Driver License Number:  <input type="text" name="driver_number"><br>
   Driver License State:   <input type="text" name="driver_states"><br>
   Driver License Exp_date:<input type="date" name="driver_exp"><br>
   Date of Birth:          <input type="date" name="driver_dob"><br>
   Address Street:         <input type="text" name="driver_street"><br>
   Address City:           <input type="text" name="driver_city"><br>
   Address State:          <input type="text" name="driver_state"><br>
   Address Zipcode:        <input type="text" name="driver_zip"><br>
 </pre>
 <pre style="border:2px solid Black; color:green;text-align:center;margin-left:10%;margin-right:10%;margin-top:15px;padding:1%">
   Card Number:          <input type="text" name="card_number"><br>
   Card Exp_date:        <input type="date" name="card_exp"><br>
   Card CVI:             <input type="text" name="card_cvi"><br>
   Card ZipCode:         <input type="text" name="card_zip"><br>
   Billing Street:       <input type="text" name="bill_street"><br>
   Billing City:         <input type="text" name="bill_city"><br>
   Billing State:        <input type="text" name="bill_state"><br>
   Billing Zipcode:      <input type="text" name="bill_zip"><br>
 </pre>
 <div style="text-align: center">
   <input type="submit" value="Sign Up" name="submit">
   <br>
   <br>
   <a href="../index.php" class="button">Display</a>
 </div>
 </form>
 </body>
 </html>
