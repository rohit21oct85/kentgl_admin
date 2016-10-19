<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse" style="position: fixed;">
			<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li class="sidebar-toggler-wrapper">
					<div class="sidebar-toggler">
					</div>
				</li>
			
				
				<li class="start " style="margin-top:30px">
							<a href="add_user.php">
							<i class="icon-user-following"></i>
							<span class="badge badge-success badge-roundless"><?php if($_SESSION["rolename"]=='admin'){ echo 'Add';};?></span><span class="title">Manage User</span></a>
						</li>
						<?php if($_SESSION["rolename"]=='admin'){ ?>
							<li>
							<a href="add_role.php">
							<i class="fa fa-cubes"></i>
							<span class="badge badge-success badge-roundless">Add</span>  <span class="title"> Manage Role </span></a>
							</li>	

							<li>
								<a href="add_product.php">
								<i class="fa fa-folder-open"></i>
								<span class="badge badge-success badge-roundless"> Add</span> <span class="title"> Manage Products </span></a>
							</li>
							<li class="show">
								<a href="add_distributor.php">
								<i class="icon-user-following"></i>
								<span class="badge badge-success badge-roundless"> Add</span> <span class="title"> Manage Distributor </span></a>
							</li>
						
							<li class="show">
								<a href="manage_groupleader.php">
								<i class="icon-user-following"></i>
								<span class="badge badge-success badge-roundless"> Add</span> <span class="title"> Manage GL </span></a>
							</li>
							
							<?php
						}?>
						
												
						<li class="last ">
							<a href="javascript:;">
							<i class="icon-briefcase"></i>
							<span class="title">Report</span>
							<span class="arrow "></span>
							</a>
							<ul class="sub-menu">
								<li>
								<a href="daily_sale_report.php">
									Daily Sales Report
								</a>
								</li>
								<li>
								<a href="download_SalesReport.php">
									Download Sales Report
								</a>
								</li>
								<li>
								<a href="daily_LoginReport.php">
									Daily Login Report
								</a>
								</li>
								<li>
								<a href="download_LoginReport.php">
									Download Login Report
								</a>
								</li>
								<li>
								<a href="waterTestReport.php">
									Water Test Report
								</a>
								</li>
							</ul>
						</li>
						
				
			</ul>
			
		</div>
	</div>
	