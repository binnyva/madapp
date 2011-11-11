<?php
$this->load->view('layout/header', array('title'=>'Classes')); ?>
<div id="head" class="clear"><h1>Classes</h1></div>

<table id="main" class="data-table">
<tr><th>Center</th><th>Class</th><th>Time</th><th>Teacher</th><th>Substitute</th><th>Status</th><th>Action</th></tr>
<?php foreach($all_classes as $class) { ?>
<tr>
<td><?php echo $class->center_name; ?></td>
<td><?php echo $class->level_name ?></td>
<td><?php echo date('M d\<\s\u\p\>S\<\/\s\u\p\>(D), h:i A', strtotime($class->class_on)); ?></td>
<td><?php echo $all_users[$class->user_id] ?></td>
<td><?php echo ($class->substitute_id) ? $all_users[$class->substitute_id] : ''; ?></td>
<td><?php echo ucfirst($class->status) ?></td>
<td><a href="<?php echo site_url('classes/edit_class/'.$class->class_id); ?>" class="edit with-icon">Edit</a></td>
</tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer'); ?>
