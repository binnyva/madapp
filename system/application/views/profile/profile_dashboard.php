
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Profile</h1>
        <br>
        <div class="row">


        <div class="col-md-4 col-sm-4 text-center"> <a  class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/profile/">
                <img src="<?php echo base_url(); ?>images/flat_ui/mad_cred.png" alt="" /> <br>MAD Cred</a></div>

        <div class="col-md-4 col-sm-4 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('user/view/'.$current_user->id); ?>">
                <img src="<?php echo base_url()?>/images/flat_ui/profile.png" alt="" /><br>View Profile</a></div>

        <div class="col-md-4 col-sm-4 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('user/edit_profile'); ?>">
                <img src="<?php echo base_url()?>/images/flat_ui/edit_profile.png" alt="" /><br>Edit Profile</a></div>

        </div>


    </div>
</div>

