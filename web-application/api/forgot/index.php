<?php
error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
		header("Content-Type: text/html");// to tell browser about the data type
        die("<h2>404 Not found</h2>");
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
if (isset($_POST["data"])) {
	require("../db-con/db.php");
	try{ 

       $data = json_decode(base64_decode($_POST["data"]));
       $email=$data->email;

       //----------validation----------//
       if (empty($email)) {
         $res= array(
            'status' => '200 OK',
            'ispresent' => "error",
            'msg' => "Email is empty"
          );
          echo json_encode($res);
          die();
       }
       //email validation//
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          //just pass it//
        } else {
          $res= array(
            'status' => '200 OK',
            'ispresent' => "error",
            'msg' => "Email is invalid"
          );
          echo json_encode($res);
          die();
        }
        //email validation//

        //HTML sanitization//
        $email=htmlspecialchars($email);
       //HTML sanitization//

        //SQL injection sanitization//
        $email=mysqli_real_escape_string($conn,$email);
        //SQL injection sanitization//
       checkemail($email);
	}
	catch(Exception $e){
     $res= array(
            'status' => '200 OK',
            'ispresent' => "error",
            'msg' => "something went wrong"
          );
          echo json_encode($res);
          http_response_code(401);
          die();
	}
}
else{
  $res= array(
    'status' => '200 OK',
    'ispresent' => "error",
    'msg' => "something went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}

function checkemail($email)
{
	require("../db-con/db.php");
	$sql = "SELECT * FROM users where email='$email'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 1) {

		while($row = mysqli_fetch_assoc($result)) {
        	$username=$row["uid"];
        	$id=$row["id"];
    	}
    	sendmail($email,$id,$uid);
	} 
	else {
        $res= array(
       		'status' => '200 OK',
    		'ispresent' => "false",
        	'msg' => "Email not found"
    	);
    	echo json_encode($res);	
    	die();
	}
}


function sendmail($email,$id,$uid){

  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $scheme = 'https://';
  } else {
    $scheme = 'http://';
  }
	$host=$scheme.$_SERVER['SERVER_NAME'];
   $to_email = $email;
   $subject = "Reset Password";
   $token=md5(time().$id.time().$uid.time().$email.time()).md5(time().$email.time());
   $body = "Your token to reset password is: ".$host."/fyp/forgot/?token=".$token;
   $headers = "From: ktwosocial@gmail.com";
 
   if ( mail($to_email, $subject, $body, $headers)) {
   	store_token($token,$id);
   } else {
      $res= array(
       		'status' => '200 OK',
    		'ispresent' => "true",
        	'msg' => "Something went wrong"
    	);
    	echo json_encode($res);
    	die();
   }

}

function store_token($token,$id){
  require("../db-con/db.php");
  $date=date("m-d-y");
	$sql = "UPDATE users SET forgot_token='$token', token_date='$date' where id='$id'";
	if (mysqli_query($conn, $sql)) {
    	$res= array(
       		'status' => '200 OK',
    		'issent' => "true",
        	'msg' => "Email sent!"
    	);
    	echo json_encode($res);
    	die();
	}
	else  {
		$res= array(
       		'status' => '200 OK',
    		'ispresent' => "true",
        	'msg' => "Something went wrong."
    	);
    	echo json_encode($res);
    	die();
	}
}
?>
