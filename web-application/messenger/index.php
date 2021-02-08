<?php
error_reporting(0); // Disable all errors.
header("Content-type: text/html");
require("../api/db-con/db.php");

if(!is_loggedin()){
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  $scheme = 'https://';
} else {
  $scheme = 'http://';
}
$host=$scheme.$_SERVER['SERVER_NAME'];
header("location:".$host."/fyp");
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <link rel="stylesheet" href="../assets/css/boot.css">
    <script type="text/javascript" src="../assets/js/jquery-modified.min.js"></script>
    <title><?php echo htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8')." ".htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" type="text/css" href="../assets/css/icons.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/global.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/messenger.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <style type="text/css">
      .no-js #loader { display: none;  }
      .js #loader { display: block; position: absolute; left: 100px; top: 0; }
      .k2-loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: center no-repeat #fff;
      }
    </style>
    </head>
    <body style="background-image: none; background-color: whitesmoke;">
      <!--loading-->
        <div class="k2-loader"><span class='spinner-border spinner-border-lg' style="top:50%;left:50%;position:fixed;" role='status' aria-hidden='true'></span></div>
      <!--loading-->

        <?php
          require("../api/inc/global.php");
        ?>

        <div class="container messenger-class" style="margin-top: 80px;">
          <div class="row" style="height: 100%;">
            <div class="col-sm-4 chat-contacts-class">
              <div class="row chat-friends">
                <!------data will added on run time----->
              </div>
            </div>
            <div class="col-sm-8" style="background-color: whitesmoke; height: 100%;">
              <div class="main-chat" style="display: none;">
                <div class="chat-header">
                  <div class="chat-user-head">
                    <div class="head-img">
                      <img class="chat-avatar" src="http://127.0.0.1/fyp/user-content/display-pic/8948ec294985c6ccc207323d13401d1bc970ae955d4468700f801c5b580e5500ab3596.png">
                    </div>
                    <div class="head-name">
                      <span class="f_name">henlo world</span>
                    </div>
                  </div>
                </div>
                <div class="chat-body">
                  <div class="messeges">
                    <!------data will added on run time----->
                  </div>
                </div>
                <div class="chat-footer">
                  <div class="chat-input">
                    <div class="msg-box">
                      <div class="row">
                        <div class="col-sm-10">
                          <input type="text" class="msg-text" name="msg-value" style="width: 100%;">
                        </div>
                        <div class="col-sm-2">
                          <button type="button" class="btn btn-success send-msg" style="padding: 0.2rem 0.75rem;">Send</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>


    <!--bootstrap assets-->
    <script type="text/javascript" src="../assets/js/popper.js" ></script>
    <script type="text/javascript" src="../assets/js/boot.js"></script>
    <script type="text/javascript" src="../assets/js/global.js"></script>
    <script type="text/javascript" src="../assets/js/messenger.js"></script>
    <!--bootstrap assets--> 
<script type="text/javascript">
  $(document).ready(function(){
    $(".k2-loader").fadeOut("slow");
  });
</script>       
</body>
</html> 

<?php
function get_user_data($uid){
  require("../api/db-con/db.php");
  $sql = "SELECT * FROM users where uid='$uid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) ==1) {
    while($row = mysqli_fetch_assoc($result)) {
      $firstname=$row["firstname"];
      $lastname=$row["lastname"];
      $bio=base64_decode($row["bio"]);
      $dp=$row["dplink"];
      $cover=$row["coverlink"];
      $date=$row["date"];
      $month=$row["month"];
      $year=$row["year"];
      $location=$row["location"];
      $work=$row["work"];
      $education=$row["education"];
    }
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      $scheme = 'https://';
    } else {
      $scheme = 'http://';
    }
    $host=$scheme.$_SERVER['SERVER_NAME'];
    $dplink=$host."/fyp/user-content/display-pic/".$dp;
    $coverlink=$host."/fyp/user-content/cover-pic/".$cover;
    $fname=ucfirst($firstname)." ".ucfirst($lastname);
    showprofile($uid,$fname,$bio,$dplink,$coverlink,ucfirst($firstname),ucfirst($lastname),$date,$month,$year,$location,$work,$education);
  }
}

function checkuser($uid){
  require("../api/db-con/db.php");
  $sql = "SELECT * FROM users where uid='$uid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) ==1) {
    return true;
  }
  else{
    return false;
  }
}

function checklogin($uid){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("../api/db-con/db.php");
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
        $userid=$row["uid"];
        }
        $sql1 = "SELECT * FROM users where id='$userid'";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) >0) {
          while($row1 = mysqli_fetch_assoc($result1)) {
            $username=$row1["uid"];
          }
          if ($username==$uid) {
            return true;
          }
          else{
            return false;
          }
        }
        else{
          return false;
        }
     }
     else{
        return false;
     }     

} 
else{
  return false;
}
}

function get_user(){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("../api/db-con/db.php");
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
        $userid=$row["uid"];
        }
        $sql1 = "SELECT * FROM users where id='$userid'";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) >0) {
          while($row1 = mysqli_fetch_assoc($result1)) {
            $username=$row1["uid"];
          }
          return $username; 
        }
        else{
          return false;
        }
     }
     else{
        return false;
     }     

} 
else{
  return false;
}
}


function is_loggedin(){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("../api/db-con/db.php");
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
      return true;       
     }
     else{
        return false;
     }     

} 
else{
  return false;
}
}


function sanitization($word){
  $word=strip_tags($word);
  $word=trim($word);
  $word = filter_var($word, FILTER_SANITIZE_STRING);
  return $word;
}

?>