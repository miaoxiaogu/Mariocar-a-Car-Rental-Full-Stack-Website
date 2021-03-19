<!-- <?php echo exec('whoami'); ?> -->
<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../database/config/setting.php';
$curr_url = $root_url . "/test";
//GET ALL TABLES NAME
// $table_names = file_get_contents($curr_url."/api/getTableName.php");
$table_names = getTableName();
// echo json_encode($table_names);
// echo $table_names;
foreach($table_names as $table_name){
  echo "<h2>".$table_name."</h2>";
  $columns = getColumn($table_name);
  $datas = getData($table_name);

  echo '<table border="1" >';
  echo '<tr>';
  foreach($columns as $c){
    echo '<th>'.$c.'</th>';
  }
  echo '</tr>';

  //TODO: INSERT DATA HERE
  //echo '<td>'.$DATA.'</td>';

  foreach ($datas as $v) {
    echo '<tr>';
    foreach ($columns as $c) {
      echo '<th>'.$v[$c].'</th>';
    }
    echo '</tr>';
  }
  echo '</table>';
}

{//TEST SECTION
  // $json = file_get_contents($curr_url."/api/testManager.php");
  // $obj = json_decode($json,true);
  // $keys = array_keys($obj);
}

function getTableName(){
    global $curr_url;
    $make_call = callAPI('GET', $curr_url."/api/getTableName.php", json_encode("1"));
    $response = json_decode($make_call, true);
    return $response;
}

function getColumn($tablename){
    $data_array =  array("table_name"=> $tablename);
    // echo $curr_url;
    global $curr_url;
    $make_call = callAPI('GET', $curr_url."/api/getTableColumn.php", json_encode($data_array));
    $response = json_decode($make_call, true);
    return $response;
}

function getData($tablename){
  $data_array =  array("table_name"=> $tablename);
  // echo $curr_url;
  global $curr_url;
  $make_call = callAPI('GET', $curr_url."/api/getTableData.php", json_encode($data_array));
  $response = json_decode($make_call, true);
  // echo $response;
  return $response;
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
</table>
