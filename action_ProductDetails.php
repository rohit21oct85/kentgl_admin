<?php 
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');

if(!empty($_REQUEST)){
	if($_REQUEST['action'] == "add" ){
		$productCode 	= $_REQUEST['productCode'];
		$productName 	= $_REQUEST['productName'];
		$productDis= $_REQUEST['productDiscription'];
		$status = $_REQUEST['status'];
	
	$data = array(
		"productCode"=> $productCode,
		"productName"=> $productName,
		"productDiscription"=> $productDis,
		"isActive"=>$status
	);
	
	$data_string = json_encode($data);

	$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_add_product.php');
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
	}else if($_REQUEST['action'] == "edit" ){
		$productCode 	= $_REQUEST['productCode'];
		$productId = $_REQUEST['productId'];
		$productname 	= $_REQUEST['productName'];
		$productDiscription= $_REQUEST['productDiscription'];
		$status = $_REQUEST['status'];
		
		$data = array(
			"productCode"=> $productCode,
			"productId" => $productId,
			"productName"=> $productname,
			"productDiscription"=> $productDiscription,
			"isActive"=>$status
		);
		
		$data_string = json_encode($data);
		
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_edit_product.php');
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
	}else{
		$id = explode("_", $_REQUEST['data']);
	
		$uid = $id[0];
		$status = $id[1];
		if($status==1){
			$data = array("isActive"=>0);
			$con = array("productId"=>$uid);
			$update = $db->update("tbl_product_master",$data,$con);
			if($update == true){
				$results = array(
					'result'=>'TRUE',
					'message'=>'Product inactive successfully'
				);
			}else{
				$results = array(
					'result'=>'FALSE',
					'message'=>"Error Found"
				);
			}

		}else{
			$data = array("isActive"=>1);
			$con = array("productId"=>$uid);
			$update = $db->update("tbl_product_master",$data,$con);
			if($update == true){
				$results = array(
					'result'=>'TRUE',
					'message'=>'Product active successfully'
				);
			}else{
				$results = array(
					'result'=>'FALSE',
					'message'=>mysql_error()
				);
			}
		}
		$response = json_encode($results);
		echo $response;
	}
}
		
			
?>