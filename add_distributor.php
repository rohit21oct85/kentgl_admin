<?php 
include('header.php');
session_start();
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();

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


</script>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<?php include('sidebar.php');?>
	<div class="page-content-wrapper">
		<div class="page-content">
			
			<h3 class="page-title">
			<?php if($_GET['dis_id']=='')
				{
				echo 'Add Distributor';	
				}else
				{
					
                echo 'Update Distributor';
				}
				?>
			
			</h3>
			<?php if($_SESSION['rolename']=='admin'){
				if(isset($_REQUEST['dis_id'])){
					$dis_id = $_REQUEST['dis_id'];
					$result = $db->getDistributorDetails($dis_id);
					//echo json_encode($result);
					foreach ($result['DisDetails'] as $key => $value) {
						$dis_id = $value['dis_id'];
						$dis_code = $value['dis_code'];
						$dis_name = $value['dis_name'];
						$state_id = $value['state_id'];
						$city_id = $value['city_id'];
						$state_name = $value['state_name'];
						$city_name = $value['city_name'];
						
						$isActive = $value['isActive'];
					}
				$btnId = "editDsitributor";	
				$action = "edit";
				$cancel = "cancelEdit";
				}else{
				$btnId = "addDsitributor";		
				$action = "add";
				$cancel = "cancelEdit";
				}	
				

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
								<label class="col-md-3 control-label">Distributor Code</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="dis_code" id="dis_code" maxlength="9"  placeholder="Enter Distributor Code" value="<?php echo $dis_code; ?>" required>
									
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Distributor Name</label>
								<div class="col-md-9">
									<input type="text" class="form-control" placeholder="Enter Distributor Name" name="dis_name" id="dis_name" value="<?php echo $dis_name; ?>"  required>
									
								</div>
							</div>
							
							<div class="form-group">
										<label class="col-md-3 control-label">Status</label>
										<div class="col-md-9">
										<select class="form-control" name="status" id="status">
										<?php 
										if(isset($_REQUEST['dis_id'])){
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
							</div>
							<div class="col-md-6">
							<?php 
						if(isset($_REQUEST['dis_id'])){
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
						if(isset($_REQUEST['dis_id'])){
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
						</div>						
						</div>
						<div class="form-actions fluid">
							<div class="row">
								<div class="col-md-offset-3 col-md-9">
									<input type="hidden"  name="dis_id" value="<?php echo $dis_id; ?>">
									<input type="hidden" name="action" value="<?php echo $action; ?>">
									<input type="button" id="<?php echo $btnId; ?>" class="btn green" value="Submit" >
									
									<button type="reset" id="<?php echo $cancel; ?>" class="btn default">Cancel</button>
								</div>
							</div>
						</div>
					</div>
					<!-- END FORM-->
				</form>
			</div>
			</div>
			</div>
				<?php }?>
			<div class="clearfix"></div>
		
	
	
			<div class="row">
				<div class="col-md-12">
					
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-globe"></i>All Distributor 
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
						<table cellpadding="0" cellspacing="0" border="0" class="table-striped table-bordered table-hover display" id="roleffms">
								<thead>
                                    <tr>
                                	 <th>Distributor Code</th>
                                	 <th>Distributor Name</th>
									 <th>Distributor State</th>
									 <th>Distributor City</th>
                                	 <th>Status</th>
									 <th <?php if($_SESSION["rolename"]!='admin'){ echo 'class="hide"';}?>>Edit</th>
                                    </tr>
								</thead>
							<tbody>
						
						<?php 
						$result_role = $db->getAllDistributorList(); 
						//print_r($result_role);
						foreach ($result_role['DistributorAllList'] as $key => $value) {
						?>
						<tr>
							<td> <?php echo $value['dis_code']; ?> </td>
							<td> <?php echo strtoupper($value['dis_name']); ?> </td>
							<td> <?php echo $value['state_name']; ?> </td>
							<td> <?php echo $value['city_name']; ?> </td>
							<?php 
							if($value['isActive']==1){
							?>
							<td> <button class="btn btn-success btn-sm action" id="<?php echo $value['dis_id']."_".$value['isActive']; ?>">Active</button></td>
							<?php
							}else{
							?>
							<td> <button class="btn btn-danger btn-sm action" id="<?php echo $value['dis_id']."_".$value['isActive']; ?>">Inactive</button></td>
							<?php
							}
							?>
							<td><a href="add_distributor.php?dis_id=<?php echo $value['dis_id'] ?>">Edit</a></td>
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
$("#dis_name").keypress(function(event){
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
$("#dis_code").keydown(function (event) {
	if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
		this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
	}	
});		
$("#addDsitributor").click(function(){
		var dis_code  = $("#dis_code").val();
		var dis_name = $("#dis_name").val();
		
		if(dis_code == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Enter Distributor Code").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
		}else if(dis_name == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Enter Distributor Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
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
				url: "./action_DistributorDetails.php",
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
							window.location.href = "add_distributor.php";      
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
	

$("#editDsitributor").click(function(){
		var dis_code  = $("#dis_code").val();
		var dis_name = $("#dis_name").val();
		
		if(dis_code == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Enter Distributor Code").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
		}else if(dis_name == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Enter Distributor Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
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
				url: "./action_DistributorDetails.php",
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
							window.location.href = "add_distributor.php";
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

$("#roleffms").on("click",".action",function(event){
			var id = this.id;
			var text = $(this).text();
			if(text == "Active"){
				var alertText = "Inactive";
			}else{
				var alertText = "Active";
			}
			var alertMsg = 'Do you want to ' +alertText+ ' this distributor ?';
			
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".message").text(alertMsg).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			$(".yes,.No").show();
			
			$(".yes").click(function(){
				
				var act = "delete";
				var data = "data="+id+"&action="+act;
				//alert(data);
				$.ajax({
						url: "./action_DistributorDetails.php",
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
									window.location.href = "add_distributor.php";      
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
 $("#cancelEdit").click(function(){
	window.location.href = "add_distributor.php";  
}); 
function fnExcelroleReport()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('roleffms'); // id of table
    tab_text =tab_text+"<th>Role Name</th><th>Emp. Name</th><th>Active/Inactive</th><th>Action</th></tr><tr>";
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