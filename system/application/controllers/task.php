<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link            http://orisysindia.com
 * @since           Version 1.0
 * @filesource
 */
class Task extends controller
{
	function Task()
	{
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
		$this->load->library('session');
		$this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		$this->load->helper('url');
		$this->load->helper('misc');
		$this->load->helper('form');
		$this->load->helper('file');
		$this->load->model('kids_model');
		$this->load->model('center_model');
		$this->load->model('task_model');
		
	}
	function index()
	{
		$this->user_auth->check_permission('event_index');
		$this->load->view('layout/header',array('title'=>'Manage Task'));
		$data['details']= $this->task_model->get_task();
		$this->load->view('task/index',$data);
		$this->load->view('layout/footer');
	}
	function addtask()
	{
		$this->user_auth->check_permission('event_add');
		$this->load->view('task/add_task');
	}
	function insert_task()
	{
		$data['name']=$_REQUEST['name'];
		$data['credit']=$_REQUEST['credit'];
		$data['type']=$_REQUEST['type'];
		$flag= $this->task_model->add_task($data);
		if($flag)
		{
			$this->session->set_flashdata('success', 'Task Added Successfully.');
			redirect('task/index');  
		}
		
	
	}
	function task_delete()
	{
		$data['id']=$this->uri->segment(3);
		$flag= $this->task_model->delete_task($data);
		if($flag)
		{
			$this->session->set_flashdata('success', 'Task Deleted Successfully.');
			redirect('task/index');  
		}
	}
	function task_edit()
	{
		
		$id=$this->uri->segment(3);
		$data['event']= $this->task_model->gettask($id);
		$this->load->view('task/edit_task',$data);
	}
	function update_task()
	{
		$data['root_id']=$_REQUEST['root_id'];
		$data['name']=$_REQUEST['name'];
		$data['credit']=$_REQUEST['credit'];
		$data['vertical']=$_REQUEST['type'];
		$flag= $this->task_model->update_task($data);
		if($flag)
		{
			$this->session->set_flashdata('success', 'Task Updated Successfully.');
			redirect('task/index');  
		}else{
			$this->session->set_flashdata('success', 'No Updation Performed.');
			redirect('task/index');
		}
		
	}
}
