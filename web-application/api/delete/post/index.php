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
         $post_id=$data->post_id;

       	//validation
       	if (empty($post_id)) {
       		$res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
            'msg' => "Invalid request"
          );
          echo json_encode($res);
          die(); 
       	}

        if (!preg_match("/^[0-9]*$/",$post_id)) {
          $res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
            'msg' => "Invalid request"
          );
          echo json_encode($res);
          die();
        }

 

        //html sanitization
        $post_id=htmlspecialchars($post_id);
 

        //sql sanitization
        $post_id=mysqli_real_escape_string($conn,$post_id);

        checklogin($post_id);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
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
    'isdeleted' => "false",
    'msg' => "Somthing went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}
function checklogin($post_id){
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
          delete_post($uid,$post_id);
     }
     else{
      $res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
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
      'isdeleted' => "false",
      'msg' => "Your are not logged in."
    );
    echo json_encode($res);
    http_response_code(401);
    die();
  }
}



	function delete_post($id,$post_id){
    require("../../db-con/db.php");
    $sql = "DELETE FROM user_post WHERE post_id='$post_id' and u_id='$id'";
		if (mysqli_query($conn, $sql)) {
			$res= array(
            'status' => '200 OK',
            'isdeleted' => "true",
            'msg' => "media deleted"
          );
          echo json_encode($res);
          die();
		}
		else{
			$res= array(
            'status' => '200 OK',
            'isdeleted' => "false",
            'msg' => "You are not the owner of this post or this post does not exists."
          );
          echo json_encode($res);
          die();
		}
	}
?>