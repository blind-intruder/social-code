<?php

//direct access not allowed
if (getcwd() == dirname(__FILE__)) {
    http_response_code(404);
    die();
}

//this function will check if user is logged in and return the id of the user else it will return 0/false
function is_logged_in(){
    if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
        require("api/db-con/db.php");
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