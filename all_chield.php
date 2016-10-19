<?php  
$conn = mysqli_connect("localhost","techteam","Tech@321","kentgl");
$pid = 469;
$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId = $pid group by parentUserId limit 1";
$sql_query = mysqli_query($conn,$query);
$result = mysqli_fetch_array($sql_query);
$sid = $result['sale_person_id'];


$query = "select parentUserId,CAST(GROUP_CONCAT(userId SEPARATOR ', ') AS CHAR) as sale_person_id from tbl_user_hierarchy where parentUserId != 0 and parentUserId IN ($sid) group by parentUserId";
$sql_query = mysqli_query($conn,$query);
while($result = mysqli_fetch_array($sql_query)){
	$pid = $result['sale_person_id'];	
	echo $pid;
}


?>
