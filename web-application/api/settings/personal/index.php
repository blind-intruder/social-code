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
       	$bio=$data->bio;
       	$fname=$data->fname;
       	$lname=$data->lname;
       	$date=$data->date;
       	$month=$data->month;
       	$year=$data->year;
       	$cdate=intval($date);
       	$cyear=intval($year);
       	$bio=base64_encode($bio);

       	//validation
       	if (empty($fname)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "first name is required"
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($lname)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Lastname is required"
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($year)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Year is required"
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($month)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "month is required"
          );
          echo json_encode($res);
          die(); 
       	}
       	if (empty($date)) {
       		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "date is required"
          );
          echo json_encode($res);
          die(); 
       	}

       	if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "First name is invalid"
          );
          echo json_encode($res);
          die();
        }
        if (!preg_match("/^[a-zA-Z ]*$/",$lname)) {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Last name is invalid"
          );
          echo json_encode($res);
          die();
        }

        //date validation//
        if (!preg_match("/^[0-9]*$/",$date)) {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Date is invalid"
          );
          echo json_encode($res);
          die();
        }
        if ($cdate<1 || $cdate>31 || is_float($date)) {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Date is invalid"
          );
          echo json_encode($res);
          die();
        }
        //date validation//

        //month validation//
        if (!preg_match("/^[a-zA-Z]*$/",$month)) {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Month is invalid"
          );
          echo json_encode($res);
          die();
        }
        if ($month!="Jan" && $month!="Feb" && $month!="Mar" && $month!="Apr" && $month!="May" && $month!="Jun" && $month!="Jul" && $month!="Aug" && $month!="Sep" && $month!="Oct" && $month!="Nov" && $month!="Dec") {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Month is invalid"
          );
          echo json_encode($res);
          die();
        }
        //month validation//

        //year validation//
        if (!preg_match("/^[0-9]*$/",$year)) {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Year is invalid"
          );
          echo json_encode($res);
          die();
        }
        if ($cyear<1905 || $cyear>2010 || is_float($year)) {
          $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Year is invalid"
          );
          echo json_encode($res);
          die();
        }
        //year validation//

        //html sanitization
        $fname=htmlspecialchars($fname);
        $lname=htmlspecialchars($lname);
        $date=htmlspecialchars($date);
        $bio=htmlspecialchars($bio);
        $month=htmlspecialchars($month);
        $year=htmlspecialchars($year);

        //sql sanitization
        $fname=mysqli_real_escape_string($conn,$fname);
        $lname=mysqli_real_escape_string($conn,$lname);
        $year=mysqli_real_escape_string($conn,$year);
        $month=mysqli_real_escape_string($conn,$month);
        $date=mysqli_real_escape_string($conn,$date);
        $bio=mysqli_real_escape_string($conn,$bio);

        checklogin($fname,$lname,$bio,$date,$month,$year);
	}
	catch(Exception $e){
		$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
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
    'isupdated' => "error",
    'msg' => "Somthing went wrong"
  );
  echo json_encode($res);
  http_response_code(401);
  die();
}
function checklogin($fname,$lname,$bio,$date,$month,$year){
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
          updatepersonal($uid,$fname,$lname,$bio,$date,$month,$year);
     }
     else{
      $res= array(
            'status' => '200 OK',
            'isupdated' => "error",
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
      'isupdated' => "error",
      'msg' => "Your are not logged in."
    );
    echo json_encode($res);
    http_response_code(401);
    die();
  }
}



	function updatepersonal($id,$fname,$lname,$bio,$date,$month,$year){
		require("../../db-con/db.php");
		$sql = "UPDATE users SET firstname='$fname', lastname='$lname', bio='$bio', date='$date', month='$month', year='$year' WHERE id='$id'";
		if (mysqli_query($conn, $sql)) {
			$res= array(
            'status' => '200 OK',
            'isupdated' => "true",
            'msg' => "Updated"
          );
          echo json_encode($res);
          die();
		}
		else{
			$res= array(
            'status' => '200 OK',
            'isupdated' => "error",
            'msg' => "Somthing went wrong"
          );
          echo json_encode($res);
          die();
		}
	}
?>