<?php
error_reporting(0); // Disable all errors.
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    http_response_code(400);
    die();
}
header("Content-Type: application/json; charset=UTF-8");// to tell browser about the data type

if(checklogin()!=0){
  get_me(checklogin());
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

function get_me($id){
  require("../../db-con/db.php");
  $sql = "SELECT * from users where id='$id'";
  $result = mysqli_query($conn, $sql);
  $dob=[];
  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $scheme = 'https://';
  } else {
    $scheme = 'http://';
  }
  $host=$scheme.$_SERVER['SERVER_NAME'];
  if (mysqli_num_rows($result)==1) {
    while($row = mysqli_fetch_assoc($result)) {
      $username=$row["uid"]; $fname=$row["firstname"];
      $lname=$row["lastname"]; $email=$row["email"];
      $bio=base64_decode($row["bio"]); $dp=$row["dplink"];
      $cover=$row["coverlink"]; $cover=$row["coverlink"];
      array_push($dob,$row["date"],$row["month"],$row["year"]);
      //TODO: add other details also
    }
    $res= array(
      'status' => '200 OK',
      'id' => $id,
      'username' => htmlspecialchars($username, ENT_QUOTES, 'UTF-8'),
      'firstname' => htmlspecialchars(ucfirst($fname), ENT_QUOTES, 'UTF-8'),
      'lastname' => htmlspecialchars(ucfirst($lname), ENT_QUOTES, 'UTF-8'),
      'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
      'bio' => htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'),
      'dp' => $host."/fyp/user-content/display-pic/".$dp,
      'cover' => $host."/fyp/user-content/cover-pic/".$cover,
      'dob' => $dob,
      'msg' => "Your data"
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
