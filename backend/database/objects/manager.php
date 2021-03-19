<?php
class manager{

    // database connection and table name
    private $conn;
    private $table_name = "MANAGER_INFO";

    // object properties
    public $managerID;
    public $managerEmail;
    public $managerPass;

    private $TABLE_EMAIL = 'MANAGER_EMAIL';
    private $TABLE_PASS = 'MANAGER_PASSWORD';
    private $TABLE_ID = 'MANAGER_ID';

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getManagers(){
        // echo time();
        // select all query
        $query = "SELECT * FROM " . $this->table_name ;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          array_push($result,$row);
        }

        return $result;
    }

}

    function register(){
        $id = $this->getIDByEmail();
        // echo "user id : $id <br>";
        if($id){
          // echo json_encode(array("status"=>"fail","message"=>"Email already exists."));
          return json_encode(array("status"=>"fail","message"=>"Email already exists.","action"=>"Register new manager."));
        }
        $query = 'INSERT INTO '
                  .$this->TABLE_NAME.
                  ' SET '
                  .$this->TABLE_EMAIL.'="'.$this->managerEmail.
                  '" , '
                  .$this->TABLE_PASS.'="'.$this->$managerPass.
                  '";';
        // echo "$query";
        $stmt = $this->conn->prepare($query);
        // echo $query;
        if($stmt->execute()){
            $id = $this->getIDByEmail();
            return json_encode(array("status"=>"success","id"=>$id,"action"=>"Register new manager."));
        }
          return json_encode(array("status"=>"fail","message"=>"Connection error","action"=>"Register new manager."));

    }


    function getIDByEmail(){
      $query = 'SELECT '
                .$this->TABLE_ID.
                ' FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_EMAIL.
                '="'
                .$this->managerEmail.
                '";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row[$this->TABLE_USERID];
      // if(!$id){
      //   return -1;
      // }
      return $id;
    }
?>
