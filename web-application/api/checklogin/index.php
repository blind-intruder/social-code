<?php


function do_login($id,$email){

$json_token=make_json_web_token($id,$email);
$md5id=md5(time().$id.$email."@agla_lgao_beta".time()."#116".time()."+098".$id.$email.time());
$k2_cookie=md5(time().$id.$email."#haqsek2@116".time()."+".time()."098".$id.$email.time());
$extra = md5(hash("sha512", $id));


require("api/db-con/db.php");


$json_token_base64=base64_encode($json_token);
$ultra_base64=base64_encode($md5id);
$k2_base64=base64_encode($k2_cookie);
$extra_base64=base64_encode($extra);
$date=date("d-m-y"); 

$sql = "INSERT INTO do_login (uid, k2_cookie, json_token, ultra_cookie, extra,date)
VALUES ('$id', '$k2_base64', '$json_token_base64','$ultra_base64','$extra_base64','$date')";

if (mysqli_query($conn, $sql)) {
    //set cookie
    // cookie will expire after 10 days
	setcookie("json_token", $json_token, time() + (86400 * 10), "/"); 
	setcookie("ultra_cookie", $md5id, time() + (86400 * 10), "/"); 
	setcookie("k2_cookie", $k2_cookie, time() + (86400 * 10), "/");
	setcookie("k2_extra", $extra, time() + (86400 * 10), "/");  
	$res= array(
       		'status' => '200 OK',
    		'islogin' => "true",
        	'msg' => "Correct login"
    	);
    	echo json_encode($res);
      die();
} else {
    $res= array(
       		'status' => '200 OK',
    		'islogin' => "false",
        	'msg' => "Something went wrong"
    	);
    	echo json_encode($res);	
      die();
}



}

function make_json_web_token($id,$email){

	// Create token header as a JSON string
	$header = json_encode(['typ' => 'JWT', 'alg' => 'ye_to_hoga']);

	$user_secret=md5(time().time().$id.$email.'haqsek2'.$id.$email.time().time());
	// Create token payload as a JSON string
	$payload = json_encode(['user_id' => $id,'user_secret'=> $user_secret, 'ye_to_hoga'=>'true', 'hackers_stay_away'=>'1']);

	// Encode Header to Base64Url String
	$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

	// Encode Payload to Base64Url String
	$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));


	//ye kisi ko nahi btana
	$secret="#Haqsek2_420";
	//ye kisi ko nahi btana


	// Create Signature Hash
	$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

	// Encode Signature to Base64Url String
	$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

	// Create JWT
	$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

	return $jwt;
}


?>