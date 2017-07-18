<?php $this->load->view('layout/flatui/header', array('title' => $title)); ?>
<script type="text/javascript">
	var batch_level_user_hirarchy = <?php echo json_encode($batch_level_user_hirarchy); ?>;
	var all_levels = <?php echo json_encode($all_levels); ?>;
	var city_id = <?php echo $_SESSION['city_id']; ?>;
</script>
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/assign.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/classes/assign.css">

<div id="content" class="clear">
<div id="main" class="clear"> 
<div id="head" class="clear">
<h1 class="title"><?php echo $title; ?></h1>

<ul class="text-muted">
<li>Total Teachers in this city: <?php echo count($all_users) ?></li>
<li>Assigned Teacher for this center: <?php echo $assigned_teacher_count; ?></li>
<li>Assigned Teacher in this city: <?php echo count($all_assigned_teachers); ?></li>
<li>Total Unassigned Teacher Count: <?php echo count($all_users) - count($all_assigned_teachers); ?></li>
</ul>

<p>Users who are <strong>bolded</strong> have already been assigned to a class.</p>

<form action="" method="post">
<input type="hidden" name="submit" value="true" />
<input type="submit" name="action" value="Save" class="btn btn-primary" />
<table class="table">
<tr><th>Teacher</th><?php if(!$show_only_batch) { ?><th>Batch</th><?php } ?><th>Class Section</th><th>Subject</th></tr>
<?php foreach($all_users as $user_id => $user_name) { 
	$show_level = 0;
	$show_level_of_batch = $show_only_batch;

	// If we are in the batch assignment mode, If the volunteer is a teacher, make sure they teaches at the right batch - any other batch, don't show.
	if($show_only_batch and isset($user_mapping[$user_id])) {
		$teaches_given_batch = false;
		foreach($user_mapping[$user_id] as $batch_level_info) {
			if(i($batch_level_info, 'batch_id') == $show_only_batch) {
				$show_level = i($batch_level_info, 'level_id');	
				$teaches_given_batch = true;
			}
		}

		if(!$teaches_given_batch) continue; // Don't show this user.
	}
?>
<tr id="teacher-<?php echo $user_id ?>">
<td <?php if(isset($all_assigned_teachers[$user_id])) echo 'class="assigned"'; ?>><span class="name"><?php echo $user_name->name ?></span>
	<?php if($show_only_batch) { ?><input type="hidden" name="batch_id[<?php echo $user_id ?>]" value="<?php echo $show_only_batch ?>" /><?php } ?></td>

<?php if(!$show_only_batch) { // Show batch selection only if we are NOT in batch edit mode ?>
<td><select name="batch_id[<?php echo $user_id ?>]" id="batch-<?php echo $user_id ?>" class="batch">
<option value="0">None</option>
<?php foreach($all_batches as $batch_id => $batch_name) { ?>
<option value="<?php echo $batch_id ?>"<?php
	if(isset($user_mapping[$user_id]))
	foreach($user_mapping[$user_id] as $batch_level_info) {
		if(i($batch_level_info, 'batch_id') == $batch_id) {
			echo ' selected="selected"';
			$show_level = $batch_level_info['level_id'];
			$show_level_of_batch = $batch_id;
		}
	}
	?>><?php echo $batch_name ?></option>
<?php } ?>
</select></td>
<?php } ?>

<td><select name="level_id[<?php echo $user_id ?>]" id="level-<?php echo $user_id ?>">
<option value="0">None</option>
<?php 
if(isset($all_levels[$show_level_of_batch]) and $all_levels[$show_level_of_batch]) {
foreach($all_levels[$show_level_of_batch] as $level_id => $level_name) { ?>
<option value="<?php echo $level_id ?>"<?php
		if($show_level == $level_id) echo ' selected="selected"';
	?>><?php echo $level_name ?></option>
<?php } 
} ?>
</select></td>

<td><select name="subject_id[<?php echo $user_id ?>]">
<?php foreach($all_subjects as $subject_id => $subject_name) { ?>
<option value="<?php echo $subject_id ?>"<?php 
	if($all_users[$user_id]->subject_id == $subject_id) echo ' selected="selected"'; 
	?>><?php echo $subject_name ?></option>
<?php } ?>
</select></td>
</tr>
<?php } ?>

</table>
<input type="submit" name="action" value="Save" class="btn btn-primary" /><br /><br />
</form>

<input type="button" id="new-teacher" value="+ Add New Teacher" class="btn btn-success btn-sm" />

<div id="new-teacher-area">
<form action="" method="post" class="form-area">
<h3>Add Teacher</h3>
<input type="text" name="email" id="email" placeholder="EMail" value="" /><br />
<p id="email-info" class="form-info"></p>
<input type="text" name="phone" id="phone" placeholder="Phone" value="" /><br />
<p id="phone-info" class="form-info"></p>
<input type="text" name="name" id="name" placeholder="Name" value="" /><br />
<p id="name-info" class="form-info"></p>

<input type="button" name="action" id="new-teacher-action" value="+ Add New Teacher" class="btn btn-success btn-sm" /><br />
<p id="form-info" class="form-info"></p>
</form>
</div>

<br /><br /><br />

</div>
</div>
</div>

<?php $this->load->view('layout/flatui/footer');
