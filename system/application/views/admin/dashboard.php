<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/thickbox.css">
    <div id="content" class="clear">
    	<div id="main" class="clear">
    		<div id="head" class="clear" style="border-bottom:none;">
            <div style="font-size:14px;background-color:#FFF89D;height:15px;padding-top:18px;padding-bottom:20px;padding-left:10px;">
            	welcome<?php echo $this->session->userdata('name'); ?> ,</div>
    	</div>
    		
    	<div id="quick" class="clear" style="margin-top:-15px;">
        	<div class="quickLink"> <a href="<?= site_url('center/manageaddcenters') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/icoPublish.png" alt="" /> <span>Add Centers</span></a></div>
    	</div>
        
        <div id="quick" class="clear" style="margin-top:-15px;">
        	<div class="quickLink"> <a href="<?= site_url('kids/manageaddkids') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/user.png" alt="" /> <span>Add Kids</span></a></div>
    	</div>
        
         <div id="quick" class="clear" style="margin-top:-15px;">
        	<div class="quickLink"> <a href="<?= site_url('exam/exam_score') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/exam_icon.jpg" alt="" /> <span>Exams Scores</span></a></div>
    	</div>
        <div id="quick" class="clear" style="margin-top:-15px;">
        	<div class="quickLink"> <a href="<?= site_url('user_group/manageadd_group') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/group.jpeg" alt="" /> <span>Add Group Name</span></a></div>
    	</div>
         <div id="quick" class="clear" style="margin-top:-15px;">
        	<div class="quickLink"> <a href="<?= site_url('user/manageadd_user') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/user.jpeg" alt="" /> <span>Add User</span></a></div>
    	</div>
    	</div>
    </div>



