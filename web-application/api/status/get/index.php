<?php
error_reporting(0); // Disable all errors.
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

if(checklogin()!=0){
	session_start();
	get_status(checklogin());
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

function get_status($id){
  require("../../db-con/db.php");
  $status=[];
  $sql1 = "SELECT * from friends where one='$id'";
  $result1 = mysqli_query($conn, $sql1);
  if (mysqli_num_rows($result1)>0) {
    while($row1 = mysqli_fetch_assoc($result1)) {
      $uid=$row1['two'];
      $sql = "SELECT * from status where user_id='$uid' order by status_id DESC";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result)>0) {
        $b=[];
        while($row = mysqli_fetch_assoc($result)) {
          $now=date("Y-m-d", strtotime("now"));
          $diff = abs(strtotime($now) - strtotime($row["time_uploaded"]));
          if ($diff==0) {
            $s=array(
              'status_id' => $row["status_id"],
              'status_text'=> htmlspecialchars(base64_decode($row["status_text"]), ENT_QUOTES, 'UTF-8'),
              'status_time'=> $row["time_uploaded"],
              'bg_color'=> base64_decode($row["bg_color"]),
              'font'=>$row["font"]
            );
            array_push($b,$s); 
            break;
          }
        }
        $a= array('user' => get_user($uid), "user_status"=>$b);
        array_push($status, array("friend"=>$a)); 
      }
    }
    if (my_status()!=0) {
      array_push($status, array("friend"=>my_status()));
    }
    if (!count($status)) {
          $status="null";
        }
        $res= array(
          'status_req' => '200 OK',
          'status' => $status
        );
          echo json_encode($res);
        die();
  }
  else{
    $res= array(
    'status' => '200 OK',
    'msg' => "Add friends to see their status."
  );
  echo json_encode($res);
  die();
  }
}

function get_user($id){
  require("../../db-con/db.php");
  $user=[];
  $sql = "SELECT * from users where id='$id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)==1) {
    while($row = mysqli_fetch_assoc($result)) {
      $u=array(
        "user_id"=>$row ["id"],
        "Name"=>htmlspecialchars(ucfirst($row["firstname"]), ENT_QUOTES, 'UTF-8')." ".htmlspecialchars(ucfirst($row["lastname"]), ENT_QUOTES, 'UTF-8'),
        'username' => $row["uid"],
        'dp_link' => $GLOBALS['host']."/fyp/user-content/display-pic/".$row["dplink"]
      );
    }
    return $u;
  }
  else{
    $res= array(
      'status' => '200 OK',
      'status' => 'Null',
      'msg' => 'something went wrong'
    );
    echo json_encode($res);
    die();
  }
}

function my_status(){
  require("../../db-con/db.php");
  $my_id=checklogin();
  $sql = "SELECT * from status where user_id='$my_id'";
  $status=[];
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result)>0) {
    $b=[];
    while($row = mysqli_fetch_assoc($result)) {
      $now=date("Y-m-d", strtotime("now"));
      $diff = abs(strtotime($now) - strtotime($row["time_uploaded"]));
      if ($diff==0) {
        $s=array(
              'status_id' => $row["status_id"],
              'status_text'=> htmlspecialchars(base64_decode($row["status_text"]), ENT_QUOTES, 'UTF-8'),
              'status_time'=> $row["time_uploaded"],
              'bg_color'=> base64_decode($row["bg_color"]),
              'font'=>$row["font"]
            );
        array_push($b,$s); 
      }
    }
    $a= array('user' => get_user(checklogin()), "user_status"=>$b);
    return $a;
  }
  else{
    return 0;
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
