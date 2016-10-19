<?php

include('header.php');
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');
if ($_SESSION["adminx"] != "")
{
	
    ?>
    <style>
        #sale{
            background:rgba(214,233,198,0.3);
            color:black;
            width: 150px;
        }
        #wtest{
            background:rgba(187,232,236,0.3);
            color:black;
            width: 180px;
        }
    </style>
<!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
    <?php include('sidebar.php'); ?>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">

                    <?php 
                      
                       
                    ?>
                <!-- BEGIN PAGE HEADER-->
                <h3 class="page-title">
                    Daily Sales Report
                </h3>
                
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
                <div class="row">
				
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->


                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-globe"></i> Sales Report On <?php echo date("Y-m-d"); ?>
                                </div>
                               
                                <div class="tools hide">
                                    <a href="javascript:;" class="reload">
                                    </a>
                                    <a href="javascript:;" class="remove">
                                    </a>
                                </div>
                            </div>
                            <style>
                              	.dataTables_info{display:none;}
								.table.dataTable thead .sorting_asc{}
								.table.dataTable thead .sorting{}
								th.sorting {min-width: 100px!important;}
								#sample_2_paginate{display:none!important}
								#sample_2_filter{display:none!important}
								#sample_2_length{display:none!important}
								#yadcf_filter__example_1_chosen {width: 200px!important;}  
								
                            </style>

                            <div class="portlet-body">
                              
					<button  onclick="fnExcelReport();" style="float:right; margin-bottom:5px;"> EXPORT TO EXCEL </button>
					<!--<span style="float:right; margin-bottom:5px;margin-right: 11px;">Note :Distance more then 30 miles(mi) marked red</span>-->
					  <table cellpadding="0" cellspacing="0" border="0" class="table-striped table-bordered table-hover display" id="example">
						<thead>
                            <tr>
                                    <th class="hide">Report Date</th>
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
                                    <th id="sale">Demo SP</th>
                                    <th id="sale">Demo GL</th>
                                    <th id="sale">Total Demo</th>
                                    <th id="sale">Sale SP</th>
									<th id="sale">Sale GL</th>
                                    <th id="sale">Total Sales</th>
                                    <th id="sale">Mtd Demo SP</th>
                                    <th id="sale">Mtd Demo GL</th>
                                    <th id="sale">Mtd Demo</th>
                                    <th id="sale">Mtd Sales SP</th>
                                    <th id="sale">Mtd Sales GL</th>
                                    <th id="sale">Mtd Sale</th>
                                    <th id="sale">Mtd Sale % SP</th>
                                    <th id="sale">Mtd Sale % GL </th>
                                    <th id="sale">Mtd Sale %</th>
                                    
                                    <th id="wtest">W.Test SP</th>
                                    <th id="wtest">W.Test GL</th>
                                    <th id="wtest">Total W.Test</th>
                                    
                                    <th id="wtest">Pro Sold SP</th>
                                    <th id="wtest">Pro Sold GL</th>
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
                       
                        $result = $db->getDailySaleReport($_SESSION['userId']);
                        $result = json_decode($result,true);
                        //print_r($result);
                        foreach ($result['ReportList'] as $key => $value) {
                        ?>
                        <tr>
                        <td class="hide"><?php
                        $date =date("Y-m-d");
                         echo date("Y-m-d",strtotime($date.'-1 day')); ?></td>
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
						</div>                              
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->

                    </div>
                </div>
                <!-- END PAGE CONTENT-->
            </div>
        </div>
        <!-- END CONTENT -->

    </div>
    <!-- END CONTAINER -->
	
	
	
	
	<?php include("footer.php");?>
 <?php

  //include("footer.php");
}
 else
	 {
    header("location:index.php");
}
?>

<script>
function fnExcelReport(){
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('example'); // id of table
    tab_text =tab_text+"<th>Report Date</th><th>Zone</th><th>State</th><th>City</th><th>Emp. Code</th><th>GL Name</th><th>Mobile</th><th>ASM Name</th><th>RM Name</th><th>Distributor Code</th><th>Distributor Name</th><th>No of Executive</th><th>Demo By SP</th><th>Demo By GL</th><th>Total Demo</th><th>Sale By SP</th><th>Sale by GL</th><th>Total Sales</th><th>Mtd Demo by Sp</th><th>Mtd Demo by GL</th><th>Mtd Demo</th><th>Mtd Sale by SP</th><th>Mtd Sale by GL </th><th>Mtd Sale</th><th>Mtd Sale % by sp</th><th>Mtd Sale % by GL</th><th>Mtd Sale % </th><th>W.Test SP</th><th>W.Test GL</th><th>Total W.Test</th><th>Pro Sold SP</th><th>pro Sold GL</th><th>Total Pro Sold</th><th>Mtd W.Test SP</th><th>Mtd W.Test GL</th><th>Mtd W.Test</th><th>Mtd Pro Sold SP</th><th>Mtd Pro Sold GL</th><th>Mtd Pro Sold </th><th>Mtd Pro Sold % SP</th><th>Mtd Pro Sold % GL</th><th>Mtd pro Sold %</th></tr><tr>";
    for(j = 1 ; j < tab.rows.length ; j++) 
    {  
        
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
        //alert(tab_text);
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    //tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"DailySaleReport.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
} 
</script>

