<?php
require_once 'user.php';
require_once 'vehicle.php';
class order{

    // database connection and table name
    private $conn;
    private $TABLE_NAME = "ORDER_INFO";

    // object properties
    public $orderID;
    public $confirm_time;
    public $register_start;
    public $register_end;
    public $actual_start;
    public $actual_end;
    public $cancle_time;
    public $user_ID;
    public $license_plate;
    public $park_location;
    public $price;
    public $condition;
    public $comments;
    public $status;

    /*
    {
    Status: dropoff,Picked UP, Completed,Cancelled,In progress
    }
    */

    private $TABLE_ORDER_ID = 'ORDER_ID';
    private $TABLE_CONFIRM_TIME = 'ORDER_CONFIRMED_TIME';
    private $TABLE_REG_START = 'REGIST_START_TIME';
    private $TABLE_REG_END = 'REGIST_END_TIME';
    private $TABLE_ACT_START = 'ACTUAL_START_TIME';
    private $TABLE_ACT_END = 'ACTUAL_END_TIME';
    private $TABLE_CANCEL = 'CANCEL_TIME';
    private $TABLE_USER_ID = 'ORDER_USER_ID';
    private $TABLE_LICENSE_PLATE = 'VEHICLE_LICENSE_PLATE';
    private $TABLE_PARK_LOCATION = 'VEHICLE_PARK_LOCATION';
    private $TABLE_PRICE = 'PRICE';
    private $TABLE_CONDITION = 'VEHICLE_CONDITION';
    private $TABLE_COMMENT = 'COMMENTS';
    private $TABLE_STATUS = 'ORDER_STATUS';


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //GET ALL ORDER FROM ALL USER
    function getAllOrders(){
      $action = 'Get All Orders';
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ';';
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          array_push($result,$row);
        }

        return json_encode(array('status'=>'success','data'=>$result,'action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>$action));
    }

    //GET ORDER FOR ONE USER
    function getOrder($id){
      $action = 'Get Orders';
      $user = new user($this->conn);
      if(empty($user->getUserById($id))){
        return json_encode(array('status'=>'fail','message'=>'User not exists.','action'=>$action));
      }
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USER_ID.
                '="'
                .$id.
                '";';
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          array_push($result,$row);
        }

        return json_encode(array('status'=>'success','data'=>$result,'action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error'));
    }

    function getComplete($id){
      $action = 'Get Orders';
      $user = new user($this->conn);
      if(empty($user->getUserById($id)))return json_encode(array('status'=>'fail','message'=>'User not exists.','action'=>$action));
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USER_ID.
                '="'
                .$id.
                '" AND ('
                .$this->TABLE_STATUS.
                '="Complete" OR '
                .$this->TABLE_STATUS.
                '="Cancelled" ); '
                ;

      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          array_push($result,$row);
        }
        return json_encode(array('status'=>'success','data'=>$result,'action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error'));

    }

    function getInprogree($id){
      $action = 'Get Orders';
      $user = new user($this->conn);
      if(empty($user->getUserById($id)))return json_encode(array('status'=>'fail','message'=>'User not exists.','action'=>$action));
      $query = 'SELECT * FROM '
                .$this->TABLE_NAME.
                ' WHERE '
                .$this->TABLE_USER_ID.
                '="'
                .$id.
                '" AND '
                .$this->TABLE_STATUS.
                '="IN PROGRESS";';
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          array_push($result,$row);
        }
        return json_encode(array('status'=>'success','data'=>$result,'action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>$action));
    }


    //REQUIRED: USER ID, REG START/END,LICENSE PLATE, VEHICLE LOCATION.
    //RETURN JSON.
    function addNew(){
      $action = 'Add New Order.';
      $curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
      //CHECK IF USER HAVE INPROGRESS ORDER
      $query = 'SELECT COUNT(*) FROM ORDER_INFO WHERE (ORDER_USER_ID="'.$this->user_ID.'") AND (ORDER_STATUS="In Progress" OR ORDER_STATUS = "Picked Up");';

      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $data = $stmt->fetchColumn();
      // echo $query;
      if($data>0){
        return json_encode(array('status'=>'fail','message'=>'User have Un-done Order.'));
      }


      // CHECK IF VEHICLE AVAILABLE
      $query = 'SELECT VEHICLE_STATUS FROM VEHICLE_INFO WHERE LICENSE_PLATE = "'
                .$this->license_plate.'";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $data = $stmt->fetchColumn();
      if($data!=='available'){
        return json_encode(array('status'=>'fail','message'=>'Selected Vehicle Unavailable'));
      }
      // CREATE NEW ORDER INFORMATION
      $query = 'INSERT INTO '
                .$this->TABLE_NAME.
                ' SET '
                .$this->TABLE_CONFIRM_TIME.'="'.$curr_time->format('Y-m-d H:i:s').
                '" , '
                .$this->TABLE_REG_START.'="'.$this->register_start.
                '" , '
                .$this->TABLE_REG_END.'="'.$this->register_end.
                '" , '
                .$this->TABLE_USER_ID.'="'.$this->user_ID.
                '" , '
                .$this->TABLE_LICENSE_PLATE.'="'.$this->license_plate.
                '" , '
                .$this->TABLE_PARK_LOCATION.'="'.$this->park_location.
                '" , '
                .$this->TABLE_STATUS.'="In Progress";';
      // echo "$query";
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        // GET CURRENT ORDER ID
        $getID_query = 'SELECT '
                  .$this->TABLE_ORDER_ID.
                  ' FROM '
                  .$this->TABLE_NAME.
                  ' WHERE '
                  .$this->TABLE_CONFIRM_TIME.'="'.$curr_time->format('Y-m-d H:i:s').
                  '" AND '
                  .$this->TABLE_REG_START.'="'.$this->register_start.
                  '" AND '
                  .$this->TABLE_REG_END.'="'.$this->register_end.
                  '" AND '
                  .$this->TABLE_USER_ID.'="'.$this->user_ID.
                  '" AND '
                  .$this->TABLE_LICENSE_PLATE.'="'.$this->license_plate.
                  '" AND '
                  .$this->TABLE_PARK_LOCATION.'="'.$this->park_location.
                  '" AND '
                  .$this->TABLE_STATUS.'="In Progress";';
          $getID = $this->conn->prepare($getID_query);
          $getID ->execute();
          $id = $getID->fetchColumn();
          // echo $id;
          $v = new vehicle($this->conn);
          $v->vehiclePlate = $this->license_plate;
          $v_id = $v->getIDByPlate();
          //UPDATE VEHICLE INFO
          $query = 'UPDATE VEHICLE_INFO SET VEHICLE_STATUS = "In Use", ORDER_ID = "'
                  .$id.'" WHERE VEHICLE_ID = "'.$v_id.'";';
          // echo $query;
          $stmt = $this->conn->prepare($query);
          $stmt ->execute();
          // $id = $getID->fetchColumn();
        return json_encode(array('status'=>'success','action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>$action));
    }

    function pickUp($orderID){
      //CHECK IF ORDER EXISTS.
      //GET ORDER INFORMATION
      //CHECK IF ORDR IS In Progress : ONLY
      //UPDATE ACTUAL PICKUP TIME
      //UPDATE STATUS TO Picked Up

      $curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
      $action = 'pick up';
      $vehicle = new vehicle($this->conn);

      //CHECK IF ORDER EXISTS.
      if(empty($this->checkExists($orderID)))
      {
        return json_encode(array('status'=>'fail','message'=>'Order Not Exists.','action'=>$action));
      }

      //GET ORDER INFORMATION
      $query = 'SELECT * FROM '.$this->TABLE_NAME.' WHERE '.$this->TABLE_ORDER_ID.'="'.$orderID.'";';

      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $order_data = $stmt->fetch(PDO::FETCH_ASSOC);
      // echo json_encode($row);

      //CHECK IF ORDR IS In Progress : ONLY
      if($order_data['ORDER_STATUS']!='In Progress'){
        return json_encode(array('status'=>'fail','message'=>'Unable to Pickup','action'=>$action));
      }
      //UPDATE ACTUAL PICKUP TIME
      //UPDATE STATUS TO Picked Up
      $query = 'UPDATE '.$this->TABLE_NAME.' SET '
                .$this->TABLE_ACT_START. '="'.$curr_time->format('Y-m-d H:i:s').'", '
                .$this->TABLE_STATUS. '="Picked Up" WHERE '
                .$this->TABLE_ORDER_ID.'="'.$orderID.'";';
      // return $query;
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        return json_encode(array('status'=>'success','action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>$action));
    }

    //REQUIRED : ORDERID,CONDITION,COMMENTS.
    function dropOff($orderID,$condition,$comments){
      //CHECK Exists
      //GET ORDER DATA
      //ONLY PICKED UP ORDER CAN dropOff
      //UPDATE STATUS=COMPLETE, ACT DROP OFF TIME=CURR.
      //ALSO UPDATE COMMENTS AND CONDITION
      //GET VEHICLE ID FROM ITS Plate
      //UPDATE VEHICLE STATUS TO AVAILABLE, SET ORDER ID TO 0
      //ALSO UPATE LAST SERVEICED TIME TO DROPOFF TIME

      $curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
      $action = 'Drop Off';
      //CHECK IF ORDER EXISTS.
      if(empty($this->checkExists($orderID)))
      {
        return json_encode(array('status'=>'fail','message'=>'Order Not Exists.','action'=>$action));
      }
      //GET ORDER INFORMATION
      $query = 'SELECT * FROM '.$this->TABLE_NAME.' WHERE '.$this->TABLE_ORDER_ID.'="'.$orderID.'";';

      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $order_data = $stmt->fetch(PDO::FETCH_ASSOC);
      //CHECK IF ORDR IS In Progress : ONLY
      if($order_data['ORDER_STATUS']!='Picked Up'){
        return json_encode(array('status'=>'fail','message'=>'Unable to Drop Off','action'=>$action));
      }

      //UPDATE STATUS=COMPLETE, ACT DROP OFF TIME=CURR.
      //ALSO UPDATE COMMENTS AND CONDITION
      $query = 'UPDATE '.$this->TABLE_NAME.' SET '
                .$this->TABLE_ACT_END. '="'.$curr_time->format('Y-m-d H:i:s').'", '
                .$this->TABLE_STATUS. '="Complete",'
                .$this->TABLE_CONDITION. '="'.$condition.'",'
                .$this->TABLE_COMMENT. '="'.$comments.'"'.
                ' WHERE '
                .$this->TABLE_ORDER_ID.'="'.$orderID.'";';
      // echo $query;
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        //GET VEHICLE ID FROM ITS Plate
        //UPDATE VEHICLE STATUS TO AVAILABLE, SET ORDER ID TO 0
        //ALSO UPATE LAST SERVEICED TIME TO DROPOFF TIME
        $vehicle = new vehicle($this->conn);
        $vehicle->vehiclePlate = $order_data[$this->TABLE_LICENSE_PLATE];
        $vehicle_id = $vehicle->getIDByPlate();
        $query = 'UPDATE VEHICLE_INFO SET VEHICLE_STATUS = "available", ORDER_ID = "'
                .'0'.'",LAST_SERVICED_TIME="'.$curr_time->format('Y-m-d H:i:s').'" WHERE VEHICLE_ID = "'.$vehicle_id.'";';

        $stmt = $this->conn->prepare($query);
        $stmt ->execute();
        return json_encode(array('status'=>'success','action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>$action));
    }


    //REQUIRED : ORDERID,
    function cancel($orderID){
      //ONLY IN PROGRESS STATUS CAN BE Cancelled
      //UPDATE CANCEL TIME
      //UPDATE ORDER STATUS TO CANCELLED
      //GET VEHICLE ID FROM ITS Plate
      //UPDATE VEHICLE STATUS TO AVAILABLE
      //UPDATE VEHICLE ORDER TO 0

      $curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
      $action = 'Cancel ';
      $vehicle = new vehicle($this->conn);

      //CHECK IF ORDER EXISTS.
      if(empty($this->checkExists($orderID)))
      {
        return json_encode(array('status'=>'fail','message'=>'Order Not Exists.','action'=>$action));
      }
      //GET ORDER INFORMATION
      $query = 'SELECT * FROM '.$this->TABLE_NAME.' WHERE '.$this->TABLE_ORDER_ID.'="'.$orderID.'";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $order_data = $stmt->fetch(PDO::FETCH_ASSOC);
      // echo json_encode($row);
      //CHECK IF ORDR IS In Progress : ONLY
      if($order_data['ORDER_STATUS']!='In Progress'){
        return json_encode(array('status'=>'fail','message'=>'Unable to Cancel','action'=>$action));
      }
      //UPDATE STATUS=Cancelled, ACT CANCEL  TIME=CURR.
      //ALSO UPDATE COMMENTS AND CONDITION
      $query = 'UPDATE '.$this->TABLE_NAME.' SET '
                .$this->TABLE_CANCEL. '="'.$curr_time->format('Y-m-d H:i:s').'", '
                .$this->TABLE_STATUS. '="Cancelled" '.
                ' WHERE '
                .$this->TABLE_ORDER_ID.'="'.$orderID.'";';
      // echo $query;
      $stmt = $this->conn->prepare($query);
      if($stmt->execute()){
        //GET VEHICLE ID FROM ITS Plate
        //UPDATE VEHICLE STATUS TO AVAILABLE, SET ORDER ID TO 0
        //ALSO UPATE LAST SERVEICED TIME TO DROPOFF TIME
        $vehicle = new vehicle($this->conn);
        $vehicle->vehiclePlate = $order_data[$this->TABLE_LICENSE_PLATE];
        $vehicle_id = $vehicle->getIDByPlate();
        $query = 'UPDATE VEHICLE_INFO SET VEHICLE_STATUS = "available", ORDER_ID = "'
                .'0'.'",LAST_SERVICED_TIME="'.$curr_time->format('Y-m-d H:i:s').'" WHERE VEHICLE_ID = "'.$vehicle_id.'";';

        $stmt = $this->conn->prepare($query);
        $stmt ->execute();
        return json_encode(array('status'=>'success','action'=>$action));
      }
      return json_encode(array('status'=>'fail','message'=>'Connection Error','action'=>$action));

    }


    function getPrice($orderID){
      $action = 'Calculating Price.';
      //CHECK IF ORDER EXISTS.
      if(empty($this->checkExists($orderID)))
      {
        return json_encode(array('status'=>'fail','message'=>'Order Not Exists.','action'=>$action));
      }
      //GET ORDER INFORMATION
      $query = 'SELECT * FROM '.$this->TABLE_NAME.' WHERE '.$this->TABLE_ORDER_ID.'="'.$orderID.'";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $order_data = $stmt->fetch(PDO::FETCH_ASSOC);
      $user_id = $order_data[$this->TABLE_USER_ID];
      $vehicle_plate = $order_data[$this->TABLE_LICENSE_PLATE];
      $status = $order_data[$this->TABLE_STATUS];
      $vehicle = new vehicle($this->conn);
      $vehicle->vehiclePlate = $vehicle_plate;
      $vehicle_id = $vehicle->getIDByPlate();
      $vehicle_type = $vehicle->getTypeByPlate();
      $query = 'SELECT * FROM TYPE_INFO WHERE TYPE_NAME = "'.$vehicle_type.'";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $type_data = $stmt->fetch(PDO::FETCH_ASSOC);
      $type_rate = $type_data['TYPE_RATE'];
      $query = 'SELECT * FROM SYSTEM_INFO;';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $system_data = $stmt->fetch(PDO::FETCH_ASSOC);
      $T = '';
      if($status === 'Picked Up' || $status ==='In Progress'){
        return json_encode(array('status'=>'fail','message'=>'Order still in progress'));
      }
      if($status === 'Complete'){
        //charge as T
        $T=$this->timeDiffInHours($order_data[$this->TABLE_ACT_START],$order_data[$this->TABLE_ACT_END]);
      }
      if($status === 'Cancelled'){
        //if T <=0, no charge. other wise charge as T
        $T=$this->timeDiffInHours($order_data[$this->TABLE_REG_START],$order_data[$this->TABLE_CANCEL])+1;
      }
      // return $T;
      $query = 'SELECT * FROM DRIVER_LICENSE WHERE USER_ID = "'.$user_id.'";';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $license = $stmt->fetch(PDO::FETCH_ASSOC);
      $birth = $license['DRIVER_BIRTHDAY'];
      $confirm_time = $order_data[$this->TABLE_CONFIRM_TIME];
      $age = $this->age($birth,$confirm_time);
      // return $age;
      $age_discount = 1;
      if($age>=25){
        $age_discount = $system_data['DISCOUNT_OVER_25'];
      }
      // return $age_discount;
      $time_discount = $system_data['TIME_RANGE_DISCOUNT_THREE'];
      if($T<$system_data['TIMEPOINT_ONE']){
        $time_discount = $system_data['TIME_RANGE_DISCOUNT_TWO'];
      }else if($T<$system_data['TIMEPOINT_TWO']){
        $time_discount = $system_data['TIME_RANGE_DISCOUNT_ONE'];
      }

      $price = -1;
      if($status === 'Cancelled'){
        if($T<=0){
          $price = 0;
        }else{
          $price =  $age_discount * $time_discount * $type_rate;
        }
      }else if($status === 'Complete'){
        $T = ($T == 0)? 1 : $T;
        // echo "$T*$age_discount * $time_discount * $type_rate - ";
        // echo "ceiling:"+ceil($T);
        $price = ceil($T)*$age_discount * $time_discount * $type_rate;
      }
      return json_encode(array('status'=>'success','price'=>$price));
    }

    function checkExists($id){
        $query = 'SELECT '.$this->TABLE_ORDER_ID.' FROM '.$this->TABLE_NAME.' WHERE '
                  .$this->TABLE_ORDER_ID.
                  '="'
                  .$id.
                  '";';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        // echo $query;
        return $stmt->fetchColumn();
    }

    //if return negative: time1 is later than time2;
    //return in hours
    function timeDiffInHours($str_time1,$str_time2){
      // echo "$str_time2    -     $str_time1  \n";
      $date1 = new DateTime($str_time1);
      $date2 = new DateTime($str_time2);
      $factor = 1;
      if($date1>$date2){
        $factor = -1;
      }
      $diff = $date2->diff($date1);
      $hours = $diff->h;
      $hours = $hours + ($diff->days*24);
      $minut = $diff->i;
      $hours = $hours + ($minut/60);
      return $hours*$factor;
    }

    function age($str_time1,$str_time2){
      // $curr_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
      $date1 = new DateTime($str_time1);
      $date2 = new DateTime($str_time2);
      $diff = $date1->diff($date2);
      $y = $diff->y;
      return $y;
    }

}
?>
