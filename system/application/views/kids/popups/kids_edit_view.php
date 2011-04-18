<?php $this->load->view('layout/thickbox_header'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>
<?php
$edt=date('Y')-2;
$sdt=date('Y')-20;
?>
<script>
jQuery(document).ready(function () {
	$('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?> });
});
</script>
<?php
$kids_details=$kids_details->result_array();
foreach($kids_details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
	$center_id=$row['center_id'];
	$birthday = $row['birthday'];
	$birthday = explode("-",$birthday);
	$birthday = $birthday[2]."/".$birthday[1]."/".$birthday[0];
	$description=$row['description'];
	$photo = $row['photo'];
}

?>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('kids/update_kids')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<fieldset class="clear" style="margin-top:50px;margin-left:-30px;">
<div class="field clear">
<label for="selBulkActions">Select Center</label> 
<select id="center" name="center" > 
<option selected="selected" >- Choose -</option> 
	<?php 
	$center = $center->result_array();
	foreach($center as $row)
	{ ?>
	<?php if($center_id==$row['id']) { 
	?>
	<option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
	<?php }else{ ?> 
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } }?>
</select>
</div>

<div class="field clear"> 
	<label for="txtName">Name</label>
	<input id="name" name="name"  type="text"  value="<?php echo $name; ?>"/> 
			
</div>

<div  class="field clear">
	<label for="date">Date of Birth</label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text" value="<?php echo $birthday ; ?>">
	<p class="error clear"></p>
</div>


<div class="field clear">
	<label for="date">Photo</label>
	<?php if($photo) { ?><img src="<?php echo base_url().'pictures/'.$photo; ?>" width="50" style="float:left;" height="50" /><?php } ?>
</div>
<div  class="field clear">
	<label for="date">Change photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</div>
<div class="field clear"> 
	<label for="txtName">Description</label>
	<textarea rows="5" cols="30" id="description" name="description"><?php echo $description; ?></textarea> 
	<p class="error clear"></p>
</div>



<div class="field clear" style="width:550px;">
	<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
	<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Edit" />
	
	<a href="#" class="cancel-button">Cancel</a>
</div>


</fieldset>
</form>
<script> 
function validate() {
	if(document.getElementById("center").value == '-1')
		{	
			alert("Select a Center.");
			return false;
		}
	if(document.getElementById("name").value == '')
		{
			alert("Name missing");
			return false;
		}
}
</script>
<?php $this->load->view('layout/thickbox_footer'); ?>