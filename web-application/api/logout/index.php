<?php

if (getcwd() == dirname(__FILE__)) {
    http_response_code(404);
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== "POST" && $_SERVER['REQUEST_METHOD'] !== "GET") {
	header("Content-Type: text/html");// to tell browser about the data type
	die("<h2>404</h2>");
}

if (isset($_POST["logout"])) {
  header("Content-Type: application/json; charset=UTF-8");
  logout();
}

function logout(){
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
    if (mysqli_num_rows($result) >0) {

		//delete user sessions from database
		$sql1 = "DELETE FROM do_login WHERE k2_cookie='$k2' and ultra_cookie='$ultra' and json_token='$json' and extra='$extra'"; 
		if (mysqli_query($conn, $sql1)) {
    		//delete cookies from browser
      setcookie("json_token", time().time()."ye to hoga", time() + (86400 * 10), "/"); 
			setcookie("ultra_cookie", "agla lgao beta".time().time(), time() + (86400 * 10), "/"); 
			setcookie("k2_cookie", time().time()."aam khaye ga?", time() + (86400 * 10), "/");
			setcookie("k2_extra", time(), time() + (86400 * 10), "/");
			$res= array(
            	'status' => '200 OK',
            	'islogin' => "false",
            	'msg' => "Logged out"
          	);
          	echo json_encode($res);
         	die(); 
		}
		else{
			$res= array(
            	'status' => '200 OK',
            	'islogin' => "error",
            	'msg' => "something went wrong"
          	);
          	echo json_encode($res);
         	die();
		} 
  	}
  }
}
?>