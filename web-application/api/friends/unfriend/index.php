<?php

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  $scheme = 'https://';
} else {
  $scheme = 'http://';
}
$host=$scheme.$_SERVER['SERVER_NAME'];

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
		header("Content-Type: text/html");// to tell browser about the data type
        die("<h2>404</h2>");
}

if (isset($_POST["data"])) {
  header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
  require("../../db-con/db.php");
	try{

       $data = json_decode(base64_decode($_POST["data"]));
       $id=$data->id;
     

       //--------------validation--------------//

       //if variable is empty//
       if (empty($id)) {
         $res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
            'msg' => "id is required"
          );
          echo json_encode($res);
          die();
       }
       //if variable is empty//

       if (!preg_match("/^[0-9]*$/",$id)) {
          $res= array(
            'status' => '200 OK',
            'iscreate' => "error",
            'msg' => "Invalid  id"
          );
          echo json_encode($res);
          die();
         }

        //SQL injection sanitization//
        $id=mysqli_real_escape_string($conn,$id);
        //SQL injection sanitization//

       if (check_login()!=0) {
         unfriend(check_login(),$id);
       }
	}
	catch(Exception $e){
     $res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
            'msg' => "Something went wrong"
          );
          echo json_encode($res);
          die();
	}
}

function unfriend($my_id,$uid)
{
	require("../../db-con/db.php");
	$sql = "DELETE FROM friends WHERE (one='$my_id' and two='$uid')";
	$result = mysqli_query($conn, $sql);
	if (mysqli_query($conn, $sql)) {
    unfriend1($my_id,$uid);
  }
  else{
    $res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
            'msg' => "friend not deleted"
          );
          echo json_encode($res);
          die();
  }
}

function unfriend1($my_id,$uid)
{
  require("../../db-con/db.php");
  $sql = "DELETE FROM friends WHERE (two='$my_id' and one='$uid')";
  $result = mysqli_query($conn, $sql);
  if (mysqli_query($conn, $sql)) {
    $res= array(
            'status' => '200 OK',
            'isdeleted' => "true",
            'msg' => "friend deleted"
          );
          echo json_encode($res);
          die();
  }
  else{
    $res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
            'msg' => "friend not deleted"
          );
          echo json_encode($res);
          die();
  }
}


function check_login(){
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
    if (mysqli_num_rows($result)==1) {
      while($row = mysqli_fetch_assoc($result)) {
        $id=$row["uid"];
      }
      return $id;
    }
    else{
      return 0;
    }

  }
  return 0;
}


?>
