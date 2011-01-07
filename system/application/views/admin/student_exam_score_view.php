<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>

<script>
function populat_textbox()
{
var sub_no = $('#sub_no').val();
 			if(isNaN(sub_no))
			 {
              alert("This Is not a number");
			  document.getElementById('sub_no').focus(); 
              return false;
			  }
	$.ajax({
            type: "POST",
            url: "<?= site_url('exam/ajax_sbjectbox')?>",
            data: "sub_no="+sub_no,
            success: function(msg){
           		$('#loading').hide();
            	$('#subject').html(msg);
            }
            });

}
function get_centers()
{
	$.ajax({
            type: "POST",
            url: "<?= site_url('exam/get_center')?>",
            success: function(msg){
           		$('#loading').hide();
            	$('#center').html(msg);
            }
            });

}
function get_kidslist(center_id)
{
	$.ajax({
            type: "POST",
            url: "<?= site_url('exam/get_kidslist')?>",
            data: "center_id="+center_id,
            success: function(msg){
           		$('#loading').hide();
            	$('#kids').html(msg);
            }
            });

}
function dataGrabber()
{
var name = $('#name').val();
var sub_no = $('#sub_no').val();
var center = $('#centers').val();
	var agents = "";
			$('#kids :checked').each(function(i, selected)
				{ 
 				agents+=($(selected).val()=="")?$(selected).val():$(selected).val()+",";
				});
				 var choiceText = [];
				 var cText = '';
				 for(var i=1; i<=sub_no; i++)
				 {
				
				 choiceText[i] = $('#choice-text-'+i).val();
				 
				 }
				 for(var i=1; i<choiceText.length; i++) 
				 {
					 if(choiceText[i] == '')
						{
							choiceText[i] = 'nil';	 	
						}
					 cText = cText+choiceText[i]+',';
					 
				 }
				$.ajax({
				 type: "POST",
				 url: "<?= site_url('exam/input_exam_mark_details')?>",
				 data: "agents="+agents+'&name='+name+'&choice_text='+cText+'&center='+center,
				 success: function(msg){
				 suceessfunction();
				 }
				 });
				
				
}
function suceessfunction()
{
		var bool = alert("successfully added")
}
//onSubmit="return false;"
</script>
<form id="formEditor" class="mainForm clear"  action="" method="post" style="width:500px;">
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">

<div id="right-column">
        <?php if($message !=''): ?>
            <div id="msgInfo" class="message" style="background-color:#CDE5EC; background-image: url(../images/ico/icoInfo.png);"><?php echo $message; ?></div>
        <?php endif; ?>
      </div>



            <div class="field clear" style="width:600px;"> 
                        <label for="txtName">Exam Name : </label>
                        <input id="name" name="name"  type="text" /> 
                      
            </div>
            <div class="field clear" style="width:600px;"> 
                        <label for="txtName">No of Subject : </label>
                        <input id="sub_no" name="sub_no"  type="text" onkeyup="javascript:populat_textbox();" /> 
                 </div>     
             <div class="field clear" id="subject" style="width:600px;"> 
            </div>
            
            <div class="field clear" id="center" style="width:600px;">
                <label for="selBulkActions">Select center:</label> 
                <select id="centers" name="center"> 
                <option  >- choose action -</option> 
                </select>
            </div>
            
            
         <div class="field clear" style="width:600px; height:100px;">
         <label for="selBulkActions">Kids:</label>
			<select id="kids" name="kids"  style="width:142px; height:80px;"multiple >
            <option selected="selected"; >- choose action -</option>  
            </select>
            </div>
            
            <div class="field clear" style="width:550px;"> 
     				<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" onclick="javascript:dataGrabber();"/>
            </div>
           
            </fieldset>
            </form>