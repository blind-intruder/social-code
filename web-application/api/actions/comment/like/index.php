<?php
//error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(404);
		header("Content-Type: text/html");// to tell browser about the data type
}
if(is_logged_in()==0){
  $res= array(
    'status' => '200 OK',
    'iscreated' => "error",
    'msg' => "You are not logged in."
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
if (isset($_POST["data"])) {
  require("../../../db-con/db.php");
	try{
		$data = json_decode(base64_decode($_POST["data"]));
         $comment_id=$data->comment_id;

       	//validation
       	if (empty($comment_id)) {
       		$res= array(
            'status' => '200 OK',
            'iscreated' => "error",
            'isdeleted' => "false",
            'msg' => "Invalid comment Id"
          );
          echo json_encode($res);
          die(); 
       	}
         
         
         //post id can only be numeric
         if (!preg_match("/^[0-9]*$/",$comment_id)) {
            $res= array(
              'status' => '200 OK',
              'iscreated' => "error",
              'isdeleted' => "false",
              'msg' => "Invalid post Id"
            );
            echo json_encode($res);
            die();
          }

        //html sanitization
        $comment_id=htmlspecialchars($comment_id);

        //sql sanitization
        $comment_id=mysqli_real_escape_string($conn,$comment_id);

        register_like(is_logged_in(),$comment_id);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'iscreated' => "error",
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
    'iscreated' => "error",
    'isdeleted' => "false",
    'msg' => "Somthing went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}


function check_comment_exits($comment_id){
  require("../../../db-con/db.php");
  $sql="SELECT * from post_comments where comment_id='$comment_id'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) ==1) {
        return true;
      }
      else{
        return false;
      }
}

function check_already_star($user_id,$comment_id){
  require("../../../db-con/db.php");
  $sql="SELECT * from post_comment_stars where comment_id='$comment_id' and user_id=$user_id";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) ==1) {
    return true;
  }
  else{
    return false;
  }
}

function register_like($user_id,$comment_id){
  require("../../../db-con/db.php");

  if(check_already_star($user_id,$comment_id)){//post already liked, so just dislike it
    $sql = "DELETE from post_comment_stars where comment_id='$comment_id' and user_id='$user_id'";
    if (mysqli_query($conn, $sql)) {
      $res= array(
        'status' => '200 OK',
        'isdeleted' => "true",
        'iscreated' => "error",
        'msg' => "Star deleted."
      );
      echo json_encode($res);
      die();
    }
    else{
      $res= array(
        'status' => '200 OK',
        'isdeleted' => "false",
        'iscreated' => "error",
        'msg' => "Star not deleted deleted."
      );
      echo json_encode($res);
      die();
    }
  }

  if(check_comment_exits($comment_id)){//check if post exits
    $sql = "INSERT INTO post_comment_stars (comment_id,user_id) VALUES ('$comment_id','$user_id')";
    if (mysqli_query($conn, $sql)) {
      $res= array(
        'status' => '200 OK',
        'iscreated' => "true",
        'isdeleted' => "false",
        'msg' => "Star created."
      );
      echo json_encode($res);
      die();
    }
    else{
      $res= array(
        'status' => '200 OK',
        'iscreated' => "error",
        'isdeleted' => "false",
        'msg' => "Something went wrong."
      );
      echo json_encode($res);
      die();
    }
  }
  else{
    $res= array(
      'status' => '200 OK',
      'iscreated' => "error",
      'isdeleted' => "false",
      'msg' => "Post does not exits."
    );
    echo json_encode($res);
    die();
  }
}


//this function will check if user is logged in and return the id of the user else it will return 0/false
function is_logged_in(){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("../../../db-con/db.php");
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
          }
          return $uid; 
      }
      else{
          return 0;
      }     

  }
  else{
      return 0;
  }

}

?>