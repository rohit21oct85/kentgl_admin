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
<!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
    <?php include('sidebar.php'); ?>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">

                <!-- BEGIN PAGE HEADER-->
                <h3 class="page-title">
                    Daily Login Report
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
                                    <i class="fa fa-globe"></i>Login Reports On <?php echo date("Y-m-d"); ?>
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
                                  
                                    <th class="hide">Login Date</th>
                                    
                                    <th>Emp. Zone</th>
                                    <th>Emp. Code</th>
                                    <th>Emp. Name</th>
                                    <th>Emp. Role</th>
									<th>State</th>
									<th>City</th>
                                    <th>Login count</th>
                                    <th>Last Login</th>
                                    <th>Last Logout</th>
                                    
                                    
                            </tr>
						</thead>
						<tbody id="hd">
                        <?php
                       
                        $pid = $_SESSION['userId']; 
                        $result = $db->getDailyLoginReport($pid);
                        //echo json_encode($result);
                        foreach ($result['LoginDetails'] as $key => $value) {
                        ?>
                        <tr>
                     
                        <td class="hide"><?php echo $value['repDate']?></td>
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
    var textRange; var j=1;
    tab = document.getElementById('example'); // id of table
    tab_text =tab_text+"<th>Login Date</th><th>Emp name</th><th>Emp Role</th><th>State</th><th>City</th><th>Login Count</th><th>Last Login</th><th>last Logout</th><th>Time Spent</th></tr><tr>";
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

