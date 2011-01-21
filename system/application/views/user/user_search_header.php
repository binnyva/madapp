<?php include_once('session_timeout.php'); ?>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script>
function update_userlist()
{
city=$('#city').val();
var agents = "";
			$('#group :checked').each(function(i, selected)
				{ 
 				agents+=($(selected).val()=="")?$(selected).val():$(selected).val()+",";
				});
name=$('#name').val();

		$.ajax({
            type: "POST",
            url: "<?= site_url('user/user_search')?>",
            data: "city="+city+"&group="+agents+"&name="+name,
            success: function(msg){
           		//$('#loading').hide();
            	$('#search').html(msg);
				divupdation(); 
            }
            });
}


function divupdation()
	{
	city=$('#city').val();
	name=$('#name').val();
	var agents = "";
			$('#group :checked').each(function(i, selected)
				{ 
 				agents+=($(selected).val()=="")?$(selected).val():$(selected).val()+",";
				});
			$.ajax({
            type: "POST",
            url: "<?= site_url('user/update_footer')?>",
            data: "city="+city+"&group="+agents+"&name="+name,
            success: function(msg){
				$('#footer_div').html(msg);  

            }
            });
	
	}
</script>


<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

                       <!-- end page actions-->

           </div>
	<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:25px;">
  	<tr>
    
            
    <td><div class="field clear">
            <label for="date" style="margin-left:20px;">Select City</label>
            <select name="city" id="city">
            <option value="0">Any City</option>
            <?php $city=$city->result_array();
			foreach($city as $row){ echo "fi=".$row['name'];
			?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
            </select>
            <p class="error clear"></p> 
            </div>
    </td>
            
    <td><div  class="field clear" style="margin-left:20px; margin-bottom:10px;">
        	<label for="date">Group</label>
            
            <select name="group" id="group" style="width:150px; height:100px;" multiple>
            <option> </option>
            <?php $group=$group->result_array();
					foreach($group as $row)
					{ ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
            </select>
            <p class="error clear"></p>
            </div>
    </td>  
     <td><div  class="field clear" style="margin-left:20px;">
        	<label for="date">Name</label>
            <input name="name" id="name"  type="text">
            <p class="error clear"></p>
            </div>
    </td>
    <td><div  class="field clear" style="margin-left:20px;">
    <input type="submit" value="Get User"  onclick="javascript:update_userlist('0');"/>
    </div>
    </td>                                     
  	</tr>
</table>
<div id="update_sales">
<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
	<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Email</th>
    <th class="colStatus">Mobile No</th>
    <th class="colPosition">Position Held</th>
    <th class="colPosition">City</th>
    <th class="colPosition">Center</th>
    <th class="colPosition">User Type</th>
</tr>
</thead>
<tbody id="search">
