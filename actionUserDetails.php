<?php 
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();

if(isset($_REQUEST['data'])){
	
	$id = explode("_", $_REQUEST['data']);
	
	$uid = $id[0];
	$status = $id[1];
	if($status==1){
		$data = array("isActive"=>0);
		$con = array("userId"=>$uid);
		$update = $db->update("tbl_user_master",$data,$con);
		if($update == true){
			$results = array(
				'result'=>'TRUE',
				'message'=>'User inactive successfully'
			);
		}else{
			$results = array(
				'result'=>'FALSE',
				'message'=>"Error found"
			);
		}

	}else{
		$data = array("isActive"=>1);
		$con = array("userId"=>$uid);
		$update = $db->update("tbl_user_master",$data,$con);
		if($update == true){
			$results = array(
				'result'=>'TRUE',
				'message'=>'User active Successfully'
			);
		}else{
			$results = array(
				'result'=>'FALSE',
				'message'=>mysql_error()
			);
		}
	}

}else{
	$results = array(
		'result'=>'FALSE',
		'message'=>'No Data For Edit'
	);
}
$response = json_encode($results);
echo $response;