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

class Level_model extends Model {
	function Level_model() {
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
		$this->year = $this->ci->session->userdata('year');
    }
    
	function get_all_levels_in_center($center_id) {
		return $this->db->where('project_id', $this->project_id)->where('center_id',$center_id)->where('year', $this->year)->get('Level')->result();
	}
	
	function get_level($level_id) {
		return $this->db->where('id', $level_id)->get('Level')->row();
	}
	
	function get_level_details($level_id) {
    	return $this->db->query("SELECT Center.name AS center_name, Level.name 
    		FROM Level INNER JOIN Center ON Center.id=Level.center_id 
    		WHERE Level.id=$level_id")->row();
    }
    
    function get_kids_in_level($level_id) {
    	$students = $this->db->query("SELECT Student.id,Student.name FROM Student 
    		INNER JOIN StudentLevel ON StudentLevel.student_id=Student.id 
    		WHERE StudentLevel.level_id=$level_id ORDER BY Student.name")->result();
    	
    	$students_ids = array();
    	foreach($students as $student) $students_ids[$student->id] = $student->name;
    	return $students_ids;
    }
    
    function create($data) {
		$this->db->insert('Level', 
			array(
				'name'		=>	$data['name'],
				'center_id'	=>	$data['center_id'],
				'project_id'=>	$data['project_id'],
				'book_id'	=>	$data['book_id'],
				'year'		=> 	$this->year
			));
			
		$level_id = $this->db->insert_id();
		$this->db->delete("StudentLevel", array('level_id'=>$level_id));
		$selected_students = $data['students'];
		if($selected_students) {
			foreach($selected_students as $student_id) {
				$this->db->insert("StudentLevel", array(
					'level_id'	=> $level_id,
					'student_id'=> $student_id
				));
			}
		}
    }
    
    function edit($level_id, $data) {
		$this->db->where('id', $level_id)->update('Level', 
			array(
				'name'		=>	$data['name'],
				'center_id'	=>	$data['center_id'],
				'project_id'=>	$data['project_id'],
				'book_id'	=>	$data['book_id'],
			));
			
		$this->db->delete("StudentLevel", array('level_id'=>$level_id));
		$selected_students = $data['students'];
		foreach($selected_students as $student_id) {
			$this->db->insert("StudentLevel", array(
				'level_id'	=> $level_id,
				'student_id'=> $student_id
			));
		}
    } 
	/**
    *
    * Function to
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_all_kids_in_level($level_id)
	{
		return $this->db->query("SELECT COUNT(id) AS count FROM StudentLevel WHERE level_id=$level_id")->row()->count;
		
	}
	/**
    *
    * Function to
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_all_kidsname_in_level($level_id)
	{
		return $this->db->query("SELECT Student.id,Student.name FROM Student JOIN 
			StudentLevel ON StudentLevel.student_id = Student.id WHERE StudentLevel.level_id=$level_id")->result();
		
	}
	function get_only_levels_in_center($center_id) {
		return $this->db->query("SELECT DISTINCT(Level.id), Level.name FROM Level JOIN 
			Exam_Event ON Level.id = Exam_Event.level_id WHERE Exam_Event.center_id=$center_id AND Level.year={$this->year}")->result();
		
	}
}
