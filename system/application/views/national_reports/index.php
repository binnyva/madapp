<?php $this->load->view('layout/header', array('title'=>'National Report')); ?>

<div id="head" class="clear"><h1>National Report</h1></div>
<br />

<a href="<?php echo site_url('national_dashboard/footprint_table_of_all_cities') ?>">Footprint</a><br />
<a href="<?php echo site_url('national_dashboard/classes_table_of_all_cities') ?>">Classes</a><br />
<a href="<?php echo site_url('national_dashboard/classes_progress_table_of_all_cities') ?>">Class Progress</a><br />
<a href="<?php echo site_url('national_dashboard/events_table_of_all_cities') ?>">Events</a><br />
<a href="<?php echo site_url('national_dashboard/class_progress_report') ?>">Exams</a><br />
<a href="<?php echo site_url('national_dashboard/kids_attendance') ?>">Starters</a><br />

<?php $this->load->view('layout/footer'); ?>