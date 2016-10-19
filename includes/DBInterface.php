<?php

class Database
{
	var $rs;
	var $dbh;
	 
	
	function Database()
	{
		$this->rs = "";
		$this->dbh = "";
	}
		
	function select_db()
	{
    	return mysql_select_db('kentgl');
	}
	    
	//Connect to Database
	
	function connect()
	{
		//crossAhead#mysql1 - .in password 
     	$this->dbh = mysql_connect('localhost', 'techteam' , 'Tech@321') or die('Not connected');
     	//$this->dbh = mysql_connect('localhost', 'root' , '') or die('Not connected');
		
	    $this->select_db();
		
		return $this->dbh;
    }	

	public function insert( $table, $variables = array() )
    {
        //self::$counter++;
        //Make sure the array isn't empty
        if( empty( $variables ) )
        {
            return false;
        }
        
        $sql = "INSERT INTO ". $table;
        $fields = array();
        $values = array();
        foreach( $variables as $field => $value )
        {
            $fields[] = $field;
            $values[] = "'".$value."'";
        }
        $fields = ' (' . implode(', ', $fields) . ')';
        $values = '('. implode(', ', $values) .')';
        
        $sql .= $fields .' VALUES '. $values;

        
        if( mysql_query( $sql ,$this->dbh ) )
        {
            
            return true;
        }
        else
        {
            return false;
        }
		
    }
	// update 
	public function update( $table, $variables = array(), $where = array() )
    {
        //self::$counter++;
        //Make sure the required data is passed before continuing
        //This does not include the $where variable as (though infrequently)
        //queries are designated to update entire tables
        if( empty( $variables ) )
        {
            return false;
        }
        $sql = "UPDATE ". $table ." SET ";
        foreach( $variables as $field => $value )
        {
            
            $updates[] = "`$field` = '$value'";
        }
        $sql .= implode(', ', $updates);
        
        //Add the $where clauses as needed
        if( !empty( $where ) )
        {
            foreach( $where as $field => $value )
            {
                $value = $value;

                $clause[] = "$field = '$value'";
            }
            $sql .= ' WHERE '. implode(' AND ', $clause);   
        }
        
       if(mysql_query( $sql ,$this->dbh ))
		{
			return true;
		}
		else
		{
			return false;
		}

    }
	
 public function delete( $table, $where = array(), $limit = '' )
    {
        
        //Delete clauses require a where param, otherwise use "truncate"
        if( empty( $where ) )
        {
            return false;
        }
        
        $sql = "DELETE FROM ". $table;
        foreach( $where as $field => $value )
        {
            $value = $value;
            $clause[] = "$field = '$value'";
        }
        $sql .= " WHERE ". implode(' AND ', $clause);
        
        if( !empty( $limit ) )
        {
            $sql .= " LIMIT ". $limit;
        }
            
        if(mysql_query( $sql ,$this->dbh ))
		{
			return true;
		}
		else
		{
			return false;
		}
    }
	
	public function adminLogin($username,$passwd){
		
		if(empty($username)){
			return json_encode(array('result'=>'FALSE','message'=>'Please send email.'));
		}else if(empty($passwd)){
			return json_encode(array('result'=>'FALSE','message'=>'Please send password.'));
		}else{
			$query = "SELECT um.userId,um.userName,um.email,um.mobileno,rm.roleId,rm.roleName,um.isActive,um.password,um.weekOff FROM tbl_user_master as um JOIN tbl_role_master as rm ON um.roleId = rm.roleId WHERE um.email = '$username' AND um.password = '$passwd' ";
			
			$result = mysql_query($query,$this->dbh);
			if( mysql_num_rows( $result ) > 0 ){
				$query_result = mysql_fetch_array($result);
			
				if($query_result['isActive']==1){
					
					if($query_result['roleId'] == 1 or $query_result['roleId'] == 2 or $query_result['roleId'] == 3 or $query_result['roleId'] == 6){

					$results = array(
						'result'=>'TRUE',
						'message'=>'Successfully login',
						'userId' => $query_result['userId'],
						'userName' => $query_result['userName'],
						'profile_pic'=>$query_result['profile_pic'],
						'roleName' => $query_result['roleName'],
						'mobile' => $query_result['mobileno'],
						'email' => $query_result['email'],
						'password'=>$query_result['password']
					);

					$data_login = array(
						'loginDate' => date('Y-m-d H:i:s'),
						'loginUser' => $query_result['userId'],
						'process'	=> "Login",
						'isActive'  => 1,
						'entryDate' => date('Y-m-d H:i:s')
						
					);
					$query = $this->insert('tbl_login_details',$data_login);

					return json_encode($results);
				}else{
					return json_encode(array('result'=>'FALSE','message'=>'You are not authorised User'));
				}
						
				}else{
					return json_encode(array('result'=>'FALSE','message'=>'Account Is Deactivated'));	
				}	
				
			}else{
				return  json_encode(array('result'=>'FALSE','message'=>'Entered email or password is not correct, please try again !'));
			}
		}
		
	}
	
public function chkLogout($username,$passwd){
		
		if(empty($username)){
			echo  json_encode(array('result'=>'FALSE','message'=>'Please send email.'));
		}else if(empty($passwd)){
			echo  json_encode(array('result'=>'FALSE','message'=>'Please send password.'));
		}else{
			$query = "SELECT userId FROM tbl_user_master WHERE email = '$username' AND password = '$passwd' ";
			
			$result = mysql_query($query,$this->dbh);
			if( mysql_num_rows( $result ) > 0 ){
				$query_result = mysql_fetch_assoc($result);
				
				$data_login = array(
					'loginDate' => date('Y-m-d H:i:s'),
					'loginUser' => $query_result['userId'],
					'process'	=> "Logout",
					'isActive'  => 1,
					'entryDate' => date('Y-m-d H:i:s')
					
				);
				$query = $this->insert('tbl_login_details',$data_login);	
				$results = array(
					'result'=>'TRUE',
					'message'=>'You are successfully logged out'
				);	
				return $results;
			}else{
				return  json_encode(array('result'=>'FALSE','message'=>'Entered email or passord is not correct, please try again !'));
			}
		}
		
	}

	function selectRole()
	{
		$return_results_top = array();
		$query = "SELECT roleId,roleName,roleDiscription,isActive,role_seq FROM tbl_role_master order by isActive DESC";
		$sql_query = mysql_query($query, $this->dbh);
		if(mysql_num_rows($sql_query) > 0){
			$data_result = array();
			while($result = mysql_fetch_array($sql_query)){
				$data_result['roleId'] = trim($result['roleId']);
				$data_result['roleName'] = trim($result['roleName']);
				$data_result['roleDiscription'] = trim($result['roleDiscription']);
				$data_result['isActive'] = trim($result['isActive']);
				$data_result['role_seq'] = trim($result['role_seq']);
				
				$return_results_top[] = $data_result;	
			}
			return  json_encode(array('result'=>'TRUE','RoleList'=>$return_results_top));
		}
		
	}
	
	function selectReporting($roleId){
		$data = array("action"=>"reporting","role_id"=>$roleId);
		$data_string = json_encode($data);
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_get_reporting_list.php');
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

		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}
	
	function select_state()
	{
		$data = array("action"=>"state");
		$data_string = json_encode($data);
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_select_state.php');
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

		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}
	
	function select_city($stateid){
		$data = array("state_id"=>$stateid);
		$data_string = json_encode($data);
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_select_city.php');
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

		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}
	
	function select_stateId($state1,$userId){
		$select_stid = "SELECT state_Id FROM `tbl_state` WHERE state_name LIKE '$state1' AND cb = $userId";
		$query = mysql_query($select_stid, $this->dbh);
		$result = mysql_fetch_array($query);
		$response = $result['state_Id'];
		return $response;
	}
	function chkState($state1){
		$select_stid = "SELECT state_Id FROM `tbl_state` WHERE state_name LIKE '$state1'";
		$query = mysql_query($select_stid, $this->dbh);
		if(mysql_num_rows($query) > 0){
			$result = mysql_fetch_array($query);
			$state_id = $result['state_Id'];
			return $state_id;
		}else{
			return 0;
		}
	}
	function chkCity($city,$state_id){
		$select_ctid = "SELECT city_id FROM `tbl_city` WHERE city_name LIKE '$city' AND state_id = $state_id";
		$query = mysql_query($select_ctid, $this->dbh);
		if(mysql_num_rows($query) > 0){
			$result = mysql_fetch_array($query);
			$city_id = $result['city_id'];
			return $city_id;
		}else{
			return 0;
		}
	}
	function select_cityId($city,$state_id){
		$select_ctid = "SELECT city_id FROM `tbl_city` WHERE city_name LIKE '$city' AND state_id = $state_id";
		$query = mysql_query($select_ctid, $this->dbh);
		$result = mysql_fetch_array($query);
		$response = $result['city_id'];
		return $response;
	}

	
	function viewSalePerson($glid){
		
		$select = "select um.roleId,rm.roleName,uh.pan_access,um.userId from tbl_user_master as um LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId LEFT OUTER JOIN tbl_user_hierarchy as uh on uh.userId = um.userId WHERE um.userId = $glid LIMIT 1";
		
		$query = mysql_query($select,$this->dbh);
		$result = mysql_fetch_array($query);
		$role = $result['roleName'];
		$pan_access = $result['pan_access'];
		$userId = $result['userId'];
		if($role == "admin"){
		$return_results_top = array();
		//echo "Not Admin";
		$query_sales = "SELECT um.userId,um.emp_code,um.zone,um.userName,um.email,um.mobileno,um.weekOff,um.isActive,rm.roleName,rm.role_seq , uh.parentUserId , c.city_name ,s.state_name FROM tbl_user_master as um 
			LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId 
			LEFT JOIN tbl_user_hierarchy as uh ON um.userId = uh.userId 
			LEFT JOIN tbl_city as c ON um.city = c.city_id
			LEFT JOIN tbl_state as s ON um.state = s.state_Id
			ORDER BY rm.role_seq ASC, um.userName ASC";
		$sql_query = mysql_query($query_sales,$this->dbh);
					
		$data_result = array();
		$x=0;
		while($result = mysql_fetch_assoc($sql_query)){
			//print_r($result);
			$data_result['userId'] = $result['userId'];
			$data_result['emp_code'] = $result['emp_code'];
			$data_result['zone'] = $result['zone'];
			$data_result['userName'] = $result['userName'];
			$data_result['email'] = $result['email'];
			$data_result['mobileno'] = $result['mobileno'];
			$data_result['weekOff'] = $result['weekOff'];
			$data_result['isActive'] = $result['isActive'];
			$data_result['roleName'] = $result['roleName'];
			$data_result['parentUserId'] = $result['parentUserId'];
			if($result['city_name']==null){
				$data_result['city'] = "";	
			}else{
				$data_result['city'] = $result['city_name'];	
			}
			if($result['state_name']==null){
				$data_result['state'] = "";	
			}else{
				$data_result['state'] = $result['state_name'];	
			}
			$return_results_top[] = $data_result;	
		$x++;	
		}
		return  json_encode(array('result'=>'TRUE','PersonalDatails'=>$return_results_top));
					
		}else if($role == "management" && $pan_access == 0){
			$return_results_top = array();
			$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $glid group by parentUserId ";
		
		$sql_query = mysql_query($query,$this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
				//echo $value;
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $value group by parentUserId ";
					$sql_query = mysql_query($query,$this->dbh);
					while($result = mysql_fetch_array($sql_query)){
						$sid = explode(",",$result['sale_person_id']);
						foreach($sid as $value){
								//echo $value;
								$query_sales = "SELECT um.userId,um.emp_code,um.zone,um.userName,um.email,um.mobileno,um.weekOff,um.isActive,rm.roleName,rm.role_seq , uh.parentUserId , c.city_name ,s.state_name FROM tbl_user_master as um 
									LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId 
									LEFT JOIN tbl_user_hierarchy as uh ON um.userId = uh.userId 
									LEFT JOIN tbl_city as c ON um.city = c.city_id
									LEFT JOIN tbl_state as s ON um.state = s.state_Id
									WHERE um.userId = $value ORDER BY rm.role_seq ASC, um.userName ASC";
								$sql_query = mysql_query($query_sales,$this->dbh);
									while($result = mysql_fetch_assoc($sql_query)){
										$data_result['userId'] = $result['userId'];
										$data_result['emp_code'] = $result['emp_code'];
										$data_result['zone'] = $result['zone'];
										$data_result['userName'] = $result['userName'];
										$data_result['email'] = $result['email'];
										$data_result['mobileno'] = $result['mobileno'];
										$data_result['weekOff'] = $result['weekOff'];
										$data_result['isActive'] = $result['isActive'];
										$data_result['roleName'] = $result['roleName'];
										$data_result['parentUserId'] = $result['parentUserId'];
														
										if($result['city_name']==null){
											$data_result['city'] = "";	
										}else{
											$data_result['city'] = $result['city_name'];	
										}
										if($result['state_name']==null){
											$data_result['state'] = "";	
										}else{
											$data_result['state'] = $result['state_name'];	
										}
										
										$return_results_top[] = $data_result;
								}
								
						}

					}
							
			}
			
			return json_encode(array('result'=>'TRUE','PersonalDatails'=>$return_results_top));	
		}
		}else if($role == "rm"){
			$return_results_top = array();
			$query = "SELECT parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy WHERE parentUserId IN (SELECT userId  FROM `tbl_user_hierarchy` WHERE `parentUserId` = $glid) or parentUserId = $glid";
		
			$sql_query = mysql_query($query,$this->dbh);
			if( mysql_num_rows( $sql_query ) > 0 ){
				$x =0;
				while($data_result = mysql_fetch_assoc($sql_query)){
					
					$sale_person_Id = $data_result['sale_person_id'];
					$query_sales = "SELECT um.userId,um.emp_code,um.zone,um.userName,um.email,um.mobileno,um.weekOff,um.isActive,rm.roleName,rm.role_seq , uh.parentUserId , c.city_name ,s.state_name FROM tbl_user_master as um 
			LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId 
			LEFT JOIN tbl_user_hierarchy as uh ON um.userId = uh.userId 
			LEFT JOIN tbl_city as c ON um.city = c.city_id
			LEFT JOIN tbl_state as s ON um.state = s.state_Id
						WHERE um.userId IN ($sale_person_Id) ORDER BY rm.role_seq ASC, um.userName ASC";
					$sql_query = mysql_query($query_sales);
					if( mysql_num_rows($sql_query) > 0){
						$data_result = array();
						while($result = mysql_fetch_assoc($sql_query)){
							$data_result['userId'] = $result['userId'];
							$data_result['emp_code'] = $result['emp_code'];
							$data_result['zone'] = $result['zone'];
							$data_result['userName'] = $result['userName'];
							$data_result['email'] = $result['email'];
							$data_result['mobileno'] = $result['mobileno'];
							$data_result['weekOff'] = $result['weekOff'];
							$data_result['isActive'] = $result['isActive'];
							$data_result['roleName'] = $result['roleName'];
							$data_result['parentUserId'] = $result['parentUserId'];
											
							if($result['city_name']==null){
								$data_result['city'] = "";	
							}else{
								$data_result['city'] = $result['city_name'];	
							}
							if($result['state_name']==null){
								$data_result['state'] = "";	
							}else{
								$data_result['state'] = $result['state_name'];	
							}
							
							$return_results_top[] = $data_result;	
						$x++;	
						}
						return  json_encode(array('result'=>'TRUE','PersonalDatails'=>$return_results_top));
					}
				else{
				return  json_encode(array('result'=>'FALSE','message'=>'No more Results'));
			}
				}	
				
			}else{
			return  json_encode(array('result'=>'FALSE','message'=>'No more Results'));	
			}

	
		}else if($role = "management" && $pan_access == 1){
			
			if($userId == 214){
				$query = "select CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where panmid = $glid group by panmid";
			}else{
				$query = "SELECT CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id FROM `tbl_user_hierarchy` WHERE `panid` = $glid group by panid ";
			}
			
			$sql_query = mysql_query($query,$this->dbh);
			if( mysql_num_rows( $sql_query ) > 0 ){
				$x =0;
				while($data_result = mysql_fetch_assoc($sql_query)){
					
					$sale_person_Id = $data_result['sale_person_id'];
					$query_sales = "SELECT um.userId,um.emp_code,um.zone,um.userName,um.email,um.mobileno,um.weekOff,um.isActive,rm.roleName,rm.role_seq , uh.parentUserId , c.city_name ,s.state_name FROM tbl_user_master as um 
			LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId 
			LEFT JOIN tbl_user_hierarchy as uh ON um.userId = uh.userId 
			LEFT JOIN tbl_city as c ON um.city = c.city_id
			LEFT JOIN tbl_state as s ON um.state = s.state_Id
						WHERE um.userId IN ($sale_person_Id) ORDER BY rm.role_seq ASC, um.userName ASC";
					$sql_query = mysql_query($query_sales);
					if( mysql_num_rows($sql_query) > 0){
						$data_result = array();
						while($result = mysql_fetch_assoc($sql_query)){
							$data_result['userId'] = $result['userId'];
							$data_result['emp_code'] = $result['emp_code'];
							$data_result['zone'] = $result['zone'];
							$data_result['userName'] = $result['userName'];
							$data_result['email'] = $result['email'];
							$data_result['mobileno'] = $result['mobileno'];
							$data_result['weekOff'] = $result['weekOff'];
							$data_result['isActive'] = $result['isActive'];
							$data_result['roleName'] = $result['roleName'];
							$data_result['parentUserId'] = $result['parentUserId'];
											
							if($result['city_name']==null){
								$data_result['city'] = "";	
							}else{
								$data_result['city'] = $result['city_name'];	
							}
							if($result['state_name']==null){
								$data_result['state'] = "";	
							}else{
								$data_result['state'] = $result['state_name'];	
							}
							
							$return_results_top[] = $data_result;	
						$x++;	
						}
						return  json_encode(array('result'=>'TRUE','PersonalDatails'=>$return_results_top));
					}
				else{
				return  json_encode(array('result'=>'FALSE','message'=>'No more Results'));
			}
				}	
				
			}else{
			return  json_encode(array('result'=>'FALSE','message'=>'No more Results'));	
			}
		}else{
		$return_results_top = array();
		$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 AND parentUserId = $glid group by parentUserId";
		
		$sql_query = mysql_query($query,$this->dbh);
			if( mysql_num_rows( $sql_query ) > 0 ){
				$x =0;
				while($data_result = mysql_fetch_assoc($sql_query)){
					
					$sale_person_Id = $data_result['sale_person_id'];
					$query_sales = "SELECT um.userId,um.emp_code,um.zone,um.userName,um.email,um.mobileno,um.weekOff,um.isActive,rm.roleName,rm.role_seq , uh.parentUserId , c.city_name ,s.state_name FROM tbl_user_master as um 
			LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId 
			LEFT JOIN tbl_user_hierarchy as uh ON um.userId = uh.userId 
			LEFT JOIN tbl_city as c ON um.city = c.city_id
			LEFT JOIN tbl_state as s ON um.state = s.state_Id
						WHERE um.userId IN ($sale_person_Id) ORDER BY rm.role_seq ASC, um.userName ASC";
					$sql_query = mysql_query($query_sales);
					if( mysql_num_rows($sql_query) > 0){
						$data_result = array();
						while($result = mysql_fetch_assoc($sql_query)){
							$data_result['userId'] = $result['userId'];
							$data_result['emp_code'] = $result['emp_code'];
							$data_result['zone'] = $result['zone'];
							$data_result['userName'] = $result['userName'];
							$data_result['email'] = $result['email'];
							$data_result['mobileno'] = $result['mobileno'];
							$data_result['weekOff'] = $result['weekOff'];
							$data_result['isActive'] = $result['isActive'];
							$data_result['roleName'] = $result['roleName'];
							$data_result['parentUserId'] = $result['parentUserId'];
											
							if($result['city_name']==null){
								$data_result['city'] = "";	
							}else{
								$data_result['city'] = $result['city_name'];	
							}
							if($result['state_name']==null){
								$data_result['state'] = "";	
							}else{
								$data_result['state'] = $result['state_name'];	
							}
							
							$return_results_top[] = $data_result;	
						$x++;	
						}
						return  json_encode(array('result'=>'TRUE','PersonalDatails'=>$return_results_top));
					}
				else{
				return  json_encode(array('result'=>'FALSE','message'=>'No more Results'));
			}
				}	
				
			}else{
			return  json_encode(array('result'=>'FALSE','message'=>'No more Results'));	
			}


		}
		// ends of management 
	

	}
	
	function getUserDetails($uid){
		
		$data = array("user_id"=>$uid);
		$data_string = json_encode($data);
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_getUserDetails.php');
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

		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}
	
	
	function getRoleDetails($role_id){
		
		$data = array("role_id"=>$role_id);
		$data_string = json_encode($data);
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_getRoleDetails.php');
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

		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}

	function getProductDetails($pid){
		$return_results_top = array();
	$select = "SELECT * from tbl_product_master where productId = $pid ";
	$query = mysql_query($select, $this->dbh);
	if(mysql_num_rows($query) >0){
		$data_result = array();
		$result = mysql_fetch_array($query);
		$data_result['product_code'] = $result['product_code'];
		$data_result['productId'] = trim($result['productId']);
		$data_result['productName'] = trim($result['productName']);
		$data_result['productDiscription'] = trim($result['productDiscription']);
		$data_result['isActive'] = trim($result['isActive']);
		$return_results_top[] = $data_result;	
		$result = json_encode(array("Result"=> "TURE","productDetails"=>$return_results_top));
	}

		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}

	function getProductList(){
		$return_results_top = array();
		$query = "select product_code,productId,productName,productDiscription,isActive from tbl_product_master order by rand()";
		$sql_query = mysql_query($query, $this->dbh);
		if( mysql_num_rows( $sql_query ) > 0 ){
		$data_result = array();
		$x = 0;
		while($row = mysql_fetch_array($sql_query)){
			$data_result['product_code'] = $row['product_code'];
			$data_result['productId'] = $row['productId'];
			$data_result['productName'] = $row['productName'];
			$data_result['productDiscription'] = $row['productDiscription'];
			$data_result['isActive'] = $row['isActive'];
			$return_results_top[] = $data_result;
		$x++;	
		}
		$result =  json_encode(array('result'=>'TRUE','ProductList'=>$return_results_top));
		}else{
			$result =  json_encode(array('result'=>'FALSE','message'=>'No more Products'));	
		}
		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}

	function getDistributorDetails($dis_id)	{
		
		$return_results_top = array();
		$select = "SELECT dm.dis_id,dm.dc,dm.distributer_name,dm.d_state as state_id,dm.d_city as city_id ,s.state_name,c.city_name,dm.isActive FROM tbl_distributor_master as dm LEFT JOIN tbl_state as s ON s.state_Id = dm.d_state LEFT JOIN tbl_city as c ON c.city_id = dm.d_city where dm.dis_id = $dis_id ";
		$query = mysql_query($select, $this->dbh);
		if(mysql_num_rows($query) > 0){
			$result_array = array();
			while ($result = mysql_fetch_array($query)) {
				$result_array['dis_id'] = $result['dis_id'];
				$result_array['dis_code'] = $result['dc'];
				$result_array['dis_name'] = $result['distributer_name'];
				$result_array['state_id'] = $result['state_id'];
				$result_array['city_id'] = $result['city_id'];
				$result_array['state_name'] = $result['state_name'];
				$result_array['city_name'] = $result['city_name'];
				
				$result_array['isActive'] = $result['isActive'];
				$return_results_top[] = $result_array;
			}
			return array("result"=> "TRUE","DisDetails"=>$return_results_top);
		}else{
			return array("result"=> "False","errormsg"=>mysql_error());
		}
	}


	function getDailySaleReport($pid){
		
		$select = "select um.roleId,rm.roleName,uh.pan_access,um.userId from tbl_user_master as um LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId LEFT OUTER JOIN tbl_user_hierarchy as uh on uh.userId = um.userId WHERE um.userId = $pid LIMIT 1";
		
		$query = mysql_query($select,$this->dbh);
		$result = mysql_fetch_array($query);
		$role = $result['roleName'];
		$pan_access = $result['pan_access'];
		$userId = $result['userId'];
		if($role == "admin"){
			$return_results_top = array();
			$sel_gl = "SELECT s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
				(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
				(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
				(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
				(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId ) as today_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId ) as mtd_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as demo_sp,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as demo_gl,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as sale_sp,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
				(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_sp,
				(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_gl,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_sale_sp,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) ) as mtd_sale_gl
				FROM tbl_user_master as um 
				LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
				LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
				LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
				LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
				LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
				LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
				WHERE um.roleId = 4 GROUP by userId ORDER by um.userId desc"; 
 				$query_gl = mysql_query($sel_gl,$this->dbh);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


						$return_results_top[] = $data_array;
				}	
				return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));

		}else if($role == "management" && $pan_access == 0){
				$return_results_top = array();
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
		$sql_query = mysql_query($query, $this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
				//echo $value;
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $value group by parentUserId ";
					$sql_query = mysql_query($query, $this->dbh);
					while($result = mysql_fetch_array($sql_query)){
						$sid = explode(",",$result['sale_person_id']);
						foreach($sid as $value){
								//echo $value;
								$sel_gl = "SELECT s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
				(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
				(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
				(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
				(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId ) as today_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId ) as mtd_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as demo_sp,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as demo_gl,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as sale_sp,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
				(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_sp,
				(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_gl,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_sale_sp,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) ) as mtd_sale_gl
				FROM tbl_user_master as um 
				LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
				LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
				LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
				LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
				LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
				LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
				WHERE um.roleId = 4 and um.userId = $value GROUP by userId ORDER by um.userId desc"; 
 				$query_gl = mysql_query($sel_gl, $this->dbh);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


						$return_results_top[] = $data_array;
						
		
				}
								
						}

					}
							
			}
			//header('content-type: application/json');
			return json_encode(array('result'=>'TRUE','ReportList'=>$return_results_top));	
		}
		}else if($role == "rm"){
			
			$return_results_top = array();
			$query = "SELECT uh.parentUserId,CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh left OUTER join tbl_user_master as um ON um.userId = uh.userId WHERE uh.parentUserId IN (SELECT userId  FROM `tbl_user_hierarchy` WHERE parentUserId = $pid) or uh.parentUserId = $pid AND um.roleId = 4 ";
		
		$sql_query = mysql_query($query, $this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
		

		//echo $file_name."-".$parent_id."--[".$sale_person_id."]"."<br>";
		$sel_gl = "SELECT s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
				(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
				(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
				(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
				(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId ) as today_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId ) as mtd_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as demo_sp,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as demo_gl,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as sale_sp,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
				(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_sp,
				(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_gl,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_sale_sp,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) ) as mtd_sale_gl
				FROM tbl_user_master as um 
				LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
				LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
				LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
				LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
				LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
				LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
				WHERE um.roleId = 4 and um.userId = $value GROUP by userId ORDER by um.userId desc"; 
 				$query_gl = mysql_query($sel_gl);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


						$return_results_top[] = $data_array;
						
		
				}	
			}
		}	
		return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));
		}else if($role = "management" && $pan_access == 1){
			
			if($userId == 214){
				$query = "select CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh LEFT outer join tbl_user_master as um on um.userId = uh.userid where uh.panmid = $pid and um.roleId = 4 group by uh.panmid";
			}else{
				$query = "SELECT CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id FROM tbl_user_hierarchy as uh left outer join tbl_user_master as um on um.userId = uh.userId WHERE uh.panid = $pid and um.roleId = 4 group by panid ";
			}
			//echo $query; die;
			$return_results_top = array();
			$sql_query = mysql_query($query, $this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
		

		//echo $file_name."-".$parent_id."--[".$sale_person_id."]"."<br>";
		$sel_gl = "SELECT s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
				(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
				(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
				(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
				(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId ) as today_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId ) as mtd_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as demo_sp,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as demo_gl,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as sale_sp,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
				(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_sp,
				(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_gl,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_sale_sp,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) ) as mtd_sale_gl
				FROM tbl_user_master as um 
				LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
				LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
				LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
				LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
				LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
				LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
				WHERE um.roleId = 4 and um.userId = $value GROUP by userId ORDER by um.userId desc"; 
 				$query_gl = mysql_query($sel_gl);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


						$return_results_top[] = $data_array;
						
		
				}	
			}
		}	
		return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));
			
		}else{

		$return_results_top = array();
		$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
		$sql_query = mysql_query($query, $this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
		

		//echo $file_name."-".$parent_id."--[".$sale_person_id."]"."<br>";
		$sel_gl = "SELECT s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
				(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
				(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
				(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
				(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId ) as today_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) = date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId ) as mtd_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as demo_sp,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as demo_gl,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=um.userId) as sale_sp,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) = date(CURRENT_DATE()) and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
				(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_sp,
				(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_demo_gl,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE())) as mtd_sale_sp,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between subdate(curdate(), (day(curdate())-1)) AND date(CURRENT_DATE()) ) as mtd_sale_gl
				FROM tbl_user_master as um 
				LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
				LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
				LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
				LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
				LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
				LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
				WHERE um.roleId = 4 and um.userId = $value GROUP by userId ORDER by um.userId desc"; 
 				$query_gl = mysql_query($sel_gl);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


						$return_results_top[] = $data_array;
						
		
				}	
			}
		}	
		return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));
	}
	}

	function getDailyLoginReport($pid){

		if(empty($pid)){
			
			return  json_encode(array('result'=>'FALSE','message'=>'Enter parent Id'));	
			
		}else{
		
		$select = "select um.roleId,rm.roleName,uh.pan_access,um.userId from tbl_user_master as um LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId LEFT OUTER JOIN tbl_user_hierarchy as uh on uh.userId = um.userId WHERE um.userId = $pid LIMIT 1";
		
		$query = mysql_query($select,$this->dbh);
		$result = mysql_fetch_array($query);
		$role = $result['roleName'];
		$pan_access = $result['pan_access'];
		$userId = $result['userId'];
		if($role == "admin"){

			$return_results_top = array();
			$sel_gl = "select distinct(ld.loginUser) as uid,date(ld.loginDate) as repDate, 
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
						WHERE  date(ld.loginDate) = date(CURRENT_DATE) AND um.roleId <= 4 order by rm.role_seq ASC,um.userName ASC"; 
 				$query_gl = mysql_query($sel_gl,$this->dbh);
 				$data_result = array();
 				while($result = mysql_fetch_array($query_gl)){

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
				return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
		}else if($role == "management" && $pan_access == 0){
			$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
		$sql_query = mysql_query($query,$this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
				//echo $value;
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $value group by parentUserId ";
					$sql_query = mysql_query($query,$this->dbh);
					while($result = mysql_fetch_array($sql_query)){
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
					$sql_query_dr = mysql_query($query_dr,$this->dbh);
					$no = mysql_num_rows($sql_query_dr);
					
					$data_result = array();
					$x = 0;
					while($result = mysql_fetch_array($sql_query_dr)){
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
			//header('content-type: application/json');
			return array('result'=>'TRUE','LoginDetails'=>$return_results_top);	
		}	
		}else if($role == "rm"){
		$return_results_top = array();
		
		$query = "SELECT uh.parentUserId,CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh WHERE uh.parentUserId IN (SELECT userId  FROM `tbl_user_hierarchy` WHERE parentUserId = $pid) or uh.parentUserId = $pid ";
		
		$sql_query = mysql_query($query, $this->dbh);
		$rcount = mysql_num_rows($sql_query);
		if($rcount == 1){
			while($result = mysql_fetch_array($sql_query)){
			$sid = $result['sale_person_id'];
			//print_r($sid);
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
			WHERE  date(ld.loginDate) = date(CURRENT_DATE) AND ld.loginUser IN ($sid) AND um.roleId <= 4 order by rm.role_seq ASC,um.userName ASC";
					$sql_query_dr = mysql_query($query_dr, $this->dbh);
					$no = mysql_num_rows($sql_query_dr);
					
					$data_result = array();
					$x = 0;
					while($result = mysql_fetch_array($sql_query_dr)){
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
					return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
			
				}
			}else{
				return array("result"=>"FALSE","message"=>mysql_error());
			}
		}else if($role = "management" && $pan_access == 1){
			$return_results_top = array();
			if($userId == 214){
				$query = "select CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh LEFT outer join tbl_user_master as um on um.userId = uh.userid where uh.panmid = $pid group by uh.panmid";
			}else{
				$query = "SELECT CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id FROM tbl_user_hierarchy as uh left outer join tbl_user_master as um on um.userId = uh.userId WHERE uh.panid = $pid group by panid ";
			}
		
		$sql_query = mysql_query($query, $this->dbh);
		$rcount = mysql_num_rows($sql_query);
		if($rcount == 1){
			while($result = mysql_fetch_array($sql_query)){
			$sid = $result['sale_person_id'];
			//print_r($sid);
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
						WHERE  date(ld.loginDate) = date(CURRENT_DATE) AND ld.loginUser IN ($sid) AND um.roleId <= 4 order by rm.role_seq ASC,um.userName ASC";
					$sql_query_dr = mysql_query($query_dr, $this->dbh);
					$no = mysql_num_rows($sql_query_dr);
					
					$data_result = array();
					$x = 0;
					while($result = mysql_fetch_array($sql_query_dr)){
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
					return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
			}
		}else{
					return array("result"=>"FALSE","message"=>mysql_error());
				}	
		}else{	

		$return_results_top = array();
		
		$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId";
		
		$sql_query = mysql_query($query, $this->dbh);
		$rcount = mysql_num_rows($sql_query);
		if($rcount == 1){
			while($result = mysql_fetch_array($sql_query)){
			$sid = $result['sale_person_id'];
			//print_r($sid);
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
						WHERE  date(ld.loginDate) = date(CURRENT_DATE) AND ld.loginUser IN ($sid) AND um.roleId <= 4 order by rm.role_seq ASC,um.userName ASC";
					$sql_query_dr = mysql_query($query_dr, $this->dbh);
					$no = mysql_num_rows($sql_query_dr);
					
					$data_result = array();
					$x = 0;
					while($result = mysql_fetch_array($sql_query_dr)){
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
					return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
			
		}
	}else{
		return array("result"=>"FALSE","message"=>mysql_error());
	}

	}

	}	

	}
	
	function getGlDetails($glid){
		
		if(empty($glid)){
			$result= json_encode(array("result"=>"False","message"=>"please enter group leader id"));
		}else{
			$return_results_top = array();
		$select = "SELECT um.userName,um.userId,um.zone,um.emp_code,um.mobileno,um.email,um.state,s.state_name,c.city_name,um.city,um.weekOff,um.roleId,rm.roleName,d.dis_id,dm.dc,dm.distributer_name,uh.parentUserId,um.isActive,(select userName from tbl_user_master WHERE userId = uh.parentUserId) as reporting_to  from tbl_user_master as um LEFT JOIN tbl_role_master as rm ON rm.roleId = um.roleId LEFT JOIN tbl_distributor as d ON d.userId = um.userId LEFT JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id LEFT JOIN tbl_state as s ON s.state_Id = um.state LEFT JOIN tbl_city as c ON c.city_id = um.city LEFT JOIN tbl_user_hierarchy as uh ON um.userId = uh.userId WHERE um.userId = $glid";
		$sql_query = mysql_query($select, $this->dbh);

		if(mysql_num_rows($sql_query) > 0){
			$result_array = array();
			while($result = mysql_fetch_array($sql_query)){
				$result_array['glid'] = $result['userId'];
				$result_array['zone'] = $result['zone'];
				$result_array['emp_code'] = $result['emp_code'];
				$result_array['name'] = $result['userName'];
				$result_array['mobileno'] = $result['mobileno'];
				$result_array['email'] = $result['email'];
				$result_array['state_id'] = $result['state'];
				$result_array['state'] = $result['state_name'];
				$result_array['city_id'] = $result['city'];
				$result_array['city'] = $result['city_name'];
				$result_array['weekOff'] = $result['weekOff'];
				$result_array['roleId'] = $result['roleId'];
				$result_array['roleName'] = $result['roleName'];
				$result_array['parentUserId'] = $result['parentUserId'];
				$result_array['reporting_to'] = $result['reporting_to'];
				$result_array['dis_id'] = $result['dis_id'];
				$result_array['isActive'] = $result['isActive'];
				if(!empty($result['dc'])){
					$result_array['DistributorCode'] = $result['dc'];
				}else{
					$result_array['DistributorCode'] = "NA";
				}
				if(!empty($result['distributer_name'])){
					$result_array['distributer_name'] = $result['distributer_name'];
				}else{
					$result_array['distributer_name'] = "NA";
				}
				
				$return_results_top[] = $result_array;
			}
			$result= json_encode(array("result"=>"TRUE","GlList"=>$return_results_top));
		}else{
			$result= json_encode(array("result"=>"False","message"=>mysql_error()));
		}
		}
		
		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}

	function getglList(){
		$data = array("action"=>"gllist");
		$data_string = json_encode($data);
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_getgl_list.php');
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
		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}
	function getDistributorList(){
		$data = array("action"=>"gllist");
		$data_string = json_encode($data);
		$ch = curl_init('http://ffms.clicktable.com/KentGL_API/admin_getDistributorList.php');
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
		$loginDetails =  json_decode($result,true);
		return $loginDetails;
	}
	
	function getAllDistributorList(){
		
		$return_results_top = array();
		$select = "SELECT dm.dis_id,dm.dc,dm.distributer_name,dm.d_state as state_id,dm.d_city as city_id ,s.state_name,c.city_name,dm.isActive FROM tbl_distributor_master as dm LEFT JOIN tbl_state as s ON s.state_Id = dm.d_state LEFT JOIN tbl_city as c ON c.city_id = dm.d_city";
		$query = mysql_query($select, $this->dbh);
		if(mysql_num_rows($query) > 0){
			$result_array = array();
			while ($result = mysql_fetch_array($query)) {
				$result_array['dis_id'] = $result['dis_id'];
				$result_array['dis_code'] = $result['dc'];
				$result_array['dis_name'] = $result['distributer_name'];
				$result_array['state_name'] = $result['state_name'];
				$result_array['city_name'] = $result['city_name'];
				
				$result_array['isActive'] = $result['isActive'];
				$return_results_top[] = $result_array;
			}
			return array("result"=> "TRUE","DistributorAllList"=>$return_results_top);
		}else{
			return array("result"=> "False","errormsg"=>mysql_error());
		}
	}


	function chkDistributorCode($dc){
		$select = "SELECT dis_id FROM tbl_distributor_master WHERE dc LIKE '$dc'";
		$query = mysql_query($select, $this->dbh);
		if(mysql_num_rows($query) > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function getMaxDc($discode,$reporting_to){
		$select = "SELECT MAX(dis_id) as dis_id FROM tbl_distributor_master WHERE dc LIKE '$discode' AND cb = $reporting_to";
		$query = mysql_query($select, $this->dbh);
		if(mysql_num_rows($query) > 0){
			$result = mysql_fetch_array($query);
			$dis_id = $result['dis_id'];
			return $dis_id;
		}else{
			return 0;
		}
	}

	function download_loginReport($pid,$from_date,$to_date){
		if(empty($pid)){
			
			return  json_encode(array('result'=>'FALSE','message'=>'Enter parent Id'));	
			
		}else{
			
		$select = "select um.roleId,rm.roleName,uh.pan_access,um.userId from tbl_user_master as um LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId LEFT OUTER JOIN tbl_user_hierarchy as uh on uh.userId = um.userId WHERE um.userId = $pid LIMIT 1";
		
		$query = mysql_query($select,$this->dbh);
		$result = mysql_fetch_array($query);
		$role = $result['roleName'];
		$pan_access = $result['pan_access'];
		$userId = $result['userId'];
		if($role == "admin"){

			$return_results_top = array();
			$sel_gl = "select distinct(ld.loginUser) as uid,date(ld.loginDate) as repDate , um.userName,um.roleId,um.zone,um.emp_code,rm.roleName,rm.role_seq,s.state_name,c.city_name ,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Login_Time,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout') as Logout_Time,
				(SELECT count(loginDate) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Totallog,
				timediff((SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout'),(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login')) as total_time
				FROM tbl_login_details as ld
				LEFT JOIN tbl_user_master as um ON ld.loginUser = um.userId
				LEFT JOIN tbl_role_master as rm ON rm.roleId = um.roleId
				LEFT JOIN tbl_state as s ON s.state_Id = um.state
				LEFT JOIN tbl_city as c ON c.city_id = um.city
				WHERE  date(ld.loginDate) BETWEEN '$from_date' AND '$to_date' AND um.roleId <= 4 order by rm.role_seq ASC ,um.userName ASC,ld.loginDate DESC"; 
 				$query_gl = mysql_query($sel_gl,$this->dbh);
 				$data_result = array();
 				while($result = mysql_fetch_array($query_gl)){

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
				return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
				
		}else if($role == "management" && $pan_access == 0){
		
		$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
		$sql_query = mysql_query($query,$this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
				//echo $value;
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $value group by parentUserId ";
					$sql_query = mysql_query($query,$this->dbh);
					while($result = mysql_fetch_array($sql_query)){
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
					$sql_query_dr = mysql_query($query_dr,$this->dbh);
					$no = mysql_num_rows($sql_query_dr);
					
					$data_result = array();
					$x = 0;
					while($result = mysql_fetch_array($sql_query_dr)){
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
			//header('content-type: application/json');
			return array('result'=>'TRUE','LoginDetails'=>$return_results_top);	
		}	
		}else if($role == "rm"){
			$return_results_top = array();
			$query = "SELECT uh.parentUserId,CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh left OUTER join tbl_user_master as um ON um.userId = uh.userId WHERE uh.parentUserId IN (SELECT userId  FROM `tbl_user_hierarchy` WHERE parentUserId = $pid) or uh.parentUserId = $pid";
			
			$sql_query = mysql_query($query, $this->dbh);
			$rcount = mysql_num_rows($sql_query);
			if($rcount == 1){
				while($result = mysql_fetch_array($sql_query)){
				$sid = $result['sale_person_id'];
				//print_r($sid);
				$query_dr = "select distinct(ld.loginUser) as uid,date(ld.loginDate) as repDate , um.userName,um.zone,um.emp_code,um.roleId,rm.roleName,rm.role_seq,s.state_name,c.city_name ,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Login_Time,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout') as Logout_Time,
				(SELECT count(loginDate) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Totallog,
				timediff((SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout'),(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login')) as total_time
				FROM tbl_login_details as ld
				LEFT JOIN tbl_user_master as um ON ld.loginUser = um.userId
				LEFT JOIN tbl_role_master as rm ON rm.roleId = um.roleId
				LEFT JOIN tbl_state as s ON s.state_Id = um.state
				LEFT JOIN tbl_city as c ON c.city_id = um.city
				WHERE  date(ld.loginDate) BETWEEN '$from_date' AND '$to_date' AND ld.loginUser IN ($sid) AND um.roleId <= 4 order by rm.role_seq ASC ,um.userName ASC,ld.loginDate DESC ";
						$sql_query_dr = mysql_query($query_dr, $this->dbh);
						$no = mysql_num_rows($sql_query_dr);
						
						$data_result = array();
						$x = 0;
						while($result = mysql_fetch_array($sql_query_dr)){
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
				return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
				
			}
		}else{
			return array("result"=>"FALSE","message"=>mysql_error());
		}
		}else if($role = "management" && $pan_access == 1){
			if($userId == 214){
				$query = "select CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where panmid = $pid group by panmid";
			}else{
				$query = "SELECT CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id FROM `tbl_user_hierarchy` WHERE `panid` = $pid group by panid ";
			}
				$return_results_top = array();			
				$sql_query = mysql_query($query, $this->dbh);
				$rcount = mysql_num_rows($sql_query);
				if($rcount == 1){
					while($result = mysql_fetch_array($sql_query)){
					$sid = $result['sale_person_id'];
					//print_r($sid);
					$query_dr = "select distinct(ld.loginUser) as uid,date(ld.loginDate) as repDate , um.userName,um.zone,um.emp_code,um.roleId,rm.roleName,rm.role_seq,s.state_name,c.city_name ,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Login_Time,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout') as Logout_Time,
					(SELECT count(loginDate) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Totallog,
					timediff((SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout'),(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login')) as total_time
					FROM tbl_login_details as ld
					LEFT JOIN tbl_user_master as um ON ld.loginUser = um.userId
					LEFT JOIN tbl_role_master as rm ON rm.roleId = um.roleId
					LEFT JOIN tbl_state as s ON s.state_Id = um.state
					LEFT JOIN tbl_city as c ON c.city_id = um.city
					WHERE  date(ld.loginDate) BETWEEN '$from_date' AND '$to_date' AND ld.loginUser IN ($sid) AND um.roleId <= 4 order by rm.role_seq ASC ,um.userName ASC,ld.loginDate DESC ";
							$sql_query_dr = mysql_query($query_dr, $this->dbh);
							$no = mysql_num_rows($sql_query_dr);
							
							$data_result = array();
							$x = 0;
							while($result = mysql_fetch_array($sql_query_dr)){
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
					return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
					
				}
			}else{
				return array("result"=>"FALSE","message"=>mysql_error());
			}
		}else{	


			$return_results_top = array();
		
	
			$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId";
			
			$sql_query = mysql_query($query, $this->dbh);
			$rcount = mysql_num_rows($sql_query);
			if($rcount == 1){
				while($result = mysql_fetch_array($sql_query)){
				$sid = $result['sale_person_id'];
				//print_r($sid);
				$query_dr = "select distinct(ld.loginUser) as uid,date(ld.loginDate) as repDate , um.userName,um.zone,um.emp_code,um.roleId,rm.roleName,rm.role_seq,s.state_name,c.city_name ,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Login_Time,(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout') as Logout_Time,
				(SELECT count(loginDate) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login') as Totallog,
				timediff((SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Logout'),(SELECT time(MAX(loginDate)) FROM `tbl_login_details` WHERE loginUser = ld.loginUser and date(loginDate) = date(ld.loginDate) AND process = 'Login')) as total_time
				FROM tbl_login_details as ld
				LEFT JOIN tbl_user_master as um ON ld.loginUser = um.userId
				LEFT JOIN tbl_role_master as rm ON rm.roleId = um.roleId
				LEFT JOIN tbl_state as s ON s.state_Id = um.state
				LEFT JOIN tbl_city as c ON c.city_id = um.city
				WHERE  date(ld.loginDate) BETWEEN '$from_date' AND '$to_date' AND ld.loginUser IN ($sid) AND um.roleId <= 4 order by rm.role_seq ASC ,um.userName ASC,ld.loginDate DESC ";
						$sql_query_dr = mysql_query($query_dr, $this->dbh);
						$no = mysql_num_rows($sql_query_dr);
						
						$data_result = array();
						$x = 0;
						while($result = mysql_fetch_array($sql_query_dr)){
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
				return array("result"=>"TRUE","LoginDetails"=>$return_results_top);
				
			}
		}else{
			return array("result"=>"FALSE","message"=>mysql_error());
		}
	}

		}	
	}

	function download_SaleReport($pid,$from_date,$to_date){
		if(empty($pid)){
			return  json_encode(array('result'=>'FALSE','message'=>'Enter parent Id'));	
		}else{
			
		$select = "select um.roleId,rm.roleName,uh.pan_access,um.userId from tbl_user_master as um LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId LEFT OUTER JOIN tbl_user_hierarchy as uh on uh.userId = um.userId WHERE um.userId = $pid LIMIT 1";
		
		$query = mysql_query($select,$this->dbh);
		$result = mysql_fetch_array($query);
		$role = $result['roleName'];
		$pan_access = $result['pan_access'];
		$userId = $result['userId'];
		if($role == "admin"){
			$return_results_top = array();
			$sel_gl = "SELECT date(dr.reportDate) as reportDate,s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, ( select userName from tbl_user_master WHERE userId = uh.parentUserId ) as asm_name ,( SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , ( select userName from tbl_user_master WHERE userId = rm_id) as rm_name , d.dis_id,dm.dc,dm.distributer_name , (SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive ,(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as demo_sp,(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as demo_gl,(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as sale_sp,
			(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
			(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) 
			BETWEEN '$from_date' AND '$to_date') as mtd_demo_sp,
			(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_demo_gl,
			(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_sale_sp,
			(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_sale_gl,
			
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as today_water_test_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as mtd_water_test_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl
 					FROM tbl_user_master as um 
					LEFT JOIN tbl_city as c ON c.city_id = um.city
					LEFT JOIN tbl_state as s ON s.state_Id = um.state
					LEFT JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
					LEFT JOIN tbl_distributor as d ON d.userId = um.userId
					LEFT JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
					LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
					LEFT OUTER JOIN tbl_daily_report as dr ON dr.userId = um.userId
 					WHERE um.roleId = 4 GROUP BY um.userId order by um.userName ASC"; 
 				$query_gl = mysql_query($sel_gl,$this->dbh);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['reportDate'] = $row['reportDate'];
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);

						$return_results_top[] = $data_array;
				}	
				return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));
		}else if($role == "management" && $pan_access == 0){
				$return_results_top = array();
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
		$sql_query = mysql_query($query, $this->dbh);
		while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			foreach($sid as $value){
				//echo $value;
				$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $value group by parentUserId ";
					$sql_query = mysql_query($query, $this->dbh);
					while($result = mysql_fetch_array($sql_query)){
						$sid = explode(",",$result['sale_person_id']);
						foreach($sid as $value){
								//echo $value;
								$sel_gl = "SELECT date(dr.reportDate) as reportDate,s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, ( select userName from tbl_user_master WHERE userId = uh.parentUserId ) as asm_name ,( SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , ( select userName from tbl_user_master WHERE userId = rm_id) as rm_name , d.dis_id,dm.dc,dm.distributer_name , (SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive ,(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as demo_sp,(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as demo_gl,(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as sale_sp,
			(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
			(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) 
			BETWEEN '$from_date' AND '$to_date') as mtd_demo_sp,
			(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_demo_gl,
			(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_sale_sp,
			(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_sale_gl,
			
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as today_water_test_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as mtd_water_test_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
			(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl
 					FROM tbl_user_master as um 
					LEFT JOIN tbl_city as c ON c.city_id = um.city
					LEFT JOIN tbl_state as s ON s.state_Id = um.state
					LEFT JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
					LEFT JOIN tbl_distributor as d ON d.userId = um.userId
					LEFT JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
					LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
					LEFT OUTER JOIN tbl_daily_report as dr ON dr.userId = um.userId
 					WHERE um.roleId = 4 and um.userId = $value GROUP BY um.userId order by um.userName ASC"; 
 				$query_gl = mysql_query($sel_gl, $this->dbh);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['reportDate'] = $row['reportDate'];
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);

						$return_results_top[] = $data_array;
						
		
				}
								
						}

					}
							
			}
			//header('content-type: application/json');
			return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));	
		}
		}else if($role == "rm"){
			$return_results_top = array();
		$query = "SELECT uh.parentUserId,CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh left OUTER join tbl_user_master as um ON um.userId = uh.userId WHERE uh.parentUserId IN (SELECT userId  FROM `tbl_user_hierarchy` WHERE parentUserId = $pid) or uh.parentUserId = $pid AND um.roleId = 4 ";
		
		$sql_query = mysql_query($query, $this->dbh);
		while($result = mysql_fetch_array($sql_query)){
		$sid = explode(",",$result['sale_person_id']);
		
		foreach($sid as $value){
		
			
		
		$sel_gl = "SELECT date(dr.reportDate) as reportDate,s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
				(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
				(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
				(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
				(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as today_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as mtd_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as demo_sp,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as demo_gl,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as sale_sp,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
				(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_demo_sp,
				(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between '$from_date' AND '$to_date') as mtd_demo_gl,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between '$from_date' AND '$to_date') as mtd_sale_sp,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between '$from_date' AND '$to_date' ) as mtd_sale_gl
				FROM tbl_user_master as um 
				LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
				LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
				LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
				LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
				LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
				LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
				LEFT OUTER JOIN tbl_daily_report as dr ON dr.userId = um.userId
				
				WHERE um.roleId = 4 and um.userId = $value GROUP by userId ORDER by um.userId desc"; 
 				$query_gl = mysql_query($sel_gl, $this->dbh);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['reportDate'] = $row['reportDate'];
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


						$return_results_top[] = $data_array;
				}
				
				}
				return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));	
			}
		}else if($role = "management" && $pan_access == 1){
			if($userId == 214){
				$query = "select CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh LEFT outer join tbl_user_master as um on um.userId = uh.userid where uh.panmid = $pid and um.roleId = 4 group by uh.panmid";
			}else{
				$query = "SELECT CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id FROM tbl_user_hierarchy as uh left outer join tbl_user_master as um on um.userId = uh.userId WHERE uh.panid = $pid and um.roleId = 4 group by panid ";
			}
			$return_results_top = array();
			$sql_query = mysql_query($query, $this->dbh);
			while($result = mysql_fetch_array($sql_query)){
			$sid = explode(",",$result['sale_person_id']);
			
			foreach($sid as $value){
			
			$sel_gl = "SELECT date(dr.reportDate) as reportDate,s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
					(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
					(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
					(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
					(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as today_water_test_sp,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as mtd_water_test_sp,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
					(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
					(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as demo_sp,
					(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as demo_gl,
					(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as sale_sp,
					(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
					(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_demo_sp,
					(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between '$from_date' AND '$to_date') as mtd_demo_gl,
					(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between '$from_date' AND '$to_date') as mtd_sale_sp,
					(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between '$from_date' AND '$to_date' ) as mtd_sale_gl
					FROM tbl_user_master as um 
					LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
					LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
					LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
					LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
					LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
					LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
					LEFT OUTER JOIN tbl_daily_report as dr ON dr.userId = um.userId
					
					WHERE um.roleId = 4 and um.userId = $value GROUP by userId ORDER by um.userId desc"; 
					$query_gl = mysql_query($sel_gl, $this->dbh);
					$data_array = array();
					while($row = mysql_fetch_array($query_gl)){

							//print_r($row);
							$data_array['reportDate'] = $row['reportDate'];
							$data_array['zone'] = $row['zone'];
							$data_array['emp_code'] = $row['emp_code'];
							$data_array['userId'] = $row['userId'];
							$data_array['userName'] = $row['userName'];
							$data_array['state'] = $row['state_name'];
							$data_array['city'] = $row['city_name'];
							$data_array['mobileno'] = $row['mobileno'];
							$data_array['asm_name'] = $row['asm_name'];
							$data_array['rm_name'] = $row['rm_name'];
							$data_array['distributorCode'] = $row['dc'];
							$data_array['distributer_name'] = $row['distributer_name'];
							$data_array['No_of_executive'] = $row['No_of_executive'];
							//demo
							$data_array['demo_sp'] = $row['demo_sp'];
							$data_array['demo_gl'] = $row['demo_gl'];
							$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
							//sale
							$data_array['sale_sp'] = $row['sale_sp'];
							$data_array['sale_gl'] = $row['sale_gl'];
							$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
							
							// mtd demo
							$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
							$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
							$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
							// mtd sale 
							$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
							$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
							$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
							

							$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

							$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

							$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

							//water test
							$data_array['water_test_sp'] = $row['today_water_test_sp'];
							$data_array['water_test_gl'] = $row['today_water_test_gl'];
							$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

							//sale water test
							$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
							$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
							$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
							
							// mtd water test
							$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
							$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
							$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
							
							// mtd water test sale
							$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
							$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
							$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

							$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

							$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

							$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


							$return_results_top[] = $data_array;
					}
					
					}
					return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));	
				}	
		}else{
		
		$return_results_top = array();
		$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
		$sql_query = mysql_query($query, $this->dbh);
		while($result = mysql_fetch_array($sql_query)){
		$sid = explode(",",$result['sale_person_id']);
		
		foreach($sid as $value){
		
			
		
		$sel_gl = "SELECT date(dr.reportDate) as reportDate,s.state_name,c.city_name,um.userId,um.zone,um.emp_code,um.userName,um.mobileno ,uh.parentUserId, 
				(select userName from tbl_user_master WHERE userId = uh.parentUserId) as asm_name,
				(SELECT parentUserId from tbl_user_hierarchy WHERE userId = uh.parentUserId) as rm_id , 
				(select userName from tbl_user_master WHERE userId = rm_id) as rm_name,d.dis_id,dm.dc,dm.distributer_name, 
				(SELECT COUNT(userId) FROM tbl_user_hierarchy WHERE parentUserId = um.userId ) as No_of_executive,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as today_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as today_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as today_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as today_sold_by_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId ) as mtd_water_test_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId) as mtd_water_test_gl,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=um.userId AND product_purchased = 1) as mtd_sold_by_sp,
				(SELECT count(userId) FROM `tbl_water_test` WHERE date(entryDate) BETWEEN '$from_date' AND '$to_date' and parentId=uh.parentUserId AND userId = um.userId AND product_purchased = 1) as mtd_sold_by_gl,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as demo_sp,
				(SELECT sum(noOfDemo) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as demo_gl,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=um.userId) as sale_sp,
				(SELECT sum(noOfSales) FROM `tbl_daily_report` WHERE date(reportDate) BETWEEN '$from_date' AND '$to_date' and cb=uh.parentUserId and userId = um.userId) as sale_gl , 
				(SELECT SUM(noOfDemo) FROM tbl_daily_report WHERE cb = um.userId AND date(reportDate) BETWEEN '$from_date' AND '$to_date') as mtd_demo_sp,
				(SELECT SUM(noOfDemo) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId  AND date(reportDate) between '$from_date' AND '$to_date') as mtd_demo_gl,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb = um.userId AND date(reportDate) between '$from_date' AND '$to_date') as mtd_sale_sp,
				(SELECT SUM(noOfSales) FROM tbl_daily_report  WHERE cb=uh.parentUserId and userId = um.userId AND date(reportDate) between '$from_date' AND '$to_date' ) as mtd_sale_gl
				FROM tbl_user_master as um 
				LEFT OUTER JOIN tbl_city as c ON c.city_id = um.city
				LEFT OUTER JOIN tbl_state as s ON s.state_Id = um.state
				LEFT OUTER JOIN tbl_user_hierarchy as uh ON uh.userId = um.userId
				LEFT outer JOIN tbl_distributor as d ON d.userId = um.userId
				LEFT OUTER JOIN tbl_distributor_master as dm ON dm.dis_id = d.dis_id
				LEFT OUTER JOIN tbl_water_test as wt ON wt.userId = um.userId
				LEFT OUTER JOIN tbl_daily_report as dr ON dr.userId = um.userId
				
				WHERE um.roleId = 4 and um.userId = $value GROUP by userId ORDER by um.userId desc"; 
 				$query_gl = mysql_query($sel_gl, $this->dbh);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){

 						//print_r($row);
						$data_array['reportDate'] = $row['reportDate'];
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userId'] = $row['userId'];
						$data_array['userName'] = $row['userName'];
						$data_array['state'] = $row['state_name'];
						$data_array['city'] = $row['city_name'];
						$data_array['mobileno'] = $row['mobileno'];
						$data_array['asm_name'] = $row['asm_name'];
						$data_array['rm_name'] = $row['rm_name'];
						$data_array['distributorCode'] = $row['dc'];
						$data_array['distributer_name'] = $row['distributer_name'];
						$data_array['No_of_executive'] = $row['No_of_executive'];
						//demo
						$data_array['demo_sp'] = $row['demo_sp'];
						$data_array['demo_gl'] = $row['demo_gl'];
						$data_array['No_of_demo'] = $row['demo_sp'] + $row['demo_gl'];
						//sale
						$data_array['sale_sp'] = $row['sale_sp'];
						$data_array['sale_gl'] = $row['sale_gl'];
						$data_array['No_of_sale'] = $row['sale_sp'] + $row['sale_gl'];
						
						// mtd demo
						$data_array['mtd_demo_sp'] = $row['mtd_demo_sp'];
						$data_array['mtd_demo_gl'] = $row['mtd_demo_gl'];
						$data_array['mtd_demo'] = $row['mtd_demo_sp'] + $row['mtd_demo_gl'];
						// mtd sale 
						$data_array['mtd_sale_sp'] = $row['mtd_sale_sp'];
						$data_array['mtd_sale_gl'] = $row['mtd_sale_gl'];
						$data_array['mtd_sale']    = $row['mtd_sale_sp'] + $row['mtd_sale_gl'];
						

						$data_array['mtd_sale_sp_per'] =  floor(($row['mtd_sale_sp'])/($row['mtd_demo_sp']) * 100);

						$data_array['mtd_sale_gl_per'] =  floor(($row['mtd_sale_gl'])/($row['mtd_demo_gl']) * 100);

						$data_array['mtd_sale_per'] =  floor(($row['mtd_sale_sp'] + $row['mtd_sale_gl'])/($row['mtd_demo_sp'] + $row['mtd_demo_gl']) * 100);

						//water test
						$data_array['water_test_sp'] = $row['today_water_test_sp'];
						$data_array['water_test_gl'] = $row['today_water_test_gl'];
						$data_array['No_of_water_test'] = $row['today_water_test_sp'] + $row['today_water_test_gl'];

						//sale water test
						$data_array['sold_by_sp'] = $row['today_sold_by_sp'];
						$data_array['sold_by_gl'] = $row['today_sold_by_gl'];
						$data_array['No_of_sold'] = $row['today_sold_by_sp'] + $row['today_sold_by_gl'];
						
						// mtd water test
						$data_array['mtd_water_test_sp'] = $row['mtd_water_test_sp'];
						$data_array['mtd_water_test_gl'] = $row['mtd_water_test_gl'];
						$data_array['mtd_water_test'] = $row['mtd_water_test_sp'] + $row['mtd_water_test_gl'];
						
						// mtd water test sale
						$data_array['mtd_sold_by_sp'] = $row['mtd_sold_by_sp'];
						$data_array['mtd_sold_by_gl'] = $row['mtd_sold_by_gl'];
						$data_array['mtd_sold'] = $row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'];

						$data_array['mtd_wsold_sp_per'] =  floor(($row['mtd_sold_by_sp'])/($row['mtd_water_test_sp']) * 100);

						$data_array['mtd_wsold_gl_per'] =  floor(($row['mtd_sold_by_gl'])/($row['mtd_water_test_gl']) * 100);

						$data_array['mtd_wsold_per'] =  floor(($row['mtd_sold_by_sp'] + $row['mtd_sold_by_gl'])/($row['mtd_water_test_sp'] + $row['mtd_water_test_gl']) * 100);


						$return_results_top[] = $data_array;
				}
				
				}
				return json_encode(array("result"=>"TRUE","ReportList"=>$return_results_top));	
			}	
		
		}
		}	
	}
	
	function download_WaterTestReport($pid,$from_date,$to_date){
		if(empty($pid)){
			return  json_encode(array('result'=>'FALSE','message'=>'Enter parent Id'));	
		}else{			
			$select = "select um.roleId,rm.roleName,uh.pan_access,um.userId from tbl_user_master as um LEFT JOIN tbl_role_master as rm ON um.roleId = rm.roleId LEFT OUTER JOIN tbl_user_hierarchy as uh on uh.userId = um.userId WHERE um.userId = $pid LIMIT 1";
			
			$query = mysql_query($select,$this->dbh);
			$result = mysql_fetch_array($query);
			$role = $result['roleName'];
			$pan_access = $result['pan_access'];
			$userId = $result['userId'];
			if($role == "admin"){
				$return_results_top = array();
				$sel_gl = "SELECT date(wt.entryDate) as reportDate,um.zone,um.emp_code,um.userName,upper(rm.roleName) as role,(select userName from tbl_user_master WHERE userId  = wt.parentId) as reporting,upper((select rm2.roleName from tbl_user_master as um2 left outer join tbl_role_master as rm2 ON rm2.roleId = um2.roleId WHERE um2.userId = wt.parentId )) as reportingRole, wt.customer_name,wt.customer_mobile,wt.address,s.state_name,c.city_name,wt.product_purchased,wt.electrolysis,wt.remark
				FROM tbl_water_test as wt 
				LEFT OUTER JOIN tbl_user_master as um ON um.userId = wt.userId
				LEFT OUTER JOIN tbl_role_master as rm ON rm.roleId = um.roleId
				LEFT OUTER JOIN tbl_state as s ON s.state_id = wt.state_id
				LEFT OUTER JOIN tbl_city as c ON c.city_id = wt.city_id
				WHERE date(wt.entryDate) BETWEEN '$from_date' AND '$to_date'";
				$query_gl = mysql_query($sel_gl,$this->dbh);
 				$data_array = array();
 				while($row = mysql_fetch_array($query_gl)){
					$data_array['reportDate'] = $row['reportDate'];
					$data_array['zone'] = $row['zone'];
					$data_array['emp_code'] = $row['emp_code'];
					$data_array['userName'] = $row['userName'];
					$data_array['role'] = $row['role'];
					$data_array['reporting'] = $row['reporting'];
					$data_array['reportingRole'] = $row['reportingRole'];
					$data_array['customer_name'] = $row['customer_name'];
					$data_array['customer_mobile'] = $row['customer_mobile'];
					$data_array['address'] = $row['address'];
					$data_array['state_name'] = $row['state_name'];
					$data_array['city_name'] = $row['city_name'];
					if($row['product_purchased'] == 1){
						$product_purchased = "product Sold";
					}else{
						$product_purchased = "product Not Sold";
					}
					if($row['electrolysis'] == 1){
						$electrolysis = "Positive";
					}else{
						$electrolysis = "Negative";
					}
					
					$data_array['product_purchased'] = $product_purchased;
					$data_array['electrolysis'] = $electrolysis;
					$data_array['remark'] = $row['remark'];					
					$return_results_top[] = $data_array;
					
				}
				return json_encode(array("result"=>"TRUE","TestReport"=>$return_results_top));	
		}else if($role == "management" && $pan_access == 0){
			$return_results_top = array();
			$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as 	sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId ";
		
			$sql_query = mysql_query($query, $this->dbh);
			while($result = mysql_fetch_array($sql_query)){
				$sid = explode(",",$result['sale_person_id']);
				foreach($sid as $value){
					$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $value group by parentUserId ";
					$sql_query = mysql_query($query, $this->dbh);
					while($result = mysql_fetch_array($sql_query)){
						$sid = explode(",",$result['sale_person_id']);
						foreach($sid as $value){
							$sel_gl = "SELECT date(wt.entryDate) as reportDate,um.zone,um.emp_code,um.userName,upper(rm.roleName) as role,(select userName from tbl_user_master WHERE userId  = wt.parentId) as reporting,upper((select rm2.roleName from tbl_user_master as um2 left outer join tbl_role_master as rm2 ON rm2.roleId = um2.roleId WHERE um2.userId = wt.parentId )) as reportingRole, wt.customer_name,wt.customer_mobile,wt.address,s.state_name,c.city_name,wt.product_purchased,wt.electrolysis,wt.remark
							FROM tbl_water_test as wt 
							LEFT OUTER JOIN tbl_user_master as um ON um.userId = wt.userId
							LEFT OUTER JOIN tbl_role_master as rm ON rm.roleId = um.roleId
							LEFT OUTER JOIN tbl_state as s ON s.state_id = wt.state_id
							LEFT OUTER JOIN tbl_city as c ON c.city_id = wt.city_id
							WHERE date(wt.entryDate) BETWEEN '$from_date' AND '$to_date' and wt.userId = $value or wt.parentId = $value ";
							$query_gl = mysql_query($sel_gl,$this->dbh);
							$data_array = array();
							while($row = mysql_fetch_array($query_gl)){
								$data_array['reportDate'] = $row['reportDate'];
								$data_array['zone'] = $row['zone'];
								$data_array['emp_code'] = $row['emp_code'];
								$data_array['userName'] = $row['userName'];
								$data_array['role'] = $row['role'];
								$data_array['reporting'] = $row['reporting'];
								$data_array['reportingRole'] = $row['reportingRole'];
								$data_array['customer_name'] = $row['customer_name'];
								$data_array['customer_mobile'] = $row['customer_mobile'];
								$data_array['address'] = $row['address'];
								$data_array['state_name'] = $row['state_name'];
								$data_array['city_name'] = $row['city_name'];
								if($row['product_purchased'] == 1){
									$product_purchased = "product Sold";
								}else{
									$product_purchased = "product Not Sold";
								}
								if($row['electrolysis'] == 1){
									$electrolysis = "Positive";
								}else{
									$electrolysis = "Negative";
								}
								
								$data_array['product_purchased'] = $product_purchased;
								$data_array['electrolysis'] = $electrolysis;
								$data_array['remark'] = $row['remark'];					
								$return_results_top[] = $data_array;
								
							}
						}
						
					}
				}
				return json_encode(array("result"=>"TRUE","TestReport"=>$return_results_top));	
			}
		}else if($role = "management" && $pan_access == 1){
			if($userId == 214){
				$query = "select CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy as uh LEFT outer join tbl_user_master as um on um.userId = uh.userid where uh.panmid = $pid and um.roleId = 4 group by uh.panmid";
			}else{
				$query = "SELECT CAST(GROUP_CONCAT(uh.userId SEPARATOR ', ') AS CHAR) as sale_person_id FROM tbl_user_hierarchy as uh left outer join tbl_user_master as um on um.userId = uh.userId WHERE uh.panid = $pid and um.roleId = 4 group by panid ";
			}
			$return_results_top = array();
			$sql_query = mysql_query($query, $this->dbh);
			while($result = mysql_fetch_array($sql_query)){
				$sid = explode(",",$result['sale_person_id']);
				foreach($sid as $value){
					$sel_gl = "SELECT date(wt.entryDate) as reportDate,um.zone,um.emp_code,um.userName,upper(rm.roleName) as role,(select userName from tbl_user_master WHERE userId  = wt.parentId) as reporting,upper((select rm2.roleName from tbl_user_master as um2 left outer join tbl_role_master as rm2 ON rm2.roleId = um2.roleId WHERE um2.userId = wt.parentId )) as reportingRole, wt.customer_name,wt.customer_mobile,wt.address,s.state_name,c.city_name,wt.product_purchased,wt.electrolysis,wt.remark
					FROM tbl_water_test as wt 
					LEFT OUTER JOIN tbl_user_master as um ON um.userId = wt.userId
					LEFT OUTER JOIN tbl_role_master as rm ON rm.roleId = um.roleId
					LEFT OUTER JOIN tbl_state as s ON s.state_id = wt.state_id
					LEFT OUTER JOIN tbl_city as c ON c.city_id = wt.city_id
					WHERE date(wt.entryDate) BETWEEN '$from_date' AND '$to_date' and (wt.userId = $value or wt.parentId = $value) ";
					//echo $sel_gl; die;
					$query_gl = mysql_query($sel_gl,$this->dbh);
					$data_array = array();
					
					while($row = mysql_fetch_array($query_gl)){
						$data_array['reportDate'] = $row['reportDate'];
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userName'] = $row['userName'];
						$data_array['role'] = $row['role'];
						$data_array['reporting'] = $row['reporting'];
						$data_array['reportingRole'] = $row['reportingRole'];
						$data_array['customer_name'] = $row['customer_name'];
						$data_array['customer_mobile'] = $row['customer_mobile'];
						$data_array['address'] = $row['address'];
						$data_array['state_name'] = $row['state_name'];
						$data_array['city_name'] = $row['city_name'];
						if($row['product_purchased'] == 1){
							$product_purchased = "product Sold";
						}else{
							$product_purchased = "product Not Sold";
						}
						if($row['electrolysis'] == 1){
							$electrolysis = "Positive";
						}else{
							$electrolysis = "Negative";
						}
						
						$data_array['product_purchased'] = $product_purchased;
						$data_array['electrolysis'] = $electrolysis;
						$data_array['remark'] = $row['remark'];					
						$return_results_top[] = $data_array;
						
					}
				}
				return json_encode(array("result"=>"TRUE","TestReport"=>$return_results_top));
			}
		}else{
			//echo $from_date . "-" .$to_date; die;
			$return_results_top = array();
			$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId";			
			$sql_query = mysql_query($query, $this->dbh);
			while($result = mysql_fetch_array($sql_query)){
				$sid = explode(",",$result['sale_person_id']);
				foreach($sid as $value){
					$sel_gl = "SELECT date(wt.entryDate) as reportDate,um.zone,um.emp_code,um.userName,upper(rm.roleName) as role,(select userName from tbl_user_master WHERE userId  = wt.parentId) as reporting,upper((select rm2.roleName from tbl_user_master as um2 left outer join tbl_role_master as rm2 ON rm2.roleId = um2.roleId WHERE um2.userId = wt.parentId )) as reportingRole, wt.customer_name,wt.customer_mobile,wt.address,s.state_name,c.city_name,wt.product_purchased,wt.electrolysis,wt.remark
					FROM tbl_water_test as wt 
					LEFT OUTER JOIN tbl_user_master as um ON um.userId = wt.userId
					LEFT OUTER JOIN tbl_role_master as rm ON rm.roleId = um.roleId
					LEFT OUTER JOIN tbl_state as s ON s.state_id = wt.state_id
					LEFT OUTER JOIN tbl_city as c ON c.city_id = wt.city_id
					WHERE date(wt.entryDate) BETWEEN '$from_date' AND '$to_date' and (wt.userId = $value or wt.parentId = $value) ";
					//echo $sel_gl; die;
					$query_gl = mysql_query($sel_gl,$this->dbh);
					$data_array = array();
					
					while($row = mysql_fetch_array($query_gl)){
						$data_array['reportDate'] = $row['reportDate'];
						$data_array['zone'] = $row['zone'];
						$data_array['emp_code'] = $row['emp_code'];
						$data_array['userName'] = $row['userName'];
						$data_array['role'] = $row['role'];
						$data_array['reporting'] = $row['reporting'];
						$data_array['reportingRole'] = $row['reportingRole'];
						$data_array['customer_name'] = $row['customer_name'];
						$data_array['customer_mobile'] = $row['customer_mobile'];
						$data_array['address'] = $row['address'];
						$data_array['state_name'] = $row['state_name'];
						$data_array['city_name'] = $row['city_name'];
						if($row['product_purchased'] == 1){
							$product_purchased = "product Sold";
						}else{
							$product_purchased = "product Not Sold";
						}
						if($row['electrolysis'] == 1){
							$electrolysis = "Positive";
						}else{
							$electrolysis = "Negative";
						}
						
						$data_array['product_purchased'] = $product_purchased;
						$data_array['electrolysis'] = $electrolysis;
						$data_array['remark'] = $row['remark'];					
						$return_results_top[] = $data_array;
						
					}
				}
				return json_encode(array("result"=>"TRUE","TestReport"=>$return_results_top));
			}
		}
	}
}

function all_reporting(){
	$top_array = array();
	$query = "SELECT 
uh.userId,umu.userName as emp_uname,urm.roleName as emp_role,umu.emp_code as emp_code,umu.zone as emp_zone, 
uh.parentUserId,ump.userName as rep_uname,uprm.roleName as rep_role,ump.emp_code as rep_code,ump.zone as rep_zone, 
uh.panid,umpan.userName as pan_uname,upanrm.roleName as pan_role,umpan.emp_code as pan_code,umpan.zone as pan_zone, 
uh.panmid,umpanmid.userName as panmid_uname,upanmidrm.roleName as panmid_role,umpanmid.emp_code as panmid_code,umpanmid.zone as panmid_zone, 
uh.mid,ummid.userName as mid_uname,umidrm.roleName as mid_role,ummid.emp_code as mid_code,ummid.zone as mid_zone 
FROM `tbl_user_hierarchy` as uh 
left outer Join tbl_user_master as umu on umu.userId = uh.userId
LEFT outer join tbl_role_master as urm on umu.roleId = urm.roleId
left outer Join tbl_user_master as ump on ump.userId = uh.parentUserId 
LEFT outer join tbl_role_master as uprm on ump.roleId = uprm.roleId
left outer Join tbl_user_master as umpan on umpan.userId = uh.panid
LEFT outer join tbl_role_master as upanrm on umpan.roleId = upanrm.roleId
left outer Join tbl_user_master as umpanmid on umpanmid.userId = uh.panmid
LEFT outer join tbl_role_master as upanmidrm on umpanmid.roleId = upanmidrm.roleId
left outer Join tbl_user_master as ummid on ummid.userId = uh.mid
LEFT outer join tbl_role_master as umidrm on ummid.roleId = umidrm.roleId";
	
	$mysqlquery = mysql_query($query,$this->dbh);
	$data_array = array();
	while($result = mysql_fetch_array($mysqlquery)){
		$results['userId'] = $result['userId'];
		$results['emp_name'] = $result['emp_uname'];
		$results['emp_roleId'] = $result['emp_role'];
		$results['emp_code'] = $result['emp_code'];
		$results['emp_zone'] = $result['zone'];
		$results['parentUserId'] = $result['parentUserId'];
		$results['rep_userName'] = $result['rep_uname'];
		$results['rep_roleId'] = $result['rep_role'];
		$results['rep_emp_code'] = $result['emp_code'];
		$results['rep_zone'] = $result['zone'];
		$results['panid'] = $result['panid'];
		$results['pan_userName'] = $result['pan_uname'];
		$results['pan_roleId'] = $result['pan_role'];
		$results['pan_emp_code'] = $result['emp_code'];
		$results['pan_zone'] = $result['zone'];
		$results['panmid'] = $result['panmid'];
		$results['panmid_userName'] = $result['panmid_uname'];
		$results['panmid_roleId'] = $result['panmid_role'];
		$results['panmid_emp_code'] = $result['emp_code'];
		$results['panmid_zone'] = $result['zone'];
		$results['mid'] = $result['mid'];
		$results['mid_userName'] = $result['mid_uname'];
		$results['mid_roleId'] = $result['mid_role'];
		$results['mid_emp_code'] = $result['emp_code'];
		$results['mid_zone'] = $result['zone'];
		
		$data_array[] = $results;
		
	}
	return $data_array;
	
	
}	
	
}
?>