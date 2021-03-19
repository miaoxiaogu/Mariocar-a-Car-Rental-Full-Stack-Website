<!-- <?php echo exec('whoami'); ?> -->
<html>
   <body>

      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="sqlfile" />
         <input type="submit" value="Submit and Process"/>
      </form>

   </body>
</html>

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

   if(isset($_FILES['sqlfile'])){
      $errors= array();
      $file_name = $_FILES['sqlfile']['name'];
      $file_size =$_FILES['sqlfile']['size'];
      $file_tmp =$_FILES['sqlfile']['tmp_name'];
      $file_type=$_FILES['sqlfile']['type'];
      // $file_ext=strtolower(end(explode('.',$_FILES['sqlfile']['name'])));
      $tmp = explode('.', $file_name);
      $file_ext = end($tmp);
      $extensions= array("sql");

      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose sql file.";
      }

      // if($file_size > 2097152){
      //    $errors[]='File size must be excately 2 MB';
      // }

      if(empty($errors)==true){
        if(file_exists($file_name)) {
          chmod($file_name,0755); //Change the file permissions if allowed
          unlink($file_name); //remove the file
        }
         if($result = move_uploaded_file($file_tmp,"upload/".$file_name)){
             echo "<br>Uploaded Success.$result";
             process($file_name);
         }else{
           echo "<br>Uploaded Failed.";
         }
      }else{
         print_r($errors);
      }
   }

   function process($file_name){
     $data = file_get_contents("upload/".$file_name);
     if(!$data){
      echo "<br>File upload failed.<br>";
     }
     // echo "-> ".$data;
     // echo "<br>Processed Success";
     $host = "127.0.0.1";
     $username = "root";
     $password = "19960502";
     // echo 'running';
     try{
       $db = new PDO("mysql:host=".$host, $username, $password);
       // $sql = file_get_contents('file.sql');
       // echo $db;
       if($qr = $db->exec($data)){
         echo "<br>Processed Success.";
       }
     } catch (Exception $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
    }
   }

?>
