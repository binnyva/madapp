<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<?php
$user_details=$user->result_array();
foreach($user_details as $row)
{	
	$root_id=$row['id'];
	$name=$row['name'];
	$title=$row['title'];
	$email=$row['email'];
	$phone=$row['phone'];
	$password=$row['password'];
	$center_id=$row['center_id'];
	$city_id=$row['city_id'];
	$project_id=$row['project_id'];
	$user_type=$row['user_type'];
}
?>
<?php
$group_name=$group_name->result_array();
foreach($group_name as $row)
{
	$group_id=$row['id'];
}
?>

<form id="formEditor" class="mainForm clear" action="<?=site_url('user/update_user')?>" method="post" onsubmit="return validate();" style="width:500px;" >
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">

		<div class="field clear" style="width:500px;"> 
                        <label for="txtName">Name : </label>
                        <input id="name" name="name"  type="text"  value="<?php echo $name; ?>"/> 
                      
            </div>
            
            <div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select Group:</label> 
            <select id="group" name="group"> 
            <option selected="selected" >- choose action -</option> 
				<?php 
                $group_details = $group_details->result_array();
                foreach($group_details as $row){ ?>
                <?php if($group_id== $row['id']){ ?>
                <option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
                <?php }else{ ?>
                 <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                <?php } }?>
            </select>
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Position : </label>
                        <input id="position" name="position"  type="text" value="<?php echo $title; ?>" /> 
                      
            </div>
			
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Email : </label>
                        <input id="email" name="email"  type="text"  value="<?php echo $email; ?>"/> 
                      
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Password : </label>
                        <input id="password" name="password"  type="password"  /> 
                      
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Password : </label>
                        <input id="cpassword" name="cpassword"  type="password" /> 
                      
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Phone : </label>
                        <input id="phone" name="phone"  type="text" value="<?php echo $phone; ?>"  /> 
                      
            </div>
            
			<div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select city:</label> 
            <select id="city" name="city" > 
            <option selected="selected" >- choose action -</option> 
				<?php 
                $details = $details->result_array();
                foreach($details as $row) { ?>
                <?php if($city_id== $row['id'] ){?>
                <option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
                <?php }else { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php }} ?>
            </select>
            </div>
            
            <div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select center:</label> 
            <select id="center" name="center"> 
            <option selected="selected" >- choose action -</option> 
				<?php 
                $center = $center->result_array();
                foreach($center as $row){ ?>
                <?php if($center_id==$row['id']){ ?>
                <option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
                <?php } else { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php }} ?>
            </select>
            </div>
            
            
            <div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select project:</label> 
            <select id="project" name="project"> 
            <option selected="selected" >- choose action -</option> 
				<?php 
                $project = $project->result_array();
                foreach($project as $row)
                {
                ?>
                <?php if($project_id==$row['id']) { ?>
                <option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
                <?php } else { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php } }?>
            </select>
            </div>
           
             <div class="field clear" style="width:500px;"> 
                        <label for="txtName">User Type : </label>
                        <input id="type" name="type"  type="text" value="<?php echo $user_type; ?>" /> 
                      
            </div>
            
            <div class="field clear" style="width:550px;"> 
            		<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
     				<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
            </div>
            </fieldset>
            </form>
            
<script language="javascript">
function validate()
{
 if(document.getElementById("password").value == '')
          {
              alert("Password Missing.");
              return false;
          }
       if(document.getElementById("cpassword").value == '')
          {
              alert("Retype Password.");
              return false;
          }
       if(document.getElementById("password").value != document.getElementById("repassword").value)
          {
              alert("Password Mismatch.");
              return false;
          }


}
</script>