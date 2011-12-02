<?php $this->load->view('layout/header', array('title'=>'Volunteer details for ' . $user->name)); ?>

<?php
$days = array('Sun','Mon','Tue','Wed','Thur','Fri','Sat');
?>

<?php if($user->photo) { ?><img src="<?php echo base_url().'uploads/users/'.$user->photo; ?>" height="100" /><br /><?php } ?>

<h2><?php echo $user->name ?> (<?php echo $user->credit ?>)</h2>

<h3>Contact Details...</h3>

<p>Email: <a href="mailto:<?php echo $user->email ?>;"><?php echo $user->email ?></a></p>
<p>Phone: <strong><?php echo $user->phone ?></strong></p>
<?php if($user->address) { ?><p>Address...<br /><?php echo nl2br($user->address); ?></p><?php } ?>

<?php if($user->user_type != 'applicant') { ?>
<h3>MAD Bio...</h3>

<p>Chapter : <strong><?php echo $all_cities[$user->city_id] ?></strong></p>
<p>User Type : <strong><?php echo ucfirst(str_replace('_',' ',$user->user_type)); ?></strong></p>
<p>Roles: <strong><?php echo implode(',', $user->groups_name); ?></strong></p>
<?php if(in_array('Teacher', $user->groups_name)) { ?>
<p>Center: <strong><?php echo $user->batch->name; ?></strong></p>
<p>Batch: <strong><?php echo $days[$user->batch->day] . ' ' . date('h:i A', strtotime(date('Y-m-d ').$user->batch->class_time)); ?></strong></p>
<p>Credit: <strong><?php echo $user->credit; ?></strong></p>
<a href="<?php echo site_url('classes/index/'.$user->id); ?>">View Class History</a><br />
<a href="<?php echo site_url('user/credithistory/'.$user->id); ?>">View Credit History</a><br />
<?php } elseif(in_array('Intern', $user->groups_name)) { ?>
<p>Admin Credit: <strong><?php echo $user->admin_credit; ?></strong></p>
<?php } ?>
<p>Joined On : <strong><?php echo date("d\<\s\u\p\>S\<\/\s\u\p\> M, Y", strtotime($user->joined_on)); ?></strong></p> 
<?php if($user->left_on != '0000-00-00') { ?><p> Left : <strong><?php echo $user->left_on; ?></strong></p><?php } ?>
<?php } ?>


<h3>Applicant Info...</h3>

<p>Source: <strong><?php echo ucfirst($user->source); ?></strong></p>
<?php if($user->birthday != '0000-00-00') { ?><p>Birthday: <strong><?php echo date("d\<\s\u\p\>S\<\/\s\u\p\> M, Y", strtotime($user->birthday)); ?></strong></p><?php } ?>
<p>Job Status: <strong><?php echo ucfirst($user->job_status); ?></strong></p>
<p>Preferred Class Days: <strong><?php echo ucfirst($user->preferred_day); ?></strong></p>
<?php if($user->why_mad) { ?><p>Why MAD: <strong><?php echo nl2br($user->why_mad); ?></strong></p><?php } ?>

<?php $this->load->view('layout/footer'); ?>
