<?php 
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');

if(!empty($_REQUEST)){
	$rolename 	= $_REQUEST['roleName'];
	$rolediscription= $_REQUEST['roleDiscription'];
	$status = $_REQUEST['status'];
	
	$data = array(
		"roleName"=> $rolename,
		"roleDis"=> $rolediscription,
		"isActive"=>$status
	);
	
	$data_string = json_encode($data);
	$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_add_role.php');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		$results =  json_decode($result,true);
			
		$response = json_encode($results);
		echo $response;
}
		
			
?>