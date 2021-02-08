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
  require("../../db-con/db.php");
	try{
		$data = json_decode(base64_decode($_POST["data"]));
         $post_id=$data->post_id;

       	//validation
       	if (empty($post_id)) {
       		$res= array(
            'status' => '200 OK',
            'msg' => "Invalid comment Id"
          );
          echo json_encode($res);
          die(); 
       	}
         
         
         //post id can only be numeric
         if (!preg_match("/^[0-9]*$/",$post_id)) {
            $res= array(
              'status' => '200 OK',
              'msg' => "Invalid post Id"
            );
            echo json_encode($res);
            die();
          }

        //html sanitization
        $post_id=htmlspecialchars($post_id);

        //sql sanitization
        $post_id=mysqli_real_escape_string($conn,$post_id);
        
        if(check_post_exits($post_id)){
          get_comments($post_id);
        }
        else{
          $res= array(
            'status' => '200 OK',
            'msg' => "post not exits."
          );
          echo json_encode($res);
          die();
        }
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
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
    'msg' => "Somthing went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}


function check_post_exits($post_id){
  require("../../db-con/db.php");
  $sql="SELECT * from user_post where post_id='$post_id'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) ==1) {
        return true;
      }
      else{
        return false;
      }
}

function get_comments($post_id){
  require("../../db-con/db.php");
  $main_array=[];
  $sub_array=[];
  $sql="SELECT * from post_comments where post_id=$post_id";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)>0) {
    while($row = mysqli_fetch_assoc($result)) {
      $sub_array=[];
      $main_comment_id=$row["comment_id"];
      $commentor_id=$row["user_id"];
      $main_comment_text=htmlspecialchars(base64_decode($row["comment_text"]), ENT_QUOTES, 'UTF-8');
      $main_comment_time=$row["time"];
      $sql1="SELECT * from post_comment_reply where main_comment_id='$main_comment_id'";
      $result1 = mysqli_query($conn, $sql1);
      if (mysqli_num_rows($result1) >0) {
        while($row1 = mysqli_fetch_assoc($result1)) {
          $rep=array(
            'reply_id' => $row1["reply_id"],
            'reply_text' => htmlspecialchars(base64_decode($row1["reply_text"]), ENT_QUOTES, 'UTF-8'),
            'time' => $row1["time"],
            'reply_author' => get_author($row1["author_id"]),
            'total_likes' => reply_comment_stars($row1["reply_id"]),
            'me_liked' => comment_reply_me_liked($row1["reply_id"])
          );
          array_push($sub_array,$rep);
        }
      }
      $m=array(
        'main_comment_id'=>$main_comment_id,
        'comment_author'=>get_author($commentor_id),
        'comment_text'=>$main_comment_text,
        'comment_time'=>$main_comment_time,
        'comment_replies'=> $sub_array,
        'total_likes'=> main_comment_stars($main_comment_id),
        'me_liked' => main_comment_me_liked($main_comment_id)
      );
      array_push($main_array,$m);
    }
    $out= array(
      'status' => '200 OK',
      'fetched'=> 'ok',
      'comments' => $main_array
    );
    echo json_encode($out);
    die();
  }
  else{
    $res= array(
      'status' => '200 OK',
      'has_comments' => "false"
    );
    echo json_encode($res);
    die();
  }
}

function get_author($user_id){
  require("../../db-con/db.php");
  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $scheme = 'https://';
  } else {
    $scheme = 'http://';
  }
  $host=$scheme.$_SERVER['SERVER_NAME'];
  $sql="SELECT * from users where id='$user_id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)==1) {
    while($row = mysqli_fetch_assoc($result)) {
      $res=array(
        'user_id'=>$row["id"],
        'username'=>$row["uid"],
        'name'=>$row["firstname"]." ".$row["lastname"],
        'dplink'=>$host."/fyp/user-content/display-pic/".$row["dplink"]
      );
    }
    return $res;
  }
}

function main_comment_stars($comment_id){
  require("../../db-con/db.php");
  $sql="SELECT * from post_comment_stars where comment_id='$comment_id'";
  $result = mysqli_query($conn, $sql);
  return mysqli_num_rows($result);
}

function reply_comment_stars($reply_id){
  require("../../db-con/db.php");
  $sql="SELECT * from post_reply_stars where reply_id='$reply_id'";
  $result = mysqli_query($conn, $sql);
  return mysqli_num_rows($result);
}


function main_comment_me_liked($comment_id){
  require("../../db-con/db.php");
  $user_id=is_logged_in();
  $sql="SELECT * from post_comment_stars where comment_id='$comment_id' and user_id='$user_id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)==1) {
    return true;
  }
  else{
    return false;
  }
}

function comment_reply_me_liked($reply_id){
  require("../../db-con/db.php");
  $user_id=is_logged_in();
  $sql="SELECT * from post_reply_stars where reply_id='$reply_id' and user_id='$user_id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)==1) {
    return true;
  }
  else{
    return false;
  }
}

//this function will check if user is logged in and return the id of the user else it will return 0/false
function is_logged_in(){
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