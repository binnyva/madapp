<?php 
$this->load->view('layout/css');

$exam_name=$exam_name->result_array();
foreach($exam_name as $row) {
	$exam_name=$row['name'];
}
?>

<style type="text/css">
div.ans {
	float:left;
	text-align:left;
	margin-top:5px;
}
div.field {
	width:300px;
}
</style>

<form id="formEditor" class="mainForm clear" action="<?php echo site_url('user/adduser')?>" method="post" onsubmit="return validate();" style="width:500px;" >
<fieldset class="clear" style="margin-top:50px;width:300px;margin-left:30px;">

	<div class="field clear"> 
		<label for="txtName">Exam Name : </label>
		<div class="ans"><?php echo $exam_name; ?> </div>
                      
	</div>
	
	<div class="field clear">
		<label for="txtName">Subject Name : </label>
		<div class="ans"><?php 
		$sub_name=$sub_name->result_array();
		foreach($sub_name as $row) { ?>
		<?php echo $row['name']; ?><br />
		<?php } ?></div>
	</div>
	
	<div class="field clear">
		<label for="txtName">Student Name : </label>
		<div class="ans"><?php
		$contents=$contents->result_array();
		foreach($contents as $row) { ?>
		<?php echo $row['name']; ?><br />
		<?php } ?></div>
	</div>
</fieldset>
</form>
      