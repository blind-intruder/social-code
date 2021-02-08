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
         $text=$data->text;
         $f_id=$data->f_id;

         if (empty($f_id)) {
           $res= array(
            'status' => '200 OK',
            'iscreate' => "error",
            'msg' => "Enter the id of the receiver"
          );
          echo json_encode($res);
          die();
         }

         if (!preg_match("/^[0-9]*$/",$f_id)) {
          $res= array(
            'status' => '200 OK',
            'iscreate' => "error",
            'msg' => "Invalid receiver id"
          );
          echo json_encode($res);
          die();
         }

       	//validation
       	if (empty($text)) {
       		$res= array(
            'status' => '200 OK',
            'iscreate' => "error",
            'msg' => "Empty msg can't be sent."
          );
          echo json_encode($res);
          die(); 
       	}
              	
        //html sanitization
        //$text=htmlspecialchars($text);

        //sql sanitization
        $text=mysqli_real_escape_string($conn,$text);
        $f_id=mysqli_real_escape_string($conn,$f_id);

        checklogin($text,$f_id);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'iscreated' => "error",
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
    'iscreated' => "error",
    'msg' => "Somthing went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}
function checklogin($text,$f_id){
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
          create_msg($uid,$text,$f_id);
     }
     else{
      $res= array(
            'status' => '200 OK',
            'iscreated' => "error",
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
      'iscreated' => "error",
      'msg' => "Your are not logged in."
    );
    echo json_encode($res);
    http_response_code(401);
    die();
  }
}



function create_msg($id,$text,$f_id){ 
		require("../../db-con/db.php");
    if (!check_friend($id,$f_id)) {
      $res= array(
      'status' => '200 OK',
      'iscreated' => "error",
      'msg' => "receiver is not your friend"
      );
      echo json_encode($res);
      http_response_code(200);
      die();
    }
    $text=base64_encode($text);
    $date=date("j F Y h:i:s A");


    $sql = "INSERT INTO msg (sender_id,receiver_id,msg,status,time) VALUES ($id,$f_id,'$text','null','$date')";
    if (mysqli_query($conn, $sql)) {
     $res= array(
        'status' => '200 OK',
        'iscreated' => "true",
        'msg' => "sent",
        'date'=> $date,
      );
      echo json_encode($res);
      die();
    }
    else{
      $res= array(
        'status' => '200 OK',
        'iscreated' => "false",
        'msg' => "something went wrong",
      );
      echo json_encode($res);
      die();
    }
  }


function check_friend($id,$f_id){
  require("../../db-con/db.php");
  $sql = "SELECT * from friends where one='$id' and two='$f_id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) >0) {
    return true;
  }
  else{
    return false;
  }
}

?>