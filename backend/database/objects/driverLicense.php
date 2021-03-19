<?php
class driverLicense{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "DRIVER_LICENSE";

    // object properties
    public $driverID;
    public $licenseNumber;
    public $licenseState;
    public $birthDate;
    public $expDate;
    public $addrState;
    public $addrCity;
    public $addrStreet;
    public $addrZip;
    public $userID;

    //TABLE FIELDS NAME
    private $TABLE_DRIVERID = 'DRIVER_LICENSE_ID';  //AUTO-INC
    private $TABLE_LICENSE_NUMBER = 'DRIVER_LICENSE_NUMBER';  //NOT NULL
    private $TABLE_BIRTH = 'DRIVER_BIRTHDAY';  //NOT NULL
    private $TABLE_EXPDATE = 'DRIVER_LICENCE_EXPIREDATE';  //NOT NULL
    private $TABLE_LICENSE_STATE = 'DRIVER_LICENSE_STATE';  // NOT NULL
    private $TABLE_ADDR_STREET = 'ADDRESS_STREET';  //NOT NULL
    private $TABLE_ADDR_CITY = 'ADDRESS_CITY';//NOT NULL
    private $TABLE_ADDR_STATE = 'ADDRESS_STATE';  //NOT NULL
    private $TABLE_ADDR_ZIP = 'ADDRESS_ZIPCODE';  //NOT NULL
    private $TABLE_USER_ID = 'USER_ID';








    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    //REGISTER FOR NEW LICENSE RECORDS
    //@RETURN : JSON
    function register(){
      $id = $this->getIDbyNumber();
      if($id){
        // echo json_encode(array("status"=>"fail","message"=>"Email already exists."));
        return json_encode(array("status"=>"fail","message"=>"Driver license already exists."));
      }
      $query = 'INSERT INTO '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_LICENSE_NUMBER.'="'.$this->licenseNumber.
                '" , '
                .$this->TABLE_BIRTH.'="'.$this->birthDate.
                '" , '
                .$this->TABLE_EXPDATE.'="'.$this->expDate.
                '" , '
                .$this->TABLE_LICENSE_STATE.'="'.$this->licenseState.
                '" , '
                .$this->TABLE_ADDR_STREET.'="'.$this->addrStreet.
                '" , '
                .$this->TABLE_ADDR_CITY.'="'.$this->addrCity.
                '" , '
                .$this->TABLE_ADDR_STATE.'="'.$this->addrState.
                '" , '
                .$this->TABLE_ADDR_ZIP.'="'.$this->addrZip.
                '";';
      // echo "$query";
      $stmt = $this->conn->prepare($query);

      if($stmt->execute()){
        $id = $this->getIDbyNumber();
        return json_encode(array("status"=>"success","id"=>$id,"number"=>$this->licenseNumber));
      }
      // echo $query;
      return json_encode(array("status"=>"fail","message"=>"Connection error"));
    }

    //GET DRIVER RECORD ID FROM DRIVER LICENSE NUMBER
    //@return : INT IF EXIST, NULL IF NOT EXIST;
    function getIDbyNumber(){
      $query = 'SELECT '
                .$this->TABLE_DRIVERID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_LICENSE_NUMBER.
                '="'
                .$this->licenseNumber.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row[$this->TABLE_DRIVERID];
      return $id;
    }

    //TODO
    function delete(){}

    //
    function setUserID($user){

        $id = $this->getIDbyNumber();
        if(!$id){return json_encode(array("status"=> "fail","message"=> "Driver License not exist."));}
        $query = 'UPDATE '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_USER_ID.
                  '="'
                  .$user.
                  '" WHERE '
                  .$this->TABLE_DRIVERID.
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


    function getDataByID($userID){
      $action = 'Get Driver License';
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USER_ID.
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

    function update($userID){
      $action = 'Update Driver License Info';
      $query = 'UPDATE '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_LICENSE_NUMBER.'="'.$this->licenseNumber.
                '" , '
                .$this->TABLE_BIRTH.'="'.$this->birthDate.
                '" , '
                .$this->TABLE_EXPDATE.'="'.$this->expDate.
                '" , '
                .$this->TABLE_LICENSE_STATE.'="'.$this->licenseState.
                '" , '
                .$this->TABLE_ADDR_STREET.'="'.$this->addrStreet.
                '" , '
                .$this->TABLE_ADDR_CITY.'="'.$this->addrCity.
                '" , '
                .$this->TABLE_ADDR_STATE.'="'.$this->addrState.
                '" , '
                .$this->TABLE_ADDR_ZIP.'="'.$this->addrZip.
                '" WHERE '
                .$this->TABLE_USER_ID.
                '="'
                .$userID.
                '";';
      // echo $query;
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        return json_encode(array("status"=>"success", 'action'=> $action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error", 'action'=> $action));

    }


}
?>
