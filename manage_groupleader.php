<?php 
include('header.php');
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
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
<?php
if($_SESSION["adminx"]!="")
{

?>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<?php include('sidebar.php');?>
	<div class="page-content-wrapper">
		<div class="page-content">
			
			<?php if($_SESSION['rolename']=='admin'){?>
			<h3 class="page-title">	Manage Group Leader</h3>
			<?php } else {?>
			<h3 class="page-title">Group Leader </h3>
			<?php }?>
			<?php if($_SESSION['rolename']=='admin'){
				if(isset($_REQUEST['glid'])){
					$glid = $_REQUEST['glid'];
					$result = $db->getGlDetails($glid);
					//echo json_encode($result);
					foreach ($result['GlList'] as $key => $value) {
						$zone =$value['zone'];
						$emp_code =$value['emp_code'];
						$glid = $value['id'];
						$glName = $value['name'];
						$mobileno = $value['mobileno'];
						$email = $value['email'];
						$state = $value['state'];
						$city = $value['city'];
						$state_id = $value['state_id'];
						$city_id = $value['city_id'];
						$weekOff = $value['weekOff'];
						$dis_id = $value['dis_id'];
						$roleId = $value['roleId'];
						$parentUserId = $value['parentUserId'];
						$reporting_to = $value['reporting_to'];
						$DistributorCode = $value['DistributorCode'];
						$isActive = $value['isActive'];
					}
				$btnId = "editGl";	
				$action = "edit";
				$cancel = "cancelEdit";
				}else{
					$btnId = "AddGl";	
					$action = "Add";
				
				}
				

				$dis_list = $db->getDistributorList();
				//echo json_encode($dis_list);
				$gl_list  = $db->getglList();	 
				
				//echo json_encode($gl_list);
			?>
			<div class="row">
				<div class="portlet box ">
				
									
				<div class="portlet-body form">
					<!-- BEGIN FORM-->
					<form action="" class="form-horizontal" method="POST">
					
					<div class="form-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-3">Name</label>
								<div class="col-md-9">
									<input type="text" class="form-control"  placeholder="Enter Group Leader name" name="name" id="name" required value="<?php echo $glName; ?>">
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3">Email</label>
								<div class="col-md-9">
									<input type="email" class="form-control"  placeholder="Enter Group Leader Emailid" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="xyz@something.com" required value="<?php echo $email; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Mobile</label>
								<div class="col-md-9">
									<input type="text" class="form-control" placeholder="Enter Group Leader Mobile No" maxlength="10" name="mobile" id="mobile" pattern="[789][0-9]{9}" required value="<?php echo $mobileno; ?>" >
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Reporting To</label>
								<div class="col-md-9">
									<select class="form-control select2me" name="reporting_to" id="reporting_to">
									<option value="">--Select Reporting--</option>
									<?php 
									$results = $db->selectReporting(4);
									foreach($results['ReportingList'] as $values){ ?>
									<option value="<?php echo $values['userId']; ?>" <?php if($parentUserId == $values['userId']){ echo "selected";} ?>><?php echo strtoupper($values['userName'])."  [".strtoupper($values['roleName'])."]"; ?></option>
							        <?php 
									} 
									?>
									</select>
								</div>
							</div>
							<?php 
							if(isset($_REQUEST['glid'])){
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
							<div class="form-group">
								<label class="control-label col-md-3">Emp Code</label>
								<div class="col-md-9">
									<input type="text" class="form-control" placeholder="Emp Code" name="code" id="code" value="<?php echo $emp_code; ?>" maxlength="10" required>
								</div>
							</div>
							<?php										
							} ?>
							

						</div>
						
					
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Weekly Off</label>
								<div class="col-md-9">
								<select class="form-control select2me" name="woff" id="weekoff">
									<option value="">Choose Weekly Off</option>
									<?php 
									if(isset($_REQUEST['glid'])){
										?>
									<option value="<?php echo $weekOff?>" selected><?php echo $weekOff?></option>	
										<?php
									}
									?>
									<option value="Sunday">Sunday</option>
									<option value="Monday">Monday</option>
									<option value="Tuesday">Tuesday</option>
									<option value="Wednesday">Wednesday</option>
									<option value="Thursday">Thursday</option>
									<option value="Friday">Friday</option>
									<option value="Saturday">Saturday</option>
								</select>
									
								</div>
							</div>	
						<?php 
						if(isset($_REQUEST['glid'])){
						?>
					
						<div class="form-group">
								<label class="control-label col-md-3">State</label>
								<div class="col-md-9">
						<select class="form-control select2me" onchange="CheckState(this.value);"  name="state" id="state_new" required>
						<option value="<?php echo $state_id?>" selected><?php echo $state?></option>
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
						if(isset($_REQUEST['glid'])){
						?>
						<div class="form-group">
								<label class="control-label col-md-3">City</label>
								
								<div class="col-md-9" id="city_1">
									<select class="form-control select2me" data-placeholder="Select City" name="city" id="city_new" onchange="CheckCity(this.value);" required>
										<option value="<?php echo $city_id?>" selected><?php echo $city?></option>
										
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
									<select class="form-control select2me" data-placeholder="Select City" name="city" id="city_new" onchange="CheckCity(this.value);" required>
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
										if(isset($_REQUEST['glid'])){
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
							



							<div class="form-group">
								<label class="control-label col-md-3">Distributor Code</label>
								<div class="col-md-9">
									<select class="form-control select2me" name="DistributorCode" id="DistributorCode" required>
									<option value=""> Select Distributor Code</option>
									<?php 
									if(isset($_REQUEST['glid'])){
									?>
									<option selected value="<?php echo $dis_id; ?>"><?php echo $DistributorCode; ?></option>
									<?php	
									}
									?>
									<?php 
									foreach ($dis_list['DisList'] as $key => $value) {
									?>
									<option  value="<?php echo $value['dis_id']; ?>"><?php echo $value['dis_code']."  ".$value['dis_name']; ?></option>
									<?php	
									}
									?>
									</select>
								</div>
							</div>
							

						</div>
						
					
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-6"></div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12 pull-right">
									
									<input type="hidden"  name="glid" value="<?php echo $_REQUEST['glid'] ?>">
									<input type="hidden"  name="parentUserId" value="<?php echo $parentUserId; ?>">
									
									<input type="hidden"  name="dis_id" value="<?php echo $dis_id; ?>">
									<input type="hidden"  name="action" id="action" value="<?php echo $action; ?>">
									
									<input type="button" id="<?php echo $btnId; ?>" class="btn green" value="Submit" >
									
									<button type="reset" id="<?php echo $cancel; ?>" class="btn default">Cancel</button>
									
								</div>
							</div>
						</div>
						
					</div>
				</div>	

					<!-- END FORM-->
				</form>
			</div>
			</div>
			</div>
				<?php } ?>
			<div class="clearfix"></div>
		
	
	
			<div class="row">
				<div class="col-md-12">
					
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-globe"></i>All Roles
							</div>
							<div class="tools hide">
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<style>
						.btn-group.tabletools-btn-group.pull-right{display:none!important;}
						.dataTables_info{display:none;}
						</style>
						
						<div class="portlet-body">
							<button  onclick="fnExcelroleReport();" style="float:right; margin-bottom:5px;"> EXPORT TO EXCEL </button>
						<table cellpadding="0" cellspacing="0" border="0" class="table-striped table-bordered table-hover display" id="adduser">
								<thead>
                                    <tr>
                                	 <th>Zone</th>
                                	 <th>Emp. Code</th>
                                	 <th>GL Name</th>
                                	 <th>Mobile No</th>
                                	 <th>Email id</th>
                                	 <th>State</th>
                                	 <th>City</th>
                                	 <th>Role Name</th>
                                	 <th>Distributor Code</th>
									 <th>Distributor Name</th>
									 <th>Weekly Off</th>
									<?php 
									if($_SESSION["rolename"]=='admin'){ 
									?>
									 <th>Edit</th>
									 <th>Action</th>
									<?php
									}?> 
                                    </tr>
								</thead>
							<tbody>
						
						<?php 
						
						
						foreach ($gl_list['GlList'] as $key => $value) {
						?>
						<tr style="font-size:12px;">
							<td> <?php echo $value['zone']; ?> </td>
							<td> <?php echo $value['emp_code']; ?> </td>
							<td> <?php echo ucfirst($value['name']); ?> </td>
							<td> <?php echo strtolower($value['mobileno']); ?> </td>
							<td> <?php echo strtolower($value['email']); ?> </td>
							<td> <?php echo ucfirst($value['state']); ?> </td>
							<td> <?php echo ucfirst($value['city']); ?> </td>
							
							<td> <?php echo ucfirst($value['roleName']); ?> </td>
							
							<td> <?php echo $value['DistributorCode']; ?> </td>
							<td> <?php echo ucfirst($value['distributer_name']); ?> </td>
							<td> <?php echo ucfirst($value['weekOff']); ?> </td>
							<?php if($_SESSION["rolename"]=='admin'){ ?>
							<td><a href="manage_groupleader.php?glid=<?php echo $value['id']; ?>">Edit</a></td>
							<?php 
							if($value['isActive']==1){
							?>
							<td> <button class="btn btn-success btn-sm action" id="<?php echo $value['id']."_".$value['isActive']; ?>">Active</button></td>
							<?php
							}else{
							?>
							<td> <button class="btn btn-danger btn-sm action"  id="<?php echo $value['id']."_".$value['isActive']; ?>">Inactive</button></td>
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
	<!-- END CONTENT -->
	
</div>
</div>
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
$("#dis_code").keydown(function (event) {
	if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
		this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
	}	
});	
function isValidEmailAddress(emailAddress) {
    var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(emailAddress);
}
$("#editGl").click(function(){
		var name  = $("#name").val();
		var email = $("#email").val();
		var mobile = $("#mobile").val();
		var discode = $("#discode").val();
		var woff = $("#weekoff").val();
		var glid = '<?php echo $_REQUEST['glid']; ?>';
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
			$(".message").html("Please Enter Emailid").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
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
			}else if(discode == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Select Distributor Code").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if(woff == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Select weekly Off").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
		}else if($("#reporting_to").val() == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Select Reporting To").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
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
			}else if( $("#DistributorCode").val() == "" ){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Distributor Code").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
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
				url: "./action_manageGl.php",
				type: "POST",
				data: data,
				success: function(res){
				//alert(res);
				  var obj = jQuery.parseJSON(res);
					if(obj.result=="TRUE")
					{
						$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
						$(".yes,.No").hide();
						$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
						window.location.href = "manage_groupleader.php";      
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
$("#cancelEdit").click(function(){
	window.location.href = "manage_groupleader.php";  
});
$("#AddGl").click(function(){
		var name  = $("#name").val();
		var email = $("#email").val();
		var mobile = $("#mobile").val();
		var discode = $("#discode").val();
		var woff = $("#weekoff").val();
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
			$(".message").html("Please Enter Emailid").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
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
		}else if(discode == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Select Distributor Code").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if(woff == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Select weekly Off").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
		}else if($("#reporting_to").val() == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Select Reporting To").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
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
				
				
			}else if( $("#DistributorCode").val() == "" ){
				$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Distributor Code").delay(30000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
				
				
			}else{
			var data = $("form").serialize();
			//alert(data);
			$.ajax({
				url: "./action_manageGl.php",
				type: "POST",
				data: data,
				success: function(res){
				//alert(res);
				  var obj = jQuery.parseJSON(res);
					if(obj.result=="TRUE")
					{
						$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
						$(".yes,.No").hide();
						$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
						setTimeout(function() {
							$('.confirmBox').fadeOut('fast');
							window.location.href = "manage_groupleader.php";       
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
						url: "./action_manageGl.php",
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
										window.location.href = "manage_groupleader.php";       
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
	
});

function fnExcelroleReport()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('adduser'); // id of table
    tab_text =tab_text+"<th>GL ID</th><th>Emp. Name</th><th>Mobile No</th><th>Email</th><th>State</th><th>City</th><th>WeekOff</th><th>Role Name</th><th>DistributorCode</th><th>Distributor Name</th><th>Weekly Off</th></tr><tr>";
    for(j = 1 ; j < tab.rows.length ; j++) 
    {  
        
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
        //alert(tab_text);
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
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