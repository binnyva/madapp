<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */
class Kids_model extends Model {
    function Kids_model() {
        parent::Model();
        
		$this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');

		$this->year = $this->ci->session->userdata('year');
    }

    /// Return all the kids in the given city.
	function get_all($city_id = 0, $center_id = 0) {
		if(!$city_id) $city_id = $this->city_id;
		
		$this->db->select('Student.*,Center.name as center_name	');
		$this->db->from('Student');
		$this->db->join('Center', 'Center.id = Student.center_id' ,'join');
		// $this->db->join('StudentLevel', 'Student.id = StudentLevel.student_id' ,'join');
		// $this->db->join('Level', 'Level.id = StudentLevel.level_id' ,'join');
		$this->db->where('Center.city_id', $city_id);

		if($center_id) $this->db->where('Student.center_id', $center_id);
		$this->db->where('Center.status', 1);
		$this->db->where('Student.status', 1);
		// $this->db->where('Level.year', $this->year);

		$this->db->orderby('Student.center_id, Student.name');
		$result = $this->db->get()->result();

		// Attach the levels of the students to the result without resorting to a join - which will hide the students without a level.
		$student_level_mapping_raw = $this->ci->level_model->get_student_level_mapping($center_id);
		$student_level_mapping = idNameFormat($student_level_mapping_raw, array('student_id', 'level_id'));
		$all_levels = idNameFormat($this->ci->level_model->get_all_level_names_in_center($center_id));

		for($i=0; $i<count($result); $i++) {
			$student_id = $result[$i]->id;
			$result[$i]->level = "";

			if(isset($student_level_mapping[$student_id])) {
				$student_level_id = $student_level_mapping[$student_id];
				if(isset($all_levels[$student_level_id])) {
					$result[$i]->level = $all_levels[$student_level_id];
					$result[$i]->level_id = $student_level_id;
				}
			}
		}

		return $result;
	}
	function getkids_details($city_id = 0, $center_id = 0) { return $this->get_all($city_id, $center_id); } // :ALIAS: :DEPRECIATED:
	
	/// Returns the deleted kids of the given center.
	function get_deleted_kids($city_id = 0) {
		if(!$city_id) $city_id = $this->city_id;
		
		$this->db->select('Student.*,Center.name as center_name');
		$this->db->from('Student');
		$this->db->join('Center', 'Center.id = Student.center_id' ,'join');
		$this->db->where('Center.city_id', $city_id);
		$this->db->where('Center.status', 1);
		$this->db->where('Student.status', '0');
		$this->db->orderby('Student.name');
		$result=$this->db->get();

		return $result;
	}

	function get_student_level($student_id) {
		$level = $this->db->query("SELECT CONCAT(L.grade, ' ', L.name) AS level FROM Level L 
				INNER JOIN StudentLevel SL ON SL.level_id=L.id
				WHERE L.year={$this->year} AND SL.student_id=$student_id")->row();
		return $level->level;
	}

	
	/**
    * Function to add_kids
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function add_kids($data) {
		$data = array('center_id' 	=> $data['center'],
					  'name' 	 	=> $data['name'],
					  'birthday'	=> $data['date'],
					  'sex'			=> $data['sex'],
				  	  'description'	=> $data['description'],
				  	  'added_on'	=> date("Y-m-d H:i:s"),
				  	  'status'		=> 1,
			   );
		$this->db->insert('Student',$data);
		$kid_id = $this->db->insert_id();
		
	 	return ($this->db->affected_rows() > 0) ? $this->db->insert_id()  : false ;
	}
	
	/**
    * Function to delete_kids
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function delete_kids($id) {
		$this->db->where('id',$id);
		$this->db->update('Student', array('status' => '0'));
		$affected = $this->db->affected_rows();

		// :TODO: Remove Level association of this student if any.

		//Disabling deleting student level associations until we figure out how to convert student to alumni when they leave the center
		/*$this->db->where('student_id',$id);
		$this->db->delete('StudentLevel');*/
		 
		return ($affected) ? true: false;
	}

	function undelete($student_id) {
		$this->db->where('id',$student_id);
		$affected = $this->db->update('Student', array('status' => '1'));

		return $affected;
	}

	/// Function to get_kids_details
	function get_kids_details($uid, $status = '1') {
		$this->db->select('*');
		$this->db->from('Student');
		$this->db->where('id',$uid);
		$this->db->where('Student.status', $status);
		$result = $this->db->get();
		return $result;
	}


	/**
    * Function to get_kids_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function get_kids_name($uid)
	{
		$this->db->select('id,name');
		$this->db->from('Student');
		$this->db->where('center_id',$uid);
		$this->db->where('Student.status', 1);
		$this->db->orderby('name');
		$result=$this->db->get();
		return $result;
	
	}
	
	/// Returns the ID and name of the Kids who are NOT assigned to any levels
	function get_free_kids($center_id) {
		$students = $this->db->query("SELECT Student.id,Student.name
			FROM StudentLevel INNER JOIN Level ON Level.id = StudentLevel.level_id AND Level.project_id={$this->project_id}
			RIGHT JOIN Student ON student_id = Student.id
			WHERE student_id IS NULL AND Student.center_id=$center_id AND Student.status='1' ORDER BY Student.name");
		return $students;
	}
	
	
	/**
    * Function to update_student
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function update_student($data) {
		$rootId=$data['rootId'];
		$update = array();
		if(isset($data['center'])) $update['center_id']	= $data['center'];
		if(isset($data['name'])) $update['name']	= $data['name'];
		if(isset($data['birthday'])) $update['birthday']	= $data['birthday'];
		if(isset($data['sex'])) $update['sex']	= $data['sex'];
		if(isset($data['description'])) $update['description']	= $data['description'];
		if(isset($data['reason_for_leaving'])) $update['reason_for_leaving']	= $data['reason_for_leaving'];

		$this->db->where('id', $rootId);
		$this->db->update('Student', $update);
		
		return ($this->db->affected_rows() > 0) ? 1: 0 ;
	}
	/**
    * Function to kids_level_update
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function kids_level_update($student_id,$level)
	{
		$this->db->where('student_id',$student_id);
		$this->db->update('StudentLevel', array('level_id'=>$level));
	}
	/**
    * Function to getkids_name_incenter
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,Array() ]
    **/
	function getkids_name_incenter($uid)
	{
		$this->db->select('id,name');
		$this->db->from('Student');
		$this->db->where('center_id',$uid);
		$this->db->where('status', 1);
		$this->db->orderby('name');
		$result=$this->db->get();
		return $result;
	
	}

	function get_kidsby_center($center_id, $sort_by='name')
	{
		$this->db->select('*');
		$this->db->from('Student');
		$this->db->where('center_id',$center_id);
		$this->db->where('status', 1);
		$this->db->orderby('name');
		$data = $this->db->get();

		return $data;
	}

	/// Returns the relevant class details for all the clasess that match the given criteria.
	function get_class_details($student_id, $batch_id, $level_id, $class_count=0) {
		$limit = '';
		$batch_check = '';
		$level_check = '';

		if($class_count) $limit = "LIMIT 0,$class_count";
		if($level_id) $level_check = "C.level_id=$level_id AND ";
		if($batch_id) $batch_check = "C.batch_id=$batch_id AND ";

		$attendance = $this->db->query("SELECT SC.participation, SC.present, SC.check_for_understanding, C.class_on, C.status, C.class_type, C.class_satisfaction
							FROM StudentClass SC
							INNER JOIN Class C ON C.id=SC.class_id
							WHERE $batch_check $level_check SC.student_id=$student_id AND C.status='happened'
							ORDER BY C.class_on DESC
							$limit")->result_array();

		return $attendance;
	}

}
