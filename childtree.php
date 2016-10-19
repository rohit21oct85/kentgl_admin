<?php  
	$conn = mysqli_connect("localhost","techteam","Tech@321","kentgl");

	$pid = 469;

	$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
		$sql_query = mysqli_query($conn,$query);
		while($result = mysqli_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
				//echo $value;
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $value group by parentUserId ";
					$sql_query = mysqli_query($conn,$query);
					while($result = mysqli_fetch_array($sql_query)){
						$sid = explode(",",$result['sale_person_id']);
						foreach($sid as $value){
								//echo $value;
								$query_dr = "select distinct(ld.loginUser) as uid,date(ld.loginDate) as repDate, 
						 um.userName,um.roleId,um.zone,um.emp_code,rm.roleName,rm.role_seq,s.state_name,c.city_name,
						 (SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Login_Time,
						 (SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout') as Logout_Time,
						(SELECT count(loginDate) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Totallog ,
						timediff((SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout'),(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login')) as total_time
						FROM tbl_login_details as ld
						LEFT JOIN tbl_user_master as um ON ld.loginUser = um.userId
						LEFT JOIN tbl_role_master as rm ON rm.roleId = um.roleId
						LEFT JOIN tbl_state as s ON s.state_Id = um.state
						LEFT JOIN tbl_city as c ON c.city_id = um.city
						WHERE  date(ld.loginDate) = date(CURRENT_DATE) AND ld.loginUser = $value AND um.roleId <= 4 order by rm.role_seq ASC,um.userName ASC";
					$sql_query_dr = mysqli_query($conn,$query_dr);
					$no = mysqli_num_rows($sql_query_dr);
					
					$data_result = array();
					$x = 0;
					while($result = mysqli_fetch_array($sql_query_dr)){
						$data_result['uid']	  = $result['uid'];
						$data_result['zone']	  = $result['zone'];
 						$data_result['emp_code']	  = $result['emp_code'];
						$data_result['repDate']	  = $result['repDate'];
						$data_result['userName']	  = $result['userName'];
						$data_result['roleName']	  = $result['roleName'];
						$data_result['state_name']	  = $result['state_name'];
						$data_result['city_name']	  = $result['city_name'];
						$data_result['Login'] = 	$result['Login_Time'];
						$data_result['Logout'] = 	$result['Logout_Time'];
						$data_result['Total_login'] = 	$result['Totallog'];	
						$data_result['total_time'] = 	$result['total_time'];	
						$return_results_top[] = $data_result;	
					}
					//return array("result"=>"TRUE","LoginDetails"=>$return_results_top);			
						}

					}
							
			}
			header('content-type: application/json');
			echo json_encode(array('result'=>'TRUE','LoginDetails'=>$return_results_top));	
		}		
?>