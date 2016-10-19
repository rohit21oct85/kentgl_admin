<?php

error_reporting(0);
include('connection.php');
function authenticate_admin($user_name,$pass)
{
	
	
							
	
							//$sel = "select * FROM ffms_users  WHERE ftsr_user_name='$user_name' AND fstr_password='$pass' AND fsnum_eployee_role IN('1','2','3') ";
							$sel = "select fu.*,fr.fstr_role_name from ffms_users as fu, ffms_role as fr where ftsr_user_name='$user_name' AND fu.fsnum_eployee_role=fr.fnum_role_id";
							$result=mysql_query($sel);
							$res=mysql_fetch_array($result);
							$num=mysql_num_rows($result);
							
							if($num>0)
							{
								
								$adServer = "14.141.214.226";
								$ldap = ldap_connect($adServer);
								$username = $user_name;
								$password = $pass;
								$ldaprdn =  $username;
								ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
								ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
								$bind = @ldap_bind($ldap, $ldaprdn, $password);
								if ($bind) 
								{
									//echo $user_name.'--'.$pass; exit;
									if($Remember_Me==1)
									{
									setcookie("username",trim(stripslashes($_POST['username'])),time()+60*60*24*30);
									setcookie("password",trim(stripslashes($_POST['password'])),time()+60*60*24*30);
									}
									else
									{
									setcookie("username","");
									setcookie("password","");
									}

									$up_id =$res['fnum_userId'];

									echo $_SESSION["adminx"]   = $res['ftsr_user_name'];
									echo $_SESSION["ID"]       = $res['fnum_userId'];
									echo $_SESSION["username"] = $res['ftsr_user_name'];
									echo $_SESSION["rolename"] = $res['fstr_role_name'];

									$user_h = "SELECT GetFamilyTree('".$up_id."') uid";
									$user_hres=mysql_query($user_h);
									$user_res=mysql_fetch_array($user_hres);
									echo $_SESSION['p_id'] =$user_res['uid'];

									header("location:add_user.php");
									exit();
								}
								else
								{
									
									//echo $user_name.'-ss-'.$pass; exit;
								return "error";

								}
							}
							else
							{
							return "error";

							}

 mysql_close($mysql);
}



function add_users($aduser)
{//print_r($aduser);exit;

	$name		 = $aduser['name'];
	$userename	 = $aduser['username'];
	$email		 = $aduser['email'];
	$mobile		 = $aduser['mobile'];
	$emp_code	 = $aduser['emp_code'];
	$role		 = $aduser['role'];
	$report_to	 = $aduser['report_to'];
	$state		 = $aduser['state'];
	$state1		 = $aduser['state1'];
	$city		 = $aduser['city'];
	$city1		 = $aduser['city1'];
	$status		 = $aduser['status'];
	
	
	if($role=='1')
	{
		$eployee_designation= 'Management';
	}
	if($role=='2')
	{
		$eployee_designation= 'Regional Head-Sales';
	}
	if($role=='3')
	{
		$eployee_designation= 'Area Sales Manager';
	}
	if($role=='4')
	{
		$eployee_designation= 'Consumer Executive Support';
	}
	if($role=='5')
	{
		$eployee_designation= 'Sales Manager';
	}
	if($state=="others")
	{
		$state = $state1;
	}
	if($city=='others')
	{
		$city =$city1;
	}
	 $query = "INSERT INTO ffms_users set fstr_employee_name='$name',ftsr_user_name='$email',fstr_email='$email',fstr_mobile='$mobile',fstr_employee_code='$emp_code',fsnum_eployee_role='$role',ffms_state='$state',ffms_city='$city',fstr_eployee_designation='$eployee_designation',fnum_isactive='$status',fdt_entry_date= '".date('Y-m-d H:i:s')."'";
	$insert = mysql_query($query);
	$userID = mysql_insert_id();
	
	$query1 = "INSERT INTO ffms_user_hierarchy set fnum_user_id='$userID',fnum_parent_user_id='$report_to',fnum_isactive='$status',fdt_entry_date='".date('Y-m-d H:i:s')."'";
	$insert1 = mysql_query($query1);
	if($insert1)
	{
		echo '<script language="javascript">alert("Record added successfully ")</script>';
		
	}
	else
	{
		echo '<script language="javascript">alert("Record not added  ")</script>';
	}
	
	mysql_close($mysql);

}

function update_user($data)
{
	if($data['u_id']!='')
	{
	$u_id        = $data['u_id'];	
	$name		 = $data['name'];
	$userename	 = $data['username'];
	$email		 = $data['email'];
	$mobile		 = $data['mobile'];
	$emp_code	 = $data['emp_code'];
	$role		 = $data['role'];
	$report_to	 = $data['report_to'];
	$state		 = $data['state'];
	$state1		 = $data['state1'];
	$city		 = $data['city'];
	$city1		 = $data['city1'];
	$status		 = $data['status'];
	if($role=='1')
	{
		$eployee_designation= 'Management';
	}
	if($role=='2')
	{
		$eployee_designation= 'Regional Head-Sales';
	}
	if($role=='3')
	{
		$eployee_designation= 'Area Sales Manager';
	}
	if($role=='4')
	{
		$eployee_designation= 'Consumer Executive Support';
	}
	if($role=='5')
	{
		$eployee_designation= 'Sales Manager';
	}
	
	if($state=="others")
	{
		$state = $state1;
	}
	if($city=='others')
	{
		$city =$city1;
	}
	 $query_udt ="UPDATE ffms_users as fu,ffms_user_hierarchy as uh SET fu.fstr_employee_name='$name',fu.ftsr_user_name='$email',fu.fstr_email='$email',fu.fstr_mobile='$mobile',fu.fstr_employee_code='$emp_code',fu.fsnum_eployee_role='$role',fu.ffms_state='$state',fu.ffms_city='$city',fstr_eployee_designation='$eployee_designation',fu.fnum_isactive='$status',uh.fnum_parent_user_id='$report_to' where fu.fnum_userId='$u_id' and fu.fnum_userId=uh.fnum_user_id";
	$update =mysql_query($query_udt);
	if($update)
	{
	echo '<script language="javascript">alert("Record updated successfully ")</script>';
	echo '<script language="javascript">window.location.href = "add_user.php"</script>';

	}
	else
	{
		echo '<script language="javascript">alert("Record not updated  ")</script>';
	}
	}

}
function edit_usr($data)
{
	
				$query3 = "select *,(select fnum_userId from ffms_users where fnum_userId=h.fnum_parent_user_id) as parent_id from ffms_users as u,ffms_user_hierarchy as h where  u.fnum_userId='".$_GET['edit_id']."' and u.fnum_userId=h.fnum_user_id";
				$result3 = mysql_query($query3);
				$reslt =   mysql_fetch_array($result3);
				return $reslt;
}
function delete_user($u_id,$status)
{
	
			 $query4 ="UPDATE ffms_users SET fnum_isactive='$status' WHERE fnum_userId='$u_id'";
		     $result4 = mysql_query($query4);
				if($result4)
				{
					echo '<script language="javascript">alert("Record updated sucessfully  ")</script>';
					echo '<script language="javascript">window.location.href = "add_user.php"</script>';
				}
				else
				{
				      echo '<script language="javascript">alert("Record Not updated")</script>';	
				}
}
function user_list()
{				if($_SESSION['p_id']=='')
					{
						
					$p_ids 		= $_SESSION["ID"];	
					}
					else
					{
						$p_ids 		= $_SESSION['p_id'].','.$_SESSION["ID"];
					}
					$admin      = $_SESSION['rolename'];				$all_users = "SELECT * FROM ffms_users";
				
				if($admin!='admin')
				{
					$all_users .= " WHERE fnum_userId IN($p_ids)";
				}
				//echo $all_users;exit;
				$result2 = mysql_query($all_users);
				return $result2;
}
function user_addrs()
{

				$all_users = "SELECT DISTINCT ffms_state FROM ffms_users";
				$result2 = mysql_query($all_users);
				return $result2;
}
function user_state()
{

				$all_users = "SELECT DISTINCT ffms_state FROM ffms_users";
				$result2 = mysql_query($all_users);
				return $result2;
}
function user_city()
{

				$all_users = "SELECT DISTINCT ffms_city FROM ffms_users";
				$result2 = mysql_query($all_users);
				return $result2;
}
function restuo_list()
{

				$all_restau = "SELECT DISTINCT fstr_restaurent_name FROM ffms_restaurent";
				$result2 = mysql_query($all_restau);
				return $result2;
}

function report_to()
{
				$query = "SELECT fnum_userId,fstr_employee_name  FROM ffms_users WHERE fnum_userId !='' AND fnum_isactive='1'";
				$resultr = mysql_query($query);
				return $resultr;
}
function add_role($adrole)
{

	$role_name		 = $adrole['role_name'];
	$role_descri	 = $adrole['role_descri'];
	$status			 = $adrole['status'];
	
	$query = "INSERT INTO ffms_role set fstr_role_name='$role_name',fstr_role_description='$role_descri',fnum_isactive='$status',fdt_entry_date= '".date('Y-m-d H:i:s')."'";
	$insert = mysql_query($query);
		
	if($insert)
	{
		$msg = "Role added sucessfully";
	}
	else
	{
		$msg = "Role not added";
	}
	
	mysql_close($mysql);

}
function edit_role($data)
{
	
				$query = "SELECT * FROM ffms_role WHERE 	fnum_role_id='".$_GET['edit_id']."'";
				$result = mysql_query($query);
				$reslt =   mysql_fetch_array($result);
				return $reslt;
}
function update_roles($data)
{
				if($data['edit_id']!='')
				{
					$role_id     = $data['edit_id'];	
					$role_name	 = $data['role_name'];
					$role_descri	 = $data['role_descri'];
					$status		 = $data['status'];
					
					$query_udt ="UPDATE ffms_role SET fstr_role_name='$role_name',fstr_role_description='$role_descri', WHERE fnum_role_id='$role_id' ";
					$update =mysql_query($query_udt);
					if($update)
					{
					$msg = "sucess";	
					}
				}

}

function role_list()
{
				
				$all_role = "SELECT * FROM ffms_role";
				$result = mysql_query($all_role);
				return $result;
}

function activity_list()
{					if($_SESSION['p_id']=='')
					{
						
					$p_ids 		= $_SESSION["ID"];	
					}
					else
					{
						$p_ids 		= $_SESSION['p_id'].','.$_SESSION["ID"];
					}
					
					$admin = $_SESSION['rolename'];
					
				 /*$actvity_repo="select u.*,us.fstr_employee_code,us.fstr_employee_name,us.ffms_city,us.ffms_state,ur.fstr_role_name,TIME_FORMAT(timediff(concat(u.fstr_checkout_date,' ',u.fstr_checkout_time),concat(u.fstr_checkin_date,' ',u.fstr_checkin_time)),'%H:%i') as hour, DISTANCE_BETWEEN(r.fstr_restaurant_latitude, r.fstr_restaurant_longitude, u.fstr_checkin_latitude, u.fstr_checkin_logitude) as distance_from_input ,r.fstr_restaurent_name,r.fstr_restaurant_address from ffms_users_activity u ,ffms_restaurent r,ffms_users us, ffms_role as ur where u.fnum_restaurent_id=r.fnum_restaurent_id and u.fnum_userId=us.fnum_userId and us.fsnum_eployee_role=ur.fnum_role_id";
				if($admin!='admin')
				{
					$actvity_repo .= " AND  us.fnum_userId IN($p_ids)";
				}
				$actvity_repo .= " union select u.*,us.fstr_employee_code,us.fstr_employee_name,us.ffms_city,us.ffms_state,ur.fstr_role_name,TIME_FORMAT(timediff(concat(u.fstr_checkout_date,' ',u.fstr_checkout_time),concat(u.fstr_checkin_date,' ',u.fstr_checkin_time)),'%H:%i') as hour,DISTANCE_BETWEEN('28.607563', '77.368332', u.fstr_checkin_latitude, u.fstr_checkin_logitude) as distance_from_input ,case when u.fnum_restaurent_id='1x1' then 'Office Visit' else 'Review Meeting' end name,'A-2, Sector-59, Noida,Uttar Pradesh' as address from ffms_users_activity u,ffms_users us,ffms_role as ur where u.fnum_restaurent_id in('1x1','2x2') and u.fnum_userId=us.fnum_userId and us.fsnum_eployee_role=ur.fnum_role_id ";
				
				if($admin!='admin')
				{
					$actvity_repo .= " AND  us.fnum_userId IN($p_ids)";
				}*/
				
				$actvity_repo="select u.*,us.fstr_employee_code,us.fstr_employee_name,us.ffms_city,us.ffms_state,ur.fstr_role_name,TIME_FORMAT(timediff(concat(u.fstr_checkout_date,' ',STR_TO_DATE (u.fstr_checkout_time , '%h:%i%p')),concat(u.fstr_checkin_date,' ',STR_TO_DATE (u.fstr_checkin_time , '%h:%i%p'))),'%H:%i') as hour, DISTANCE_BETWEEN(r.fstr_restaurant_latitude, r.fstr_restaurant_longitude, u.fstr_checkin_latitude, u.fstr_checkin_logitude) as distance_from_input ,r.fstr_restaurent_name,r.fstr_restaurant_address from ffms_users_activity u ,ffms_restaurent r,ffms_users us, ffms_role as ur where u.fnum_restaurent_id=r.fnum_restaurent_id and u.fnum_userId=us.fnum_userId and us.fsnum_eployee_role=ur.fnum_role_id";
				if($admin!='admin')
				{
					$actvity_repo .= " AND  us.fnum_userId IN($p_ids)";
				}
				$actvity_repo .= " union select u.*,us.fstr_employee_code,us.fstr_employee_name,us.ffms_city,us.ffms_state,ur.fstr_role_name,TIME_FORMAT(timediff(concat(u.fstr_checkout_date,' ',STR_TO_DATE (u.fstr_checkout_time , '%h:%i%p')),concat(u.fstr_checkin_date,' ',STR_TO_DATE (u.fstr_checkin_time , '%h:%i%p'))),'%H:%i') as hour,DISTANCE_BETWEEN('28.607563', '77.368332', u.fstr_checkin_latitude, u.fstr_checkin_logitude) as distance_from_input ,case when u.fnum_restaurent_id='1x1' then 'Office Visit' else 'Review Meeting' end name,'A-2, Sector-59, Noida,Uttar Pradesh' as address from ffms_users_activity u,ffms_users us,ffms_role as ur where u.fnum_restaurent_id in('1x1','2x2') and u.fnum_userId=us.fnum_userId and us.fsnum_eployee_role=ur.fnum_role_id ";
				
				if($admin!='admin')
				{
					$actvity_repo .= " AND  us.fnum_userId IN($p_ids)";
				}
				//echo $actvity_repo;exit;
				$result = mysql_query($actvity_repo);
				return $result;
}


function attendence_list()
{
				if($_SESSION['p_id']=='')
					{
						
					$p_ids 		= $_SESSION["ID"];	
					}
					else
					{
						$p_ids 		= $_SESSION['p_id'].','.$_SESSION["ID"];
					}
				$admin = $_SESSION['rolename'];
				 //$attend_repo = "select datas.attdate,SEC_TO_TIME( SUM( TIME_TO_SEC((datas.hour)))) hour,datas.employeecode,datas.employeename,datas.city,datas.state,datas.role from (SELECT date(ua.fdt_entry_date) attdate, TIME_FORMAT(timediff(concat(ua.fstr_checkout_date,' ',ua.fstr_checkout_time),concat(ua.fstr_checkin_date,' ',ua.fstr_checkin_time)),'%H:%i') as hour,u.fstr_employee_code employeecode,u.fstr_employee_name employeename,u.ffms_city city,u.ffms_state state,r.fstr_role_name role FROM ffms_users_activity AS ua LEFT JOIN ffms_restaurent As fr ON (ua.fnum_restaurent_id = fr.fnum_restaurent_id) LEFT JOIN ffms_users As u ON (ua.fnum_userId = u.fnum_userId) LEFT JOIN ffms_role As r ON (u.fsnum_eployee_role = r.fnum_role_id) WHERE ua.fnum_isactive ='1' ";
				 
				 
				 $attend_repo = "select datas.attdate,SEC_TO_TIME( SUM( TIME_TO_SEC((datas.hour)))) hour,datas.employeecode,datas.employeename,datas.city,datas.state,datas.role from (SELECT date(ua.fdt_entry_date) attdate,TIME_FORMAT(timediff(concat(ua.fstr_checkout_date,' ',STR_TO_DATE (ua.fstr_checkout_time , '%h:%i%p')),concat(ua.fstr_checkin_date,' ',STR_TO_DATE (ua.fstr_checkin_time , '%h:%i%p'))),'%H:%i') as hour,u.fstr_employee_code employeecode,u.fstr_employee_name employeename,u.ffms_city city,u.ffms_state state,r.fstr_role_name role FROM ffms_users_activity AS ua LEFT JOIN ffms_restaurent As fr ON (ua.fnum_restaurent_id = fr.fnum_restaurent_id) LEFT JOIN ffms_users As u ON (ua.fnum_userId = u.fnum_userId) LEFT JOIN ffms_role As r ON (u.fsnum_eployee_role = r.fnum_role_id) WHERE ua.fnum_isactive ='1' ";
				 
				
				 

				if($admin!='admin')
				{
					$attend_repo .= " AND u.fnum_userId IN($p_ids)";
				}
				
				$attend_repo .= " group BY ua.fdt_entry_date) datas group by datas.attdate,datas.employeecode,datas.employeename,datas.role";
				
				$actvity_repo .= " order by fdt_entry_date desc";

				// echo $attend_repo;exit;
				$result = mysql_query($attend_repo);
				return $result;
}

function login_report()
{				if($_SESSION['p_id']=='')
					{
						
					$p_ids 		= $_SESSION["ID"];	
					}
					else
					{
						$p_ids 		= $_SESSION['p_id'].','.$_SESSION["ID"];
					}
				$admin = $_SESSION['rolename'];
				 		 
				 
				 $login_reprt ="SELECT lg.*,u.fstr_employee_name,u.fstr_employee_code,u.ffms_city,u.ffms_state FROM ffms_users_login_log As lg LEFT JOIN ffms_users AS u on(lg.fsnum_login_user=u.fnum_userId) WHERE u.fnum_isactive ='1'";
				 if($admin!='admin')
				{
					$login_reprt .= " AND u.fnum_userId IN($p_ids)";
				}
				   $login_reprt .= " order by lg.fdt_entry_date";
				  //echo $login_reprt;exit;
				 $resulrt = mysql_query($login_reprt);
				 return $resulrt;
				
}


function beat_report()
{				if($_SESSION['p_id']=='')
					{
						
					$p_ids 		= $_SESSION["ID"];	
					}
					else
					{
						$p_ids 		= $_SESSION['p_id'].','.$_SESSION["ID"];
					}
				$admin      = $_SESSION['rolename'];
				
				$beat_reop="select b.fnum_restaurentIds,b.fnum_userId,CASE when b.fnum_restaurentIds='1x1' then concat('Office Visit','@A-2, Sector-59, Noida','','@','','@','','@','','@','','@','') when b.fnum_restaurentIds='2x2' then concat('review meeting','@A-2, Sector-59, Noida','','@','','@','','@','','@','','@','') else(select concat(fstr_restaurent_name,'@',fstr_restaurant_address,'@',fstr_restaurant_state,'@',fstr_restaurant_city,'@',fstr_restaurant_latitude,'@',fstr_restaurant_longitude) from ffms_restaurent where fnum_restaurent_id=b.fnum_restaurentIds) end reastaurent,b.fdt_plan_date,u.fstr_employee_code,u.fstr_employee_name,u.ffms_city,u.ffms_state from ffms_beat b,ffms_users u where date(b.fdt_plan_date)>=date(CURRENT_DATE()) and b.fnum_restaurentIds not in (select a.fnum_restaurent_id from ffms_users_activity a where date(a.fstr_checkin_date)=(b.fdt_plan_date) and a.fnum_userId=b.fnum_userId) and b.fnum_userId=u.fnum_userId";
				
				
				
				 if($admin!='admin')
				{
					$beat_reop .= " AND u.fnum_userId in($p_ids)";
				}
				    $beat_reop .= " order by b.fdt_plan_date";
				//echo $beat_reop;exit;
				$resulrt = mysql_query($beat_reop);
				 return $resulrt;

}


function comment_check()
{
				$commentcheck ="SELECT * from ffms_users_activity_comments";
				$resulrt = mysql_query($commentcheck);
				$acomentr = mysql_fetch_array($resulrt);
				 $cnt= $acomentr['fnum_activityId'];
				
				return $resulrt;
}
?>






