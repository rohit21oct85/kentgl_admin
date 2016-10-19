<?php 
include('header.php');
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
//echo $_SESSION["userId"];
if($_SESSION["adminx"]!="")
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
		color:#000;
	}
</style>
<script type="text/javascript">
function CheckState(val){
 var state=document.getElementById('state');
	 
 if(val=='others')
   state.style.display='block';
 else  
    state.style.display='none';
	
	var data='state_id='+val;
	//alert(data);

	$.ajax({url:"./getCity.php",
			 type:"post",
			 data: data,
			 success:function(rel){
			 $("#city_2").css("display","blcok");
			 $("#city_1").remove();
			 $("#city_2").html(rel);	 
		
				 }
	});
}
function CheckCity(val){
 var city=document.getElementById('city');
 if(val=='others')
   city.style.display='block';
 else  
   city.style.display='none';
}

function CheckReporting(val){

 var data='role_id='+val;
 $.ajax({url:"./getReporting.php",
			 type:"post",
			 data: data,
			 success:function(rel){
			 $("#rep_2").css("display","blcok");
			 $("#rep_1").remove();
			 $("#rep_2").html(rel);	 
		
				 }
	});
}
</script>

<div class="page-container">

	<?php include('sidebar.php');?>

	<div class="page-content-wrapper">
		<div class="page-content">
		
			<?php 
			if($_SESSION['rolename'] == 'admin'){
			 if($_GET['user_id']=='')
				{
					?>
					<h3 class="page-title">Employee Role Master</h3>
					<?php
				}else
				{
				?>
					<h3 class="page-title">Update User</h3>
				<?php
				}
			}
			?>
			
							<style>
                                .btn-group.tabletools-btn-group.pull-right{display:none!important;}
								.dataTables_info{display:none;}
								.table.dataTable thead .sorting_asc{}
								table.dataTable thead .sorting{}
                            </style>
			
		
			<!-- END PAGE HEADER-->
			<!-- BEGIN DASHBOARD STATS -->
			<!-- END DASHBOARD STATS -->
			<div class="clearfix"></div>
		
			<div class="row" style="margin-right: -5px;margin-left: -5px;">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
				
					
					<!-- END EXAMPLE TABLE PORTLET-->
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-globe"></i>Users
							</div>
							<div class="tools hide">
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<style>
						
						</style>
						
		<div class="portlet-body">
			<button  onclick="fnExceluserReport();" style="float:right; margin-bottom:5px;"> EXPORT TO EXCEL </button>
			<table cellpadding="0" cellspacing="0" border="0" class="table-striped table-bordered table-hover display" id="adduser">
			<thead>
			<tr>
				<th>Zone</th>
				<th>Emp. Code</th>
				<th>Name</th>
				<th>Emp. Role</th>
				
				<th>Zone</th>
				<th>Emp. Code</th>
				<th>Name</th>
				<th>Emp. Role</th>
				
				<th>Zone</th>
				<th>Emp. Code</th>
				<th>Name</th>
				<th>Emp. Role</th>
				
				<th>Zone</th>
				<th>Emp. Code</th>
				<th>Name</th>
				<th>Emp. Role</th>
				
				<th>Zone</th>
				<th>Emp. Code</th>
				<th>Name</th>
				<th>Emp. Role</th>
			</tr>
			</thead>
			
			<?php 
			$resu = $db->all_reporting();
			echo $results = json_encode($resu,true);
			die;
			foreach($results['PersonalDatails'] as $key=> $values){
				$no = explode("/", $values['mobileno']);
				?>
				<tr class="center" style="line-height: 35px;">
					
					<td><?php echo $values['emp_zone']; ?></td>
					<td><?php echo $values['emp_code']; ?></td>
					<td><?php echo $values['userName']; ?></td>
					<td><?php echo $values['email']; ?></td>
					
					<td><?php echo $values['zone']; ?></td>
					<td><?php echo $values['emp_code']; ?></td>
					<td><?php echo $values['userName']; ?></td>
					<td><?php echo $values['email']; ?></td>
					
					<td><?php echo $values['zone']; ?></td>
					<td><?php echo $values['emp_code']; ?></td>
					<td><?php echo $values['userName']; ?></td>
					<td><?php echo $values['email']; ?></td>
					
					<td><?php echo $values['zone']; ?></td>
					<td><?php echo $values['emp_code']; ?></td>
					<td><?php echo $values['userName']; ?></td>
					<td><?php echo $values['email']; ?></td>
					
					<td><?php echo $values['zone']; ?></td>
					<td><?php echo $values['emp_code']; ?></td>
					<td><?php echo $values['userName']; ?></td>
					<td><?php echo $values['email']; ?></td>
				</tr>					
				<?php
			}?>
			
			</tbody>
			</table>
							
							<div class="confirmBox">
								<p class="message"></p>
								<button class="btn btn-sm btn-danger yes" value="Yes">Yes</button>
								<button class="btn btn-sm btn-success No" value="No">No</button>
							</div>			
							
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>		
		
		</div>
	</div>
	<!-- END CONTENT -->

</div>
<!-- END CONTAINER -->


<?php 
include('footer.php');
}
else
{
header("location:index.php");	
}

?>

<script>
$(document).ready(function(){

$("#name").keypress(function(event){
	var inputValue = event.charCode;
	if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)){
		$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
		$(".yes,.No").hide();
		$(".message").html("enter only character").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
		setTimeout(function() {
			$('.confirmBox').fadeOut('fast');
		}, 2000);
		event.preventDefault();
	}
});

$("#mobile,#code").keydown(function (event) {
	// Prevent shift key since its not needed
	if (event.shiftKey == true) {
		event.preventDefault();
	}
	// Allow Only: keyboard 0-9, numpad 0-9, backspace, tab, left arrow, right arrow, delete
	if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
		// Allow normal operation
	} else {
		// Prevent the rest
		$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
		$(".yes,.No").hide();
		$(".message").html("enter only number").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
		setTimeout(function() {
			$('.confirmBox').fadeOut('fast');
		}, 2000);
		event.preventDefault();
	}
});
function isValidEmailAddress(emailAddress) {
    var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(emailAddress);
}
		
$("#addUser").click(function(){
			var name  = $("#name").val();
			var email = $("#email").val();
			var mobile = $("#mobile").val();
			if(name == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
			}else if(email == ""){
					$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
					$(".yes,.No").hide();
					$(".message").html("Please Enter Email Id").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
					setTimeout(function() {
						$('.confirmBox').fadeOut('fast');
					}, 2000);	
				
				
			}else if(!isValidEmailAddress(email)){
					$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
					$(".yes,.No").hide();
					$(".message").html("Please Enter Valid Email Id").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
					setTimeout(function() {
						$('.confirmBox').fadeOut('fast');
					}, 2000);
			}else if(mobile == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Mobile No").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);	
			}else if( mobile.charAt(0) < 7 || mobile.length != 10){
					$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
					$(".yes,.No").hide();
					$(".message").html("Please Enter Valid Mobile No").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
					setTimeout(function() {
						$('.confirmBox').fadeOut('fast');
					}, 2000);
			}else if($("#role").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select Role").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
			}else if($("#report_to").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select Reporting Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
			}else if($("#state_new").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select state").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
			}else if( $("#state_new").val() == "others" && $("#state1").val() == ""){
					$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
					$(".yes,.No").hide();
					$(".message").html("Please Enter state").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
					setTimeout(function() {
						$('.confirmBox').fadeOut('fast');
					}, 2000);
				
			}else if($("#city_new").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select City").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
			}else if($("#city_new").val() == "others" && $("#city1").val() == ""){
				//$("#city").css({'display':'block'});
				
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter City").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
				
				
			}else{
				var data = $("form").serialize();
				//alert(data);
				$.ajax({
					url: "./addUserDetails.php",
					type: "POST",
					data: data,
					success: function(rel){
					//alert(rel);
					var obj = jQuery.parseJSON(rel);
						if(obj.result=="TRUE")
						{
							$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
							$(".yes,.No").hide();
							$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
							setTimeout(function() {
								$('.confirmBox').fadeOut('fast');
								window.location.href = "add_user.php";      
							}, 2000);
							
						}else if(obj.result=="FALSE"){ 
							$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
							$(".yes,.No").hide();
							$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
							setTimeout(function() {
								$('.confirmBox').fadeOut('fast');
							}, 2000);
						}
					}
				});        
			return false; 
			}
				
});
		
$("#EditUser").click(function(){
			var name  = $("#name").val();
			var email = $("#email").val();
			var mobile = $("#mobile").val();
			if(name == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
			}else if(email == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Email Id").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);	
			}else if(!isValidEmailAddress(email)){
					$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
					$(".yes,.No").hide();
					$(".message").html("Please Enter Valid Email Id").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
					setTimeout(function() {
						$('.confirmBox').fadeOut('fast');
					}, 2000);
		}else if(mobile == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Mobile No").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);	
		}else if( mobile.charAt(0) < 7 || mobile.length != 10){
					$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
					$(".yes,.No").hide();
					$(".message").html("Please Enter Valid mobile No").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
					setTimeout(function() {
						$('.confirmBox').fadeOut('fast');
					}, 2000);
		}else if($("#role").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select Role").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if($("#report_to").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select Reporting Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if($("#state_new").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select state").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if( $("#state_new").val() == "others" && $("#state1").val() == ""){
					$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
					$(".yes,.No").hide();
					$(".message").html("Please Enter state").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
					setTimeout(function() {
						$('.confirmBox').fadeOut('fast');
					}, 2000);
				
		}else if($("#city_new").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please select City").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if($("#city_new").val() == "others" && $("#city1").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter City").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if($("#zone").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Select Emp. Zone").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if($("#code").val() == ""){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Employee Code").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else{
				var data = $("form").serialize();
				//alert(data);
				$.ajax({
					url: "./EditUserDetails.php",
					type: "POST",
					data: data,
					success: function(rel){
					//alert(rel);
					var obj = jQuery.parseJSON(rel);
						if(obj.result=="TRUE")
						{
						$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
						$(".yes,.No").hide();
						$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
						setTimeout(function() {
							$('.confirmBox').fadeOut('fast');
							window.location.href = "add_user.php";      
						}, 2000);
						
						}else if(obj.result=="FALSE"){ 
							$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
						$(".yes,.No").hide();
						$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
						setTimeout(function() {
							$('.confirmBox').fadeOut('fast');
						}, 2000);
						}
					}
				});        
			return false; 
			}
				
		});
});

$("#adduser").on("click",".action",function(event){
			//event.preventDefault();
			var id = this.id;
			
			var text = $(this).text();
			if(text == "Active"){
				var alertText = "inactive";
			}else{
				var alertText = "active";
			}
			var alertMsg = 'Do you want to ' +alertText+ ' this user';
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".message").text(alertMsg).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			$(".yes,.No").show();

			
			$(".yes").click(function(){
				
				var data = "data="+id;
				//alert(data);
				$.ajax({
						url: "./actionUserDetails.php",
						type: "POST",
						data: data,
						
						success: function(rel){
						//alert(rel);
						var obj = jQuery.parseJSON(rel);
							if(obj.result=="TRUE")
							{
								$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
								$(".yes,.No").hide();
								$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
								setTimeout(function() {
									$('.confirmBox').fadeOut('fast');
									window.location.href = "add_user.php";      
								}, 2000);
								
							}else if(obj.result=="FALSE"){ 
								$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
								$(".yes,.No").hide();
								$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
								setTimeout(function() {
									$('.confirmBox').fadeOut('fast');
								}, 2000);
							}
						}
					}); 
				});
				$(".No").click(function(){
					$(".confirmBox").hide();
				});
});
		
 $("#cancelEdit").click(function(){
	window.location.href = "add_user.php";  
}); 
function fnExceluserReport(){
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('adduser'); // id of table
    tab_text =tab_text+"<th>Emp Code</th><th>Name</th><th>E-mail_id</th><th>Role</th><th>City</th><th>State</th></tr><tr>";
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
        sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
} 

</script>