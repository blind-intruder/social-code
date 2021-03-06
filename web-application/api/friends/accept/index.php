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
            'issent' => "false",
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

       if (check_login()!=0 && !isset($_GET['action'])) {
         accept_request(check_login(),$id);
       }
       if (check_login()!=0 && isset($_GET['action'])) {
         delete_request(check_login(),$id);
       }
	}
	catch(Exception $e){
     $res= array(
            'status' => '200 OK',
            'issent' => "false",
            'msg' => "Something went wrong"
          );
          echo json_encode($res);
          die();
	}
}

function accept_request($my_id,$uid)
{
	require("../../db-con/db.php");
	$sql = "INSERT into friends (one,two) values ('$my_id','$uid')";
	if (mysqli_query($conn, $sql)) {
    add_two($my_id,$uid);
  }
  else{
    $res= array(
            'status' => '200 OK',
            'issent' => "false",
            'msg' => "friend not deleted"
          );
          echo json_encode($res);
          die();
  }
}

function add_two($my_id,$uid){
  require("../../db-con/db.php");
  $sql = "INSERT into friends (one,two) values ('$uid','$my_id')";
  if (mysqli_query($conn, $sql)) {
    delete_request($my_id,$uid);
  }
  else{
    $res= array(
            'status' => '200 OK',
            'issent' => "false",
            'msg' => "friend not deleted"
          );
          echo json_encode($res);
          die();
  }
}

function delete_request($my_id,$uid)
{
  require("../../db-con/db.php");
  $sql = "DELETE from requests where sender='$uid' and receiver='$my_id'";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    $res= array(
            'status' => '200 OK',
            'issent' => "true",
            'msg' => "friend request action performed"
          );
    echo json_encode($res);
          die();
  }
  else{
    $res= array(
            'status' => '200 OK',
            'issent' => "false",
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
