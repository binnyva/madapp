<?php
$title = 'Adding Volunteers to '.$batch_name.' Batch';
$this->load->view('layout/header', array('title'=>$title)); ?>

<div id="train-nav">
<ul>
<?php if($this->session->userdata("active_center")) { ?>
<li id="train-prev"><a href="<?php echo site_url('batch/index/center/'.$this->session->userdata("active_center"))?>">&lt; Manage Batches</a></li>
<li id="train-top"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">^ Manage Center</a></li>
<?php } else { ?>
<li id="train-prev"></li>
<li id="train-top"><a href="<?php echo site_url('center/manageaddcenters')?>">^ Manage Center</a></li>
<?php } ?>
</ul></div><br />

<form action="<?php echo site_url("batch/add_volunteers_action") ?>" method="post">

<table>
<tr>
<?php
$level_count = 0;
foreach($levels_in_center as $level) { 
	$level_count++;
?>
<td width="200"><h3><?php echo $level->grade . ' ' . $level->name ?></h3>

<select name="teachers_in_level[<?php echo $level->id ?>][]" id="teachers_in_level_<?php echo $level->id ?>" multiple="multiple">
<?php foreach($level_teacher[$level->id] as $teacher_id=>$value) { // Show the selected volunteers first.
	if(isset($all_teachers[$teacher_id])) {
?>
<option value="<?php echo $teacher_id ?>" selected="selected"><?php echo $all_teachers[$teacher_id] ?></option>
<?php
	}
}

// Now show the rest of the volunteers...
foreach($all_teachers as $id=>$name) {
	//Don't show the row if its selected - we have already shown it...
	if(isset($level_teacher[$level->id][$id])) continue;
?>
<option value="<?php echo $id ?>"><?php echo $name ?></option>
<?php } ?>
</select><br />
<input name="filter[]" class="filter-multiselect" target-field="teachers_in_level_<?php echo $level->id ?>" value="" placeholder="Filter..." />
<br /><br />

<label for="volunteer_requirement[<?php echo $level->id ?>]">Extra Volunteers<br /> Required</label>
<input type="text" size="2" name="volunteer_requirement[<?php echo $level->id ?>]" value="<?php 
	echo empty($volunteer_requirement[$level->id]) ? 0 : $volunteer_requirement[$level->id] ?>" />
</td>
<?php 
if($level_count % 4 == 0) print "</tr><tr>";
} ?>
</tr></table><br />
<p class="with-icon info">To select multiple volunteers, use Ctrl+Click</p>
<br />

<?php 
echo form_hidden('batch_id', $batch->id);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', "Save", 'class="green button primary"');
?>
</form><br />

<a href="<?php echo site_url('batch/index/center/'.$center_id) ?>">See All Batches</a>
<script type="text/javascript" src="<?php echo base_url()?>js/libraries/filter-multiselect.js"></script>

<?php $this->load->view('layout/footer');

