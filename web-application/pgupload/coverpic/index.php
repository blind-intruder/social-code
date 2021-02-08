<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Content-Type: text/html"); // to tell browser about the data type
      die("<h2>404</h2>");
}

header("Content-Type: application/json; charset=UTF-8"); // to tell browser about the data type
if (isset($_POST["data"])) {
  require("../../db-con/db.php");
  try{
       $data = json_decode(base64_decode($_POST["data"]));
       $picture=$data->image;
       checklogin($picture);
    }
  catch(Exception $e){
     $res= array(
            'status' => '200 OK',
            'isupdated' => "false",
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
    'isupdated' => "false",
    'msg' => "Something went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
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
          changedp($uid,$image);
     }
     else{
      $res= array(
            'status' => '200 OK',
            'isupdated' => "false",
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
    'isupdated' => "false",
    'msg' => "you are not logged in."
  );
  echo json_encode($res);
  http_response_code(401);
  die();
} 
}




function changedp($uid,$image){
  try{
    $bin=str_replace("data:image/png;base64,","",$image);
    $binimage = base64_decode($bin);
    $info = getimagesizefromstring($binimage);
  }
  catch(Exception $e){
    $res= array(
            'status' => '200 OK',
            'isupdated' => "false",
            'msg' => "Invalid image."
          );
          echo json_encode($res);
          die();
  }
  if ($info["mime"]!="image/png") {
    $res= array(
            'status' => '200 OK',
            'isupdated' => "false",
            'msg' => "Invalid image."
          );
          echo json_encode($res);
          die();
  }

  try{
    $fimage = imageCreateFromString($binimage);
  }
  catch(Exception $e){
    $res= array(
        'status' => '200 OK',
        'isupdated' => "false",
        'msg' => "Invalid image."
        );
    echo json_encode($res);
    die();
  }
  if (!$fimage) {
    $res= array(
            'status' => '200 OK',
            'isupdated' => "false",
            'msg' => "Invalid image."
          );
          echo json_encode($res);
          die();
  }
  else{
    $imagename=rand(10,1000).md5($uid.time().rand(10,1000)).md5(date("d-m-y").time()).rand(10,1000).".png";
    $img_loc = '../../../page-content/cover-pic/'.$imagename;
    imagepng($fimage, $img_loc);
    updatedp($uid,$imagename);
  }
}

function updatedp($uid,$imagename){
  require("../../db-con/db.php");
  $sql = "UPDATE pages SET coverlink='$imagename' WHERE paid='$uid'";

  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $scheme = 'https://';
  } else {
    $scheme = 'http://';
  }
	$host=$scheme.$_SERVER['SERVER_NAME'];

  if (mysqli_query($conn, $sql)) {
    $newimglink=$host."/fyp/user-content/cover-pic/".$imagename;
    $res= array(
            'status' => '200 OK',
            'isupdated' => "true",
            'msg' => "Image updated.",
            'link'=> $newimglink
          );
          echo json_encode($res);
          die();
  }
  else{
    $res= array(
            'status' => '200 OK',
            'isupdated' => "false",
            'msg' => "Image not updated."
          );
          echo json_encode($res);
          die();
  }
}
?>