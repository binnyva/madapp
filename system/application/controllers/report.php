<?php
class Report extends Controller {
	private $message;
	
	function Report() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
	
		$this->load->model('Report_model', 'report_model');
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
	}
	
	function index() {
		$this->user_auth->check_permission('report_index');
		$this->load->view('report/index');
	}
	
	function users_with_low_credits() {
		$this->user_auth->check_permission('report_view');
		$report_data = $this->report_model->get_users_with_low_credits();
		$this->show_report($report_data, array('name'=>'Name', 'credit'=>'Credits'), 'Users With Low Credits(0 or less)');
	}
	
	function absent() {
		$report_data = $this->report_model->get_users_absent_without_substitute();
		$this->show_report($report_data, array('name'=>'Name', 'class_on'=>'Class Time', 'center_name'=>'Center Name'), 
			'Users Who Were Absent Without a Substitute');
	}
	
	function volunteer_requirement() {
		$report_data = $this->report_model->get_volunteer_requirements();
		$this->show_report($report_data, array('name'=>'Center', 'requirement'=>'Volunteers Required'), 
			'Volunteer Required for all Centers');
	}
	
	function show_report($data, $fields, $title) {
		$this->user_auth->check_permission('report_view');
		$this->load->view('report/report', array('data'=>$data, 'fields'=>$fields, 'title'=>$title));
	}

}