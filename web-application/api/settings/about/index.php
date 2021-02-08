<?php
error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(404);
		header("Content-Type: text/html");// to tell browser about the data type
    die("<h1>Server don't accept this type of request</h1>");
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
if (isset($_POST["data"])) {
  require("../../db-con/db.php");
	try{
		$data = json_decode(base64_decode($_POST["data"]));
       	$country=$data->country;
       	$city=$data->city;
       	$workplace=$data->workplace;
       	$worktitle=$data->worktitle;
       	$classname=$data->classname;
       	$institue=$data->institue;

       	//validation
       	if (empty($country)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Country is required."
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($city)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "City is required."
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($workplace)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Workplace is required."
          );
          echo json_encode($res);
          die(); 
       	}

       	if (empty($worktitle)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Job title is required."
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($classname)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Class/Degree is required."
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($institue)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "University/school/college is required."
          );
          echo json_encode($res);
          die(); 
       	}
       	if (!preg_match("/^[a-zA-Z ]*$/",$country) || !preg_match("/^[a-zA-Z ]*$/",$city)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Location is invalid."
          );
          echo json_encode($res);
          die(); 
       	}
       	if (!preg_match("/^[a-zA-Z0-9 ]*$/",$workplace) || !preg_match("/^[a-zA-Z0-9 ]*$/",$worktitle)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Job is invalid."
          );
          echo json_encode($res);
          die(); 
       	}
       	if (!preg_match("/^[a-zA-Z0-9 ]*$/",$classname) || !preg_match("/^[a-zA-Z0-9 ]*$/",$institue)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Education entered is invalid."
          );
          echo json_encode($res);
          die(); 
       	}

       	//html sanitization
        $country=htmlspecialchars($country);
        $city=htmlspecialchars($city);
        $classname=htmlspecialchars($classname);
        $institue=htmlspecialchars($institue);
        $workplace=htmlspecialchars($workplace);
        $worktitle=htmlspecialchars($worktitle);

        //sqli sanitization
        $country=mysqli_real_escape_string($conn,$country);
        $city=mysqli_real_escape_string($conn,$city);
        $classname=mysqli_real_escape_string($conn,$classname);
        $institue=mysqli_real_escape_string($conn,$institue);
        $workplace=mysqli_real_escape_string($conn,$workplace);
        $worktitle=mysqli_real_escape_string($conn,$worktitle);

        $work=$worktitle." at ".$workplace;
        $education=$classname." from ".$institue;
        $Location=$city.",".$country;

        //base64 encode
        $work=base64_encode($work);
        $education=base64_encode($education);
        $Location=base64_encode($Location);


        checklogin($work,$education,$Location);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "something went wrong."
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
    'msg' => "something went wrong."
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}

function checklogin($work,$education,$location){
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
          updateabout($uid,$work,$education,$location);
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
      'msg' => "Your are not logged in."
    );
    echo json_encode($res);
    http_response_code(401);
    die();
  } 
}

function updateabout($id,$work,$education,$location){
	require("../../db-con/db.php");
	$sql = "UPDATE users SET work='$work', education='$education', location='$location' WHERE id='$id'";
	if (mysqli_query($conn, $sql)) {
		$res= array(
            'status' => '200 OK',
            'isupdated' => "true",
            'msg' => "Updated."
          );
          echo json_encode($res);
          die();
	}
	else{
		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "something went wrong."
          );
          echo json_encode($res);
          die();
	}
}

?>