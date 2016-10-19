<?php 
require_once('includes/DBInterface.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');

if(!empty($_REQUEST['state_id'])){
	$state_id = $_REQUEST['state_id'];
	if($state_id == 'others'){
		$sid = 	"";
	}else{
		$sid = $_REQUEST['state_id'];
	}
	$cityList = $db->select_city($sid);
	?>
	<div class="col-md-9">
	<select class="form-control select2me" data-placeholder="Select City" name="city" id="city_new" onchange="CheckCity(this.value);" required>
		<option value="">--Select City--</option>
		<?php foreach($cityList['CityList'] as $key=>$values){ ?>
		<option value="<?php echo $values['city_id']; ?>"><?php echo $values['city_name']; ?></option>
        <?php } ?>
		<option value="others">others</option>
	</select>
	
	</div>
	<?php
}

?>