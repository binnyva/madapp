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
class User extends Controller  {
    /*
    * constructor 
    **/
    function User()
    {
        parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('misc');
		$this->load->model('center_model');
		$this->load->model('project_model');
		$this->load->model('users_model');
		$this->load->model('city_model');
		$this->load->library('upload');
    }

    function index()
    {
        
    }
    
    /// View all the important details about the user in one convinent location.
    function view($user_id) {
		$this->user_auth->check_permission('user_view');
		$data['all_cities']= idNameFormat($this->city_model->get_all());
		$data['all_cities'][0] = 'None';
		$data['user'] = $this->users_model->user_details($user_id);
	
		$data['all_groups'] = idNameFormat($this->users_model->get_all_groups());
		
		$this->load->view('user/view',$data);
    }

	/**
    * Function to get_userlist
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_userlist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Users';
		$linkCount = $this->users_model->users_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->users_model->getuser_details();
		$this->load->view('user/user_list',$data);
	
	}
	/**
    * Function to popupAdduser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupAdduser()
	{
		$this->user_auth->check_permission('user_add');
		$data['all_cities']= idNameFormat($this->city_model->get_all());
		$data['all_cities'][0] = 'None';
		$data['all_groups'] = idNameFormat($this->users_model->get_all_groups());
		$data['this_city_id'] = $this->session->userdata('city_id');
		$data['this_project_id'] = $this->session->userdata('project_id');
		
		$this->load->view('user/popups/add_user',$data);
	}
	/**
    * Function to adduser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function adduser()
	{
		$this->user_auth->check_permission('user_add');
		$data['name'] = $_POST['names'];
		$data['group'] = $this->input->post('group');
		$data['email'] = $_POST['emails'];
		$data['password'] = $_POST['spassword'];
		$data['phone'] = $_POST['phone'];
		$data['address'] = $_POST['address'];
		$data['joined_on'] = $_REQUEST['joined_on'];
		$data['left_on'] = $_REQUEST['left_on'];
		$data['type'] = $_POST['type'];
		
		$data['city'] = $this->session->userdata('city_id');
		$data['project'] = $this->session->userdata('project_id');
		
		$data['id']= $this->users_model->adduser($data);
		
		$config['upload_path'] = dirname(BASEPATH) . '/uploads/users/';
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
                    $flag=$this->users_model->process_pic($data);
                }
             }
        }
		if($data['id'] !='')
		{
			$returnFlag= $this->users_model->adduser_to_group($data['id'], $data['group']);
			if($returnFlag) {
				$this->session->set_flashdata('success', 'User Inserted successfully');
			} else {
				$this->session->set_flashdata('error', 'User Insertion failed!');
			}
		} else  {
			$this->session->set_flashdata('error', "The User can't be added because email '".$data['email']."' is already taken");
		}
		redirect('user/view_users');
	}
	/**
    * Function to popupEditusers
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEditusers($user_id)
	{	
		$this->user_auth->check_permission('user_edit');
		$data['all_cities']= idNameFormat($this->city_model->get_all());
		$data['all_cities'][0] = 'None';
		$data['user'] = $this->users_model->user_details($user_id);
	
		$data['all_groups'] = idNameFormat($this->users_model->get_all_groups());
		
		$this->load->view('user/popups/user_edit_view',$data);
	}
	/**
    * Function to update_user
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_user() {
		$this->user_auth->check_permission('user_edit');
		$data['rootId'] = $this->input->post('rootId');
		$data['name'] = $this->input->post('names');
		
		$data['group'] = array();
		if(!empty($_POST['group'])) $data['group'] = $_POST['group'];
		$data['email'] = $this->input->post('emails');
		
		if($this->input->post('spassword')) $data['password'] = $this->input->post('spassword');
		
		$data['phone'] = $this->input->post('phone');
		if($this->input->post('city')) $data['city'] = $this->input->post('city');
		$data['address'] = $this->input->post('address');
		if($this->input->post('project')) $data['project'] = $this->input->post('project');
		if($this->input->post('type')) $data['type'] = $this->input->post('type');
		$data['joined_on'] = $this->input->post('joined_on');
		$data['left_on'] = $this->input->post('left_on');
		$flag= $this->users_model->updateuser($data);
		$returnFlag= $this->users_model->updateuser_to_group($data);
		$data['id']=$data['rootId'];
		$config['upload_path'] = './uploads/users/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
        
		foreach($_FILES as $key => $value) {
            if(!empty($key['name'])) {
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($key)) {
                    $errors[] = $this->upload->display_errors();
                    
                } else {
                    $flag1=$this->users_model->process_pic($data);
                }
             }
        }
		
		
		if($flag || $returnFlag ) $this->session->set_flashdata('success', 'User Updated successfully');
		else $this->session->set_flashdata('error', 'User Updation failed');

		redirect('user/view_users');
	}
	
	function delete($user_id) {	
		$this->user_auth->check_permission('user_delete');
	
		if($this->users_model->delete($user_id)) $this->session->set_flashdata('success', 'User deleted successfully');
		else $this->session->set_flashdata('error', 'Error deleting User!');
		redirect('user/view_users');
	}
	
	/// The User index is handled by this action
	function view_users()
	{
		$this->user_auth->check_permission('user_index');
		$data['title'] = 'Manage Volunteers';
		$data['all_cities'] = $this->city_model->get_all();
		$data['city_id'] = $this->session->userdata('city_id');
		if($this->input->post('city_id') !== false) $data['city_id'] = $this->input->post('city_id');
		
		$data['all_user_group'] = idNameFormat($this->users_model->get_all_groups());
		if($this->input->post('user_group') !== false) $data['user_group'] = $this->input->post('user_group');
		else $data['user_group'] = array();
		
		$data['name'] = '';
		if($this->input->post('name') !== false) $data['name'] = $this->input->post('name');
		$data['get_user_groups'] = true;
		
		$data['user_type'] = 'volunteer';
		if($this->input->post('user_type') !== false) $data['user_type'] = $this->input->post('user_type');
		
		$data['all_users'] = $this->users_model->search_users($data);
		
		$this->load->view('user/view_users', $data);
	}

	
	/**
    * Function to csv_export
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function csv_export()
	{
		$this->user_auth->check_permission('user_export');
		$query= $this->users_model->getuser_details_csv();
		query_to_csv($query, TRUE, 'user_details.csv');
	}
	/**
    * Function to update_footer
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_footer()
	{
		$data['city']=$_REQUEST['city'];
		$data['name']=$_REQUEST['name'];
		$group=$_REQUEST['group'];
		$group_sub = substr($group,0,strlen($group)-1);
		$group_ex= explode(",",trim($group_sub));
		$data['group'] =implode("-",$group_ex);
		$this->load->view('user/update_csvbutton_footer',$data);
	}
	
	/**
    * Function to updated_csv_export
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function updated_csv_export()
	{
		$this->user_auth->check_permission('user_export');
		$data['city']=$this->uri->segment(3);
		$data['group']=$this->uri->segment(4);
		$data['name']=$this->uri->segment(5);
		$group=$data['group'];
		//search by any city with group only
		if($data['city'] == 0 && $data['group'] !='' && $data['name']=='' )
		{
			$query= $this->users_model->searchuser_by_anycity($data);
			//print_r($query->result());
			query_to_csv($query,TRUE,'user_details.csv');
		}
		//search by any city with group only
		else if($data['city'] == 0 && $group !='' && $data['name'] !='' )
		{
			$query= $this->users_model->searchuser_by_anycity_grp_name($data);
			query_to_csv($query,TRUE,'user_details.csv');
		
		}
		//search by city only.
		else if($data['city'] !='' && $data['name']=='' && $group=='')
		{
			$query= $this->users_model->search_by_city($data);
			query_to_csv($query,TRUE,'user_details.csv');
		
		}
		
		//search by city with group and name.
		else if($data['city'] !='' && $data['name'] !='' && $group !='')
		{
			$explode_agent = explode("-",trim($group));
			for($i=0;$i<sizeof($explode_agent);$i++)
			{
		 	$data['group']=$explode_agent[$i];
			//$query= $this->users_model->searchuser_details_csv($data);
			$query= $this->users_model->searchuser_details($data);
			$result=$query->result_array();
			foreach($result as $row)
			{
				$id=$row['id'];
				$name=$row['name'];
				$title=$row['title'];
				$email=$row['email'];
				$phone=$row['phone'];
				$center_name=$row['center_name'];
				$city_name=$row['city_name'];
				$user_type=$row['user_type'];
				
				$details_array=array( $id, $name, $title,$email,$phone,$center_name,$city_name,$user_type);
				
			}
			//$header_array=array('id','Name','Position Held','Email','Mobile No','Center','City');
			$array[$i]=$details_array;
		
			}
			array_to_csv($array,'user_details.csv');
		}
		//search by city and group.
		else if($data['city'] !='' && $data['name'] =='' && $group !='')
		{
			$explode_agent = explode("-",trim($group));
			for($i=0;$i<sizeof($explode_agent);$i++) {
				$data['group']=$explode_agent[$i];
				$query= $this->users_model->searchuser_details_by_grp_city($data);
				$result=$query->result_array();
				$j=0;
				foreach($result as $row) {
					$id=$row['id'];
					$name=$row['name'];
					$title=$row['title'];
					$email=$row['email'];
					$phone=$row['phone'];
					$center_name=$row['center_name'];
					$city_name=$row['city_name'];
					$user_type=$row['user_type'];
					
					$details_array=array( $id, $name, $title,$email,$phone,$center_name,$city_name,$user_type);
					$array[$j]=$details_array;
					$j++;
				}
			//$header_array=array('id','Name','Position Held','Email','Mobile No','Center','City');
			}
			array_to_csv($array,'user_details.csv');
		
		}
		//search by city and name.
		 else if($data['city'] !='' && $data['name'] !='' && $group =='')
		 {
			 $query= $this->users_model->search_by_city_name($data);
			 query_to_csv($query,TRUE,'user_details.csv');
		 }
	}
	/**
    * Function to edit_profile
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function edit_profile()
	{	
		$uid = $this->session->userdata('id');
		$data['user']= $this->users_model->user_details($uid);
		$this->load->view('user/edit_profile',$data);
	}
	
	/**
    * Function to update_profile
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_profile()
	{
		$data['rootId'] = $this->session->userdata('id');
		$data['name'] = $this->input->post('name');
		$data['email'] = $this->input->post('email');
		$data['phone'] = $this->input->post('phone');
		$data['address'] = $this->input->post('address');
		
		if($this->input->post('password')) $data['password'] = $this->input->post('password');
		
		$flag = $this->users_model->updateuser($data);
		
		$data['id'] = $data['rootId'];
		$config['upload_path'] = dirname(BASEPATH) . '/uploads/users/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
		foreach($_FILES as $key => $value) {
            if( ! empty($key['name'])) {
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($key)) $errors[] = $this->upload->display_errors();
                else $this->users_model->process_pic($data);
             }
        }
		
		if($flag) {
			$this->session->set_flashdata('success', "Profile edited successfully.");
			redirect('user/edit_profile');
		} else {
			$this->session->set_flashdata('error', 'Profile not edited.');
			redirect('user/edit_profile');
		}
	}
	
	
	/// Importing the CSV file
	function import() {
		$this->user_auth->check_permission('user_add');
		$this->load->view('user/import/import');
	}
	
	function import_field_select() {
		// Read the CSV file and analyis it. Give the user a chance to make sure the connections are correct.
		if(!empty($_FILES['csv_file']['tmp_name'])) {
			ini_set('auto_detect_line_endings', true);
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
			
			$this->load->view('user/import/import_field_select', array('all_rows'=>$rows));
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
			
			$message = array();
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
					elseif($fields[$key] == 'Role') $insert['title'] = $value;
					elseif($fields[$key] == 'Email') $insert['email'] = $value;
					elseif($fields[$key] == 'Phone') $insert['phone'] = $value;
				}
				$insert['city_id'] = $this->session->userdata('city_id');
				$insert['project_id'] = $this->session->userdata('project_id');
				$insert['user_type'] = 'volunteer';
				$insert['password'] = 'pass'; //Default Password.
				$insert['credit'] = 3;
				$insert['joined_on'] = date('Y-m-d');
				
				if($insert['name'] and $insert['email']) { // Make sure that we have the neceassy values before importing.
					$flag = $this->users_model->check_email_availability($insert);
				
					if($flag) {
						$message[] = "'$insert[name]' can't be imported - the email '$insert[email]' is already in the database";
					} else {
						$this->db->insert('User', $insert);
						
						// Add volunteer to the default user group...
						$default_group = 9; // :HARD-CODE: 9 being the default group
						$this->users_model->adduser_to_group($this->db->insert_id(), array($default_group)); 
					}
				}
			}
			fclose($handle);
			unlink($this->input->post('uploaded_file'));
			
			if($message) {
				$this->load->view('user/import/import_error',array('message'=>$message));
			} else {
				$this->load->view('user/import/import_success');
			}
		}
	}
	/**
    * Function to credithistory
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function credithistory()
	{
		//$this->user_auth->check_permission('user_group_index');
		$i=0;
		$data['title'] = 'Credit History';
		$current_user_id=$this->session->userdata('id');
		$this->load->view('layout/header',$data);
		$this->load->view('user/usercredit_head', $data);
		$data['details']= $this->users_model->get_usercredits();
		$details=$data['details']->result_array();
		$credit = 3;
		
		foreach($details as $row){
		
		if ($row['user_id'] == $current_user_id && $row['substitute_id'] == 0 && $row['status'] == 'absent')
		{	
			$i++;
			$data['i']=$i;
			$credit = $credit - 2;
			$data['class_on']=$row['class_on'];
			$data['Substitutedby']='Absent';
			$data['lost']="Lost 2 credits";
			$data['credit']=$credit;
			$this->load->view('user/user_credit', $data);
    	}
		else if ($row['user_id'] == $current_user_id && $row['substitute_id'] != 0 )
		{
			$i++;
			$data['i']=$i;
			$substitute_id=$row['substitute_id'];
			$Name_of_Substitute=$this->users_model->get_name_of_Substitute($substitute_id);
			if(sizeof($Name_of_Substitute) >0){
			$Name_of_Substitute=$Name_of_Substitute->name;
			 } else { $Name_of_Substitute ='No Name'; }
			$credit = $credit - 1;
			$data['class_on']= $row['class_on'];
			$data['Substitutedby']="Substituted by ".$Name_of_Substitute." ";
			$data['lost']="Lost 1 credits";
			$data['credit'] = $credit;
			
			$this->load->view('user/user_credit', $data);
			
		}
		else if($row['substitute_id'] == $current_user_id && $row['status'] == 'absent')
		{
			$i++;
			$data['i']=$i;
			$credit = $credit - 2;
			$data['class_on']= $row['class_on'];
			$data['Substitutedby']='Substitute Class Absent';
			$data['lost']="Lost 2 credits";
			$data['credit']=$credit;
			$this->load->view('user/user_credit', $data);
   		}
		else if ($row['substitute_id'] == $current_user_id && $row['status'] == 'attended')
		{
			$i++;
			$data['i']=$i;
			$credit = $credit + 1;
			$data['class_on']= $row['class_on'];
			$data['Substitutedby']="Substitute Class Attended";
			$data['lost']="Gained 1 credits";
			$data['credit']=$credit;
			$this->load->view('user/user_credit', $data);
		}
		}
		$this->load->view('user/usercredit_footer');
		$this->load->view('layout/footer');
	}
	
}	
	


