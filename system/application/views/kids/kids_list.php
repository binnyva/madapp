<script>
tb_init('a.thickbox, input.thickbox');

function triggerSearch() {
	q = $('#searchQuery').val();
	get_groupList('0',q);
}

$(document).ready(function(){
	$('#example').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';

		$(this).attr('href', url);
	});
	
	$('.groupmanage').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=430&width=850';
	
		$(this).attr('href', url);
	});
		
	$('.group').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';
	
		$(this).attr('href', url);
	});
}
);
</script>

<div id="content" class="clear">

<!-- Main Begins -->
<div id="main" class="clear">
	<div id="head" class="clear">
		<h1><?php echo $title; ?></h1>

		<div id="actions">
		<?php if($this->user_auth->get_permission('kids_add')) { ?>
		<a href="<?php echo site_url('kids/popupaddKids')?>" class="thickbox button primary" id="example" name="<strong>Add Kids</strong>">Add  Kids</a>
		<?php } ?>
		</div>
	</div>

<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Birth Day</th>
    <th class="colStatus">Center</th>
	<th class="colStatus">Image</th>
   <th class="colActions">Actions</th>
</tr>
</thead>
<tbody>

<?php 
//
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = ''; 
$statusIco = '';
$statusText = '';
$content = $details->result_array();
foreach($content as $row)
{	
	$norecord_flag = 0;

	if($shadeFlag == 0)
	  {
  		$shadeClass = 'even';
		$shadeFlag = 1;
  	  }
	else if($shadeFlag == 1)
	  {
  		$shadeClass = 'odd';
		$shadeFlag = 0;
  	  }
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colCheck1"><?php echo $row['id']; ?></td>
    <td class="colName left"><?php echo $row['name']; ?></td>
    <td class="colCount"><?php echo $row['birthday']; ?></td> 
    <td class="colStatus" style="text-align:left"><?php echo $row['center_name'];?></td>
	<td class="colPosition"><?php if($row['photo']) { ?><img src="<?php echo base_url().'pictures/'.$row['photo']; ?>" width="50" height="50" /><?php } ?></td>
    
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('kids_edit')) { ?><a href="<?= site_url('kids/popupEdit_kids/'.$row['id'])?>" class="thickbox" style="cursor:pointer;background-image:url (<?php echo base_url(); ?>/images/ico/icoEdit.png)" class="group" id="group-<?php echo $row['id']; ?>" name="<strong>Edit student : <?= strtolower($row['name']) ?></strong>">Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('kids_delete')) { ?><a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>')">Delete</a><?php } ?>
    </td>
</tr>

<?php  }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
	  if($currentPage != '0') { ?>
       <script>
      	 get_centerlist('<?php echo $currentPage-1; ?>');
	   </script>
<?php } else {
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	  }
}    ?>


</div>


</div>

