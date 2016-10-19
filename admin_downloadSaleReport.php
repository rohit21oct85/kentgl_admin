<?php 
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();

if((isset($_REQUEST['datepicker'])) && (isset($_REQUEST['datepicker2'])) ) {
	
	$from_date =  date("Y-m-d",strtotime($_REQUEST['datepicker']));
	$to_date =  date("Y-m-d",strtotime($_REQUEST['datepicker2']));
	$pid  = $_REQUEST['pid'];
	//echo $from_date ."--".$to_date . "--".$pid;

	$result = $db->download_SaleReport($pid,$from_date,$to_date);
    //echo json_encode($result);
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
                    <!--<span style="float:right; margin-bottom:5px;margin-right: 11px;">Note :Distance more then 30 miles(mi) marked red</span>-->
    <table cellpadding="0" cellspacing="0" border="0" id="example">
	 <thead>
        <tr>
		<th>Report Date</th>
        <th>Zone</th>
        <th>State</th>
        <th>City</th>
        <th>Emp. Code</th>
		<th>GL Name</th>
		<th>Mobile</th>
        <th>ASM Name</th>
        <th>RM Name</th>
        <th>Distributor Code</th>
        <th>Distributor name</th>
        <th>No Of Executive</th>
        <th>Demo By SP</th>
        <th>Demo By GL</th>
        <th>Total Demo</th>
        <th>Sale By SP</th>
        <th>Sale By GL</th>
        <th>Total Sales</th>
        <th>Mtd Demo By SP</th>
        <th>Mtd Demo By GL</th>
        <th>Mtd Demo</th>
        <th>Mtd Sales By SP</th>
        <th>Mtd Sales By GL</th>
        <th>Mtd Sale</th>
        <th>Mtd Sale % SP</th>
        <th>Mtd Sale % GL </th>
        <th>Mtd Sale %</th>
        <th id="wtest">W.Test SP</th>
        <th id="wtest">W.Test GL</th>
        <th id="wtest">Total W.Test</th>
        
        <th id="wtest">Pro Sold SP</th>
        <th id="wtest">pro Sold GL</th>
        <th id="wtest">Total Pro Sold</th>
        
        <th id="wtest">Mtd W.Test SP</th>
        <th id="wtest">Mtd W.Test GL</th>
        <th id="wtest">Mtd W.Test</th>
        
        <th id="wtest">Mtd Pro Sold SP</th>
        <th id="wtest">Mtd Pro Sold GL</th>
        <th id="wtest">Mtd Pro Sold </th>

        <th id="wtest">Mtd Pro Sold % SP</th>
        <th id="wtest">Mtd Pro Sold % GL </th>
        <th id="wtest">Mtd pro Sold %</th>
        </tr>
    </thead>
    <tbody id="hd">

                    
	<?php
    $result = json_decode($result,true);
    foreach ($result['ReportList'] as $key => $value) {
    ?>
   <tr>
    <td><?php echo $value['reportDate'] ?></td>               
    <td><?php echo ucfirst($value['zone']) ?></td>
    <td><?php echo ucfirst($value['state'])?></td>
   <td><?php echo ucfirst($value['city']); ?></td>
    <td><?php echo strtoupper($value['emp_code']); ?></td>
    <td><?php echo ucfirst($value['userName']); ?></td>
    <td><?php echo ucfirst($value['mobileno'])?></td>
    <td><?php echo ucfirst($value['asm_name'])?></td>
    <td><?php echo ucfirst($value['rm_name'])?></td>
    <td><?php echo ucfirst($value['distributorCode'])?></td>
    <td><?php echo ucfirst($value['distributer_name'])?></td>
    <td><?php echo $value['No_of_executive']?></td>
    <td><?php echo $value['demo_sp']?></td>
      <td><?php echo $value['demo_gl']?></td>
    <td><?php echo $value['No_of_demo']?></td>
    <td><?php echo $value['sale_sp']?></td>
    <td><?php echo $value['sale_gl']?></td>
    <td><?php echo $value['No_of_sale']?></td>
    <td><?php echo $value['mtd_demo_sp']?></td>
    <td><?php echo $value['mtd_demo_gl']?></td>
    <td><?php echo $value['mtd_demo']?></td>
    <td><?php echo $value['mtd_sale_sp']?></td>
    <td><?php echo $value['mtd_sale_gl']?></td>
    <td><?php echo $value['mtd_sale']?></td>
    <td><?php echo $value['mtd_sale_sp_per']."%"?></td>
    <td><?php echo $value['mtd_sale_gl_per']."%"?></td>
    <td><?php echo $value['mtd_sale_per']."%"?></td>

    <td><?php echo $value['water_test_sp']?></td>
    <td><?php echo $value['water_test_gl']?></td>
    <td><?php echo $value['No_of_water_test']?></td>
    
    <td><?php echo $value['sold_by_sp']?></td>
    <td><?php echo $value['sold_by_gl']?></td>
    <td><?php echo $value['No_of_sold']?></td>
    
    <td><?php echo $value['mtd_water_test_sp']?></td>
    <td><?php echo $value['mtd_water_test_gl']?></td>
    <td><?php echo $value['mtd_water_test']?></td>
    
    <td><?php echo $value['mtd_sold_by_sp']?></td>
    <td><?php echo $value['mtd_sold_by_gl']?></td>
    <td><?php echo $value['mtd_sold']?> </td>

    <td><?php echo $value['mtd_wsold_sp_per']."%"?></td>
    <td><?php echo $value['mtd_wsold_gl_per']."%"?></td>
    <td><?php echo $value['mtd_wsold_per']."%"?></td>
    </tr>
    <?php
    }
    ?>
        </tbody>
                        </table>
    <?php                
}
?>