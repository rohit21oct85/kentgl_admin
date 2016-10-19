<?php 

session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');

if(!empty($_REQUEST)){
	if($_REQUEST['action'] == "add" ){
		
		$dis_code 	= $_REQUEST['dis_code'];
		$dis_name= $_REQUEST['dis_name'];
		$status = $_REQUEST['status'];
		
		if(is_numeric($_REQUEST['state']) == 0){
			$state1 = $_REQUEST['state1'];
			$state_id = $db->chkState($state1);
			if($state_id == 0){
				$data_state = array("state_name"=>$state1,"isActive"=>1,"cb"=>$parentId,"entryDate"=>date("y-m-d h:i:s"));
				$insert_state = $db->insert("tbl_state",$data_state);
					if($insert_state == true){
						$state1 = $_REQUEST['state1'];
						if(is_numeric($_REQUEST['city']) == 0){
								$state_id = $db->select_stateId($state1,$parentId);
								$data_city = array("isActive"=>1,"entryDate"=>date("y-m-d h:i:s"),"city_name"=>$city,"state_id"=>$state_id);
								$insert_city = $db->insert("tbl_city",$data_city);
								if($insert_city == true){
									$city_id = $db->select_cityId($city,$state_id);
								}else{
									echo mysql_error();
								}	
						}
					}else{
						echo mysql_error();
					}
			}else{
				$state = $state_id;
				$city = $_REQUEST['city1'];
				$city_id = $db->chkCity($city,$state);
				if($city_id==0){
					$data_city = array("isActive"=>1,"entryDate"=>date("y-m-d h:i:s"),"city_name"=>$city,"state_id"=>$state);
					$insert_city = $db->insert("tbl_city",$data_city);
					if($insert_city == true){
						$city_id = $db->select_cityId($city,$state_id);
					}else{
						echo mysql_error();
					}
				}else{
					$city_id = $city_id;
				}
			}
			
		
		$state = $state_id;
		$city = $city_id;
	}
	
	if(is_numeric($_REQUEST['state']) == 1){
		$state = $_REQUEST['state'];
		if(is_numeric($_REQUEST['city']) == 0){
			$city = $_REQUEST['city1'];
			$city_id = $db->chkCity($city,$state);
			if($city_id == 0){
				$data_city = array("isActive"=>1,"entryDate"=>date("y-m-d h:i:s"),"city_name"=>$city,"state_id"=>$state);
				$insert_city = $db->insert("tbl_city",$data_city);
				if($insert_city == true){
					$city_id = $db->select_cityId($city,$state);
				}else{
					echo mysql_error();
				}	
			}else{
				$city = $city_id;
			}
		}
		$city = $city_id;		
	}
	if(is_numeric($_REQUEST['city']) == 1){
		$city = $_REQUEST['city'];	
	}
	
	$data = array(
		"distributerCode"=> $dis_code,
		"distributer_name"=> $dis_name,
		"d_state"=> $state,
		"d_city"=> $city,
		"isActive"=>$status
	);
	
	$data_string = json_encode($data);
	
	$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_addDistributor.php');
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

		$dis_id = $_REQUEST['dis_id'];
		$dis_code 	= $_REQUEST['dis_code'];
		$dis_name= $_REQUEST['dis_name'];
		$status = $_REQUEST['status'];
		
		if(is_numeric($_REQUEST['state']) == 0){
			$state1 = $_REQUEST['state1'];
			$state_id = $db->chkState($state1);
			if($state_id == 0){
				$data_state = array("state_name"=>$state1,"isActive"=>1,"cb"=>$parentId,"entryDate"=>date("y-m-d h:i:s"));
				$insert_state = $db->insert("tbl_state",$data_state);
					if($insert_state == true){
						$state1 = $_REQUEST['state1'];
						if(is_numeric($_REQUEST['city']) == 0){
								$state_id = $db->select_stateId($state1,$parentId);
								$data_city = array("isActive"=>1,"entryDate"=>date("y-m-d h:i:s"),"city_name"=>$city,"state_id"=>$state_id);
								$insert_city = $db->insert("tbl_city",$data_city);
								if($insert_city == true){
									$city_id = $db->select_cityId($city,$state_id);
								}else{
									echo mysql_error();
								}	
						}
					}else{
						echo mysql_error();
					}
			}else{
				$state = $state_id;
				$city = $_REQUEST['city1'];
				$city_id = $db->chkCity($city,$state);
				if($city_id==0){
					$data_city = array("isActive"=>1,"entryDate"=>date("y-m-d h:i:s"),"city_name"=>$city,"state_id"=>$state);
					$insert_city = $db->insert("tbl_city",$data_city);
					if($insert_city == true){
						$city_id = $db->select_cityId($city,$state_id);
					}else{
						echo mysql_error();
					}
				}else{
					$city_id = $city_id;
				}
			}
			
		
		$state = $state_id;
		$city = $city_id;
	}
	
	if(is_numeric($_REQUEST['state']) == 1){
		$state = $_REQUEST['state'];
		if(is_numeric($_REQUEST['city']) == 0){
			$city = $_REQUEST['city1'];
			$city_id = $db->chkCity($city,$state);
			if($city_id == 0){
				$data_city = array("isActive"=>1,"entryDate"=>date("y-m-d h:i:s"),"city_name"=>$city,"state_id"=>$state);
				$insert_city = $db->insert("tbl_city",$data_city);
				if($insert_city == true){
					$city_id = $db->select_cityId($city,$state);
				}else{
					echo mysql_error();
				}	
			}else{
				$city = $city_id;
			}
		}
		$city = $city_id;		
	}
	if(is_numeric($_REQUEST['city']) == 1){
		$city = $_REQUEST['city'];	
	}
		
		$data = array(
			"dis_id"=>$dis_id,
			"distributerCode"=> $dis_code,
			"distributer_name"=> $dis_name,
			"d_state"=> $state,
			"d_city"=> $city,
			"isActive"=>$status
		);
		
		$data_string = json_encode($data);
		
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_ediDistributor.php');
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
			$con = array("dis_id"=>$uid);
			$update = $db->update("tbl_distributor_master",$data,$con);
			if($update == true){
				$results = array(
					'result'=>'TRUE',
					'message'=>'distributor inactive successfully'
				);
			}else{
				$results = array(
					'result'=>'FALSE',
					'message'=>"Error Found"
				);
			}

		}else{
			$data = array("isActive"=>1);
			$con = array("dis_id"=>$uid);
			$update = $db->update("tbl_distributor_master",$data,$con);
			if($update == true){
				$results = array(
					'result'=>'TRUE',
					'message'=>'distributor active successfully'
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