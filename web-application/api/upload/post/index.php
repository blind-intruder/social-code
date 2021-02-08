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
         $media_ids=$data->media_id;

        $n=count($media_ids);
        if($n>5){
          $res= array(
            'status' => '200 OK',
            'iscreate' => "error",
            'msg' => "Only 6 pictures/videos are allowed in one post"
          );
          echo json_encode($res);
          die();
        }
        for($i=0;$i<$n;$i++){
          if (!preg_match("/^[0-9]*$/",$media_ids[$i])) {
            $res= array(
              'status' => '200 OK',
              'iscreate' => "error",
              'msg' => "Invalid media ID"
            );
            echo json_encode($res);
            die(); 
          }
          $media_ids[$i]=mysqli_real_escape_string($conn,$media_ids[$i]);
        }
       	//validation
       	if (empty($text) && count($media_ids)==0) {
       		$res= array(
            'status' => '200 OK',
            'iscreate' => "error",
            'msg' => "Empty post can't be created."
          );
          echo json_encode($res);
          die(); 
       	}
              	
        //html sanitization
        //$text=htmlspecialchars($text);

        //sql sanitization
        $text=mysqli_real_escape_string($conn,$text);

        checklogin($text,$media_ids);
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
function checklogin($text,$media_ids){
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
          create_post($uid,$text,$media_ids);
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



function create_post($id,$text,$media_ids){ 
		require("../../db-con/db.php");
    $n=count($media_ids);
    for($i=0;$i<$n;$i++){
      if(!check_media_owner($id,$media_ids[$i])){
        $res= array(
          'status' => '200 OK',
          'iscreated' => "error",
          'msg' => "This media is not owned by you."
        );
        echo json_encode($res);
        die();
      }
    }
    $text=base64_encode($text);
    $date=date("j F Y h:i:s A");
    $ids=serialize($media_ids);

    //unserialize($media_ids); to deserialize the media ids
    //use this method to get all the media ids from array
    //$a_id=unserialize($ids);
    //$n=count($a_id);
    //for($i=0;$i<$n;$i++){
      //echo $a_id[$i];
    //}
    $sql = "INSERT INTO user_post (u_id, post_text,post_media_id,post_time) VALUES ('$id','$text','$ids','$date')";
    if (mysqli_query($conn, $sql)) {
      $sql1="SELECT * from user_post where u_id='$id' and post_text='$text' and post_media_id='$ids' and post_time='$date'";
      $result = mysqli_query($conn, $sql1);
      if (mysqli_num_rows($result) ==1) {
        while($row = mysqli_fetch_assoc($result)) {
          $post_id=$row["post_id"];
        }
        $res= array(
          'status' => '200 OK',
          'iscreated' => "true",
          'msg' => "Post has been created",
          'post_id'=> $post_id,
          'medias' => get_all_media_link($media_ids),
          'time' => $date
        );
        echo json_encode($res);
        die();
      }
      else{
        $res= array(
          'status' => '200 OK',
          'iscreated' => "false",
          'msg' => "Post can't be created",
        );
        echo json_encode($res);
        die();
      }
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

  function get_all_media_link($media_ids){
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      $scheme = 'https://';
    } else {
      $scheme = 'http://';
    }
    $host=$scheme.$_SERVER['SERVER_NAME'];
    require("../../db-con/db.php");
    $media_id_array=[];
    $n=count($media_ids);
    for($i=0;$i<$n;$i++){
      $id=$media_ids[$i];
      $sql="SELECT * from post_media where media_id='$id'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) ==1) {
        while($row = mysqli_fetch_assoc($result)) {
          $media_name=$row["media_name"];
        }
        $media=$host."/fyp/user-content/post-media/".$media_name;
        array_push($media_id_array,$media);
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
    return $media_id_array;
  }
  
  function check_media_owner($id,$m_id){
    require("../../db-con/db.php");
    $sql="SELECT * from post_media where media_id='$m_id' and user_id='$id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) ==1) {
      return true;
    }
    else{
      return false;
    }
  }
?>