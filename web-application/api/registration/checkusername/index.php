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
       $uname=$data->username;

       //----------validation----------//
       if (empty($uname)) {
         $res= array(
            'status' => '200 OK',
            'ispresent' => "error",
            'msg' => "Username is empty"
          );
          echo json_encode($res);
          die();
       }
       if (strlen($uname)<6) {
         $res= array(
            'status' => '200 OK',
            'ispresent' => "error",
            'msg' => "Username must be greater than 5 characters."
          );
          echo json_encode($res);
          die();
       }
       if (!preg_match("/^[a-zA-Z0-9]*$/",$uname)) {
          $res= array(
            'status' => '200 OK',
            'isregister' => "error",
            'msg' => "Username is invalid"
          );
          echo json_encode($res);
          die();
        }

        //HTML sanitization//
        $uname=htmlspecialchars($uname);
       //HTML sanitization//

        //SQL injection sanitization//
        $uname=mysqli_real_escape_string($conn,$uname);
        //SQL injection sanitization//

       checkusername($uname);
	}
	catch(Exception $e){
    $res= array(
      'status' => '200 OK',
      'isregister' => "error",
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
    'isregister' => "error",
    'msg' => "something went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}

function checkusername($uname)
{
	require("../../db-con/db.php");
	$sql = "SELECT * FROM users where uid='$uname'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
    	$res= array(
       		'status' => '200 OK',
    		'ispresent' => "true",
        'msg' => "Username already exists"
    	);
    	echo json_encode($res);
	} 
	else {
        $res= array(
       		'status' => '200 OK',
    		'ispresent' => "false",
        'msg' => "ok"
    	);
    	echo json_encode($res);	
	}
}
?>
