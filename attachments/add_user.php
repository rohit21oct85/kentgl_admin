<?php 
include('header.php');
//session_start();

if($_SESSION["adminx"]!="")
{
?>

<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<?php include('sidebar.php');?>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN STYLE CUSTOMIZER -->
			
			<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title">
			<?php if($_GET['edit_id']!='')
				{
				echo 'Update User';	
				}
				else
				{
				echo 'Add User';	
				}
				if($_GET['status']!='' && $_GET['u_id']!='' )
				{
					$u_id       = $_GET['u_id'];
					$status    = $_GET['status'];
					
					$delete_usr =delete_user($u_id,$status);
				}
				?>
			
			</h3>
			<div class="page-bar hide">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="index_ffms.html">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
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
				<div class="portlet-body form" style="padding:12px 20px 0 20px!important;">
				<?php
				$user_id =$_SESSION["ID"];
				/*****Start Report to List ***/
				$repot_to =report_to();
				/*****End Report to List ***/
				/*****Start Add user ***/
				if(isset($_POST['add_user']))
				{
					$_POST['name'];
					$_POST['username'];
					$_POST['email'];
					$_POST['mobile'];
					$_POST['emp_code'];
					$_POST['role'];
					$_POST['report_to'];
					$_POST['state'];
					$_POST['city'];
					$_POST['status'];
					$_POST['entry_date'];
					$result = add_users($_POST,"submit");
					
				}
				/*****End Add user ***/
				/*****Start update user ***/
				if(isset($_POST['update_user']))
				{   $_POST['u_id'];
					$_POST['name'];
					$_POST['username'];
					$_POST['email'];
					$_POST['mobile'];
					$_POST['emp_code'];
					$_POST['role'];
					$_POST['report_to'];
					$_POST['state'];
					$_POST['city'];
					$_POST['status'];
					$_POST['entry_date'];
					$result_update = update_user($_POST,"submit");
					print_r($result_update['msg']);
				}
				/*****End update user ***/

				$user_id =$_SESSION["ID"];
				/*****Start Edit user ***/
				if($_GET['edit_id']!='')
				{
					$reslt = edit_usr($data);
				
				}
				/*****End edit user ***/
				/*****Start User List***/
				$all_lis = user_list();
				/*****Start User List***/
 				?>
				
										<!-- BEGIN FORM-->
										<form action="" class="form-horizontal" method="POST">
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Name</label>
															<div class="col-md-9">
																<input type="text" class="form-control" value="<?php echo $reslt['fstr_employee_name']?>" placeholder="Name" name="name">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">User Name</label>
															<div class="col-md-9">
																<input type="text" class="form-control" value="<?php echo $reslt['ftsr_user_name']?>" placeholder="User Name" name="username">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>
														<!--<div class="form-group hide">
															<label class="control-label col-md-3">Password</label>
															<div class="col-md-9">
																<input type="password" class="form-control" placeholder="Password" name="password">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>-->
														<div class="form-group">
															<label class="control-label col-md-3">Email</label>
															<div class="col-md-9">
																<input type="email" class="form-control" value="<?php echo $reslt['fstr_email']?>" placeholder="Email" name="email">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Mobile</label>
															<div class="col-md-9">
																<input type="text" class="form-control" value="<?php echo $reslt['fstr_mobile']?>" placeholder="Mobile" name="mobile">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Employee Code</label>
															<div class="col-md-9">
																<input type="text" class="form-control" value="<?php echo $reslt['fstr_employee_code']?>" placeholder="Employee Code" name="emp_code">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>
														</div>
													
												
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Role</label>
															<div class="col-md-9">
																<select class="form-control" name="role">
																
																	<option>Select Role</option>
																	<option value="1" <?php if($reslt['fsnum_eployee_role']=='1'){echo 'selected';}?>>Management</option>
																	<option value="2" <?php if($reslt['fsnum_eployee_role']=='2'){echo 'selected';}?>>RM</option>
																	<option value="3" <?php if($reslt['fsnum_eployee_role']=='3'){echo 'selected';}?>>ASM</option>
																	<option value="4" <?php if($reslt['fsnum_eployee_role']=='4'){echo 'selected';}?>>CES</option>
																	<option value="5" <?php if($reslt['fsnum_eployee_role']=='5'){echo 'selected';}?>>SM</option>
																	 
																	
																</select>
																<span class="help-block hide">
																Select User Role. </span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Reports To</label>
															<div class="col-md-9">
																<select class="form-control" name="report_to">
																    <option>Select Report to</option>
																	<?php  while ( $db_field = mysql_fetch_array($repot_to) ) {?>
																	<option value="<?php echo $db_field['fnum_userId']?>"<?php if($reslt['parent_id']==$db_field['fnum_userId']){echo 'selected';}?> ><?php echo $db_field['fstr_employee_name']?></option>
																	<?php } ?>
																</select>
																<span class="help-block hide">
																Select User Role. </span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-md-3">State</label>
															<div class="col-md-9">
																<input type="text" class="form-control" value="<?php echo $reslt['ffms_state']?>" placeholder="State" name="state">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">City</label>
															<div class="col-md-9">
																<input type="text" class="form-control" value="<?php echo $reslt['ffms_city']?>" placeholder="City" name="city">
																<span class="help-block hide">
																This is inline help </span>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Active/Inactive</label>
															<div class="col-md-9">
																<select class="form-control" name="status">
																	<option value="1">Yes</option>
																	<option value="0">No</option>
																</select>
																<span class="help-block hide">
																Select User Role. </span>
															</div>
														</div>
													</div>
													
												
											</div>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-6"></div>
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<!--<input type="hidden"  value="<?php //$_SESSION['ID']?>" name="user_id">-->
																<input type="hidden"  value="<?php echo $_GET['edit_id'] ?>" name="u_id">
																<input type="submit" class="btn green" value="Submit" name="<?php if($_GET['edit_id']!=''){echo 'update_user';}else{echo 'add_user';}?>">
																<?php if($_GET['edit_id']!=''){?>
																<a href="add_user.php"><button type="button" class="btn default">Cancel</button></a>
																<?php }else{?>
																<button type="reset" class="btn default">Cancel</button>
																<?php }?>
															</div>
														</div>
													</div>
													
												</div>
											</div>
										</form>
										<!-- END FORM-->
									</div>
			</div>
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
						.btn-group.tabletools-btn-group.pull-right{display:none!important;}
						.dataTables_info{display:none;}
						</style>
						
						<div class="portlet-body">
							
							<table class="table table-striped table-bordered table-hover" id="sample_2">
							
							<thead>
							<tr>
								<th>
									 Employee Code
								</th>
								<th>
									  Name
								</th>
								<th>
									E-mail_id
								</th>
								<th>
									 City
								</th>
								<th>
									 Edit
								</th>
								<th>
									 Action
								</th>
							</tr>
							</thead>
							<thead>
							<tr>
								<td>
									
									<select class="form-control input-small select2me" data-placeholder="Select..." id="filter">
											 <option value=""></option>
											 <?php 
											 $all_listr = user_list();
											 $i=1;
											 while ( $db_fieldr = mysql_fetch_array($all_listr) ) { ?>
											 <option value="<?php echo $i; ?>"><?php echo $db_fieldr['fstr_employee_code']?></option>
											 <?php $i++; }?>
									 </select>
								</td>
								<td>
									<select class="form-control input-small select2me" data-placeholder="Select..." id="filter1">
											<option value=""></option>
											<?php 
											 $all_listr = user_list();
											  $i=1;
											 while ( $db_fieldr = mysql_fetch_array($all_listr) ) {?>
											 <option value="<?php echo  $i; ?>"><?php echo $db_fieldr['fstr_employee_name']?></option>
											 <?php $i++; }?>
									</select>
								</td>
								<td>
									<select class="form-control input-small select2me" data-placeholder="Select..." id="filter2">
											 <option value=""></option>
											 <?php 
											 $all_listr = user_list();
											  $i=1;
											 while ( $db_fieldr = mysql_fetch_array($all_listr) ) {?>
											 <option value="<?php echo  $i; ?>"><?php echo $db_fieldr['fstr_email']?></option>
											 <?php  $i++; }?>
									 </select>
								</td>
								<td>
									 <select class="form-control input-small select2me" data-placeholder="Select..." id="filter3">
											 <option value=""></option>
											 <?php 
											 $all_listr = user_list();
											  $i=1;
											 while ( $db_fieldr = mysql_fetch_array($all_listr) ) {?>
											 <option value="<?php echo  $i; ?>"><?php echo $db_fieldr['ffms_city']?></option>
											 <?php  $i++; }?>
									 </select>
								</td>
								<td></td>
								<td></td>
															
							</tr>
							</thead>
							
							<tbody>
							
							
							
							<?php while ( $db_field = mysql_fetch_array($all_lis) ) {?>
							<tr>
								<td>
									<?php echo $db_field['fstr_employee_code'];?>
								</td>
								<td>
									 <?php echo $db_field['fstr_employee_name'];?>
								</td>
								<td>
									 <?php echo $db_field['fstr_email'];?>
								</td>
								<td class="center">
									<?php echo $db_field['ffms_city'];?>
								</td>
								<td>
									<a class="edit" onclick="return confirm('Are you update this user?');" href="add_user.php?edit_id=<?php echo $db_field['fnum_userId'];?>" id="edit_user">
									Edit </a>
									
								</td>
								<td>
									<a class="delete" <?php if($db_field['fnum_isactive']=='0'){?>onclick="return confirm('Are you sure you want to actvate this user?');"<?php }if($db_field['fnum_isactive']=='1'){ ?>onclick="return confirm('Are you sure you want to inactvate this user?');"<?php }?> href="add_user.php?u_id=<?php echo $db_field['fnum_userId'];?>&status=<?php if($db_field['fnum_isactive']=='1'){echo '0';}if($db_field['fnum_isactive']=='0'){echo '1';}?>">
									<?php if($db_field['fnum_isactive']=='1'){echo 'Inactivate';}if($db_field['fnum_isactive']=='0'){echo 'Activate';}?> </a>
								</td>
							</tr>
							<?php }?>
							
							
							</tbody>
							</table>
							
							
						
							
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
		
		
		</div>
	</div>
	<!-- END CONTENT -->
	<!-- BEGIN QUICK SIDEBAR -->
	<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
	<div class="page-quick-sidebar-wrapper">
		<div class="page-quick-sidebar">
			<div class="nav-justified">
				<ul class="nav nav-tabs nav-justified">
					<li class="active">
						<a href="#quick_sidebar_tab_1" data-toggle="tab">
						Users <span class="badge badge-danger">2</span>
						</a>
					</li>
					<li>
						<a href="#quick_sidebar_tab_2" data-toggle="tab">
						Alerts <span class="badge badge-success">7</span>
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						More<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<a href="#quick_sidebar_tab_3" data-toggle="tab">
								<i class="icon-bell"></i> Alerts </a>
							</li>
							<li>
								<a href="#quick_sidebar_tab_3" data-toggle="tab">
								<i class="icon-info"></i> Notifications </a>
							</li>
							<li>
								<a href="#quick_sidebar_tab_3" data-toggle="tab">
								<i class="icon-speech"></i> Activities </a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="#quick_sidebar_tab_3" data-toggle="tab">
								<i class="icon-settings"></i> Settings </a>
							</li>
						</ul>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
						<div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
							<h3 class="list-heading">Staff</h3>
							<ul class="media-list list-items">
								<li class="media">
									<div class="media-status">
										<span class="badge badge-success">8</span>
									</div>
									<img class="media-object" src="../../assets/admin/layout/img/avatar3.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Bob Nilson</h4>
										<div class="media-heading-sub">
											 Project Manager
										</div>
									</div>
								</li>
								<li class="media">
									<img class="media-object" src="../../assets/admin/layout/img/avatar1.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Nick Larson</h4>
										<div class="media-heading-sub">
											 Art Director
										</div>
									</div>
								</li>
								<li class="media">
									<div class="media-status">
										<span class="badge badge-danger">3</span>
									</div>
									<img class="media-object" src="../../assets/admin/layout/img/avatar4.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Deon Hubert</h4>
										<div class="media-heading-sub">
											 CTO
										</div>
									</div>
								</li>
								<li class="media">
									<img class="media-object" src="../../assets/admin/layout/img/avatar2.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Ella Wong</h4>
										<div class="media-heading-sub">
											 CEO
										</div>
									</div>
								</li>
							</ul>
							<h3 class="list-heading">Customers</h3>
							<ul class="media-list list-items">
								<li class="media">
									<div class="media-status">
										<span class="badge badge-warning">2</span>
									</div>
									<img class="media-object" src="../../assets/admin/layout/img/avatar6.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Lara Kunis</h4>
										<div class="media-heading-sub">
											 CEO, Loop Inc
										</div>
										<div class="media-heading-small">
											 Last seen 03:10 AM
										</div>
									</div>
								</li>
								<li class="media">
									<div class="media-status">
										<span class="label label-sm label-success">new</span>
									</div>
									<img class="media-object" src="../../assets/admin/layout/img/avatar7.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Ernie Kyllonen</h4>
										<div class="media-heading-sub">
											 Project Manager,<br>
											 SmartBizz PTL
										</div>
									</div>
								</li>
								<li class="media">
									<img class="media-object" src="../../assets/admin/layout/img/avatar8.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Lisa Stone</h4>
										<div class="media-heading-sub">
											 CTO, Keort Inc
										</div>
										<div class="media-heading-small">
											 Last seen 13:10 PM
										</div>
									</div>
								</li>
								<li class="media">
									<div class="media-status">
										<span class="badge badge-success">7</span>
									</div>
									<img class="media-object" src="../../assets/admin/layout/img/avatar9.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Deon Portalatin</h4>
										<div class="media-heading-sub">
											 CFO, H&D LTD
										</div>
									</div>
								</li>
								<li class="media">
									<img class="media-object" src="../../assets/admin/layout/img/avatar10.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Irina Savikova</h4>
										<div class="media-heading-sub">
											 CEO, Tizda Motors Inc
										</div>
									</div>
								</li>
								<li class="media">
									<div class="media-status">
										<span class="badge badge-danger">4</span>
									</div>
									<img class="media-object" src="../../assets/admin/layout/img/avatar11.jpg" alt="...">
									<div class="media-body">
										<h4 class="media-heading">Maria Gomez</h4>
										<div class="media-heading-sub">
											 Manager, Infomatic Inc
										</div>
										<div class="media-heading-small">
											 Last seen 03:10 AM
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="page-quick-sidebar-item">
							<div class="page-quick-sidebar-chat-user">
								<div class="page-quick-sidebar-nav">
									<a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>
								</div>
								<div class="page-quick-sidebar-chat-user-messages">
									<div class="post out">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar3.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Bob Nilson</a>
											<span class="datetime">20:15</span>
											<span class="body">
											When could you send me the report ? </span>
										</div>
									</div>
									<div class="post in">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar2.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Ella Wong</a>
											<span class="datetime">20:15</span>
											<span class="body">
											Its almost done. I will be sending it shortly </span>
										</div>
									</div>
									<div class="post out">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar3.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Bob Nilson</a>
											<span class="datetime">20:15</span>
											<span class="body">
											Alright. Thanks! :) </span>
										</div>
									</div>
									<div class="post in">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar2.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Ella Wong</a>
											<span class="datetime">20:16</span>
											<span class="body">
											You are most welcome. Sorry for the delay. </span>
										</div>
									</div>
									<div class="post out">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar3.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Bob Nilson</a>
											<span class="datetime">20:17</span>
											<span class="body">
											No probs. Just take your time :) </span>
										</div>
									</div>
									<div class="post in">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar2.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Ella Wong</a>
											<span class="datetime">20:40</span>
											<span class="body">
											Alright. I just emailed it to you. </span>
										</div>
									</div>
									<div class="post out">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar3.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Bob Nilson</a>
											<span class="datetime">20:17</span>
											<span class="body">
											Great! Thanks. Will check it right away. </span>
										</div>
									</div>
									<div class="post in">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar2.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Ella Wong</a>
											<span class="datetime">20:40</span>
											<span class="body">
											Please let me know if you have any comment. </span>
										</div>
									</div>
									<div class="post out">
										<img class="avatar" alt="" src="../../assets/admin/layout/img/avatar3.jpg"/>
										<div class="message">
											<span class="arrow"></span>
											<a href="#" class="name">Bob Nilson</a>
											<span class="datetime">20:17</span>
											<span class="body">
											Sure. I will check and buzz you if anything needs to be corrected. </span>
										</div>
									</div>
								</div>
								<div class="page-quick-sidebar-chat-user-form">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Type a message here...">
										<div class="input-group-btn">
											<button type="button" class="btn blue"><i class="icon-paper-clip"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
						<div class="page-quick-sidebar-alerts-list">
							<h3 class="list-heading">General</h3>
							<ul class="feeds list-items">
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-info">
													<i class="fa fa-check"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 You have 4 pending tasks. <span class="label label-sm label-warning ">
													Take action <i class="fa fa-share"></i>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 Just now
										</div>
									</div>
								</li>
								<li>
									<a href="#">
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-success">
													<i class="fa fa-bar-chart-o"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 Finance Report for year 2013 has been released.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 20 mins
										</div>
									</div>
									</a>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-danger">
													<i class="fa fa-user"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 You have 5 pending membership that requires a quick review.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 24 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-info">
													<i class="fa fa-shopping-cart"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 New order received with <span class="label label-sm label-success">
													Reference Number: DR23923 </span>
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 30 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-success">
													<i class="fa fa-user"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 You have 5 pending membership that requires a quick review.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 24 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-info">
													<i class="fa fa-bell-o"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 Web server hardware needs to be upgraded. <span class="label label-sm label-warning">
													Overdue </span>
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 2 hours
										</div>
									</div>
								</li>
								<li>
									<a href="#">
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-default">
													<i class="fa fa-briefcase"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 IPO Report for year 2013 has been released.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 20 mins
										</div>
									</div>
									</a>
								</li>
							</ul>
							<h3 class="list-heading">System</h3>
							<ul class="feeds list-items">
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-info">
													<i class="fa fa-check"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 You have 4 pending tasks. <span class="label label-sm label-warning ">
													Take action <i class="fa fa-share"></i>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 Just now
										</div>
									</div>
								</li>
								<li>
									<a href="#">
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-danger">
													<i class="fa fa-bar-chart-o"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 Finance Report for year 2013 has been released.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 20 mins
										</div>
									</div>
									</a>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-default">
													<i class="fa fa-user"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 You have 5 pending membership that requires a quick review.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 24 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-info">
													<i class="fa fa-shopping-cart"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 New order received with <span class="label label-sm label-success">
													Reference Number: DR23923 </span>
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 30 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-success">
													<i class="fa fa-user"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 You have 5 pending membership that requires a quick review.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 24 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-warning">
													<i class="fa fa-bell-o"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 Web server hardware needs to be upgraded. <span class="label label-sm label-default ">
													Overdue </span>
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 2 hours
										</div>
									</div>
								</li>
								<li>
									<a href="#">
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-sm label-info">
													<i class="fa fa-briefcase"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													 IPO Report for year 2013 has been released.
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											 20 mins
										</div>
									</div>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="tab-pane page-quick-sidebar-settings" id="quick_sidebar_tab_3">
						<div class="page-quick-sidebar-settings-list">
							<h3 class="list-heading">General Settings</h3>
							<ul class="list-items borderless">
								<li>
									 Enable Notifications <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF">
								</li>
								<li>
									 Allow Tracking <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="ON" data-off-color="default" data-off-text="OFF">
								</li>
								<li>
									 Log Errors <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF">
								</li>
								<li>
									 Auto Sumbit Issues <input type="checkbox" class="make-switch" data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF">
								</li>
								<li>
									 Enable SMS Alerts <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF">
								</li>
							</ul>
							<h3 class="list-heading">System Settings</h3>
							<ul class="list-items borderless">
								<li>
									 Security Level
									<select class="form-control input-inline input-sm input-small">
										<option value="1">Normal</option>
										<option value="2" selected>Medium</option>
										<option value="e">High</option>
									</select>
								</li>
								<li>
									 Failed Email Attempts <input class="form-control input-inline input-sm input-small" value="5"/>
								</li>
								<li>
									 Secondary SMTP Port <input class="form-control input-inline input-sm input-small" value="3560"/>
								</li>
								<li>
									 Notify On System Error <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF">
								</li>
								<li>
									 Notify On SMTP Error <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF">
								</li>
							</ul>
							<div class="inner-content">
								<button class="btn btn-success"><i class="icon-settings"></i> Save Changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END QUICK SIDEBAR -->
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