<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  header("Content-Type: text/html"); // to tell browser about the data type
    die("<h2>404</h2>");
}

header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type
if (isset($_POST["data"])) {
  require("../../db-con/db.php");
  try{
       $data = json_decode(base64_decode($_POST["data"]));
       $media=$data->media;
       checklogin($media);
    }
  catch(Exception $e){
     $res= array(
            'status' => '200 OK',
            'isuploaded' => "false",
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
    'isuploaded' => "false",
    'msg' => "Something went wrong"
  );
  http_response_code(401);
  echo json_encode($res);
  die();
}
function checklogin($image){
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
          upload($uid,$image);
     }
     else{
      $res= array(
            'status' => '200 OK',
            'isuploaded' => "false",
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
    'isuploaded' => "false",
    'msg' => "You are not logged in."
  );
  echo json_encode($res);
  http_response_code(401);
  die();
} 
}




function upload($uid,$image){
  if(strpos($image, "data:video")!== false)
  {//media is video
    upload_video($uid,$image);
    die();
  } 
  try{
    $bin=str_replace("data:image/png;base64,","",$image);
    $bin=str_replace("data:image/jpg;base64,","",$bin);
    $bin=str_replace("data:image/jpeg;base64,","",$bin);
    $binimage = base64_decode($bin);
    $info = getimagesizefromstring($binimage);
  }
  catch(Exception $e){
    $res= array(
            'status' => '200 OK',
            'isuploaded' => "false",
            'msg' => "Invalid image."
          );
          echo json_encode($res);
          die();
  }
  if ($info["mime"]!="image/png" && $info["mime"]!="image/jpeg" && $info["mime"]!="image/jpg") {
    $res= array(
            'status' => '200 OK',
            'isuploaded' => "false",
            'msg' => "Invalid image"
          );
          echo json_encode($res);
          die();
  }
  
  $fimage = imageCreateFromString($binimage);
  if (!$fimage) {
    $res= array(
            'status' => '200 OK',
            'isuploaded' => "false",
            'msg' => "Invalid image."
          );
          echo json_encode($res);
          die();
  }
  else{
    $imagename=rand(10,1000).md5($uid.time().rand(10,1000)).md5(date("d-m-y").time()).rand(10,1000).".png";
    $img_loc = '../../../user-content/post-media/'.$imagename;
    try{
      imagepng($fimage, $img_loc);
    }
    catch(Exception $e){
      $res= array(
        'status' => '200 OK',
        'isuploaded' => "false",
        'msg' => "Something went wrong."
      );
      echo json_encode($res);
      die();
    }
    update($uid,$imagename,"picture");
  }
}

function upload_video($uid,$video){
  try{
    if(mime_content_type($video)!="video/mp4" || mime_content_type($video)!="video/mp4" || mime_content_type($video)!="video/mov" || mime_content_type($video)!="video/3gp" || mime_content_type($video)!="video/mpeg"){
      $video_ext=str_replace ("video/", "", mime_content_type($video));
      $bin=str_replace("data:video/mp4;base64,","",$video);
      $bin=str_replace("data:video/avi;base64,","",$bin);
      $bin=str_replace("data:video/mov;base64,","",$bin);
      $bin=str_replace("data:video/3gp;base64,","",$bin);
      $bin=str_replace("data:video/mpeg;base64,","",$bin);
      $imagename=rand(10,1000).md5($uid.time().rand(10,1000)).md5(date("d-m-y").time()).rand(10,1000).".".$video_ext;
      $target_dir = '../../../user-content/post-media/';
      $target_file = $target_dir . $imagename;
      $binvideo = base64_decode($bin);
      try{
        file_put_contents($target_file,$binvideo);
      }
      catch(Exception $e){
        $res= array(
          'status' => '200 OK',
          'isuploaded' => "false",
          'msg' => "Invalid video."
        );
        echo json_encode($res);
        die();
      }
      update($uid,$imagename,"video");
    }else{
      $res= array(
        'status' => '200 OK',
        'isuploaded' => "false",
        'msg' => "Invalid video."
      );
      echo json_encode($res);
      die();
    }
  }
  catch(Exception $e){
    $res= array(
            'status' => '200 OK',
            'isuploaded' => "false",
            'msg' => "Invalid video."
          );
          echo json_encode($res);
          die();
  }
}


function update($uid,$imagename,$type){
  require("../../db-con/db.php");
  $sql="INSERT INTO post_media (media_name,media_type,user_id) VALUES ('$imagename', 'image', '$uid')";

  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $scheme = 'https://';
  } else {
    $scheme = 'http://';
  }
	$host=$scheme.$_SERVER['SERVER_NAME'];

  if (mysqli_query($conn, $sql)) {
    try{
      $newimglink=$host."/fyp/user-content/post-media/".$imagename;
    }
    catch(Exception $e){
      $res= array(
        'status' => '200 OK',
        'isuploaded' => "false",
        'msg' => "Image not updated."
      );
      echo json_encode($res);
      die();
    }

    $sql1 = "SELECT * from post_media where media_name='$imagename'";
    $result1 = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result1) == 1){
      while($row = $result1->fetch_assoc()) {
        $m_id=$row["media_id"];
      }
      $res= array(
        'status' => '200 OK',
        'isuploaded' => "true",
        'msg' => "Image updated.",
        'm_id'=>$m_id,
        'link'=> $newimglink,
        'type'=> $type
      );
      echo json_encode($res);
      die();
    }
    else{
      $res= array(
        'status' => '200 OK',
        'isuploaded' => "false",
        'msg' => "Image not updated."
      );
      echo json_encode($res);
      die();
    }
  }
  else{
    $res= array(
            'status' => '200 OK',
            'isuploaded' => "false",
            'msg' => "Image not updated."
          );
          echo json_encode($res);
          die();
  }
}
?>