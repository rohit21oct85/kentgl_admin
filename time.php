<?php  
	session_start();
	require_once('includes/DBInterface.php');
	$db = new Database();
	$db->connect();
	$uid = 1;
	$result = $db->logoutDate($uid);
	echo json_encode($result);
?>