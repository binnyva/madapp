<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class Kids extends Controller  {

    /**
    * constructor 
    **/

    function Kids() {
        parent::Controller();
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('file');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
		$this->load->library('upload');
    }
	
    /**
    *
    * Function to 
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/

    function index()
    {
        
    }

    /**
    *
    * Function to manageaddkids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function manageaddkids()
	{
		$this->user_auth->check_permission('kids_index');
	
		$this->load->view('layout/header',array('title'=>'Manage Kids'));
		
		$data['details']= $this->kids_model->getkids_details();
		$data['center_list']=$this->center_model->get_all();
		$this->load->view('kids/kids_list',$data);
		$this->load->view('layout/settings_footer');
	
	}
		
	function get_kids_details()
	{
		$center_id=$_REQUEST['center_id'];
		$page_no = $_REQUEST['page_no'];
		$linkCount = $this->kids_model->kids_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		if($center_id) {
			$data['kids_details']=$this->kids_model->get_kidsby_center($center_id);
			$data['center_name'] = $this->center_model->center_name($center_id);
		} else {
			$data['kids_details']=$this->kids_model->getkids_details();
			$data['center_name'] = '';
		}
		
		$this->load->view('kids/kids_update_list',$data);
	}
	/**
    *
    * Function to popupaddKids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupaddKids()
	{	
		$this->user_auth->check_permission('kids_add');
		
		$data['center']= $this->center_model->getcenter();
		$this->load->view('kids/popups/addkids_popup',$data);
	
	}
	/**
    *
    * Function to popupEdit_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupEdit_kids()
	{
		$this->user_auth->check_permission('kids_edit');
		
		$uid = $this->uri->segment(3);
		$data['center']= $this->center_model->getcenter();
		$data['kids_details']= $this->kids_model->get_kids_details($uid);
		$this->load->view('kids/popups/kids_edit_view',$data);
	
	}
	/**
    *
    * Function to update_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_kids()
	{
		$this->user_auth->check_permission('kids_edit');
		
		$flag='';
		$data['rootId'] = $_REQUEST['rootId'];
		$id=$data['rootId'];
		$data['id']=$id;
		$data['center']=$_REQUEST['center'];
		$data['name']=$_REQUEST['name'];
		
		$date= date('Y-m-d', strtotime($_REQUEST['date-pick']));
		$data['date'] = $date;
		$data['description']=$_REQUEST['description'];
		$returnFlag= $this->kids_model->update_student($data);
		
		$config['upload_path'] = './uploads/kids/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
		foreach($_FILES as $key => $value)
        {
            if( ! empty($key['name']))
            {
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload($key))
                {
                    $errors[] = $this->upload->display_errors();
                }    
                else
                {
                    $flag=$this->users_model->process_pic($data, 'kids');
                }
             }
        }
		if($returnFlag != '') 
			{
			$this->session->set_flashdata('success', 'Student updated successfully.');
			redirect('kids/manageaddkids');  
			}
		elseif($flag!= '')
		{
			$this->session->set_flashdata('success', 'Student updated successfully.');
			redirect('kids/manageaddkids');
			}
		else
			{
			$this->session->set_flashdata('error', 'Student not edited.');
			redirect('kids/manageaddkids');  
			}
	}
	
	/**
    *
    * Function to addkids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addkids()
	{
		$this->user_auth->check_permission('kids_add');
		$data['center']=$_REQUEST['center'];
		$data['name']=$_REQUEST['name'];
		
		$data['date'] = '';
		if(!empty($_REQUEST['date-pick'])) {
			$data['date'] = date('Y-m-d', strtotime($_REQUEST['date-pick']));
		}
		$data['description']=$_REQUEST['description'];
		
		$returnFlag= $this->kids_model->add_kids($data);
		$data['id']=$returnFlag;
		
		$config['upload_path'] = dirname(BASEPATH) . '/uploads/kids/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
		
		foreach($_FILES as $key => $value) {
            if( ! empty($key['name'])) {
                $this->upload->initialize($config);
        
                if ( ! $this->upload->do_upload($key)) {
                    $errors[] = $this->upload->display_errors();
                    
                } else {
                    $this->users_model->process_pic($data, 'kids');
                }
             }
        }
        
		if($returnFlag) {
			
			$this->session->set_flashdata('success', 'Student Inserted successfully');
			redirect('kids/manageaddkids');
		} else {
			$this->session->set_flashdata('success', 'Insertion Failed');
			redirect('kids/manageaddkids');
		}
	}
	
	

	/**
    *
    * Function to ajax_deleteStudent
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function ajax_deleteStudent($kid_id)
	{	
		$this->user_auth->check_permission('kids_delete');
		
		//$data['entry_id'] = $_REQUEST['entry_id'];
		$flag= $this->kids_model->delete_kids($kid_id);
		if($flag){
			$this->session->set_flashdata('success', 'The Kids has been deleted successfully.');
			redirect('kids/manageaddkids');
		}
		//return true;
	}
	
	
	
	/// Importing the CSV file
	function import() {
		$centers = $this->center_model->get_all();
		$this->load->view('kids/import', array('centers'=>$centers));
	}
	
	function import_field_select() {
		// Read the CSV file and analyis it. Give the user a chance to make sure the connections are correct.
		if(!empty($_FILES['csv_file']['tmp_name'])) {
			$handle = fopen($_FILES['csv_file']['tmp_name'],'r');
			if(!$handle) die('Cannot open uploaded file.');
		
			$row_count = 0;
			$rows = array();
		
			//Read the file as csv
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$row_count++;
				$rows[] = $data;
				if($row_count > 5) break;
			}
			fclose($handle);
			move_uploaded_file($_FILES['csv_file']['tmp_name'], $_FILES['csv_file']['tmp_name']."_saved");
			
			$center_id = $this->input->post('center_id');
			
			$this->load->view('kids/import_field_select', array('all_rows'=>$rows, 'center_id'=>$center_id));
		}
	}
	
	/// User has made the choice - add the data into the database
	function import_action() {
		if($this->input->post('uploaded_file')) {
			if(!preg_match('/^\/tmp\/[^\.]+$/', $this->input->post('uploaded_file'))) die("Hack attempt"); // someone changed the value of the uploaded_file in the form.
			
			$handle = fopen($this->input->post('uploaded_file'),'r');
			if(!$handle) die('Cannot open uploaded file.');
		
			$row_count = 0;
			$rows = array();
			$fields = $this->input->post('field');

			//Read the file as csv
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$row_count++;
				if($this->input->post('ignore_header') == 1 and $row_count == 1) continue; // Ignore the first row.
				
				$insert = array();
				$emails = array();
				$phones = array();
				
				foreach($data as $key=>$value) {
					if(empty($fields[$key])) continue;
					
					if($fields[$key] == 'Name') $insert['name'] = $value;
					elseif($fields[$key] == 'Description') $insert['description'] = $value;
					elseif($fields[$key] == 'Birthday') $insert['birthday'] = date('Y-m-d', strtotime($value));
				}
				$insert['center_id'] = $this->input->post('center_id');
				
				if($insert['name'])
					$this->db->insert('Student', $insert);
			}
			fclose($handle);
			unlink($this->input->post('uploaded_file'));
			$this->load->view('kids/import_success');
		}
	}
}
