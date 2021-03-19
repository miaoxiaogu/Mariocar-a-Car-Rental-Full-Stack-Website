<?php
class user{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "USER_INFO";
    // object properties
    public $userEmail;
    public $password;
    public $userfirst;
    public $userlast;
    // public $memberStatus;
    // public $startTime;
    // public $endTime;
    public $driverID;

    //TABLE FIELDS NAME
    private $TABLE_USERID = 'USER_ID';  //AUTO-INC
    private $TABLE_USEREMAIL = 'USER_EMAIL';  //NOT NULL
    private $TABLE_PASSWORD = 'USER_PASSWORD';  //NOT NULL
    private $TABLE_MEMBER = 'MEMBERSHIP_STATUE';
    private $TABLE_START = 'MEMBER_STARTTIME';  //DATETIME
    private $TABLE_END = 'MEMBER_ENDTIME';  //DATETIME
    private $TABLE_FIRST_NAME = 'USER_FIRST_NAME';  //NOT NULL
    private $TABLE_LAST_NAME = 'USER_LAST_NAME';  //NOT NULL

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //FOR TEST
    //GET ALL USER FROM LIST
    function getUsers(){
      // select all query
      $query = "SELECT * FROM " . $this->TABLE_NAME ;

      // prepare query statement
      $stmt = $this->conn->prepare($query);

      // execute query
      if($stmt->execute()){
        $result = array();
        $result['status']='success';
        $result['data'] = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          array_push($result['data'],$row);
        }
        return json_encode($result);
      }  return json_encode(array("status"=>"fail","message"=>"Connection Error."));

    }

    //GET USER EMAIL BY USER ID;
    function getUserById($user_id){
        $query = 'SELECT '
                  .$this->TABLE_USEREMAIL.
                  ' FROM '
                  .$this->TABLE_NAME.
                  ' WHERE '
                  .$this->TABLE_USERID.
                  '="'
                  .$user_id.
                  '";';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchColumn();
        return $row;
    }

    //GET USER ID BY USER EMAIL;
    function getUserByEmail(){
      $query = 'SELECT '
                .$this->TABLE_USERID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USEREMAIL.
                '="'
                .$this->userEmail.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchColumn();
    }

    //REGISTER USER BASED ON INPUT DATA
    //@return : JSON
    function register(){
      $id = $this->getUserByEmail();
      // echo "user id : $id <br>";
      if($id){
        // echo json_encode(array("status"=>"fail","message"=>"Email already exists."));
        return json_encode(array("status"=>"fail","message"=>"Email already exists."));
      }
      $query = 'INSERT INTO '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_USEREMAIL.'="'.$this->userEmail.
                '" , '
                .$this->TABLE_PASSWORD.'="'.$this->password.
                '" , '
                .$this->TABLE_FIRST_NAME.'="'.$this->userfirst.
                '" , '
                .$this->TABLE_LAST_NAME.'="'.$this->userlast.
                '" , '
                .$this->TABLE_MEMBER.'="inactive";';
      // echo "$query";
      $stmt = $this->conn->prepare($query);
      // echo $query;
      if($stmt->execute()){
        $renewResult = $this->renewMembership();
        $arr = json_decode($renewResult,true);
        // echo $arr;
        if($arr['status']=='success'){
          $id = $this->getUserByEmail();
          return json_encode(array("status"=>"success","id"=>$id));
        }
          return json_encode(array("status"=>"fail","message"=>"Connection error"));

      }
        return json_encode(array("status"=>"fail","message"=>"Connection error"));

    }

    //UPDATE RECORDS
    function updateFirstName($newFirst){
      $action = 'Update First Name';
      if(!$newFirst){
        return json_encode(array('status'=>'fail','message'=>'Empty new first name.','action'=>$action));
      }
      $id = $this->getUserByEmail();
      if(!$id){
        return json_encode(array('status'=>'fail','message'=>'Invalid user email.','action'=>$action));
      }
      $vali = $this->validation();
      $vali = json_encode(array('status'=>'success'));
      if(json_decode($vali,true)['status']!='success'){
        return json_encode(array('status'=>'fail','message'=>json_decode($vali),'action'=>$action));
      }else{
        $query = 'UPDATE '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_FIRST_NAME.
                  '="'
                  .$newFirst.
                  '" WHERE '
                  .$this->TABLE_USERID.
                  '='
                  .$id.
                  ';';
        $stmt = $this->conn->prepare($query);
        // echo $query;
        if($stmt->execute()){
          return json_encode(array("status"=>"success",'action'=>$action));
        }
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
      }
        return json_encode(array('status'=>'fail','action'=>$action));


    }

    //@required: email, pass
    function updateLastName($newLast){
      $action = 'Update Last Name';
      if(!$newLast){
        return json_encode(array('status'=>'fail','message'=>'Empty new first name.','action'=>$action));
      }
      $id = $this->getUserByEmail();
      if(!$id){
        return json_encode(array('status'=>'fail','message'=>'Invalid user email.','action'=>$action));
      }
      $vali = $this->validation();
      $vali = json_encode(array('status'=>'success'));
      if(json_decode($vali,true)['status']!='success'){
        return json_encode(array('status'=>'fail','message'=>json_decode($vali),'action'=>$action));
      }else{
        $query = 'UPDATE '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_LAST_NAME.
                  '="'
                  .$newLast.
                  '" WHERE '
                  .$this->TABLE_USERID.
                  '='
                  .$id.
                  ';';
        $stmt = $this->conn->prepare($query);
        // echo $query;
        if($stmt->execute()){
          return json_encode(array("status"=>"success",'action'=>$action));
        }
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
      }
        return json_encode(array('status'=>'fail','action'=>$action));


    }

    //CHANGE PASSWORD
    //@return : JSON
    function changePass($newPass){
      if(!$newPass){
        return json_encode(array('status'=>'fail','message'=>'Empty new pass.','action'=>'Change password.'));
      }
      $id = $this->getUserByEmail();
      if(!$id){
        return json_encode(array('status'=>'fail','message'=>'Invalid user email.','action'=>'Change password.'));
      }


      $vali = $this->validation();
      if(json_decode($vali,true)['status']!='success'){
        return json_encode(array('status'=>'fail','message'=>json_decode($vali)));
      }else{
        $query = 'UPDATE '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_PASSWORD.
                  '="'
                  .$newPass.
                  '" WHERE '
                  .$this->TABLE_USERID.
                  '='
                  .$id.
                  ';';
        $stmt = $this->conn->prepare($query);
        // echo $query;
        if($stmt->execute()){
          return json_encode(array("status"=>"success",'action'=>'Change password.'));
        }
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>'Change password.'));
      }
        return json_encode(array('status'=>'fail','message'=>json_decode($vali),'action'=>'Change password.'));
    }

    //SET DRIVER LICENSE TO USER INFO
    //@return JSON status:fail message:error;
    /*
    function setDriverLicense($driverL){
      $id = $this->getUserByEmail();
      if(!$id){return json_encode(array("status"=> "fail","message"=> "User not exist."));}
      $query = 'UPDATE '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_DRIVER.
                '="'
                .$driverL.
                '" WHERE '
                .$this->TABLE_USERID.
                '='
                .$id.
                ';';
      $stmt = $this->conn->prepare($query);
      // echo $query;
      if($stmt->execute()){
        return json_encode(array("status"=>"success"));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error"));

    }
    */

    //RENEW MEMBER SHIP
    //CHANGE START/END IF NOT MEMBER OR EXPIRED
    //EXPAND END TIME IF CURRENT IN MEMBERSHIP
    //@return JSON
    function renewMembership(){
      $timezone = 'America/Los_Angeles';
      $status = $this->getMembership();
      $id = $this->getUserByEmail();
      if(!$id){
        return json_encode(array("status"=>"fail","message"=>"User not exist."));
      }
      if($status=='active'){
        // echo "active<br>";
        $query = 'SELECT '
                  .$this->TABLE_END.
                  ' FROM '
                  .$this->TABLE_NAME.
                  ' WHERE '
                  .$this->TABLE_USERID.
                  '="'
                  .$id.
                  '";';
        // echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $old_time = $stmt->fetchColumn();
        $new_time = date('Y-m-d H:i', strtotime($old_time. ' + 6 months'));
        $query = 'UPDATE '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_END.'="'.$new_time.
                  '" WHERE '
                  .$this->TABLE_USERID.
                  '='
                  .$id.
                  ';';
        // echo $query;
        $stmt = $this->conn->prepare($query);
        // echo $query;
        if($stmt->execute()){
          return json_encode(array("status"=>"success",'message'=>"Extended Membership to $new_time"));
        }
        return json_encode(array("status"=>"fail","message"=>"Connection error"));

      }else{
        // $curr_time = DateTime("Y-m-d h:i:s",$timezone);
        $curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
        // $end_time = date('Y-m-d', strtotime($curr_time. ' + 6 months'));
        $end_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
        date_modify($end_time,'+6 months');
        // echo $curr_time."<br>";
        // echo $end_time."<br>";
        $query = 'UPDATE '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_START.'="'.$curr_time->format('Y-m-d H:i').
                  '",'
                  .$this->TABLE_END.'="'.$end_time->format('Y-m-d H:i').
                  '",'
                  .$this->TABLE_MEMBER.'="active" WHERE '
                  .$this->TABLE_USERID.
                  '='
                  .$id.
                  ';';
        // echo $query;
        $stmt = $this->conn->prepare($query);
        // echo $query;
        if($stmt->execute()){
          return json_encode(array("status"=>"success",'message'=>'Started Membership from '. $curr_time->format('Y-m-d H:i') .' to '.$end_time->format('Y-m-d H:i')));
        }
        return json_encode(array("status"=>"fail","message"=>"Connection error"));







      }
    }

    //GET CURRENT MEMEBER SHIP STATUS
    //IF CURRENT TIME PAST END TIME . UPDATE MEMBERSHIP TO suspend.
    function getMembership(){
      $id = $this->getUserByEmail();
      if(!$id){
        return -1;
      }
      $now_time = new DateTime("now", new DateTimeZone('America/Los_Angeles') );
      $curr_time = $now_time->format('Y-m-d H:i');
      //test
      // $curr_time = '2021-01-01';
      // echo "id : $id<br>";
      // echo "now time is :$curr_time <br>";
      $query = 'SELECT '
                .$this->TABLE_MEMBER.
                ','
                .$this->TABLE_END.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USEREMAIL.
                '="'
                .$this->userEmail.
                '";'
                ;

      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $result = 'null';
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      // echo "data time is ".$row[$this->TABLE_END]."<br>";
      // echo "data status is ".$row[$this->TABLE_MEMBER]."<br>";
      if(!$row[$this->TABLE_MEMBER])return 'None';
      if(strtotime($curr_time)>strtotime($row[$this->TABLE_END])){
        // echo "now is later;<br>";
        if($row[$this->TABLE_MEMBER]=='active'){
          // echo "should change.<br>";
          $query = 'UPDATE '
                    .$this->TABLE_NAME.
                    ' SET '
                    .$this->TABLE_MEMBER.
                    '="inactive" WHERE '
                    .$this->TABLE_USERID.
                    '='
                    .$id.
                    ';';
          // return 'inactive';
          $result = 'inactive';
        }else{
          // echo 'result stays<br>';
          return $row[$this->TABLE_MEMBER];
        }
      }else{
        // echo "now is earlier<br>";
        if($row[  $this->TABLE_MEMBER]=='active'){
          // echo "result stays.<br>";
          return $row[$this->TABLE_MEMBER];
        }else{
          // echo 'should change <br>';
          $query = 'UPDATE '
                    .$this->TABLE_NAME.
                    ' SET '
                    .$this->TABLE_MEMBER.
                    '="active" WHERE '
                    .$this->TABLE_USERID.
                    '='
                    .$id.
                    ';';

          $result = 'active';
        }
      }

      // echo $query;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      // echo "<br>set to $result<br>";
      if(!$result){
        return -1;
      }
      return $result;

      // echo $result[0][$this->TABLE_END];
      //
      // echo json_encode($result);
      // echo ;
      // return
    }

    //VALIDATE USER EMAIL AND PASSWORD
    // RETURN JSON
    function validation(){
      if(!$this->getUserByEmail()){
        return json_encode(array('status'=>'fail','message'=>'Email not exist','action'=>'User Validation'));
      }
      $query = 'SELECT '
                .$this->TABLE_PASSWORD.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USEREMAIL.
                '="'
                .$this->userEmail.
                '";';
      $stmt = $this->conn->prepare($query);
      if(!$stmt->execute()){
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>'User Validation'));

      }
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $old_pass = $row[$this->TABLE_PASSWORD];
      if($this->password !=  $old_pass){
        return json_encode(array('status'=>'fail','message'=>'Wrong Pass','action'=>'User Validation'));
      }
      return json_encode(array('status'=>'success','action'=>'User Validation'));

    }

    //DELETE A USER
    //THIS FUNCTION WILL ALSO DELETE ALL RELATED DRIVER LICENSE
    //AND CREDIT CARD
    //REQUIRE Email
    function delete(){
      $action = 'Deleting user.';
      $id = $this->getUserByEmail();
      if(!$id){
        return json_encode(array('status'=>'fail','message'=>'Email not exists.','action'=>$action));
      }
      $query = 'DELETE FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USERID.
                '="'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      if(!$stmt->execute()){
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
      }
      return json_encode(array("status"=>"success",'action'=>$action));
    }

    // TERMINATE A Membership
    function terminateMember(){
      $action = 'Terminate Membership.';
      $id = $this->getUserByEmail();
      if(!$id){
        return json_encode(array('status'=>'fail','message'=>'Email not exists.','action'=>$action));
      }
      $query = 'UPDATE '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_MEMBER.
                ' = "inactive", '
                .$this->TABLE_START.
                '= NULL, '
                .$this->TABLE_END.
                '= NULL WHERE '
                .$this->TABLE_USERID.
                ' = "'
                .$id.
                '";';
      // echo $query;
      $stmt = $this->conn->prepare($query);
      if(!$stmt->execute()){
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
      }
      return json_encode(array("status"=>"success",'action'=>$action));

    }


    function getDataByID($userID){
      $action = 'Get User';
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USERID.
                '="'
                .$userID.
                '";';

      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($row);
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error.','action' => $action));

    }
}


?>
