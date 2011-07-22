<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Event</h2>
<script type="text/javascript" src="<?php echo base_url()?>css/datetimepicker_css.js"></script>

<?php 
foreach($event as $event_row):
?>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('event/update_event')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
	<li><label for="txtName">Name: </label>
	<input id="name" name="name"  type="text" value="<?=$event_row->name;?>" /> 
	</li>		

<li><label for="date">Starts On: </label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text" value="<?=$event_row->starts_on;?>" >
    <img src="<?=base_url()?>images/calender_images/cal.gif" onclick="javascript:NewCssCal ('date-pick','yyyyMMdd','arrow',true,'24',true)"   style="cursor:pointer"/>
	<p class="error clear"></p>
</li>
<li><label for="date">Ends On: </label>
	<input name="date-pick-ends" class="date-pick" id="date-pick-ends" type="text" value="<?=$event_row->ends_on;?>" >
    <img src="<?=base_url()?>images/calender_images/cal.gif" onclick="javascript:NewCssCal ('date-pick-ends','yyyyMMdd','arrow',true,'24',true)"   style="cursor:pointer"/>
	<p class="error clear"></p>
</li>
<li><label for="date">Place: </label>
	<input name="place"  id="place" type="text" value="<?=$event_row->place;?>" >
	<p class="error clear"></p>
</li>
<li><label for="date">Type: </label>
<select id="type" name="type" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<option value="process" <?php if($event_row->type == 'process'){?> selected="selected "<?php } ?>>process</option> 
	<option value="curriculam" <?php if($event_row->type == 'curriculam'){?> selected="selected "<?php } ?>>curriculam</option> 
    <option value="teacher" <?php if($event_row->type == 'teacher'){?> selected="selected "<?php } ?>>teacher</option> 
</select>
</li>
<?php endforeach;?>
 </ul>
 <ul>
<li>
<input type="hidden" name="root_id" id="root_id" value="<?=$event_row->id;?>">
<input  id="btnSubmit" class="button green" type="submit" value="Edit  Event"  />
<a href="<?=site_url('event/index')?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<script>
function validate()
{
if(document.getElementById("city").value == '-1')
	{		
		alert("Select a City");
		return false;
	}
if(document.getElementById("name").value == '')
	{
		alert("Name missing");
		return false;
	}
if(document.getElementById("date-pick").value == '')
	{
		alert("Start Date missing");
		return false;
	}
if(document.getElementById("date-pick-ends").value == '')
	{
		alert("End Date missing");
		return false;
	}
if(document.getElementById("place").value == '')
	{
		alert("Place missing");
		return false;
	}
if(document.getElementById("type").value == '-1')
	{
		alert("Select Event Type");
		return false;
	}
}
</script>

