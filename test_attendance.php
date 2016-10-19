<?php 
$link = mysql_connect('localhost', 'techteam', 'Tech@321');
$response = array();
if (!$link) {
  
die('Not connected server:' . mysql_error());}
$db_selected = mysql_select_db('FFMS', $link);
if (!$db_selected) {
  die('database is not connected :' . mysql_error());
}

	$query = "select distinct(h.fnum_parent_user_id) as parent_id,u.fstr_employee_name,u.fstr_email,
		`GetFamilyTree`(u.fnum_userId) as sale_person_id
		from ffms_user_hierarchy h,ffms_users u  where h.fnum_parent_user_id = u.fnum_userId and h.fnum_parent_user_id = 5 and h.fnum_parent_user_id <> 'NULL' ";
	
	$result 	= mysql_query($query);
	
	$yesterday = date("Y-m-d", strtotime("yesterday"));
	while($row = mysql_fetch_array($result))
	{
		$mail_send_id = $row['fstr_email'];
		echo $sales_person_id = $row['sale_person_id'];
		$file_name = $row['fstr_employee_name'];
	}
	
	
	
?>