<?php 
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
	
	$username = trim($_REQUEST['username']);
	$passwd = trim($_REQUEST['password']);

	$result = $db->adminLogin($username,$passwd);
	
	$results = json_decode($result,true);

	if($results['result']=="TRUE"){


		//header("location:add_user.php");
		ini_set('session.gc_maxlifetime', 3600);
		session_set_cookie_params(3600);
		session_start();
		$_SESSION['email'] = $results['email']; 
		$_SESSION['password'] = $results['password']; 
		$_SESSION['adminx'] = $results['userName'];
		$_SESSION['userName'] = $results['userName'];
		$_SESSION['rolename'] = $results['roleName'];	
		$_SESSION['userId'] = $results['userId'];

		echo $result;
	}else{
		echo $result;
	}
	
}else{
		echo "Please Enter username and password ?";
}



?>