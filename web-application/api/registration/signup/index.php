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
       $email=$data->email;
       $pass=$data->password;
       $uid=$data->uid;
       $firstname=$data->firstname;
       $lastname=$data->lastname;
       $date=$data->date;
       $month=$data->month;
       $year=$data->year;

       $cdate=intval($date);
       $cyear=intval($year);

       //-----------------validation----------------//

       //Check if any variable is empty//
       if(empty($email) || empty($pass) || empty($uid) || empty($firstname) || empty($lastname) || empty($date) || empty($month) || empty($year)){
          if (empty($email)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Email is empty"
          );
          echo json_encode($res);
          die(); 
          }
          if (empty($pass)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Password is empty"
          );
          echo json_encode($res);
          die(); 
          }
          if (empty($uid)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Username is empty"
          );
          echo json_encode($res);
          die(); 
          }
          if (empty($firstname)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "First name is empty"
            );
          echo json_encode($res);
          die(); 
          }
          if (empty($lastname)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Last name is empty"
            );
          echo json_encode($res);
          die(); 
          }
          if (empty($date)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "date is empty"
            );
          echo json_encode($res);
          die(); 
          }
          if (empty($month)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "month is empty"
            );
          echo json_encode($res);
          die(); 
          }
          if (empty($year)) {
           $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Year is empty"
            );
          echo json_encode($res);
          die(); 
          }
       }
       //Check if any variable is empty//

       //username validation//
       if (!preg_match("/^[a-zA-Z0-9]*$/",$uid)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Username is invalid"
          );
          echo json_encode($res);
          die();
        }
        if (strlen($uid)<6) {
         $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Username must be greater than 5 characters."
          );
          echo json_encode($res);
          die();
       }
        //username validation//

        //first name validation//
        if (!preg_match("/^[a-zA-Z ]*$/",$firstname)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "First name is invalid"
          );
          echo json_encode($res);
          die();
        }
        //first name validation//

        //last name validation//
        if (!preg_match("/^[a-zA-Z ]*$/",$lastname)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Last name is invalid"
          );
          echo json_encode($res);
          die();
        }
        //last name validation//

        //date validation//
        if (!preg_match("/^[0-9]*$/",$date)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Date is invalid"
          );
          echo json_encode($res);
          die();
        }
        if ($cdate<1 || $cdate>31 || is_float($date)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Date is invalid"
          );
          echo json_encode($res);
          die();
        }
        //date validation//

        //month validation//
        if (!preg_match("/^[a-zA-Z]*$/",$month)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Month is invalid"
          );
          echo json_encode($res);
          die();
        }
        if ($month!="Jan" && $month!="Feb" && $month!="Mar" && $month!="Apr" && $month!="May" && $month!="Jun" && $month!="Jul" && $month!="Aug" && $month!="Sep" && $month!="Oct" && $month!="Nov" && $month!="Dec") {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Month is invalid"
          );
          echo json_encode($res);
          die();
        }
        //month validation//

        //year validation//
        if (!preg_match("/^[0-9]*$/",$year)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Year is invalid"
          );
          echo json_encode($res);
          die();
        }
        if ($cyear<1905 || $cyear>2010 || is_float($year)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Year is invalid"
          );
          echo json_encode($res);
          die();
        }
        //year validation//

        //email validation//
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          //just pass it//
        } else {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Email is invalid"
          );
          echo json_encode($res);
          die();
        }
        //email validation//


       //HTML sanitization//
        $email=htmlspecialchars($email);
        $uid=htmlspecialchars($uid);
        $firstname=htmlspecialchars($firstname);
        $lastname=htmlspecialchars($lastname);
        $date=htmlspecialchars($date);
        $month=htmlspecialchars($month);
        $year=htmlspecialchars($year);
       //HTML sanitization//

        //SQL injection sanitization//
        $email=mysqli_real_escape_string($conn,$email);
        $uid=mysqli_real_escape_string($conn,$uid);
        $firstname=mysqli_real_escape_string($conn,$firstname);
        $lastname=mysqli_real_escape_string($conn,$lastname);
        $date=mysqli_real_escape_string($conn,$date);
        $year=mysqli_real_escape_string($conn,$year);
        $month=mysqli_real_escape_string($conn,$month);
        $year=mysqli_real_escape_string($conn,$year);
        //SQL injection sanitization//

        //convert password into (sha512(md5(password)))//
          $passv1 = hash("sha512", $pass);
          $passv2 = md5($passv1);

          //check if email already exists
          checkemail($email);
          //check if email already exists

          //check if username already exists
          checkuid($uid);
          //check if username already exists

       registeruser($email,$passv2,$uid,$firstname,$lastname,$date,$month,$year);
	}
	catch(Exception $e){
     $res= array(
          'status' => '200 OK',
          'isregister' => "error",
          'msg' => "Some thing went wrong"
      );
      echo json_encode($res);
      http_response_code(401);
      die();
	}
}
else{
  $res= array(
    'status' => '200 OK',
    'isregister' => "error",
    'msg' => "something went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}

function checkemail($email){
  require("../../db-con/db.php");
  $sql = "SELECT * FROM users where email='$email'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
      $res= array(
          'status' => '200 OK',
          'isregister' => "error",
          'msg' => "Email already exists"
      );
      echo json_encode($res);
      die();
  }
}

function checkuid($uid){
  require("../../db-con/db.php");
  $sql = "SELECT * FROM users where uid='$uid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
      $res= array(
          'status' => '200 OK',
          'isregister' => "error",
          'msg' => "Username already exists"
      );
      echo json_encode($res);
      die();
  }
}

function registeruser($email,$pass,$uid,$firstname,$lastname,$date,$month,$year)
{
	require("../../db-con/db.php");
	$sql = "INSERT INTO users (uid, email, firstname, lastname,password,date,month,year) VALUES ('$uid', '$email', '$firstname', '$lastname','$pass',$date,'$month',$year)";
	$result = mysqli_query($conn, $sql);
	if ($result) {
    	$res= array(
       		'status' => '200 OK',
    		  'isregister' => "true",
          'msg' => "ok"
    	);
    	echo json_encode($res);
      die();
	} 
	else {
        $res= array(
       		'status' => '200 OK',
    		  'isregister' => "error",
          'msg' => "Some thing went wrong"
    	);
    	echo json_encode($res);
      die();	
	}
}



?>
