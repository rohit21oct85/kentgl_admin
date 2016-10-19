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
					<h3 class="page-title">Add User</h3>
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
			
			<div class="page-bar hide">
				<ul class="page-breadcrumb">
					<li>
						<a href="#">Add New User</a>
					</li>
				</ul>
				<div class="page-toolbar">
					<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-salt" data-placement="top" data-original-title="Change dashboard date range">
						<i class="icon-calendar"></i>&nbsp;
						<span class="thin uppercase visible-lg-inline-block">&nbsp;</span>&nbsp;
						<i class="fa fa-angle-down"></i>
					</div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN DASHBOARD STATS -->
			<!-- END DASHBOARD STATS -->
			
			<div class="row">
			<?php 
			if($_SESSION['rolename']=='admin'){ 
			if(isset($_REQUEST['user_id'])){
				$uid = $_REQUEST['user_id'];
				$results = $db->getUserDetails($uid);
			
				foreach($results['UserDetails'] as $value){
					
					$userId =$value['userId'];
					$zone =$value['zone'];
					$emp_code =$value['emp_code'];
					$name = $value['userName'];
					$email = $value['email'];
					$mobileno = $value['mobileno'];
					$isActive = $value['isActive'];
					$roleId = $value['roleId'];
					$roleName = $value['roleName'];
					$parentUserId = $value['parentUserId'];
					$reporting_to = $value['reporting_to'];
					$city_id = $value['city_id'];
					$city_name = $value['city_name'];
					$state_id = $value['state_id'];
					$state_name = $value['state_name'];
				}
			$btnId = "EditUser";
			$cancel = "cancelEdit";			
			}else{
			$btnId = "addUser";	
			}	
			?>
			<div class="portlet-body form" style="padding:12px 20px 0 20px!important;">
			
			
			<!-- BEGIN FORM-->
			<form action="" class="form-horizontal" method="POST">
				<div class="form-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-3">Name</label>
								<div class="col-md-9">
									<input type="text" class="form-control" value="<?php echo $name; ?>" placeholder="Name" name="name" id="name" required>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3">Email</label>
								<div class="col-md-9">
									<input type="email" class="form-control" value="<?php echo $email; ?>" placeholder="Email" name="email" onblur="validateEmail(this.value);" id="email"  title="xyz@something.com" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Mobile</label>
								<div class="col-md-9">
									<input type="text" class="form-control" value="<?php echo $mobileno; ?>" placeholder="Mobile" maxlength="10" name="mobile" id="mobile" pattern="[0-9]{1,3}" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Role</label>
								<div class="col-md-9">
									
									<select class="form-control" name="role" id="role" onchange="CheckReporting(this.value)" required>
										<?php 
										if(isset($_REQUEST['user_id'])){
										?>
										<option value="<?php echo $roleId; ?>" selected>
										<?php echo $roleName; ?>
										</option>
										<?php										
										} ?>
										
										<option value="">Select Role</option>
										<?php 
											$results = $db->selectRole();
											$resultsnew = json_decode($results, true) ;
											foreach($resultsnew['RoleList'] as $value){
											if($value['isActive'] == 1){
											?>				
											<option value="<?php echo $value['roleId']; ?>">
											<?php echo strtoupper($value['roleName']); ?></option>
											<?php
											}											
											}
										?>
									</select>
								</div>
							</div>
							<?php 
							if(isset($_REQUEST['user_id'])){
							?>
							<div class="form-group">
								<label class="control-label col-md-3">Emp Zone</label>
								<div class="col-md-9">
									<select class="form-control" name="zone" id="zone"  required>
										<option value="<?php echo $zone; ?>" selected>
										<?php echo $zone; ?>
										</option>
										
										<option value="">Select Zone</option>
										<option value="East">East</option>
										<option value="West">West</option>
										<option value="North">North</option>
										<option value="South">South</option>
									</select>
								</div>
							</div>
							<?php										
							} ?>
										
							</div>
						
					
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-3">Reports To</label>
								<div class="col-md-9" id="rep_1">
									<select class="form-control select2me" name="report_to" id="report_to">
										<?php 
										if(isset($_REQUEST['user_id'])){
										?>
										<option value="<?php echo $parentUserId; ?>" selected>
										<?php echo $reporting_to; ?>
										</option>
										<?php } ?>
										<option value="">Select Report to</option>
									</select>
								</div>
								<div id="rep_2"></div>
							</div>
						<?php 
						if(isset($_REQUEST['user_id'])){
						?>
						<div class="form-group">
								<label class="control-label col-md-3">State</label>
								<div class="col-md-9">
						<select class="form-control select2me" onchange="CheckState(this.value);"  name="state" id="state_new" required>
						<option value="<?php echo $state_id?>" selected><?php echo $state_name?></option>
						<?php 
											$results = $db->select_state();
											//$resultsnew = json_decode($results, true) ;
											foreach($results['StateList'] as $value){
											?>				
											<option value="<?php echo $value['stateId']; ?>">
												<?php echo strtoupper($value['stateName']); ?>
											</option>
											<?php	
											}
										?>
						<option value="others">others</option>
						</select>
							</div>
							
						</div>
						<div class="form-group"  id="state" style="display:none;">
								<div class="col-md-9" style="float:right">
									<input type="text" class="form-control" value="" placeholder="State" name="state1">
								</div>
						</div>
						<?php						
						}else{
						?>
						<div class="form-group">
								<label class="control-label col-md-3">State</label>
								<div class="col-md-9">
								
									<select class="form-control select2me" data-placeholder="Select State" name="state" id="state_new"   onchange="CheckState(this.value);" required>
										<option value=""></option>
										<?php 
											$results = $db->select_state();
											//$resultsnew = json_decode($results, true) ;
											foreach($results['StateList'] as $value){
											?>				
											<option value="<?php echo $value['stateId']; ?>">
												<?php echo strtoupper($value['stateName']); ?>
											</option>
											<?php	
											}
										?>
										<option value="others">others</option>
									</select>
								</div>
						</div>
						<div class="form-group"  id="state" style="display:none;">
								<div class="col-md-9" style="float:right">
									<input type="text" class="form-control" value="" placeholder="State" name="state1" id="state1">
								</div>
						</div>
						<?php	
						}
						?>	
					
						<?php 
						if(isset($_REQUEST['user_id'])){
						?>
						<div class="form-group">
								<label class="control-label col-md-3">City</label>
								
								<div class="col-md-9" id="city_1">
									<select class="form-control select2me" data-placeholder="Select City" name="city" id="city_new" onchange="CheckCity(this.value);" required>
										<option value="<?php echo $city_id?>" selected><?php echo $city_name?></option>
										
										<option value="others">others</option>
									</select>
									
								 </div>
								<div id="city_2"></div>
								 
							</div>
							
							<div class="form-group"  id="city" style="display:none;">
								
								<div class="col-md-9" style="float:right">
									<input type="text" class="form-control" value="" placeholder="city" name="city1" id="city1">
									
								</div>
							</div>	
						<?php
						}else{
						?>
							<div class="form-group">
								<label class="control-label col-md-3">City</label>
								
								<div class="col-md-9" id="city_1">
									<select class="form-control select2me" data-placeholder="Select City" name="city" id="city_new" required>
										<option value=""></option>
										<option value="others">others</option>
									</select>
									
								 </div>
								<div id="city_2"></div>
								 
							</div>
							
							<div class="form-group"  id="city" style="display:none;">
								<div class="col-md-9" style="float:right">
									<input type="text" class="form-control" value="" placeholder="city" name="city1" id="city1">
									
								</div>
							</div>	
									
						<?php						
						}		
						?>			
							
							
								
									
							
							<div class="form-group">
								<label class="control-label col-md-3">Status</label>
								<div class="col-md-9">
									<select class="form-control" name="status" id="status">
										
											<?php 
										if(isset($_REQUEST['user_id'])){
											if($isActive ==1){
											?>
											<option value="1" selected>Active</option>
											<option value="0">Inactive</option>	
												<?php 
											}else{
												?>
											<option value="0" selected>Inactive</option>	
											<option value="1">Active</option>
												<?php 
											}
										}else{
											?>
											<option value="1">Active</option>
											<option value="0">Inactive</option>	
											<?php
										}
										?>
											
									</select>
								</div>
							</div>
							
							<?php 
							if(isset($_REQUEST['user_id'])){
							?>
							<div class="form-group">
								<label class="control-label col-md-3">Emp Code</label>
								<div class="col-md-9">
									<input type="text" class="form-control" placeholder="Emp Code" name="code" id="code" value="<?php echo $emp_code; ?>" maxlength="10" required>
								</div>
							</div>
							<?php										
							} ?>
							
						</div>
						
					
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-6"></div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12 pull-right">
									<!--<input type="hidden"  value="<?php //$_SESSION['ID']?>" name="user_id">-->
									<input type="hidden"  name="userId" value="<?php echo $userId; ?>">
									<input type="button" id="<?php echo $btnId; ?>" class="btn green" value="Submit" >
									
									<button type="reset" id="<?php echo $cancel; ?>" class="btn default">Cancel</button>
									
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</form>
			<!-- END FORM-->
			</div>
			</div>
			<?php 
			
		} ?>
			<div class="clearfix"></div>
		<?php
		?>
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
				<th>Mobile No</th>
				<th>Email Id</th>
				<th>Emp. Role</th>
				<th>City</th>
				<th>State</th>
				<?php 
				if($_SESSION["rolename"]=='admin'){ 
				?>
				<th>Edit</th>
				<th>Action</th>
				<?php
				}?>
				
			</tr>
			</thead>
			
			<?php 
			$resu = $db->viewSalePerson($_SESSION["userId"]);
			$results = json_decode($resu,true);
			foreach($results['PersonalDatails'] as $key=> $values){
				$no = explode("/", $values['mobileno']);
				?>
				<tr class="center" style="line-height: 35px;">
					
					<td><?php echo $values['zone']; ?></td>
					<td><?php echo $values['emp_code']; ?></td>
					<td><?php echo $values['userName']; ?></td>
					<td><?php echo $no[0]; ?></td>
					<td><?php echo $values['email']; ?></td>
					<td><?php echo strtoupper($values['roleName']); ?></td>
					<td><?php echo $values['city']; ?></td>
					<td><?php echo $values['state']; ?></td>
					<?php if($_SESSION["rolename"]=='admin'){ ?>
					<td><a href="add_user.php?user_id=<?php echo $values['userId']; ?>">Edit</a></td>
					<?php 
					if($values['isActive']==1){
					?>
					<td> <button class="btn btn-success btn-sm action" id="<?php echo $values['userId']."_".$values['isActive']; ?>">Active</button></td>
					<?php
					}else{
					?>
					<td> <button class="btn btn-danger btn-sm action"  id="<?php echo $values['userId']."_".$values['isActive']; ?>">Inactive</button></td>
					<?php
					}
					} 
					?>
						
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