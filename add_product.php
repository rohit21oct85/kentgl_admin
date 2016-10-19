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
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<?php include('sidebar.php');?>
	<div class="page-content-wrapper">
		<div class="page-content">
			
			<?php if($_SESSION['rolename']=='admin'){?>
			<h3 class="page-title">	Add Product</h3>
			<?php } else {?>
			<h3 class="page-title">Product</h3>
			<?php }?>
			<?php if($_SESSION['rolename']=='admin'){
				if(isset($_REQUEST['product_id'])){
					$pid = $_REQUEST['product_id'];
					$result = $db->getProductDetails($pid);
					//print_r($result);
					foreach ($result['productDetails'] as $key => $value) {
						$productCode = $value['product_code'];
						$productId = $value['productId'];
						$productName = $value['productName'];
						$productDiscription = $value['productDiscription'];
						$isActive = $value['isActive'];
					}
				$btnId = "editProduct";	
				$action = "edit";
				$cancel = "cancelEdit";
				}else{
				$btnId = "addProduct";		
				$action = "add";
				}	
				

			?>
			<div class="row">
				<div class="portlet box ">
				
									
				<div class="portlet-body form">
					<!-- BEGIN FORM-->
					<form action="" class="form-horizontal" method="POST">
						
						<div class="form-body">
							<div class="form-group">
								<label class="col-md-3 control-label">Product Code</label>
								<div class="col-md-4">
									<input type="text" class="form-control" name="productCode" id="productCode"  placeholder="Enter Product Code" value="<?php echo $productCode; ?>" required>
									
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Product Name</label>
								<div class="col-md-4">
									<input type="text" class="form-control" name="productName" id="productName"  placeholder="Enter Product Name" value="<?php echo $productName; ?>" required>
									
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Product Description</label>
								<div class="col-md-4">
									<input type="text" class="form-control" placeholder="Enter Product Description" name="productDiscription" id="productDiscription" value="<?php echo $productDiscription; ?>"  required>
									
								</div>
							</div>
							<div class="form-group">
										<label class="col-md-3 control-label">Status</label>
										<div class="col-md-4">
											<select class="form-control" name="status" id="status">
													<?php 
										if(isset($_REQUEST['product_id'])){
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
											<span class="help-block hide">
											Select User Role. </span>
										</div>
									</div>
						<div class="form-actions fluid">
							<div class="row">
								<div class="col-md-offset-3 col-md-9">
									<input type="hidden"  name="productId" value="<?php echo $pid; ?>">
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
						<table cellpadding="0" cellspacing="0" border="0" class="table-striped table-bordered table-hover display" id="roleffms">
								<thead>
                                    <tr>
                                	 <th>Product Code</th>
									 <th>Product Name</th>
                                	 <th>product Discription</th>
									<th>Status</th>
									<th <?php if($_SESSION["rolename"]!='admin'){ echo 'class="hide"';}?>>Edit</th>
                                    </tr>
								</thead>
							<tbody>
						
						<?php 
						$result_role = $db->getProductList(); 
						//print_r($result_role);
						foreach ($result_role['ProductList'] as $key => $value) {
						?>
						<tr>
							<td> <?php echo $value['product_code']; ?> </td>
							<td> <?php echo $value['productName']; ?> </td>
							<td> <?php echo $value['productDiscription']; ?> </td>
							<?php 
							if($value['isActive']==1){
							?>
							<td> <button class="btn btn-success btn-sm action" id="<?php echo $value['productId']."_".$value['isActive']; ?>">Active</button></td>
							<?php
							}else{
							?>
							<td> <button class="btn btn-danger btn-sm action" id="<?php echo $value['productId']."_".$value['isActive']; ?>">Inactive</button></td>
							<?php
							}
							?>
					<td><a href="add_product.php?product_id=<?php echo $value['productId']; ?>">Edit</a></td>
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
$("#productName,#productDiscription").keypress(function(event){
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
$("#productCode").keydown(function (event) {
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

$("#addProduct").click(function(){
		var productCode = $("#productCode").val();
		var productName  = $("#productName").val();
		var productDiscription = $("#productDiscription").val();
		
		if(productCode == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Enter Product Code").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
		}else if(productName == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Product Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if(productDiscription == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter product Discription").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);	
		}else{
			var data = $("form").serialize();
			//alert(data);
			$.ajax({
				url: "./action_ProductDetails.php",
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
							window.location.href = "add_product.php";      
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
	
	
$("#editProduct").click(function(){
		var productCode = $("#productCode").val();
		var productname  = $("#productName").val();
		var productDiscription = $("#productDiscription").val();
		
		if(productCode == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".yes,.No").hide();
			$(".message").html("Please Enter Product Code").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			setTimeout(function() {
				$('.confirmBox').fadeOut('fast');
			}, 2000);
		}else if(productName == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter Product Name").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);
		}else if(productDiscription == ""){
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
				$(".yes,.No").hide();
				$(".message").html("Please Enter product Discription").delay(3000).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
				setTimeout(function() {
					$('.confirmBox').fadeOut('fast');
				}, 2000);	
		}else{
			var data = $("form").serialize();
			//alert(data);
			$.ajax({
				url: "./action_ProductDetails.php",
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
							window.location.href = "add_product.php";      
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
				var alertText = "In-Active";
			}else{
				var alertText = "Active";
			}
			var alertMsg = 'Do you want to ' +alertText+ ' this product ?';
			$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
			$(".message").text(alertMsg).css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
			$(".yes,.No").show();
			$(".yes").click(function(){
				var act = "delete";
				var data = "data="+id+"&action="+act;
				//alert(data);
				$.ajax({
						url: "./action_ProductDetails.php",
						type: "POST",
						data: data,
						success: function(rel){
						//alert(rel);
						var obj = jQuery.parseJSON(rel);
						if(obj.result=="TRUE"){
						$(".confirmBox").css({'display':'block',"position":'fixed','top':'10px','height':'50px'});
						$(".yes,.No").hide();
						$(".message").html(obj.message).delay(30000).fadeOut("slow").css({'fontSize':'16px','textAlign':'left','marginLeft':'15px','marginTop':'15px','fontWeight':'normal'});
						setTimeout(function() {
							$('.confirmBox').fadeOut('fast');
							window.location.href = "add_product.php";      	
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
	window.location.href = "add_product.php";  
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