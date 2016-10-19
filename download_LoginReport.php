<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
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
	.confirmBox{
		width:420px;
		height:100px;
		position:fixed;
		top:-50px;
		left:38%;
		background:#fdfdfd;
		border:1px solid rgba(0,0,0,0.3);
		z-index:99999999;
		display:none;
	}
	.yes{
		position: absolute;
		bottom: 6px;
		right: 54px;
	}
	.No{
		position: absolute;
		bottom: 6px;
		right: 10px;
	}
	.message{
		color:black;
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

                <!-- BEGIN PAGE HEADER-->
                <h3 class="page-title">
                   Dowload Login Report
                </h3>
                <hr>
                <div class="col-md-12">
                    <form action="" method="post">
                        <div class="form-group col-md-6">
                        <label class="control-label col-md-3">From Date</label>
                        <div class="col-md-6">
                        <input type="text" class="form-control" style="display:inline-block" id="datepicker" name="datepicker" placeholder="From-Date">
                        </div>
                        </div>
                        <div class="form-group col-md-6">
                                <label class="control-label col-md-3">To Date</label>
                                <div class="col-md-6">
                        <input type="text" class="form-control" style="display:inline-block" id="datepicker2" name="datepicker2" placeholder="To-Date">
                        </div></div>
                        <div class="form-group col-md-6 pull-right"> 
                        <?php
                          $pid = $_SESSION['userId']; 
                        ?>
                        <input type="hidden" id="pid" name="pid" value="<?php echo $pid; ?>">   
                        <input type="button" id="submit" class="pull-right btn green" name="submit" style="margin-right:140px;" value="submit">
                        </div>
                    </form>
                </div>
                <hr>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->

                <div class="row">
				
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                         <br stule="clear:both">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-globe"></i>Reports <span class="rep_date"></span>
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
							
							<div class="portlet-body" style="overflow-x:scroll">
                            </div>
                     

                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
						<div class="confirmBox">
							<p class="message"></p>
							<button class="btn btn-sm btn-danger yes" value="Yes">Yes</button>
							<button class="btn btn-sm btn-success No" value="No">No</button>
						</div>		
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

$(document).ready(function(){
$("#datepicker").datepicker({
    maxDate:0,
    numberOfMonths: 1,
    onSelect: function(selected) {
      $("#datepicker2").datepicker("option","minDate", selected)
    }
});
$("#datepicker2").datepicker({ 
    maxDate:0,
    numberOfMonths: 1,
    onSelect: function(selected) {
       $("#datepicker1").datepicker("option","maxDate", selected)
    }
}); 

$(".portlet-body").css({'display':'none'});  
$("#submit").on("click",function(){
        var datepicker = $("#datepicker").val();
        var datepicker2 = $("#datepicker2").val();
        var repDate = "On " + datepicker + " To " + datepicker2;
        $(".rep_date").html(repDate).css({'font-weight':'normal','fonr-size':'10px'});
        if(datepicker == ""){
            $(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please select From-Date").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
        }else if(datepicker2 == ""){
            $(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please select To-Date").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
        }else{
        var data = $("form").serialize();
		//alert(data);
        $.ajax({
                    url: "./admin_downloadReport.php",
                    type: "POST",
                    data: data,
                    success: function(rel){
                    //alert(rel);
                    $(".portlet-body").css({'display':'block'}).html(rel);
                    }
                }); 
        //$(".portlet-body").show(); 
        }
       
    });
});

function fnExcelReport(){
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=1;
    tab = document.getElementById('example'); // id of table
    tab_text =tab_text+"<th>Login Date</th><th>Emp Zone</th><th>Emp Code</th><th>Emp name</th><th>Emp Role</th><th>State</th><th>City</th><th>Login count</th><th>Last Login</th><th>last Logout</th></tr><tr>";
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

