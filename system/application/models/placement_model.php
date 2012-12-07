<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer

 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */
class Placement_model extends Model {

    function Placement_model() {
        parent::Model();
        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
        $this->project_id = $this->ci->session->userdata('project_id');
        $this->year = $this->ci->session->userdata('year');
    }

    /**
     * Function to getgroup_details
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Array()]
     * */
    function getgroup_details() {
        $this->db->select('*');
        $this->db->from('Placement_Group');
        $this->db->order_by('id', 'DESC');
        $result = $this->db->get();
        return $result;
    }

    /**
     * Function to add_group_name
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function add_group_name($data) {
        $datas = array('name' => $data['groupname'],
            'group' => $data['cgroup'],
            'center_id' => $data['centreid'],
            'sex' => $data['sex'],
            'activity_frequency' => $data['actfrq'],
            'code' => $data['code'],
        );
        $this->db->insert('Placement_Group', $datas);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }

    /**
     * Function to edit_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [ Array()]
     * */
    function edit_group($user_id) {
        $this->db->select('*');
        $this->db->from('Placement_Group');
        $this->db->where('id', $user_id);
        $result = $this->db->get();
        return $result;
    }

    /**
     * Function to update_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function update_group($data) {
        $datas = array('name' => $data['group_name'],
            'group' => $data['cgroup'],
            'center_id' => $data['centreid'],
            'sex' => $data['sex'],
            'activity_frequency' => $data['actfrq'],
            'code' => $data['code'],
        );
        $this->db->where('id', $data['group_id']);
        $this->db->update('Placement_Group', $datas);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /**
     * Function to delete_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean, ]
     * */
    function delete_group($data) {
        $id = $data['entry_id'];
        $this->db->where('id', $id);
        $this->db->delete('Placement_Group');

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /**
     * Function to edit_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [ Array()]
     * */
    function getcenter_details() {
        $this->db->select('id,name');
        $this->db->from('Center');
        $this->db->where('city_id', $this->session->userdata('city_id'));
        $this->db->where('status', '1');
        $result = $this->db->get();
        return $result;
    }

    /**
     * Function to add_group_name
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function add_activity_name($data) {
        $datas = array('name' => $data['activityname'],
            'location' => $data['locact'],
            'skill' => $data['skill'],
            'career' => $data['career'],
            'sex' => $data['sex'],
            'generalised' => $data['generalised'],
            'specialised' => $data['specialised'],
            'field_expert' => $data['field_expert'],
            'created_by_city_id ' => $this->session->userdata('city_id'),
            'file' => $data['filename'],
            'link' => $data['link'],
        );
        $this->db->insert('Placement_Activity', $datas);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }

    /**
     * Function to getgroup_details
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Array()]
     * */
    function getactivity_details() {
        $this->db->select('*');
        $this->db->from('Placement_Activity');
        $this->db->order_by('id', 'DESC');
        $result = $this->db->get();
        return $result;
    }

    /**
     * Function to edit_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [ Array()]
     * */
    function edit_activity($user_id) {
        $this->db->select('*');
        $this->db->from('Placement_Activity');
        $this->db->where('id', $user_id);
        $result = $this->db->get();
        return $result;
    }

    /**
     * Function to update_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function update_activity($data) {
        $datas = array('name' => $data['activityname'],
            'location' => $data['locact'],
            'skill' => $data['skill'],
            'career' => $data['career'],
            'sex' => $data['sex'],
            'generalised' => $data['generalised'],
            'specialised' => $data['specialised'],
            'field_expert' => $data['field_expert'],
            'created_by_city_id ' => $this->session->userdata('city_id'),
            'file' => $data['filename'],
            'link' => $data['link'],
        );
        $this->db->where('id', $data['group_id']);
        $this->db->update('Placement_Activity', $datas);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /**
     * Function to delete_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean, ]
     * */
    function delete_activity($data) {
        $id = $data['entry_id'];
        $this->db->where('id', $id);
        $this->db->delete('Placement_Activity');

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /**
     * Function to add_group_name
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function add_event_name($data) {
        $datas = array('name' => $data['eventname'],
            'user_id' => $data['usid'],
            'started_on' => $data['datepick'],
            'placement_activity_id' => $data['activity_id'],
            'corporate_partner' => $data['corpname'],
            'corporate_volunteer_count' => $data['novol'],
            'corporate_poc' => $data['corpoc'],
            'cr_intern_user_id' => $data['crintrn'],
        );
        $this->db->insert('Placement_Event', $datas);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }

    function add_event_group_name($data) {
        $datas = array('placement_event_id' => $data['event_id'],
            'placement_group_id' => $data['group_id'],
        );
        $this->db->insert('Placement_Eventgroup', $datas);
    }

    /**
     * Function to getgroup_details
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Array()]
     * */
    function getevent_details() {
        $this->db->select('*');
        $this->db->from('Placement_Event');
        $this->db->order_by('id', 'DESC');
        $result = $this->db->get();
        return $result;
    }

    /**
     * Function to edit_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [ Array()]
     * */
    function edit_event($id) {
        $this->db->select('*');
        $this->db->from('Placement_Event');
        $this->db->where('id', $id);
        $result = $this->db->get();
        return $result;
    }

    /**
     * Function to add_group_name
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function update_event($data) {
        $datas = array('name' => $data['eventname'],
            'started_on' => $data['datepick'],
            'placement_activity_id' => $data['activity_id'],
            'corporate_partner' => $data['corpname'],
            'corporate_volunteer_count' => $data['novol'],
            'corporate_poc' => $data['corpoc'],
            'cr_intern_user_id' => $data['crintrn'],
        );
        $this->db->where('id', $data['event_id']);
        $this->db->update('Placement_Event', $datas);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /**
     * Function to delete_group
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean, ]
     * */
    function delete_event($data) {
        $id = $data['entry_id'];
        $this->db->where('id', $id);
        $this->db->delete('Placement_Event');

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    function getevent_feedback_details($event) {
        $this->db->select("Student.name,Student.id");
        $this->db->from('Placement_Eventgroup');
        $this->db->join('Placement_Studentgroup', 'Placement_Eventgroup.placement_group_id = Placement_Studentgroup.placement_group_id', 'left');
        $this->db->join('Student', 'Student.id = Placement_Studentgroup.student_id', 'left');
        $this->db->where('Placement_Eventgroup.placement_event_id =', $event);
        $result = $this->db->get();
        return $result;
    }

    
    
   function getevent_student_details($event)
   {
       
        $this->db->select("Placement_Eventstudent.student_id");
        $this->db->from('Placement_Eventstudent');        
        $this->db->where('Placement_Eventstudent.placement_event_id =', $event);
        $result = $this->db->get();
        return $result;
   }
   
   
    /* Function to add_feeedback
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */

    function add_feedback($data) {
        $datas = array('feedback_score' => $data['feedback_score'],
            'feedback_career' => $data['feedback_career'],
            'feedback_repeat' => $data['feedback_repeat'],
            'feedback_volunteer_count' => $data['feedback_volunteer_count'],
            'feedback_volunteer_repeat_strongly_agree' => $data['feedback_volunteer_repeat_strongly_agree'],
            'feedback_volunteer_repeat_agree' => $data['feedback_volunteer_repeat_agree'],
            'feedback_volunteer_repeat_neutral' => $data['feedback_volunteer_repeat_strongly_neutral'],
            'feedback_volunteer_repeat_disagree' => $data['feedback_volunteer_repeat_disagree'],
            'feedback_volunteer_repeat_strongly_disagree' => $data['feedback_volunteer_repeat_strongly_disagree'],
            'feedback_volunteer_engaging_strongly_agree' => $data['feedback_volunteer_engaging_strongly_agree'],
            'feedback_volunteer_engaging_agree' => $data['feedback_volunteer_engaging_agree'],
            'feedback_volunteer_engaging_neutral' => $data['feedback_volunteer_engaging_strongly_neutral'],
            'feedback_volunteer_engaging_disagree' => $data['feedback_volunteer_engaging_disagree'],
            'feedback_volunteer_engaging_strongly_disagree' => $data['feedback_volunteer_engaging_strongly_disagree'],
            'feedback_volunteer_suggestion' => $data['feedback_volunteer_suggestion'],
            'feedback_partner_engaging_strongly_agree' => $data['feedback_partner_engaging_strongly_agree'],
            'feedback_partner_engaging_agree' => $data['feedback_partner_engaging_agree'],
            'feedback_partner_engaging_neutral' => $data['feedback_partner_engaging_neutral'],
            'feedback_partner_engaging_disagree' => $data['feedback_partner_engaging_disagree'],
            'feedback_partner_engaging_strongly_disagree' => $data['feedback_partner_engaging_strongly_disagree'],
            'feedback_partner_rating_excelent' => $data['feedback_partner_rating_excelent'],
            'feedback_partner_rating_very_good' => $data['feedback_partner_rating_very_good'],
            'feedback_partner_rating_average' => $data['feedback_partner_rating_average'],
            'feedback_partner_rating_poor' => $data['feedback_partner_rating_poor'],
            'feedback_partner_rating_very_poor' => $data['feedback_partner_rating_very_poor'],
        );
        // print_r($data['attendance']);

        $this->db->where('id', $data['eventid']);
        $this->db->update('Placement_Event', $datas);

        foreach ($data['attendance'] as $attendance) {

            $this->db->select('placement_event_id,student_id');
            $this->db->from('Placement_Eventstudent');
            $this->db->where('placement_event_id', $data['eventid']);
            $this->db->where('student_id', $attendance);
            $result = $this->db->get();
            //echo "hello". $result->num_rows();
            if ($result->num_rows() == 0) {
                $attend = array(
                    'placement_event_id' => $data['eventid'],
                    'student_id' => $attendance,
                    'present' => '1',
                );
                $this->db->insert('Placement_Eventstudent', $attend);
            }
        }

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /* Function to get event calender
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */

    function getcalendar_event_details() {
        $this->db->select('started_on');
        $this->db->from('Placement_Event');        
        $result = $this->db->get();
        return $result;
        
    }
    
    function list_event($data) {
        $this->db->select('*');
        $this->db->from('Placement_Event');    
          $this->db->where('started_on', $data);
        $result = $this->db->get();
        return $result;
        
    }

    /**
     * Function to add_group_name
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function update_calendar_event($data) {
        $datas = array('name' => $data['eventname'],
            'started_on' => $data['datepick'],
            'placement_activity_id' => $data['activity_id'],
            'corporate_partner' => $data['corpname'],
            'corporate_volunteer_count' => $data['novol'],
            'corporate_poc' => $data['corpoc'],
            'cr_intern_user_id' => $data['crintrn'],
        );
        $this->db->where('id', $data['event_id']);
        $this->db->update('Placement_Event', $datas);
        
        
        
        
        foreach ($data['attendance'] as $attendance) {

            $this->db->select('placement_event_id,student_id');
            $this->db->from('Placement_Eventstudent');
            $this->db->where('placement_event_id', $data['event_id']);
            $this->db->where('student_id', $attendance);
            $result = $this->db->get();
            //echo "hello". $result->num_rows();
            if ($result->num_rows() == 0) {
                $attend = array(
                    'placement_event_id' => $data['event_id'],
                    'student_id' => $attendance,
                    'present' => '1',
                );
                $this->db->insert('Placement_Eventstudent', $attend);
            }
        }
        
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    
    
    
}
