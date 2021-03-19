<?php
class vehicle{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "VEHICLE_INFO";

    // object properties
    public $vehicleID;
    public $vehiclePlate;
    public $vehicleType;
    public $vehicleCondition;
    public $vehicleBrand;
    public $vehicleModel;
    public $vehicleMile;
    public $vehicleYear;
    public $vehicleLocation;
    public $vehicleRegistration;
    public $vehicleLastUse;
    public $vehicleStatus;
    public $vehicleOrderID;

    private $TABLE_ID = 'VEHICLE_ID';
    private $TABLE_PLATE = 'LICENSE_PLATE';
    private $TABLE_TYPE= 'VEHICLE_TYPE';
    private $TABLE_CONDITION= 'VEHICLE_CONDITION';
    private $TABLE_BRAND= 'VEHICLE_BRAND';
    private $TABLE_MODEL= 'VEHICLE_MODEL';
    private $TABLE_MILE= 'VEHICLE_CURRENT_MILEAGE';
    private $TABLE_YEAR= 'VEHICLE_YEAR';
    private $TABLE_LOCATION= 'VEHICLE_LOCATION';
    private $TABLE_REGISTRATION= 'VEHICLE_REGISTRATION_TAG';
    private $TABLE_LAST_USE= 'LAST_SERVICED_TIME';
    private $TABLE_STATUS = 'VEHICLE_STATUS';
    private $TABLE_ORDER_ID = 'ORDER_ID';


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // GET ID BY PLATE
    // return int
    function getTypeByPlate(){
      $query = 'SELECT '
                .$this->TABLE_TYPE.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_PLATE.
                '="'
                .$this->vehiclePlate.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $type = $row[$this->TABLE_TYPE];
      return $type;
    }

    function getIDByPlate(){
      $query = 'SELECT '
                .$this->TABLE_ID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_PLATE.
                '="'
                .$this->vehiclePlate.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row[$this->TABLE_ID];
      return $id;
    }

    // GET PLATE BY ID
    // RETURN STRING
    function getPlateByID($id){
      $query = 'SELECT '
                .$this->TABLE_PLATE.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_ID.
                '="'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $plate = $row[$this->TABLE_PLATE];
      return $plate;

    }

    // GET ID from  REGISTRATION TAG
    // RETURN STRING
    function getIDByTag(){
      $query = 'SELECT '
                .$this->TABLE_ID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_REGISTRATION.
                '="'
                .$this->vehicleRegistration.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row[$this->TABLE_ID];
      return $id;
    }

    //TODO GET ALL VEHICLES
    //RETURN JSON
    function getAllVehicle(){}

    //TODO GET ALL VEHICLES FROM LOCATION
    //RETURN JSON
    function getVehicleByLocation($locatinoID){}

    // GET VEHICLE BY UNIQUE INFORMATION
    function getVehicleInfo($plate, $tag){
      $action = 'getVehicleInfo';
        $query = 'SELECT * FROM '.$this->TABLE_NAME.' WHERE ';
        if($plate){
          //PLATE INFO PROVIDED
          $query .= $this->TABLE_PLATE.'="'.$plate.'" ';
          // $query .= $info;
          if($tag){
            //BOTH INFO PROVIDED
            $query .= 'AND '.$this->TABLE_REGISTRATION.'="'.$tag.'" ';
          }
        }else{
          //ONLY TAG PROVIDED
          $query .= $this->TABLE_REGISTRATION.'="'.$tag.'" ';
        }
        $query .= ';';
        $stmt = $this->conn->prepare($query);
        if($stmt->execute()){

          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if(!$row){
            return json_encode(array('status'=>'success','data'=>array()));
          }
          return json_encode(array('status'=>'success','data'=>$row));
          // echo "<br> $row<row>";
        }
        return json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>$action));
    }

    // ADD NEW VEHICLE
    function addNew(){
      if($this->getIDByPlate()){
        return json_encode(array('status'=>'fail','message'=>'Vehicle Plate Already exists.'));
      }
      if($this->getIDByTag()){
        return json_encode(array('status'=>'fail','message'=>'Vehicle Registration Tag Already exists.'));
      }

        $query = 'INSERT INTO '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_PLATE.'="'.$this->vehiclePlate.
                  '" , '
                  .$this->TABLE_TYPE.'="'.$this->vehicleType.
                  '" , '
                  .$this->TABLE_CONDITION.'="'.$this->vehicleCondition.
                  '" , '
                  .$this->TABLE_BRAND.'="'.$this->vehicleBrand.
                  '" , '
                  .$this->TABLE_MODEL.'="'.$this->vehicleModel.
                  '" , '
                  .$this->TABLE_MILE.'="'.$this->vehicleMile.
                  '" , '
                  .$this->TABLE_YEAR.'="'.$this->vehicleYear.
                  '" , '
                  .$this->TABLE_LOCATION.'="'.$this->vehicleLocation.
                  '" , '
                  .$this->TABLE_STATUS.'="'.'available'.
                  '" , '
                  .$this->TABLE_REGISTRATION.'="'.$this->vehicleRegistration.
                  '";';
        // echo "$query";
        $stmt = $this->conn->prepare($query);

        if($stmt->execute()){
          $id = $this->getIDByPlate();
          return json_encode(array("status"=>"success","id"=>$id));
        }
        return json_encode(array("status"=>"fail","message"=>"Connection error"));

    }

    // DELETE A VEHICLE
    function delete(){
      $action = 'Delete Vehicle';
      $id = $this->getIDByPlate();
      if(!$id){
        return json_encode(array('status'=>'fail','message'=>'Vehicle Not Exists.','action'=>$action));
      }
      $query = 'DELETE FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_ID.
                '="'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      if(!$stmt->execute()){
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
      }
      return json_encode(array("status"=>"success",'action'=>$action));
    }

    // SET LOCATION OF A VEHICLE
    function setLocation($locationID){
      $id = $this->getIDByPlate();
      if(!$id){return json_encode(array("status"=> "fail","message"=> "CreditCard not exist."));}
      $query = 'UPDATE '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_LOCATION.
                '="'
                .$locatinoID.
                '" WHERE '
                .$this->TABLE_ID.
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

    //TODO SET TYPE OF A VEHICLE
    function setType(){}

    //TODO UPDATE VEHICLE
    function update($newCondition,$newLocation,$newLastUse,$newMile){
      $id = $this->getIDByPlate();
      $action = 'Update Vehicle';
      $input = array();
      if(!$id){return json_encode(array("status"=> "fail","message"=> "Vehicle not exist.", 'action'=> $action));}
      $query = 'UPDATE '.$this->TABLE_NAME.' SET ';
      //iterate all inputs
      if($newCondition){
        $temp = $this->TABLE_CONDITION.'="'.$newCondition.'"';
        array_push($input,$temp);
      }
      if($newLocation){
        $temp = $this->TABLE_LOCATION.'="'.$newLocation.'"';
        array_push($input,$temp);
      }
      if($newLastUse){
        $temp = $this->TABLE_LAST_USE.'="'.$newLastUse.'"';
        array_push($input,$temp);
      }
      if($newMile){
        $temp = $this->TABLE_MILE.'="'.$newMile.'"';
        array_push($input,$temp);
      }
      $str = implode(' , ' , $input);

      $query .= $str;
      $query .= ' WHERE '
                .$this->TABLE_ID.
                '="'
                .$id.
                '";';
      // echo $query;
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        return json_encode(array("status"=>"success", 'action'=> $action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error", 'action'=> $action));
    }

    //TODO SET VEHICLE INFOR WITH ORDER_USER_ID
    // GETS ORDER ID AND UPDATE ID AND status
    // IF GETS 0 AS ORDER ID WILL MAKE STATUS AVAILABLE
    // WHICH MEANS THE VEHICLE BEEN SET TO NOT IN USE.
    // ALL OTHER VALID INTEGER VALUE SET IT TO BE NOT AVAILABLE
    function setOrder($order_id){}

    function getLocation($id){
      $query = 'SELECT '
                .$this->TABLE_LOCATION.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_ID.
                '="'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetchColumn();
      return $row;
    }
}
?>
