<?php
//error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(404);
		header("Content-Type: text/html");// to tell browser about the data type
    die("<h1>Server don't accept this type of request</h1>");
}
$s_type="";
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
if (isset($_POST["data"])) {
  require("../../db-con/db.php");
	try{
		$data = json_decode(base64_decode($_POST["data"]));
       	$type=$data->type;
        $text=$data->text;
        $media=$data->media;
        $bg=$data->bg_color;
        $font=$data->font;

        //validation
        if ($type!="text" && $type!="media") {
          //invalid type of status
          $res= array(
              'status' => '200 OK',
              'isuplaoded' => "error",
              'msg' => "Invalid status type"
              );
              echo json_encode($res);
              http_response_code(200);
              die(); 
        }

        if ($type=="text") {
          if (strlen($text)>0 && strlen($text)<250) {
            //do nothing

            $s_type=$type;
          }
          else{
              $res= array(
              'status' => '200 OK',
              'isuplaoded' => "error",
              'msg' => "Status text can't be empty in text status"
              );
              echo json_encode($res);
              http_response_code(200);
              die(); 
          }
        }

        $str=$bg;
        $pattern = "/rgb\((?:\s*[\d*\.]+\s*,){2}\s*[\d*\.]+\)/i";
        if(preg_match($pattern, $str)){
          //do nothing
        }
        else{
          $pattern2 = "/rgba\((?:\s*[\d*\.]+\s*,){3}\s*[\d*\.]+\)/i";
          if(preg_match($pattern2, $str)){

          }
          else{
            $bg="rgb(5, 205, 81)";
          }
        }
        $font=str_replace('"',"",$font);

        $bg=mysqli_real_escape_string($conn,$bg);
        $font=mysqli_real_escape_string($conn,$font);
        $text=base64_encode($text);
        $bg=base64_encode($bg);

        checklogin($text,$bg,$font);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'isuplaoded' => "error",
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
            'isuplaoded' => "error",
            'msg' => "Something went wrong"
          );
          echo json_encode($res);
          http_response_code(401);
          die(); 
}

function checklogin($text,$bg_color,$font){
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
          upload_status($uid,$text,$bg_color,$font);
     }
     else{
      $res= array(
            'status' => '200 OK',
            'isuplaoded' => "error",
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
            'isuplaoded' => "error",
            'msg' => "You are not logged in."
          );
          echo json_encode($res);
          http_response_code(401);
          die();
	} 
}

function upload_status($id,$text,$bg_color,$font){
	require("../../db-con/db.php");
  $date=date("Y-m-d", strtotime("now"));
  $type=$GLOBALS['s_type'];
  if ($GLOBALS['s_type']=="text") {
    //status is text
    $sql = "INSERT INTO `status`(`user_id`, `type`, `status_text`, `media`, `time_uploaded`, `bg_color`, `font`) VALUES ($id,'$type','$text','none','$date','$bg_color','$font')";
    if (mysqli_query($conn, $sql)) {
      $res= array(
            'status' => '200 OK',
            'isuplaoded' => "true",
            'msg' => "status has been added"
          );
          echo json_encode($res);
          http_response_code(200);
          die();
    }
    else{
      $res= array(
            'status' => '200 OK',
            'isuplaoded' => "error",
            'msg' => "can't add status"
          );
          echo json_encode($res);
          http_response_code(400);
          die();
    }
  }
  else{
    $res= array(
            'status' => '200 OK',
            'isuplaoded' => "error",
            'msg' => "Invalid status type"
          );
          echo json_encode($res);
          http_response_code(400);
          die();
  }
}

?>