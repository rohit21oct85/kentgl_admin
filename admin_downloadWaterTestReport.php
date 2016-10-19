<?php 
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();

if((isset($_REQUEST['datepicker'])) && (isset($_REQUEST['datepicker2'])) ) {
	
	$from_date =  date("Y-m-d",strtotime($_REQUEST['datepicker']));
	$to_date =  date("Y-m-d",strtotime($_REQUEST['datepicker2']));
	$pid  = $_REQUEST['pid'];
	//echo $from_date ."--".$to_date . "--".$pid;die;
	$result = $db->download_WaterTestReport($pid,$from_date,$to_date);
	//echo $result;die;
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
			min-width:150px;
			font-size:13px;
			font-weight:bold;
			text-align: left;
			border-left:1px solid rgba(0,0,0,0.3);
			border-right:1px solid rgba(0,0,0,0.3);
		}
		#example thead tr th.address{
			padding-left: 10px;
			padding-right:10px;
			min-width:300px;
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
			min-width:150px;
			font-size:13px;
			font-weight:normal;
			text-align: left;
			border-left:1px solid rgba(0,0,0,0.3);
			border-right:1px solid rgba(0,0,0,0.3);
		}
	</style>
	  <button  onclick="fnExcelReport();" style="float:right; position:absolute; top:28px; right:25px;"> EXPORT TO EXCEL </button>
                   
    <table cellpadding="0" cellspacing="0" border="0" id="example">
	 <thead>
        <tr>
		<th>Reporting Date</th>
        <th>Zone</th>
        <th>Emp. Code</th>
		<th>Emp. Name</th>
        <th>Emp. Role</th>
		<th>Reporting Name</th>
        <th>Reporting Role</th>
        <th>Customer name</th>
        <th>Customer Mobile</th>
        <th class="address">Customer Address</th>
        <th>Customer State</th>
        <th>Customer City</th>
        <th>Product Status</th>
        <th>Electrolysis </th>
        <th class="address">remark</th>
        </tr>
    </thead>
    <tbody id="hd">

                    
	<?php
    $result = json_decode($result,true);
    foreach ($result['TestReport'] as $key => $value) {
    ?>
    <tr>
        <td><?php echo $value['reportDate']; ?></td>
        <td><?php echo $value['zone']; ?></td>
        <td><?php echo $value['emp_code']; ?></td>
        <td><?php echo $value['userName']; ?></td>
        <td><?php echo $value['role']; ?></td>
        <td><?php echo $value['reporting']; ?></td>
        <td><?php echo $value['reportingRole']; ?></td>
        <td><?php echo $value['customer_name']; ?></td>
        <td><?php echo $value['customer_mobile']; ?></td>
        <td><?php echo $value['address']; ?></td>
        <td><?php echo $value['state_name']; ?></td>
        <td><?php echo $value['city_name']; ?></td>
        <td><?php echo $value['product_purchased']; ?></td>
        <td><?php echo $value['electrolysis']; ?></td>
        <td><?php echo $value['remark']; ?></td>

    </tr>
    <?php
    }
    ?>
        </tbody>
                        </table>
    <?php                
}
?>