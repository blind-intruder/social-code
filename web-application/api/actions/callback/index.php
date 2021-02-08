<?php
//error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    http_response_code(400);
    die();
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  $scheme = 'https://';
} else {
  $scheme = 'http://';
}
$host=$scheme.$_SERVER['SERVER_NAME'];


if(checklogin()!=0){
	session_start();
	add_read(checklogin());
}
else{
  $res= array(
    'status' => '200 OK',
    'msg' => "You are not logged in."
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}



function add_read($id){
  require("../../db-con/db.php");
  $t=date("Y-m-d h:i:s");
  if (is_new($id)==false) {
    $sql = "UPDATE callback SET time='$t' WHERE user_id='$id'";
    if (mysqli_query($conn, $sql)) {
      $res= array(
          'status' => '200 OK',
          'msg' => "callback registered1"
        );
        echo json_encode($res);
        http_response_code(200);
        die();
    } 
  }
}


function is_new($id){
  require("../../db-con/db.php");
  $sql = "SELECT * from callback where user_id='$id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)>0) {
    return false;
  }
  else{
    $t=date("Y-m-d h:i:s");
    $sql1 = "INSERT into callback (user_id,time) values ('$id','$t')";
    if (mysqli_query($conn, $sql1)) {
      $res= array(
        'status' => '200 OK',
        'msg' => "callback registered"
      );
      echo json_encode($res);
      http_response_code(200);
      die();
    }
  }
}



function checklogin(){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("../../db-con/db.php");
      $json=$_COOKIE["json_token"];
      $ultra=$_COOKIE["ultra_cookie"];
      $k2=$_COOKIE["k2_cookie"];
      $extra=$_COOKIE["k2_extra"];
      $json=mysqli_real_escape_string($conn,$json);
      $ultra=mysqli_real_escape_string($conn,$ultra);
      $k2=mysqli_real_escape_string($conn,$k2);
      $extra=mysqli_real_escape_string($conn,$extra);

      $json=base64_encode($json);
      $ultra=base64_encode($ultra);
      $k2=base64_encode($k2);
      $extra=base64_encode($extra);
      $sql = "SELECT * FROM do_login where k2_cookie='$k2' and ultra_cookie='$ultra' and json_token='$json' and extra='$extra'";
      $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) >0) {
          while($row = mysqli_fetch_assoc($result)) {
          $uid=$row["uid"];
          }
          return $uid; 
     }
     else{
      return 0;
     }     

	}
	else{
    return 0;
	} 
}
?>
