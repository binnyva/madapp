<?php
class Class_model extends Model {
    function Class_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
    
    function get_all($user_id) {
    	return $this->db->query("SELECT Class.id AS class_id, UserClass.user_id, UserClass.substitute_id, UserClass.status, Class.batch_id, Class.level_id, Level.name AS level_name, Class.class_on, Center.name AS center_name
    		FROM Class INNER JOIN UserClass ON UserClass.class_id=Class.id
				INNER JOIN Level ON Class.level_id=Level.id
				INNER JOIN Center ON Level.center_id=Center.id
    		WHERE Class.project_id={$this->project_id} AND (UserClass.user_id=$user_id OR UserClass.substitute_id=$user_id)
    		ORDER BY Class.class_on DESC")->result();
    }
    
    function get_all_by_batch($batch_id) {
    	return $this->db->query("SELECT Class.id AS class_id, UserClass.user_id, UserClass.substitute_id, UserClass.status, Class.batch_id, Class.level_id, Class.class_on
    		FROM Class INNER JOIN UserClass ON UserClass.class_id=Class.id 
    		WHERE Class.project_id={$this->project_id} AND Class.batch_id=$batch_id")->result();
    }
    
    function confirm_class($class_id, $user_id) {
    	$this->db->query("UPDATE UserClass SET status='confirmed' WHERE user_id=$user_id AND class_id=$class_id");
		return $this->db->affected_rows();
    }
    
    /// Sets the status of all the teacher in the set class as cancelled. Used to cancel a class.
    function cancel_class($class_id) {
		$previous_class_data = $this->db->where(array('class_id'=>$class_id))->get('UserClass')->result_array();
		foreach($previous_class_data as $class_data) {
			$this->revert_user_class_credit($class_data['id'], $class_data);
		}
		$this->db->query("UPDATE UserClass SET status='cancelled' WHERE class_id=$class_id");
		return $this->db->affected_rows();
    }
    
    /// Deletes the future classes of the given user - happens when a user is taken off a batch.
    function delete_future_classes($user_id, $batch_id, $level_id) {
		// First get all the classes of this guy in the future.
		$future_classes = $this->db->query("SELECT UserClass.id AS user_class_id,Class.id AS class_id 
			FROM UserClass INNER JOIN Class ON Class.id=UserClass.class_id 
			WHERE UserClass.user_id=$user_id AND Class.batch_id=$batch_id AND Class.level_id=$level_id AND Class.class_on > NOW()")->result();
		
		$class_ids = array();
		foreach($future_classes as $classes) {
			$class_ids[] = $classes->class_id;
			
			// Delete his part of the class...
			$this->db->delete("UserClass", array('id'=>$classes->user_class_id));
		}
		
		// Now go thru the class that he was there in. If the class has no other teacher, delete the class as well.
		foreach($class_ids as $id) {
			$teacher_count = oneFormat($this->db->query("SELECT COUNT(id) FROM UserClass WHERE class_id=$id")->row());

			// No other teacher. Delete class.
			if(!$teacher_count) $this->db->delete("Class", array('id'=>$id));
		}
		
		return true;
    }
    
    /// Revert a class cancellation. The status becomes projected.
    function uncancel_class($class_id) {
		$this->db->query("UPDATE UserClass SET status='projected' WHERE class_id=$class_id");
		return $this->db->affected_rows();
    }
    
    function get_last_class_in_batch($batch_id) {
    	return $this->db->query("SELECT * FROM Class WHERE batch_id=$batch_id AND class_on<NOW() ORDER BY class_on DESC LIMIT 0,1")->row();
    }
    
    
    /// Returns the last unit taught in that level/batch.
    function get_last_unit_taught($level_id, $batch_id=0) {
		$batch_condition = '';
		if($batch_id) $batch_condition = "batch_id=$batch_id AND ";
		$class = $this->db->query("SELECT lesson_id FROM Class WHERE $batch_condition level_id=$level_id AND class_on<NOW() AND lesson_id!=0 ORDER BY class_on DESC LIMIT 0,1")->row();
		
		if($class) return $class->lesson_id;
		return 0;
    }
    
    function save_class($data) {
    	// Try to find the class if the necessay data. Any class can be identified with the batch_id, level_id and the time of the class.
    	$class_id = $this->get_by_batch_level_time($data['batch_id'], $data['level_id'], $data['class_on']);
    	
    	// If the class is not found, create one.
    	if(!$class_id) {
			$this->db->insert('Class', array(
					'batch_id'	=> $data['batch_id'],
					'level_id'	=> $data['level_id'],
					'project_id'=> 1,
					'class_on'	=> $data['class_on']
				));
			$class_id = $this->db->insert_id();
		}
		
		// Add the given user to the class.
	    $this->db->insert('UserClass', array(
	    		'user_id'	=> $data['teacher_id'],
	    		'class_id'	=> $class_id,
	    		'substitute_id'=>0,
	    		'status'	=> 'projected'
	    	));
	    
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }
    
    function save_class_lesson($class_id, $lesson_id) {
    	$this->db->where('id', $class_id)->update('Class',array('lesson_id'=>$lesson_id));
    }
    
    /// Get just the class information for the current level
    function get_classes_by_level($level_id) {
    	$classes = $this->db->query("SELECT * FROM Class WHERE level_id=$level_id ORDER BY class_on")->result();
    	return $classes;
    }
    
    /// Get just the class information for the current level/batch
    function get_classes_by_level_and_batch($level_id, $batch_id) {
    	$classes = $this->db->query("SELECT Class.*,UserClass.user_id,UserClass.substitute_id,UserClass.status 
    		FROM Class INNER JOIN UserClass ON Class.id=UserClass.class_id 
    		WHERE Class.level_id=$level_id AND Class.batch_id=$batch_id ORDER BY Class.class_on")->result();
    	return $classes;
    }
    
    /// Get both teacher information and class information together.
    function get_by_level($level_id) {
    	$classes = $this->db->query("SELECT Class.*,UserClass.user_id,UserClass.substitute_id,UserClass.status 
    		FROM Class INNER JOIN UserClass ON Class.id=UserClass.class_id 
    		WHERE Class.level_id=$level_id ORDER BY class_on")->result();
    	return $classes;
    }
    
    // Returns the class id.
    function get_by_batch_level_time($batch_id, $level_id, $class_on) {
    	$class = $this->db->where('batch_id', $batch_id)->where('level_id', $level_id)->where('class_on',$class_on)->get("Class")->row();
    	if($class) return $class->id;
    	return 0;
    }
    
    function get_by_teacher_time($teacher_id, $time) {
    	return $this->db->query("SELECT Class.id FROM Class 
    		INNER JOIN UserClass on UserClass.class_id=Class.id 
    		WHERE UserClass.user_id=$teacher_id AND Class.class_on='$time'")->result();
    }
    
    /// The the absent/present status of the kids of any perticular class.
    function get_attendence($class_id) {
    	$result = $this->db->where('class_id', $class_id)->get('StudentClass')->result();
    	
    	$attendence = array();
    	foreach($result as $class) {
    		$attendence[$class->student_id] = $class->present;
    	}
    	
    	return $attendence;
    }
    
    function save_attendence($class_id, $all_students, $attendence) {
    	$this->db->where('class_id', $class_id)->delete("StudentClass");
    	
    	foreach($all_students as $student_id=>$name) {
    		$present = !(empty($attendence[$student_id])) ? '1' : '0';
	    	$this->db->insert("StudentClass", array(
	    		'class_id'	=> $class_id, 
	    		'student_id'=> $student_id, 
	    		'present'	=> $present));
	    }
    }
    
    /// See if the given class has a teacher with the giver user id. Returns true if yes. False otherwise
    function is_class_teacher($class_id, $user_id) {
    	return ($this->db->where(array('class_id'=>$class_id, 'user_id'=>$user_id))->get('UserClass'));
    }
    
    function get_class($class_id) {
    	$class_details = $this->db->where('id',$class_id)->get('Class')->row_array();
    	$class_details['teachers'] = $this->db->where('class_id',$class_id)->get("UserClass")->result_array();
    	
    	return $class_details;
    }
    
    function save_class_teachers($user_class_id, $data) {
    	if(!$user_class_id) { // Sometimes, the UserClass.id is not provided. Then we find the unique row using UserClass.class_id and UserClass.user_id. Then cache its UserClass.id
    		$user_class_id = $this->db->where(array('class_id'=>$data['class_id'],'user_id'=>$data['user_id']))->get('UserClass')->row()->id;
    	}
    
    	// When editing the class info, make sure that the credits asigned during the last edit is removed...
    	$previous_class_data = $this->db->where(array('id'=>$user_class_id))->get('UserClass')->row_array();
    	$this->revert_user_class_credit($user_class_id, $previous_class_data);
    	
    	$this->db->update('UserClass', $data, array('id'=>$user_class_id));
    	$this->calculate_users_class_credit($user_class_id, $data);

    	return $this->db->affected_rows();
    }
    
    /// Calculates the credit that should be given to the user for the given class.
    /// Argument: $user_class_id - the id of a row in the UserClass table.
    function calculate_users_class_credit($user_class_id, $data = array()) {
    	if(!$data) $data = $this->db->where('id',$user_class_id)->get('UserClass')->row_array();
    	$this->load->model('user_model','user_model');
    	
    	$debug = false;
    	if($debug) {print "Class Data: ";dump($data);}
    	
    	extract($data);
    	if($status == 'attended') {
    		if($substitute_id) {
    			// A substitute has attended the class. Substitute gets one credit, Original teacher loses one credit.
    			$this->user_model->update_credit($substitute_id, 1);
    			$this->user_model->update_credit($user_id, -1);
    			if($debug) print "<br />Substitute attended. Sub +1 and Teacher -1";
    		}
    	} elseif($status == 'absent') {
    		if($substitute_id) {
    			// A substitute was supposed to come - but didn't. Substitute loses two credit, Original teacher loses one credit.
    			$this->user_model->update_credit($substitute_id, -2);
    			$this->user_model->update_credit($user_id, -1);
    			if($debug) print "<br />Substitute was absent. Sub -2 and Teacher -1";
    		} else {
    			// Absent without substitute. Teacher loses two credit.
    			$this->user_model->update_credit($user_id, -2);
    			if($debug) print "<br />Teacher was absent. Teacher -2";
    		}
    	}
    }
    
    
    /// When editing the class info, we have to make sure that the credits asigned durring the last edit is removed. Thats what this function is for
    /// Argument: $user_class_id - the id of a row in the UserClass table.
    function revert_user_class_credit($user_class_id, $data = array()) {
    	if(!$data) $data = $this->db->where('id',$user_class_id)->get('UserClass')->row_array();
    	$this->load->model('user_model','user_model');
    	
    	$debug = false;
    	if($debug) {print "Last Class Data: ";dump($data);}
    	
    	extract($data);
    	// Note - S = Substitute Teacher, OT= Original Teacher.
    	if($status == 'attended') {
    		if($substitute_id) {
    			// A substitute had attended the class. That would have changed the credits like S = +1, OT = -1. So we make S = -1 and OT = +1 - to make the changed credits 0
    			$this->user_model->update_credit($substitute_id, -1);
    			$this->user_model->update_credit($user_id, 1);
    			if($debug) print "<br />Substitute had attended. Reverting means Sub -1 and Teacher +1";
    		}
    	} elseif($status == 'absent') {
    		if($substitute_id) {
    			// A substitute was supposed to come - but didn't. S=-2, OT=-1. So in revert, S=+2,OT=+1
    			$this->user_model->update_credit($substitute_id, +2);
    			$this->user_model->update_credit($user_id, +1);
    			if($debug) print "<br />Substitute was absent. Reverting means Sub +2 and Teacher +1";
    		} else {
    			// Absent without substitute. Teacher lost two credit. So, give them +2.
    			$this->user_model->update_credit($user_id, +2);
    			if($debug) print "<br />Teacher was absent. Reverting Teacher +2";
    		}
    	}
    }
    
    /// Returns all the unconfirmed classes for the next $days days. 
    function get_unconfirmed_classes($days) {
    	return $this->db->query("SELECT Level.center_id, Class.class_on, User.name, User.phone, Class.batch_id
				FROM UserClass 
					INNER JOIN Class ON Class.id=UserClass.class_id
					INNER JOIN `Level` ON Class.level_id=Level.id
					INNER JOIN User ON UserClass.user_id=User.id
				WHERE UserClass.status='projected' AND UserClass.substitute_id='0'
					AND DATE(Class.class_on) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL $days DAY)")->result();
	}
    
    /// Return all the upcoming classes of the given user. Projected or confirmed.
    function get_upcomming_classes($user_id = false) {
    	if(!$user_id) $user_id = $this->ci->session->userdata('id');
    	
    	$query = "SELECT Class.id, Center.name, Class.class_on, UserClass.status FROM UserClass 
    					INNER JOIN Class ON Class.id=UserClass.class_id 
    					INNER JOIN Level ON Class.level_id=Level.id
    					INNER JOIN Center ON Level.center_id=Center.id
    					WHERE UserClass.user_id=$user_id 
    						AND Class.project_id={$this->project_id}
    						AND UserClass.status != 'cancelled'
    						AND Class.class_on > NOW()
    					ORDER BY Class.class_on";
    				
    	return $this->db->query($query)->result();
    }
    
    /// Returns the closest unconfirmed class. This is the class that get 'confirmed' when a user replies to a text we send.
    function get_closest_unconfirmed_class($user_id) {
		$closest_unconfirmed_class = $this->db->query("SELECT Class.id FROM UserClass
				INNER JOIN Class ON Class.id=UserClass.class_id
				WHERE UserClass.user_id=$user_id
					AND Class.class_on > NOW()
					AND UserClass.status = 'projected'
					ORDER BY Class.class_on LIMIT 0,1")->row()->id;
		return $closest_unconfirmed_class;
	}

    
    function search_classes($data) {
    	$query = "SELECT Class.id,Class.class_on,Class.lesson_id,Level.id AS level_id,Level.name,UserClass.user_id,UserClass.substitute_id,UserClass.zero_hour_attendance,UserClass.status
			FROM Class
			INNER JOIN Level ON Class.level_id=Level.id
			INNER JOIN UserClass ON UserClass.class_id=Class.id
			WHERE Class.batch_id=$data[batch_id] AND DATE(Class.class_on)='$data[from_date]'";
		$data = $this->db->query($query)->result();
		return $data;
    }
    
    function add_class_manually($level_id, $batch_id, $class_on, $user_id) {
		$existing_class = $this->db->query("SELECT id FROM Class WHERE batch_id=$batch_id AND level_id=$level_id AND class_on='$class_on'")->row();
		if(!$existing_class) {
			$this->db->insert('Class', array(
				'level_id'	=> $level_id,
				'batch_id'	=> $batch_id,
				'class_on'	=> $class_on,
				'project_id'=> $this->project_id
			));
			$class_id = $this->db->insert_id();
		} else {
			$class_id = $existing_class->id;
		}
		
		$existing_user_class = $this->db->query("SELECT id FROM UserClass WHERE class_id=$class_id AND user_id=$user_id")->row();
		if(!$existing_user_class) {
			$this->db->insert('UserClass', array(
				'user_id'	=> $user_id,
				'class_id'	=> $class_id,
				'status'	=> 'projected'
			));
			$user_class_id = $this->db->insert_id();
		} else {
			$user_class_id = $existing_user_class->id;
		}
		
		return array($class_id, $user_class_id);
    }
    
    function delete($class_id) {
		$this->db->delete('Class',array('id'=>$class_id));
		$this->db->delete('UserClass',array('class_id'=>$class_id));
		$this->db->delete('StudentClass',array('class_id'=>$class_id));
    }

	/// Get just the class information for the current level/batch
    function get_classes_by_level_and_center($level_id) {
    	$classes = $this->db->query("SELECT Class.id,Class.class_on,UserClass.status FROM Class JOIN UserClass ON UserClass.class_id=Class.id WHERE level_id=$level_id ORDER BY class_on ASC")->result();
    	return $classes;
    }
    
    //////////////////////////////////////// Monthly Review functions.
    
    /// Returns the classes that happened in the given month.
    function get_classes_in_month($year_month, $city_id=0, $project_id=1) {
		if(!$city_id) $city_id = $this->city_id;
		$data = $this->db->query("SELECT Class.*, UserClass.* FROM Class 
				INNER JOIN UserClass ON Class.id=UserClass.class_id 
				INNER JOIN Level ON Class.level_id=Level.id
				INNER JOIN Center ON Level.center_id=Center.id
			WHERE DATE_FORMAT(Class.class_on, '%Y-%m')='$year_month'
				AND Class.project_id=$project_id
				AND Center.city_id=$city_id")->result();
		return $data;
    }
    
    /// Returns the attendance of classes that happened in the given month.
    function get_attendance_in_month($year_month, $city_id=0, $project_id=1) {
		if(!$city_id) $city_id = $this->city_id;
		$data = $this->db->query("SELECT StudentClass.* FROM Class 
				INNER JOIN StudentClass ON Class.id=StudentClass.class_id 
				INNER JOIN Level ON Class.level_id=Level.id
				INNER JOIN Center ON Level.center_id=Center.id
			WHERE DATE_FORMAT(Class.class_on, '%Y-%m')='$year_month'
				AND Class.project_id=$project_id
				AND Center.city_id=$city_id")->result();
		return $data;
    }
    
        
	 /**
    *
    * Function to
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get__kids_attendance ($class_id)
	{
		$attendance = $this->db->query("SELECT COUNT(id) as count FROM StudentClass WHERE class_id=$class_id AND present=1")->row()->count;
    	return $attendance;
	}
	 /**
    *
    * Function to
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	 /// Get just the class information for the current level/batch
    function get_examname_by_level_and_center($level_id) {
    	$classes = $this->db->query("SELECT Exam.id,Exam.name,Exam_Event.exam_on,Exam_Event.Level_id,Exam_Event.center_id 
			FROM Exam JOIN Exam_Event ON Exam.id=Exam_Event.exam_id WHERE Exam_Event.center_id=$level_id ORDER BY Exam.id ASC")->result();
    	return $classes;
    }
	function get__student_marks ($exam,$students)
	{
		return $this->db->query("SELECT Exam_Subject.name,Exam_Mark.mark FROM Exam_Mark JOIN Exam_Subject ON Exam_Mark.subject_id = 
			Exam_Subject.id WHERE Exam_Mark.exam_id=$exam AND Exam_Mark.student_id=$students")->result();	
			
	}
	function get__student_attendence($kids_id)
	{
		return $this->db->query("SELECT COUNT(StudentClass.id) AS count FROM StudentClass JOIN Exam_Mark ON Exam_Mark.student_id = 
			StudentClass.student_id WHERE StudentClass.student_id=$kids_id AND StudentClass.present=1")->row()->count;	
	}
	
}
