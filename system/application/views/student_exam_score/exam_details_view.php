<?php 
$this->load->view('layout/css');

$exam_name=$exam_name->result_array();
foreach($exam_name as $row) {
	$exam_name=$row['name'];
}
?>

<form id="formEditor" class="mainForm clear" action="<?php echo site_url('user/adduser')?>" method="post" onsubmit="return validate();" style="width:500px;" >
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">

	<div class="field clear" style="width:500px;"> 
		<label for="txtName">Exam Name : </label>
		<div><?php echo $exam_name; ?> </div>
                      
	</div>
	
	<div class="field clear" style="width:500px;"> 
		<label for="txtName">Subject Name : </label>
		<?php 
		$sub_name=$sub_name->result_array();
		foreach($sub_name as $row) { ?>
		<div><?php echo $row['name']; ?></div>
		<?php } ?>
	</div>
	<div class="field clear" style="width:500px;"> 
		<label for="txtName">Student Name : </label>
		<?php
		$contents=$contents->result_array();
		foreach($contents as $row) { ?>
		<div><?php echo $row['name']; ?></div>
		<?php } ?>
	</div>
</fieldset>
</form>
      