<?php
error_reporting(0); // Disable all errors.

if(isset($_GET["token"])){
	header("Content-Type: text/html");// to tell browser about the data type
$token=$_GET["token"];
require("../api/db-con/db.php");
//validation
$token=mysqli_real_escape_string($conn,$token);


	function checktoken($token){
		require("../api/db-con/db.php");
		$sql = "SELECT * FROM users where forgot_token='$token'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) ==1) {
			$today_date=date("m-d-y");
			while($row = mysqli_fetch_assoc($result)) {
        		$token_date=$row["token_date"];
    		}
    		if ($today_date!=$token_date) {
    			http_response_code(404);
				show404();
				die();
    		}
    		else{
    			showreal();
    			die();
    		}
		}
		else{
			http_response_code(404);
			show404();
			die();
		}
	}
checktoken($token);
}
else{

if (isset($_POST["data"])) {
	header("Content-Type: application/json");// to tell browser about the data type
	try{
		require("../api/db-con/db.php");
		$data = json_decode(base64_decode($_POST["data"]));
       	$password=$data->password;
       	$token=$data->token;

       	//validation
       	if (empty($password)) {
       		$res= array(
            'status' => '200 OK',
            'isset' => "error",
            'msg' => "Password is empty"
          );
          echo json_encode($res);
          die();
       	}
       	if (empty($token)) {
       		$res= array(
            'status' => '200 OK',
            'isset' => "error",
            'msg' => "token is empty"
          );
          echo json_encode($res);
          die();
       	}
       	$token=mysqli_real_escape_string($conn,$token);
       	//convert into sha512(md5(password)) 
       	$passv1 = hash("sha512", $password);
        $passv2 = md5($passv1);
        valid_token($token,$passv2);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'isset' => "error",
            'msg' => "Something went wrong"
          );
          echo json_encode($res);
          die();
	}
}
else{
header("Content-Type: text/html");// to tell browser about the data type
http_response_code(404);
show404();
die();
}

}


function valid_token($token,$pass){
	require("../api/db-con/db.php");
	$sql = "SELECT * FROM users where forgot_token='$token'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
        	$tdate=$row["token_date"];
        	$id=$row["id"];
    	}
    	$sdate=date("m-d-y");
    	if ($sdate==$tdate) {
    		resetpass($pass,$id);	
    	}
    	else{
    		$res= array(
            'status' => '200 OK',
            'isset' => "error",
            'msg' => "Invalid token"
          );
          echo json_encode($res);
          die();
    	}
	}
	else{
		$res= array(
            'status' => '200 OK',
            'isset' => "error",
            'msg' => "Invalid token"
          );
          echo json_encode($res);
          die();
	}

}

function resetpass($pass,$id){
	require("../api/db-con/db.php");
	$date=date("m-d-y");
	$sql = "UPDATE users SET password='$pass',forgot_token='',token_date='' where id='$id'";
	if (mysqli_query($conn, $sql)) {
		$res= array(
            'status' => '200 OK',
            'isset' => "true",
            'msg' => "Password has been reset"
          );
          echo json_encode($res);
          die();
	}
	else{
		$res= array(
            'status' => '200 OK',
            'isset' => "false",
            'msg' => "Password not has been reset"
          );
          echo json_encode($res);
          die();
	}
}

?>  
<?php
function showreal(){
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--bootstrap assets-->
      <link rel="stylesheet" href="../assets/css/boot.css">
      <script type="text/javascript" src="../assets/js/jquery-modified.min.js"></script>
    <!--bootstrap assets-->
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/forgot/forgot.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/global.css">
    </head>
    <body>

      <div class="container-fluid">
        <div class="row">
         <div class="col-sm-12">

          <!--------------------New password row-------------------->
          <div class="row" id="forgot_pass">
              <div class="col-sm-12"  >
                <div class="d-flex justify-content-center"  >
                    <div class="card" style="margin-top: 5%; width: 20em;">
                      <div class="singin-container">
                        <!--logo in login form--> 
                    <div class="row">
                      <div class="col-sm-12" style="padding-bottom: 30px;">
                        <img src="../assets/images/logop.png">
                      </div>
                    </div>
                    <!--logo in login form-->
                    <hr>
                        <form >
                            <div class="form-group">
                              <label for="useremail">Reset your password</label>
                              <input type="password" class="form-control" placeholder="Enter password" id="passv1">
                            </div>
                            <div class="form-group">
                              <input type="password" class="form-control" placeholder="Re-enter password" id="passv2">
                            </div>
                            <div style="text-align: center;">
                              <button type="button" class="btn btn-success" id="reset-sub">Reset</button>
                              <p id="forgot-response"></p>
                              <a href="http://localhost:8079/fyp/" class="simple_button">Login</a>
                            </div>
                  
                        </form>
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
        <!--bootstrap assets--> 

    <script type="text/javascript" src="../assets/js/forgot/forgot.js">
    </script>       

    </body>
    </html>  
<?php
}
?>



<?php
function show404(){
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>404</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,900" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="../assets/css/404.css" />
</head>
<body>
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>Oops!</h1>
			</div>
			<h2>404 - Page not found</h2>
			<p>This page is not available.</p>
		</div>
	</div>
</body>
</html>
<?php
}
?>