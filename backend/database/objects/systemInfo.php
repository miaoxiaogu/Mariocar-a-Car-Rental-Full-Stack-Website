<?php
class systemInfo{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "SYSTEM_INFO";

    // object properties
    public $recordID;
    public $membershipFee;
    public $timepoint_1;
    public $timepoint_2;
    public $timeDiscount_1;
    public $timeDiscount_2;
    public $timeDiscount_3;
    public $over_25;

    private $TABLE_RECORDID = 'RECORD_ID' ;
    private $TABLE_MEMBER_FEE = 'MEMBERSHIP_FEE' ;
    private $TABLE_TIMEPOINT_1 = 'TIMEPOINT_ONE';
    private $TABLE_TIMEPOINT_2 = 'TIMEPOINT_TWO';
    private $TABLE_DISCOUNT_1 = 'TIME_RANGE_DISCOUNT_ONE';
    private $TABLE_DISCOUNT_2 = 'TIME_RANGE_DISCOUNT_TWO';
    private $TABLE_DISCOUNT_3 = 'TIME_RANGE_DISCOUNT_THREE';
    private $TABLE_OVER_25 = 'DISCOUNT_OVER_25';


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    //CHECK IF EXISTS RECORD
    //ONLY ONE RECORD IS ALLOWED IN DATABASE
    //return true/false
    function checkExists(){
      $query = 'SELECT COUNT(*) FROM '
                .$this->TABLE_NAME.
                ';';

      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $count = $stmt->fetchColumn();
      return (empty($count))?'false':'true';
    }

    //GET ALL Data
    function getData(){
      $action = 'GET SYSTEM INFORMATION.';
      if($this->checkExists()!=='true'){
        return json_encode(array("status"=>"fail","message"=>"System Data Not Exists.", 'action'=> $action));
      }
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ';';

      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode(array('status'=>'success','data'=>$row,'action'=> $action));
      }
      return json_encode(array("status"=>"fail","message"=>"Connection Error.", 'action'=> $action));
    }

    function getID(){
      if($this->checkExists()!=='true')return 0;
      $query = 'SELECT '
                .$this->TABLE_RECORDID.
                ' FROM '
                .$this->TABLE_NAME.
                ';';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchColumn();
    }

    //MODIFY DATAS
    function modify($fee,$time1,$time2,$discount1,$discount2,$discount3,$over){
      $action = 'MODIFY SYSTEM INFORMATION.';
      if($this->checkExists()!=='true'){
        return json_encode(array("status"=>"fail","message"=>"System Data Not Exists.", 'action'=> $action));
      }
      $id = $this->getID();
      $input = array();

      //GET INPUT Data

      if($fee!==null){
        $temp = $this->TABLE_MEMBER_FEE.'="'.$fee.'"';
        array_push($input,$temp);
      }
      if($time1!==null){
        $temp = $this->TABLE_TIMEPOINT_1.'="'.$time1.'"';
        array_push($input,$temp);
      }
      if($time2!==null){
        $temp = $this->TABLE_TIMEPOINT_2.'="'.$time2.'"';
        array_push($input,$temp);
      }
      if($discount1!==null){
        $temp = $this->TABLE_DISCOUNT_1.'="'.$discount1.'"';
        array_push($input,$temp);
      }
      if($discount2!==null){
        $temp = $this->TABLE_DISCOUNT_2.'="'.$discount2.'"';
        array_push($input,$temp);
      }
      if($discount3!==null){
        $temp = $this->TABLE_DISCOUNT_3.'="'.$discount3.'"';
        array_push($input,$temp);
      }
      if($over!==null){
        $temp = $this->TABLE_OVER_25.'="'.$over.'"';
        array_push($input,$temp);
      }

      $query = 'UPDATE '.$this->TABLE_NAME.' SET ';
      $str = implode(' , ' , $input);
      $query .= $str;
      $query .= ' WHERE '
                .$this->TABLE_RECORDID.
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

}
?>
