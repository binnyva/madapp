<?php 
$this->load->view('layout/thickbox_header'); 
$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

if(!$batch['id']) $batch = array(
	'id'			=> 0,
	'day'			=> 0,
	'class_time'	=> '16:00:00',
	'batch_head_id'	=> 0,
	'center_id'		=> $batch['center_id'],
	);
?>


<div id="head" class="clear"><h1><?php echo $action . ' Batch in ' . $center_name ?></h1></div>

<form action="<?php echo site_url('batch/'.strtolower($action).'_action'); ?>" class="form-area" method="post">
<ul class="form city-form">
<li>
<label for="day">Day</label>
<?php echo form_dropdown('day', $day_list, $batch['day']); ?><br />
</li>
<li>
<label for='class_time'>Time</label>
<input type="text" name="class_time" value="<?php echo set_value('class_time', $batch['class_time']); ?>" /><br />
</li>
<li>
<label for='batch_head_id'>Mentor</label>
<?php echo form_dropdown('batch_head_id', $batch_volunters, $batch['batch_head_id']); ?><br />
</li>

<li>
<label for="subjects">Subjects:</label>
<select id="subjects" name="subjects[]">
<?php foreach($all_subjects as $id=>$name) { ?>
<option value="<?php echo $id; ?>" <?php 
	if(isset($batch['selected_subjects']) and in_array($id, $batch['selected_subjects'])) echo 'selected'; 
?>><?php echo $name; ?></option> 
<?php } ?>
</select>
</li>

<li>
<label>Levels</label><br />
<?php
foreach($all_levels as $level) { ?>
	<input type="checkbox" name="batch_level_connection[<?php echo $batch['id'] ?>][<?php echo $level->id ?>]" id="batch-<?php echo $batch['id'] ?>-level-<?php echo $level->id ?>" <?php
			foreach($connected_levels as $level_id) {
				if($level->id == $level_id) {
					print "checked='checked'";
				}
			}
		?> />
	<label for="batch-<?php echo $batch['id'] ?>-level-<?php echo $level->id ?>"><?php echo $level->grade . ' ' . $level->name; ?></label><br />
<?php } ?>
</li>

<?php 
echo form_hidden('id', $batch['id']);
echo form_hidden('center_id', $batch['center_id']);
echo form_hidden('project_id', 1);
echo "<label for='action'>&nbsp;</label>"; echo form_submit('action', $action);
?>
</form>

