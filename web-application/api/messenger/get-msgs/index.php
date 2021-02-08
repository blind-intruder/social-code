<?php
//error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    http_response_code(400);
    die();
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  $scheme = 'https://';
} else {
  $scheme = 'http://';
}
$host=$scheme.$_SERVER['SERVER_NAME'];

if (isset($_GET["id"])) {
  if (!preg_match("/^[0-9]*$/",$_GET["id"])) {
          $res= array(
            'status' => '200 OK',
            'iscreate' => "error",
            'msg' => "Invalid id"
          );
          echo json_encode($res);
          die();
         }
}
else{
  $res= array(
    'status' => '200 OK',
    'msg' => "missing get parameter (id)"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}

if(checklogin()!=0){
	session_start();
	get_me(checklogin(),$_GET["id"]);
}
else{
  $res= array(
    'status' => '200 OK',
    'msg' => "You are not logged in."
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}


function get_me($id,$f_id){
  require("../../db-con/db.php");
  $id=mysqli_real_escape_string($conn,$id);
  $sql="SELECT * from msg where (sender_id='$f_id' and receiver_id='$id') or (sender_id='$id' and receiver_id='$f_id')";
  $f=[];
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)>0) {
    while($row = mysqli_fetch_assoc($result)) {
      if ($id==$row["sender_id"]) {
        $type="sent";
      }
      if($id==$row["receiver_id"]){
        $type="received"; 
      }
      $b= array('text' => htmlspecialchars(base64_decode($row["msg"]), ENT_QUOTES, 'UTF-8'), 'time'=> $row["time"], 'type'=>$type);
      array_push($f,$b); 
      add_read($row["msg_id"]);
    }
  }
  if (!count($f)) {
    $f="null";
  }
  $res= array(
      'status' => '200 OK',
      'msgs' => $f
    );
    echo json_encode($res);
    die();
}

function add_read($id){
  require("../../db-con/db.php");
  $sql = "UPDATE msg SET status='read' WHERE msg_id='$id'";
  if (mysqli_query($conn, $sql)) {

  }
}



function get_user_details($id){
  require("../../db-con/db.php");
  $sql = "SELECT * from users where id='$id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)==1) {
    while($row = mysqli_fetch_assoc($result)) {
      $res= array(
        'user_id' => $row["id"],
        'Name' => htmlspecialchars(ucfirst($row["firstname"]), ENT_QUOTES, 'UTF-8')." ".htmlspecialchars(ucfirst($row["lastname"]), ENT_QUOTES, 'UTF-8'),
        'dp_link' => $GLOBALS['host']."/fyp/user-content/display-pic/".$row["dplink"]
      );
    }
    return $res;
  }
  else{
    $res= array(
      'status' => '200 OK',
      'posts' => 'Null',
      'msg' => 'something went wrong'
    );
    echo json_encode($res);
    die();
  }
}

function checklogin(){
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
