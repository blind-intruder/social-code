<?php
error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
		header("Content-Type: text/html");// to tell browser about the data type
        die("<h2>Server don't accept this type of request!</h2>");
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
if (isset($_POST["data"])) {
	require("../../db-con/db.php");
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
            'isregister' => "error",
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
            'isregister' => "error",
            'msg' => "Somthing went wrong"
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
    'msg' => "Somthing went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}

function checkemail($email)
{
	require("../../db-con/db.php");
	$sql = "SELECT * FROM users where email='$email'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
    	$res= array(
       		'status' => '200 OK',
    		'ispresent' => "true",
        'msg' => "Email already exists."
    	);
      echo json_encode($res);
      die();
	} 
	else {
        $res= array(
       		'status' => '200 OK',
    		'ispresent' => "false",
        'msg' => "ok"
    	);
      echo json_encode($res);	
      die();
	}
}
?>
