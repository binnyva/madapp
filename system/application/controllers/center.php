<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Center extends Controller  {

    function Center() {
        parent::Controller();

		$this->load->library('session');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) redirect('auth/login');

		$this->load->library('validation');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
		$this->load->model('batch_model');
		$this->load->model('users_model');
		$this->load->model('subject_model');
		$this->load->model('comment_model');
    }

    /// Show all centers in the current city
	function index() {
		$this->user_auth->check_permission('center_index');
		
		set_city_year($this);
		
		$data = array(
			'title' 	=> 'Manage Centers',
			'details'	=> $this->center_model->get_all_info(),
		);
		
		$this->load->view('layout/header',$data);
		$this->load->view('center/index',$data);
		$this->load->view('layout/footer');
	}
	function manageaddcenters() { $this->index(); } // Alias for backward compatibilty :DEPRICIATED: :ALIAS:

	/// Add center.
	function popupaddCenter() {
		$this->user_auth->check_permission('center_add');
		$data['all_users']= $this->users_model->get_users_in_city();
		$this->load->view('center/popups/addcenter_popup',$data);
	}

	/**
    *
    * Function to addCenter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addCenter() {
		$this->user_auth->check_permission('center_add');
		$data['city']= $this->session->userdata('city_id');
		$data['user_id']=$_REQUEST['user_id'];
		$data['center']=$_REQUEST['center'];
		$data['class_starts_on'] = $_REQUEST['class_starts_on'];
		$returnFlag= $this->center_model->add_center($data);
	
		if($returnFlag) {
			$this->session->set_flashdata('success', 'The Center has been added successfully');
			redirect('center/manageaddcenters');
		} else {
			$this->session->set_flashdata('error', 'Insertion Failed.');
			redirect('center/manageaddcenters');
		}
	
	}
	
	/**
    *
    * Function to popupEdit_center
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupEdit_center($center_id)
	{
		$this->user_auth->check_permission('center_edit');
		$data['details']= $this->center_model->edit_center($center_id);
		$data['all_users']= $this->users_model->get_users_in_city();
		$this->load->view('center/popups/center_edit_view',$data);
	}
	
	/**
    *
    * Function to update_Center
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_Center()
	{
		$this->user_auth->check_permission('center_edit');
		$data['rootId'] = $_REQUEST['rootId'];
		$data['user_id']= $_REQUEST['user_id'];
		$data['center']= $_REQUEST['center'];
		$data['medium'] = $_REQUEST['medium'];
		$data['preferred_gender'] = $_REQUEST['preferred_gender'];
		$data['class_starts_on'] = $_REQUEST['class_starts_on'];
		$returnFlag= $this->center_model->update_center($data);
		
		if($returnFlag == true) {
			$this->session->set_flashdata('success', 'The Center has been updated successfully');
			redirect('center/manage/'.$data['rootId']);		  
		} else {
			$this->session->set_flashdata('error', 'The Center updation failed.');
			redirect('center/manage/'.$data['rootId']);	  
		}
	
	}
	
	/**
    *
    * Function to ajax_deletecenter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function deletecenter($center_id)
	{
		$this->user_auth->check_permission('center_delete');
				
		if($this->center_model->delete_center($center_id)) $this->session->set_flashdata("success", "Center deleted");
		else $this->session->set_flashdata("error", "Error deleting center.");
		
		redirect("center/manageaddcenters");
	}
	
	
	function manage($center_id) {
		$this->user_auth->check_permission('center_edit');
		set_city_year($this); // Will take care of Program change.

		$issues = $this->center_model->find_issues($center_id);
		$issues['centers_with_foundation_program'] = [115,207,222,122,113,181,208,153,206,210,4, 	154,184,220]; // All the Shelters that has the fondation program. :HARDCODE:
		
		$issues['center_name'] = $this->center_model->get_center_name($center_id);
		$issues['center_id'] = $center_id;
		$issues['comments'] = $this->comment_model->get_all('Center', $center_id);
		$issues['all_projects'] = ['1' => 'Ed Support', '2' => 'Foundation'];
		$this->session->set_userdata("active_center", $center_id);
		
		$this->load->view('center/manage', $issues);
	}

	function info($center_id) {
		$data = array();
		$all_levels = $this->level_model->get_all_level_names_in_center($center_id);
		$center = $this->center_model->get_info($center_id);
		$all_users = idNameFormat($this->users_model->search_users(array('user_type'=>'volunteer', 'status' => '1', 'user_group'=>9)), array('id'));
		$all_subjects = idNameFormat($this->subject_model->get_all_subjects());
		$all_subjects[0] = "None";
		$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

		foreach ($all_levels as $level) {
			$level_id = $level->id;
			$all_batches_in_level = $this->batch_model->get_batches_in_level($level_id);

			$batch_data = array();
			foreach ($all_batches_in_level as $batch) {
				$batch_data[$batch->id] = array(
					'name'		=>  $day_list[$batch->day] . ', ' . date('h:i A', strtotime('2000-01-01 ' . $batch->class_time)),
					'teachers'	=> $this->batch_model->get_teachers_in_batch_and_level($batch->id, $level_id),
				);
			}

			$data[$level_id] = array(
					'level_name'=> $level->name,
					'kids'		=> $this->level_model->get_kids_in_level($level_id),
					"batch"		=> $batch_data
				);
		}
		$this->load->view('center/info', array('data' => $data, 'all_users' => $all_users, 'center' => $center[0], 'all_subjects' => $all_subjects));
	}
}
