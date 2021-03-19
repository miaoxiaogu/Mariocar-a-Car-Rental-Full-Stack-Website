<?php
class parkLocation{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "PARK_LOCATION";

    // object properties
    public $locationID;
    public $locationState;
    public $locationCity;
    public $locationZip;
    public $locationStreet;
    public $locationCapacity;
    public $locationName;

    private $TABLE_LOCATIONID='LOCATION_ID';
    private $TABLE_STATE = 'LOCATION_STATE';
    private $TABLE_CITY = 'LOCATION_CITY';
    private $TABLE_ZIP = 'LOCATION_ZIPCODE';
    private $TABLE_STREET = 'LOCATION_STREET';
    private $TABLE_CAPACITY = 'LOCATION_CAPACITY';
    private $TABLE_LOCATION_NAME = 'LOCATION_NAME';

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // GET ALL LOATION INFORMATION
    function getAllLocations(){
        $query = 'SELECT * FROM '
                  .$this->TABLE_NAME.
                  ';';
        // echo $query;
        $stmt = $this->conn->prepare($query);
        if($stmt->execute()){

          $result = array();
          $result['status']= 'success';
          $result['data'] = array();
          while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($result['data'],$row);
          }
          $result['action'] = 'getAllLocations';
          return json_encode($result);
        }
        return json_encode(array('status'=>'fail','message'=>'Connection Error.','action' => 'getAllLocation'));

    }

    // GET LOCATION NAME BY ID
    function getNameByID(){
          $query = 'SELECT '
                    .$this->TABLE_LOCATION_NAME.
                    ' FROM '
                    .$this->TABLE_NAME.
                    ' WHERE '
                    .$this->TABLE_LOCATIONID.
                    '="'
                    .$this->locationID.
                    '";';
          $stmt = $this->conn->prepare($query);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $name = $row[$this->TABLE_LOCATION_NAME];
          return $name;
    }

    // ADD NEW LOCATION
    function addNew(){
      $action = 'Add new Park Location';
      //ECHCK EXISTANCE
      if($this->checkExists()){
        return json_encode(array('status'=>'fail','message'=>'Park Location exists.'));
      }
      //INSERT

      $query = 'INSERT INTO '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_LOCATION_NAME.'="'.$this->locationName.
                '" , '
                .$this->TABLE_CAPACITY.'="'.$this->locationCapacity.
                '" , '
                .$this->TABLE_STREET.'="'.$this->locationStreet.
                '" , '
                .$this->TABLE_CITY.'="'.$this->locationCity.
                '" , '
                .$this->TABLE_STATE.'="'.$this->locationState.
                '" , '
                .$this->TABLE_ZIP.'="'.$this->locationZip.
                '";';
      // echo "$query";
      $stmt = $this->conn->prepare($query);
      // echo $query;
      if($stmt->execute()){
          $id = $this->checkExists();
          return json_encode(array("status"=>"success","id"=>$id,'action'=>$action));

      }
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));


    }

    //GET PARTICULAR LOCATION INFO
    function getInfoByID($locationid){
        $query = 'SELECT * FROM '
                  .$this->TABLE_NAME.
                  ' WHERE '
                  .$this->TABLE_LOCATIONID.
                  '="'
                  .$locationid.
                  '";';
        // echo $query;
        $stmt = $this->conn->prepare($query);
        if($stmt->execute()){
          if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return json_encode(array('status'=>'success','data'=>$row,'action' => 'Get Location Info'));

          }
          return json_encode(array('status'=>'fail','message'=>'Location ID not Found.','action' => 'Get Location Info'));


        }
        return json_encode(array('status'=>'fail','message'=>'Connection Error.','action' => 'Get Location Info'));

    }

    //returns id if found.
    function checkExists(){
      $query = 'SELECT '
                .$this->TABLE_LOCATIONID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_STREET.'="'.$this->locationStreet.
                '" AND '
                .$this->TABLE_CITY.'="'.$this->locationCity.
                '" AND '
                .$this->TABLE_STATE.'="'.$this->locationState.
                '" AND '
                .$this->TABLE_ZIP.'="'.$this->locationZip.
                '";';
      // echo $query;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row[$this->TABLE_LOCATIONID];
      return $id;
    }

    // DELETE LOCATION
    function delete($id){
        $action = 'Deleting Location.';
        $this->locationID = $id;
        $exists = $this->getNameByID();
        if(!$exists){
          return json_encode(array('status'=>'fail','message'=>'Location not exists.','action'=>$action));
        }
        $query = 'DELETE FROM '
                  .$this->TABLE_NAME.
                  ' WHERE '
                  .$this->TABLE_LOCATIONID.
                  '="'
                  .$id.
                  '";';
        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute()){
          return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
        }
        return json_encode(array("status"=>"success",'action'=>$action));
    }

    //TODO EDIT LOCATION INFORMATION
    function updateLocation($newName,$newCap){

        $id = $this->locationID;
        $action = 'Update Location';
        $input = array();
        // echo $this->locationID();
        if(!$this->getNameByID()){return json_encode(array("status"=> "fail","message"=> "Park Location not exist.", 'action'=> $action));}

        $query = 'UPDATE '.$this->TABLE_NAME.' SET ';
        //iterate all inputs
        if($newName!==null){
          $temp = $this->TABLE_LOCATION_NAME.'="'.$newName.'"';
          array_push($input,$temp);
        }
        if($newCap!==null){
          $temp = $this->TABLE_CAPACITY.'="'.$newCap.'"';
          array_push($input,$temp);
        }
        $str = implode(' , ' , $input);

        $query .= $str;
        $query .= ' WHERE '
                  .$this->TABLE_LOCATIONID.
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

    //TODO LOOK UP LOCATION
}
?>
