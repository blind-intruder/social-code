<?php
error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(404);
		header("Content-Type: text/html");// to tell browser about the data type
    die("<h1>Server don't accept this type of request</h1>");
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
if (isset($_POST["data"])) {
  require("../../../db-con/db.php");
	try{
		$data = json_decode(base64_decode($_POST["data"]));
       	$npass=$data->npass;
        $opass=$data->opass;

        //convert password into (sha512(md5(password)))//
        $npass = hash("sha512", $npass);
        $npass = md5($npass);

        $opass = hash("sha512", $opass);
        $opass = md5($opass);


        //SQL injection sanitization//
        $opass=mysqli_real_escape_string($conn,$opass);
        $npass=mysqli_real_escape_string($conn,$npass);
        //SQL injection sanitization//

        checklogin($opass,$npass);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Something went wrong"
          );
          echo json_encode($res);
          http_response_code(401);
          die(); 
	}
}
else{
	$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Something went wrong"
          );
          echo json_encode($res);
          http_response_code(401);
          die(); 
}

function checklogin($opass,$npass){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("../../../db-con/db.php");
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
          changepass($uid,$opass,$npass);
     }
     else{
      $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Your are not logged in."
          );
          echo json_encode($res);
          http_response_code(401);
          die();
     }     

	}
	else{
	$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "You are not logged in."
          );
          echo json_encode($res);
          http_response_code(401);
          die();
	} 
}

function changepass($id,$opass,$npass){
	require("../../../db-con/db.php");
  if (checkoldpass($id,$opass)) {
    $sql = "UPDATE users SET password='$npass' where id='$id'";
    if (mysqli_query($conn, $sql)) {
      $res= array(
            'status' => '200 OK',
            'isupdated' => "true",
            'msg' => "Password changed."
          );
          echo json_encode($res);
          die();
    }
    else{
      $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Something went wrong."
          );
          echo json_encode($res);
          die();
    }
  }
  else{
    $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Current password entered is wrong."
          );
          echo json_encode($res);
          die();
  }
}

function checkoldpass($id,$opass){
  require("../../../db-con/db.php");
  $sql = "SELECT * FROM users where password='$opass' and id='$id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) >0) {
    return true;
  }
  else{
    return false;
  }
}

?>