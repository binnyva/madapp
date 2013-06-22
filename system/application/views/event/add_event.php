<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Event</h2>
<script type="text/javascript" src="<?php echo base_url()?>css/datetimepicker_css.js"></script>

<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('event/insert_event')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
<li><label for="name">Name: </label>
	<input id="name" name="name"  type="text" /> 
</li>

<li><label for="description">Description: </label>
	<textarea name="description" rows="5" cols="30"></textarea> 
</li>

<li><label for="date">Starts On: </label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text"> 
    <img src="<?=base_url()?>images/calender_images/cal.gif" onclick="javascript:NewCssCal ('date-pick','yyyyMMdd','arrow',true,'24',true)"   style="cursor:pointer"/>
	<p class="error clear"></p>
</li>
<!--
<li><label for="date">Ends On: </label>
	<input name="date-pick-ends" class="date-pick" id="date-pick-ends" type="text">
        <img src="<?=base_url()?>images/calender_images/cal.gif" onclick="javascript:NewCssCal ('date-pick-ends','yyyyMMdd','arrow',true,'24',true)"   style="cursor:pointer"/>
	<p class="error clear"></p>
</li>
-->
<li><label for="date">Place: </label>
	<input name="place" id="place" type="text">
	<p class="error clear"></p>
</li>
<li><label for="date">Type: </label>
<select id="type" name="type"> 
	<option value="others">Other</option> 
	<option value="process">Process Training</option> 
	<option value="curriculum">Curriculum Training</option> 
    <option value="teacher">Teacher Training I</option>
    <option value="teacher2">Teacher Training II</option> 
	<option value="avm">Center Circle</option>
	<option value="coreteam_meeting">Core Team Meeting</option> 
	<option value="admin_meeting">Admin Meeting</option>
	<option value="monthly_review">Monthly Review</option>
</select>
</li>
</ul>

<ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="+ Add New Event" />
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

