<?php
require_once 'user.php';
class creditCard{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "CREDIT_CARD";

    // object properties
    public $cardID;
    public $cardNumber;
    public $expDate;
    public $cardCVI;
    public $cardZIP;
    public $billStreet;
    public $billCity;
    public $billState;
    public $billZip;

    //TABLE FIELDS NAME
    private $TABLE_CARDID = 'CARD_ID';  //AUTO-INC
    private $TABLE_CARD_NUMBER = 'CARD_NUMBER';  //NOT NULL
    private $TABLE_EXPDATE = 'CARD_EXPIREDATE';  //NOT NULL
    private $TABLE_CVI = 'CVI';  //NOT NULL
    private $TABLE_ZIP = 'ZIPCODE';  // NOT NULL
    private $TABLE_BILL_STREET = 'CARD_BILL_ADDRESS_STREET';  //NOT NULL
    private $TABLE_BILL_CITY = 'CARD_BILL_ADDRESS_CITY';//NOT NULL
    private $TABLE_BILL_STATE = 'CARD_BILL_ADDRESS_STATE';  //NOT NULL
    private $TABLE_BILL_ZIP = 'CARD_BILL_ADDRESS_ZIPCODE';  //NOT NULL
    private $TABLE_TO_USER_ID = 'USER_ID';  //INT TO USER ID

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //
    //REGISTER FOR NEW LICENSE RECORDS
    //@RETURN : JSON
    function register(){
      $id = $this->getIDbyNumber();
      if($id){
        // echo json_encode(array("status"=>"fail","message"=>"Email already exists."));
        return json_encode(array("status"=>"fail","message"=>"Credit Card already exists."));
      }
      $query = 'INSERT INTO '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_CARD_NUMBER.'="'.$this->cardNumber.
                '" , '
                .$this->TABLE_EXPDATE.'="'.$this->expDate.
                '" , '
                .$this->TABLE_CVI.'="'.$this->cardCVI.
                '" , '
                .$this->TABLE_ZIP.'="'.$this->cardZIP.
                '" , '
                .$this->TABLE_BILL_STREET.'="'.$this->billStreet.
                '" , '
                .$this->TABLE_BILL_CITY.'="'.$this->billCity.
                '" , '
                .$this->TABLE_BILL_STATE.'="'.$this->billState.
                '" , '
                .$this->TABLE_BILL_ZIP.'="'.$this->billZip.
                '";';
      // echo "$query";
      $stmt = $this->conn->prepare($query);

      if($stmt->execute()){
        $id = $this->getIDbyNumber();
        return json_encode(array("status"=>"success","id"=>$id,"number"=>$this->cardNumber));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error"));
    }

    //GET DRIVER RECORD ID FROM DRIVER LICENSE NUMBER
    //@return : INT IF EXIST, NULL IF NOT EXIST;
    function getIDbyNumber(){
      $query = 'SELECT '
                .$this->TABLE_CARDID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_CARD_NUMBER.
                '="'
                .$this->cardNumber.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row[$this->TABLE_CARDID];
      return $id;
    }

    function setOwner($user_id){
      $id = $this->getIDbyNumber();
      if(!$id){return json_encode(array("status"=> "fail","message"=> "CreditCard not exist."));}
      $query = 'UPDATE '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_TO_USER_ID.
                '="'
                .$user_id.
                '" WHERE '
                .$this->TABLE_CARDID.
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

    //DELETE USER CREDIT CARD INFORMATION
    function delete($userID){
      $action = 'Delete Credit Cart';
      $query = 'DELETE FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_TO_USER_ID.
                '="'
                .$userID.
                '";';
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        return json_encode(array("status"=>"success", 'action'=> $action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error", 'action'=> $action));

    }

    //GET CREDIT CARD INFO BY USER ID.
    function getCreditCard($userID){
      $action = 'Get User Credit Cart';
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_TO_USER_ID.
                '="'
                .$userID.
                '";';
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode(array("status"=>"success",'data'=>$row, 'action'=> $action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error", 'action'=> $action));
    }

    function checkExists(){
        $query = 'SELECT '
                  .$this->TABLE_CARDID.
                  'FROM '
                  .$this->TABLE_NAME.
                  ' WHERE '
                  .$this->TABLE_CARD_NUMBER.
                  '="'
                  .$this->cardNumber.
                  '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $id = $stmt->fetchColumn();
      return (empty($id))?'false':'true';
    }

    //UPDATE CREDIT CARD OF A USER, ALL FIELD QUIRED.
    function update($userID){

      $action = 'Update Card Info';
      $query = 'UPDATE '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_CARD_NUMBER.'="'.$this->cardNumber.
                '" , '
                .$this->TABLE_EXPDATE.'="'.$this->expDate.
                '" , '
                .$this->TABLE_CVI.'="'.$this->cardCVI.
                '" , '
                .$this->TABLE_ZIP.'="'.$this->cardZIP.
                '" , '
                .$this->TABLE_BILL_STREET.'="'.$this->billStreet.
                '" , '
                .$this->TABLE_BILL_CITY.'="'.$this->billCity.
                '" , '
                .$this->TABLE_BILL_STATE.'="'.$this->billState.
                '" , '
                .$this->TABLE_BILL_ZIP.'="'.$this->billZip.
                '"WHERE '
                .$this->TABLE_TO_USER_ID.
                '="'
                .$userID.
                '";';

      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        return json_encode(array("status"=>"success", 'action'=> $action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error", 'action'=> $action));

    }

    //UPDATE GET DATA FROM USER ID
    function getDataByID($userID){
      $action = 'Get CreditCard';
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_TO_USER_ID.
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
