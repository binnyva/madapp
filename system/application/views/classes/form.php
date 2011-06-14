<?php $this->load->view('layout/header', array('title'=>'Edit Class on '. date('jS M Y, H:i A', strtotime($class_details['class_on'])))); ?>

<form action="<?php echo site_url('classes/edit_class_save') ?>" class="form-area" method="post">

<?php for($i=0; $i<count($class_details['teachers']); $i++) {
	$class = $class_details['teachers'][$i];
	// You don't get to edit others stuff if you don't have super privilages.
	$edit = false;
	if($class['user_id'] == $this->session->userdata('id') or $this->user_auth->get_permission('class_edit_class'))
		$edit = true;
	
	if($edit) echo form_hidden('user_id['.$i.']', $class['user_id']);
?>
<label for='user_id[<?php echo $i ?>]'>Teacher</label>
<span class="form-element-holder"><strong><?php echo $teachers[$class['user_id']] ?></strong></span><br />


<label for='substitute_id[<?php echo $i ?>]'>Substitue</label>
<?php 
if($edit) echo form_dropdown('substitute_id['.$i.']', $substitutes, $class['substitute_id']); 
else echo $substitutes[$class['substitute_id']];
?><br />

<label for='status[<?php echo $i ?>]'>Status</label>
<?php 
if($this->user_auth->get_permission('class_edit_class')) $possible_statuses = $statuses;
else $possible_statuses = $statuses = array('projected'	=> 'Projected', 'confirmed'	=> 'Confirmed');

if($edit) echo form_dropdown('status['.$i.']', $possible_statuses, $class['status']); 
else echo $statuses[$class['status']];
?><br />

<?php echo form_hidden('user_class_id['.$i.']', $class['id']); ?>
<br />
<?php } ?>

<?php if(date('Y-m-d H:i:s') > $class_details['class_on']) { ?>
<label for="lesson_id">Feedback</label>
<?php echo form_dropdown('lesson_id', $all_lessons, $class_details['lesson_id']); ?><br />
<br />
<?php } ?>

<?php 
echo form_hidden('class_id', $class_details['id']);
echo form_hidden('project_id', 1);
echo '<label for="action">&nbsp;</label>' . form_submit('action', 'Edit');
?>
</form>

<?php $this->load->view('layout/footer'); ?>
