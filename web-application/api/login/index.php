<?php

if (getcwd() == dirname(__FILE__)) {
    http_response_code(404);
    die();
}

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  $scheme = 'https://';
} else {
  $scheme = 'http://';
}
$host=$scheme.$_SERVER['SERVER_NAME'];

if ($_SERVER['REQUEST_METHOD'] !== "POST" && $_SERVER['REQUEST_METHOD'] !== "GET") {
		header("Content-Type: text/html");// to tell browser about the data type
        die("<h2>404</h2>");
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  header("Content-Type: text/html");// to tell browser about the data type
}
if (isset($_POST["data"])) {
  header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
  require("api/db-con/db.php");
	try{

       $data = json_decode(base64_decode($_POST["data"]));
       $email=$data->email;
       $pass=$data->password;
     

       //--------------validation--------------//

       //if variable is empty//
       if (empty($email)) {
         $res= array(
            'status' => '200 OK',
            'islogin' => "false",
            'msg' => "Email is required"
          );
          echo json_encode($res);
          die();
       }
       if (empty($pass)) {
         $res= array(
            'status' => '200 OK',
            'islogin' => "false",
            'msg' => "Password is required"
          );
          echo json_encode($res);
          die();
       }
       //if variable is empty//

       //email validation//
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          //just pass it//
        } else {
          $res= array(
            'status' => '200 OK',
            'islogin' => "false",
            'msg' => "Email is invalid"
          );
          echo json_encode($res);
          die();
        }
        //email validation//

        //SQL injection sanitization//
        $email=mysqli_real_escape_string($conn,$email);
        //SQL injection sanitization//

        //convert password into (sha512(md5(password)))//
          $passv1 = hash("sha512", $pass);
          $passv2 = md5($passv1);

       checklogin($email,$passv2);
	}
	catch(Exception $e){
     $res= array(
            'status' => '200 OK',
            'islogin' => "false",
            'msg' => "Something went wrong"
          );
          echo json_encode($res);
          die();
	}
}

function checklogin($email,$pass)
{
	require("api/db-con/db.php");
	$sql = "SELECT * FROM users where email='$email' AND password='$pass'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) ==1) {
    	require("api/checklogin/index.php");
      while($row = mysqli_fetch_assoc($result)) {
        $uid=$row["id"];
        $uemail=$row["email"];
      }
      do_login($uid,$uemail);
	} 
	else {
        $res= array(
       	'status' => '200 OK',
    		'islogin' => "false",
        'msg' => "Incorrect login"
    	);
    	echo json_encode($res);	
      die();
	}
}


function check_login(){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("api/db-con/db.php");
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
          $uid=$row["uid"];
          $date=$row["date"];
          }
          $ldate=date("d-m-y");
          if (($ldate-$date)<10) {
            update_session($uid,$json,$k2,$ultra);
            //user is logged in
          }
    }
  }
}


function update_session($id,$json,$k2,$ultra){
  require("api/db-con/db.php");
  $email=get_email($id);

  $json_token=make_json_web_tokens($id,$email);
  $md5id=md5(time().$id.$email."@agla_lgao_beta".time()."#116".time()."+098".$id.$email.time());
  $k2_cookie=md5(time().$id.$email."#haqsek2@116".time()."+".time()."098".$id.$email.time());
  $extra = md5(hash("sha512", $id));

  $json_token_base64=base64_encode($json_token);
  $ultra_base64=base64_encode($md5id);
  $k2_base64=base64_encode($k2_cookie);
  $extra_base64=base64_encode($extra);
  $date=date("d-m-y"); 

  $sql = "UPDATE do_login SET k2_cookie='$k2_base64', json_token='$json_token_base64', ultra_cookie='$ultra_base64', date='$date' WHERE uid='$id' and json_token='$json' and k2_cookie='$k2' and ultra_cookie='$ultra'";

  if (mysqli_query($conn, $sql)) {
    ///update cookie
    setcookie("json_token", $json_token, time() + (86400 * 10), "/"); 
    setcookie("ultra_cookie", $md5id, time() + (86400 * 10), "/"); 
    setcookie("k2_cookie", $k2_cookie, time() + (86400 * 10), "/");
    setcookie("k2_extra", $extra, time() + (86400 * 10), "/");
  }
  else{
    die("something went wrong1");
  }
}

function get_email($id){
  require("api/db-con/db.php");
  $sql = "SELECT * FROM users where id='$id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) >0) {
          while($row = mysqli_fetch_assoc($result)) {
          $email=$row["email"];
          }
          return $email;
    }
    else{
      die("something went wrong");
    }
}



function make_json_web_tokens($id,$email){

  // Create token header as a JSON string
  $header = json_encode(['typ' => 'JWT', 'alg' => 'ye_to_hoga']);

  $user_secret=md5(time().time().$id.$email.'haqsek2'.$id.$email.time().time());
  // Create token payload as a JSON string
  $payload = json_encode(['user_id' => $id,'user_secret'=> $user_secret, 'ye_to_hoga'=>'true', 'hackers_stay_away'=>'1']);

  // Encode Header to Base64Url String
  $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

  // Encode Payload to Base64Url String
  $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));


  //ye kisi ko nahi btana
  $secret="#Haqsek2_420";
  //ye kisi ko nahi btana


  // Create Signature Hash
  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

  // Encode Signature to Base64Url String
  $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

  // Create JWT
  $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

  return $jwt;
}
?>
