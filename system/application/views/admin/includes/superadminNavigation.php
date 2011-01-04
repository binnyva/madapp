<!-- superAdmin Navigation Begins -->

<div id="top">
<div id="title" class="clear"> <a href="<?= site_url('admin/dashboard') ?>">Madapp</a> <span> | Admin Panel</span></div>
<div id="menu" class="clear">
<ul>
	
    <!-- Navigation Dashboard Check Begins -->
    <?php if($navId == 'root') { ?>
    <li> <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
    <?php } ?>
    <!-- Navigation Dashboard Check Ends -->

    <!-- Navigation Dashboard Check Begins -->
    <?php if($navId == '0') { ?>
    <li class="active"> <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
    <?php } else if($navId != 'root') { ?>
    <li> <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
    <?php } ?>
    <!-- Navigation Dashboard Check Ends -->

    <!-- Navigation Manage Users Check Begins -->
    <?php if($navId == '1'): ?>
    <li class="active"> <a href="<?= site_url('admin/manageaddcenters') ?>">Add Centers</a></li>
    <?php else: ?>
    <li> <a href="<?= site_url('admin/manageaddcenters') ?>">Add Centers</a></li>
    <?php endif; ?>
    <!-- Navigation Manage Users Check Ends -->

    <!-- Navigation Manage Group Check Begins -->
    <?php if($navId == '2'): ?>
    <li class="active"><a href="<?= site_url('admin/manageaddkids') ?>">Add kids</a></li>
    <?php else: ?>
    <li> <a href="<?= site_url('admin/manageaddkids') ?>">Add kids</a></li>
    <?php endif; ?>
    
    
    
    <!-- Navigation Manage Group Check Ends -->
        
    <!--<li> <a href="5.html">Gallery</a></li> 
    <li> <a href="6.html">Other</a></li>-->

</ul>
</div>


<!-- Toolbar Starts -->
<div id="toolbar" class="clear">
    <p id="user" style="margin-left: 110px;">Logged in as <a href="#">Admin</a></p>
        <div id="buttons"><a href="<?= site_url('common/logout') ?>" class="button tool" style="margin-left: 120px;">Logout</a></div>
</div>
<!-- Toolbar Ends -->

</div>

<!-- superAdmin Navigation Begins -->
