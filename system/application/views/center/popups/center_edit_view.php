<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<?php
$details=$details->result_array();
foreach($details as $row)
{
$root_id=$row['id'];
$name=$row['name'];
$city_id=$row['city_id'];
$user_id=$row['center_head_id'];
}

?>
<form id="formEditor" class="mainForm clear" action="<?=site_url('center/update_Center')?>" method="post" style="width:500px;"  onsubmit="return validate();">
<fieldset class="clear" style="margin-top:70px;width:500px;margin-left:-30px;">
			<div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select city:</label> 
            <select id="city" name="city" > 
            <option selected="selected"  value="-1">- choose action -</option> 
				<?php 
                $details = $city->result_array();
                foreach($details as $row)
                {
                ?>
                <?php if($city_id==$row['id']) { ?>
                <option value="<?php echo $row['id']; ?>" selected="selected" ><?php echo $row['name']; ?></option> 
                <?php }else { ?>
                <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option> 
                <?php } }?>
            </select>
            </div>
            
            <div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select Head:</label> 
            <select id="user_id" name="user_id" > 
            <option selected="selected" value="-1" >- choose action -</option> 
				<?php 
                $user_name = $user_name->result_array();
                foreach($user_name as $row)
                {
                ?>
                <?php if($user_id==$row['id'] ){ ?>
                <option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
                <?php }else { ?>
                 <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                 <?php }} ?>
            </select>
            </div>
           
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Center : </label>
                        <input id="center" name="center"  type="text" value="<?php echo $name; ?> " /> 
                      
            </div>
            
            <div class="field clear" style="width:550px;"> 
            		<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
     				<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
            </div>
            </fieldset>
            </form>
             <script>
     function validate()
     {
        if(document.getElementById("city").value == '-1')
          {		
              alert("Select a Center.");
              return false;
          }
       if(document.getElementById("user_id").value == '-1')
          {
              alert("Select a Head");
              return false;
          }
       if(document.getElementById("center").value == '')
          {
              alert("Center Missing.");
              return false;
          }
	}
		</script>