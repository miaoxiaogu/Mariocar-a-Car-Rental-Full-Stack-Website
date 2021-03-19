<?php
class typeInfo{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "TYPE_INFO";

    // object properties
    public $typeID;
    public $typeName;
    public $typeRate;


    private $TABLE_ID = 'TYPE_ID';
    private $TABLE_TYPENAME = 'TYPE_NAME';
    private $TABLE_RATE = 'TYPE_RATE';

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // ADD NEW TYPE WITH RATE
    function addNew(){
      if($this->getIDByName()){
        return json_encode(array('status'=>'fail','message'=>'Vehicle Type Already exists.'));
      }

        $query = 'INSERT INTO '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_TYPENAME.'="'.$this->typeName.
                  '" , '
                  .$this->TABLE_RATE.'="'.$this->typeRate.
                  '";';
        // echo "$query";
        $stmt = $this->conn->prepare($query);

        if($stmt->execute()){
          $id = $this->getIDByName();
          return json_encode(array("status"=>"success","id"=>$id));
        }
        return json_encode(array("status"=>"fail","message"=>"Connection error"));

    }

    // GET ID FROM TYPE NAME
    function getIDByName(){
      $query = 'SELECT '
                .$this->TABLE_ID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_TYPENAME.
                '="'
                .$this->typeName.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row[$this->TABLE_ID];
      return $id;

    }

    // GET ALL TYPE INFO
    // RETURN JSON
    function getAllTypes(){
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
        return json_encode($result);
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error.','action' => 'getAllType'));
    }

    // DELETE TYPE
    function delete(){
      $action = 'Delete Type';
      $id = $this->getIDByName();
      if(!$id){
        json_encode(array('status'=>'fail','message'=>'Type Info Not Exists.', 'action'=>$action));
      }

      $query = 'DELETE FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_ID.
                '="'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      // echo '<br>'.$query.'<br>';
      if(!$stmt->execute()){
        return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
      }
      return json_encode(array("status"=>"success",'action'=>$action));

    }

    // GET PARTICULAR RATE
    // JSON
    function getRate(){
      $action = 'Get Rate of Type';
      $id = $this->getIDByName();
      if(!$id){
        json_encode(array('status'=>'fail','message'=>'Type Info Not Exists.', 'action'=>$action));
      }

      $query = 'SELECT '
                .$this->TABLE_RATE.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_ID.
                ' = "'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        return json_encode(array('status'=>'success','message'=>$stmt->fetchColumn(),'action'=>$action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));
    }

    // CHANGE RATE OF TYPE BY name
    // JSON
    function changeRate($newRate){
      $action = 'Change Rate of Type';
      $id = $this->getIDByName();
      // echo "<br>$id<br>";
      if(!$id){
        return json_encode(array('status'=>'fail','message'=>'Vehicle type Not Exists.', 'action'=>$action));
      }

      $query = 'UPDATE '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_RATE.
                '="'
                .$newRate.
                '" WHERE '
                .$this->TABLE_ID.
                '="'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        return json_encode(array('status'=>'success','action'=>$action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection error",'action'=>$action));

      // return $query;
    }
}



?>
