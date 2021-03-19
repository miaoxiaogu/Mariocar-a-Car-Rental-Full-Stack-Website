<html>
<body>

<form action="logintest.php" method="post">
EMAIL: <input type="text" name="email"><br>
PASS: <input type="text" name="pass"><br>
<input type="submit" value="click" name="submit">
</form>
</body>
</html>
<?php
  ini_set('display_errors',1);
  error_reporting(E_ALL);
  require_once '../../database/config/setting.php';
  $api_url = $root_url . "/database/users";
  if(isset($_POST['submit']))
  {
     login();
  }
  function login(){
    global $api_url;
    $result = callAPI("POST",$api_url.'/login.php',json_encode(array("email"=>$_POST['email'],"password"=>$_POST['pass'])));

    echo "<br>$result";
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
