<?php
$title = 'Manage ' . $center_name;
$this->load->view('layout/header',array('title'=>$title));

function showMessage($count, $message, $type='') {
	if($message) $message = "($message)";
	
	$threshold = 0; // For most cases, the threshold is 0. We just need one item.
	if($type == 'kids') $threshold = 29;
	if($type == 'volunteers') $threshold = 29;
	
	if($count > $threshold) echo '<span class="success with-icon" style="color:darkgreen;">Completed ' . $message . '</span>'; 
	else echo '<span class="error with-icon">Incomplete ' . $message . '</span>';
}

?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>
</div>

<div id="main">
<table id="tableItems" class="clear data-table info-table-box" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Step #</th>
	<th class="colName left sortable">Task</th>
    <th class="colStatus sortable">Status</th>
</tr>
</thead>
<tbody>

<?php if($this->user_auth->get_permission('center_edit')) { ?>
<tr><td>1</td>
<td><a class="thickbox popup" href="<?php echo site_url('center/popupEdit_center/'.$center_id); ?>">Edit Center Details</a></td>
<td><?php showMessage($details['center_head_id'], ''); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('user_index')) { ?>
<tr><td>2</td>
<td><a href="<?php echo site_url('user/view_users'); ?>">Manage Volunteers</a></td>
<td><?php showMessage($details['total_volunteer_count'], $details['total_volunteer_count'] . " Volunteers", 'volunteers'); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('kids_index')) { ?>
<tr><td>3</td>
<td><a href="<?php echo site_url('kids/manageaddkids'); ?>">Manage Kids</a></td>
<td><?php showMessage($details['kids_count'], $details['kids_count'] . " Kids", 'kids'); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('level_index')) { ?>
<tr><td>4</td>	
<td><a href="<?php echo site_url('level/index/center/'.$center_id); ?>">Manage Class Sections</a></td>
<td><?php showMessage($details['level_count'], $details['level_count'] . " Class Sections"); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { ?>
<tr><td>5</td>	
<td><a href="<?php echo site_url('batch/index/center/'.$center_id); ?>">Manage Batches</a></td>
<td><?php showMessage($details['batch_count'], $details['batch_count'] . " Batchs/".$details['teacher_count'] . " volunteers assigned"); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { ?>
<tr><td>6</td>	
<td><a href="<?php echo site_url('batch/level_assignment/'.$center_id); ?>">Batch/Class Assignment</a></td>
<td></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { ?>
<tr><td>7</td>	
<td><a href="<?php echo site_url('classes/assign/'.$center_id); ?>">Assign Tecahers</a></td>
<td></td></tr><?php } ?>

</table>

<br /><br />
<?php if($this->user_auth->get_permission('center_delete')) { ?><a href="<?php echo site_url("center/deletecenter/".$center_id); ?>" class="confirm delete with-icon">Delete <?php echo $center_name ?> Center</a><?php } ?>
</div>

<?php
$this->load->view('layout/footer');
