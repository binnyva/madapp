<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 * @package		madapp
 * @author		Rabeesh
 */  
class Settings extends Controller {
	private $message;
	
	function Settings() {
		parent::Controller();
		$message = array('success'=>false, 'error'=>false);
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('misc');
		$this->load->scaffolding('Setting');
		$this->load->model('settings_model','model', TRUE);
		
		$this->load->model('Users_model','user_model');
	}
	/**
    * Function to index
    * @author : Rabeesh
    * @param  : [$data]
    **/
	function index() {
		$this->user_auth->check_permission('setting_index');
		$all_settings = $this->model->getsettings();
		$this->load->view('settings/settings_index', array('all_settings'	=> $all_settings, 'message'=>$this->message));
	}
	function add_settings()
	{
	$this->load->view('settings/settings_view.php');
	}
	function setting_list_refresh()
	{
		$all_settings = $this->model->getsettings();
		$this->load->view('settings/setting_update', array('all_settings'	=> $all_settings, 'message'=>$this->message));
	}
	/**
    * Function to create
    * @author : Rabeesh 
    * @param  : [$data]
    **/
	function create() {
		$this->user_auth->check_permission('setting_create');
		// Make a new setting.
		$name=$_REQUEST['name'];
		$value=$_REQUEST['value'];
		$data=$_REQUEST['data'];
			$data = array(
						'name'	=>	$name, 
						'value'	=>	$value,
						'data'	=>	$data,
					);
			$this->model->addsetting($data);
			echo  "The Setting has been added";
	}
	function edit_settings()
	{
		$settings_id = $this->uri->segment(3);
		$settings = $this->model->get_settings($settings_id);
		$this->load->view('settings/settings_editview.php', array('setting'=> $settings));
	}
	/**
    * Function to edit
    * @author : Rabeesh
    * @param  : [$data]
    **/
	function edit() {
		$this->user_auth->check_permission('setting_edit');
		 $settings_id = $this->uri->segment(3);
			$name=$_REQUEST['name'];
			$value=$_REQUEST['value'];
			$data=$_REQUEST['data'];
			$data = array(
						'name'	=>	$name, 
						'value'	=>	$value,
						'data'	=>	$data,
					);
			$this->model->editsetting($data,$settings_id);
			echo "The Setting has been edited ";
		
		
	}
	function delete()
	{
		$id = $this->uri->segment(3);
		$this->model->deletesetting($id);
		$this->message['success'] = 'The Setting successfully deleted';
		$this->index();
	}

}