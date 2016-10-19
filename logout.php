<?php
error_reporting(0);
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();

if(!empty($_SESSION['email']) && !empty($_SESSION['password'])){
$username = $_SESSION['email'];
$passwd = $_SESSION['password'];
$result = $db->chkLogout($username,$passwd);
if($result['result'] == "TRUE"){
	session_start();
	session_destroy();
	$_SESSION['ID']="";
	$_SESSION['adminx']="";

	header("Location:index.php");
}


}
?>