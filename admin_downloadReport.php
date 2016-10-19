<?php 
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();

if((isset($_REQUEST['datepicker'])) && (isset($_REQUEST['datepicker2'])) ) {
	
	$from_date =  date("Y-m-d",strtotime($_REQUEST['datepicker']));
	$to_date =  date("Y-m-d",strtotime($_REQUEST['datepicker2']));
	$pid  = $_REQUEST['pid'];
	//echo $from_date ."--".$to_date . "--".$pid;

	$result = $db->download_LoginReport($pid,$from_date,$to_date);

	
	?>
	<style>
		#example{
		width:100%;margin:10px 0px;
		}
		#example thead tr{
			height:30px;background:#ffffff;
			border-top: 1px solid rgba(0,0,0,0.3);
			border-bottom: 1px solid rgba(0,0,0,0.3);
			font-size: 14px;
		}
		#example thead tr th{
			padding-left: 10px;
			padding-right:10px;
			min-width:160px;
			font-size:13px;
			font-weight:bold;
			text-align: left;
			border-left:1px solid rgba(0,0,0,0.3);
			border-right:1px solid rgba(0,0,0,0.3);
		}
		#example tbody tr{
			height: 30px;
			margin: 5px 0px;
			padding-left: 10px;
			background: #ffffff;
			border-bottom: 1px solid rgba(0,0,0,0.3);
		}
		#example tbody tr td{
			padding-left: 10px;
			padding-right:10px;
			min-width:200px;
			font-size:13px;
			font-weight:normal;
			text-align: left;
			border-left:1px solid rgba(0,0,0,0.3);
			border-right:1px solid rgba(0,0,0,0.3);
		}
	</style>
	  <button  onclick="fnExcelReport();" style="float:right; margin-bottom:5px; position:absolute; top:28px; right:25px;"> EXPORT TO EXCEL </button>
                    <!--<span style="float:right; margin-bottom:5px;margin-right: 11px;">Note :Distance more then 30 miles(mi) marked red</span>-->
    <table cellpadding="0" cellspacing="0" border="0"id="example">
	 <thead>
        <tr style="">
              	<th>Login Date</th>
              	<th>Emp. Zone</th>
              	<th>Emp. Code</th>
                <th>Emp. Name</th>
                <th>Emp. Role</th>
                <th>State</th>
                <th>City</th>
                <th>Login Count</th>
                <th>Last Login</th>
                <th>Last Logout</th>
				
               
        </tr>
    </thead>
    <tbody id="hd">

                    
	<?php
    foreach ($result['LoginDetails'] as $key => $value) {
    ?>
    <tr>
 	<td><?php echo $value['repDate']?></td>
 	<td><?php echo $value['zone']?></td>
 	<td><?php echo $value['emp_code']?></td>
    <td><?php echo $value['userName']?></td>
    <td><?php echo strtoupper($value['roleName']); ?></td>
    <td><?php echo strtoupper($value['state_name']); ?></td>
    <td><?php echo strtoupper($value['city_name']); ?></td>
    <td><?php echo $value['Total_login']?></td>
    <td><?php echo $value['Login']?></td>
    <td><?php echo $value['Logout']?></td>
    
    </tr>
    <?php
    }
    ?>
        </tbody>
                        </table>
    <?php                
}
?>