<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Event</h2>
<script type="text/javascript" src="<?php echo base_url()?>css/datetimepicker_css.js"></script>

<?php 
foreach($event as $event_row):
?>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('task/update_task')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
	<li><label for="txtName">Name: </label>
	<input id="name" name="name"  type="text" value="<?php echo $event_row->name;?>" /> 
	</li>		


<li><label for="date">Credit: </label>
	<input name="credit"  id="credit" type="text" value="<?php echo $event_row->credit;?>" >
	<p class="error clear"></p>
</li>
<li><label for="date">Type: </label>
<select id="type" name="type" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<option value="1" <?php if($event_row->vertical == 'hr'){?> selected="selected "<?php } ?>>HR</option> 
	<option value="2" <?php if($event_row->vertical == 'pr'){?> selected="selected "<?php } ?>>PR</option> 
    <option value="3" <?php if($event_row->vertical == 'eph'){?> selected="selected "<?php } ?>>EPH</option> 
    <option value="4" <?php if($event_row->vertical == 'cr'){?> selected="selected "<?php } ?>>CR</option> 
	<option value="5" <?php if($event_row->vertical == 'finance'){?> selected="selected "<?php } ?>>Finance</option> 
    <option value="6" <?php if($event_row->vertical == 'ops'){?> selected="selected "<?php } ?>>Operations</option> 
</select>
</li>
<?php endforeach;?>
 </ul>
 <ul>
<li>
<input type="hidden" name="root_id" id="root_id" value="<?php echo $event_row->id; ?>">
<input  id="btnSubmit" class="button green" type="submit" value="Edit Task" />
<a href="<?php echo site_url('task/index') ?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<script type="text/javascript">
function validate() {
	if(document.getElementById("name").value == '') {
		alert("Name missing");
		return false;
	}

	if(document.getElementById("credit").value == '') {
		alert("Credit missing");
		return false;
	}
	if(document.getElementById("type").value == '-1') {
		alert("Select  Type");
		return false;
	}
}
</script>
