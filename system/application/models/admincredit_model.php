<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.in
 * @since		Version 1.0
 * @filesource
 */
class Admincredit_model extends Model {
    /*
     * constructor 
     */

    function Admincredit_model() {
        parent::model();
        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
    }

    /*
     * Function Name : get_credit()
     * Wroking :This function returns all the credit details of current logged user. 
     * @author:Rabeesh
     * @param :[]
     * @return: type: [Array]
     */

    function get_credit() {
        $current_user_id = $this->ci->session->userdata('id');
        return $this->db->query("SELECT AdminCredit.id, AdminCredit.user_id, AdminCredit.added_on,
				AdminCredit.awarded_by AS person_id,Task.name,Task.credit, Task.vertical FROM AdminCredit 
				INNER JOIN Task ON AdminCredit.task_id=Task.id 
				WHERE user_id='$current_user_id' ORDER BY id DESC")->result();
    }

    /*
     * Function Name : get_credits_awarded_by()
     * Wroking :This function returns all the credit awarded by details of current logged user. 
     * @author:Rabeesh
     * @param :[$user_id]
     * @return: type: [Array]
     */

    function get_credits_awarded_by($user_id=0) {
        if (!$user_id)
            $user_id = $this->ci->session->userdata('id');
        return $this->db->query("SELECT AdminCredit.id, AdminCredit.user_id, AdminCredit.added_on,  
				AdminCredit.user_id AS person_id,Task.name,Task.credit, Task.vertical FROM AdminCredit 
				INNER JOIN Task ON AdminCredit.task_id=Task.id 
				WHERE awarded_by='$user_id' ORDER BY id DESC")->result();
    }

    /*
     * Function Name : get_task()
     * Wroking :This function returns all Existing Tasks. 
     * @author:Rabeesh
     * @param :[]
     * @return: type: [Array]
     */

    function get_task() {

        return $this->db->query("SELECT * FROM Task ORDER BY id DESC")->result();
    }

    /*
     * Function Name : get_users()
     * Wroking :This function returns all the users from the current city. 
     * @author:Rabeesh
     * @param :[]
     * @return: type: [Array]
     */

    function get_users() {
        $city_id = $this->ci->session->userdata('city_id');
        $admin_user_group = 14;
        return $this->db->query("SELECT User.* FROM User INNER JOIN UserGroup ON UserGroup.user_id=User.id  WHERE UserGroup.group_id=$admin_user_group AND User.city_id='$city_id'")->result();
    }

    /*
     * Function Name : update_admincredits($data)
     * Wroking :This function used to Update the admin credits. 
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [Boolean]
     */

    function update_admincredits($data) {
        $current_user_id = $this->ci->session->userdata('id');
        $dtm = date("Y-m-d H:i:s");
        $task_id = $data['task_id'];
        $user_id = $data['user'];
        if ($this->input->post('reason')) {
            $reason = $this->input->post('reason');
            $credit = $this->input->post('credit');
            $event = $this->db->query("UPDATE User SET admin_credit='$credit' WHERE id='$user_id'");

            $this->db->insert("AdminCredit", array(
                'user_id' => $data['user'],
                'task_id' => $data['task_id'],
                'awarded_by' => $current_user_id,
                'reason' => $reason,
                'added_on' => $dtm,
                'credit' => $credit,
                'vertical' => $this->input->post('type')
            ));
        } else {
            $reason = '';
            $event = $this->db->query("SELECT * FROM Task WHERE id='$task_id'")->row();
            $credit = $event->credit;
            $vertical = $event->vertical;
            $event = $this->db->query("UPDATE User SET admin_credit='$credit' WHERE id='$user_id'");

            $this->db->insert("AdminCredit", array(
                'user_id' => $data['user'],
                'task_id' => $data['task_id'],
                'awarded_by' => $current_user_id,
                'reason' => $reason,
                'added_on' => $dtm,
                'credit' => $credit,
                'vertical' => $vertical
            ));
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : get_alladmincredit($data)
     * Wroking :This function return all the admin credits in that city.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [Boolean]
     */

    function get_alladmincredit() {
        $current_user_id = $this->ci->session->userdata('id');
        return $this->db->query("SELECT `AdminCredit`.*, `User`.`name` as username,`User`.`id` as userid, `Task`.* 
				FROM (`AdminCredit`) JOIN `User` ON `User`.`id` = `AdminCredit`.`user_id` 
				JOIN `Task` ON `Task`.`id` = `AdminCredit`.`task_id` 
				WHERE `User`.`city_id` = '{$this->city_id}' ORDER BY AdminCredit.id DESC")->result();
    }

    /*
     * Function Name : delete_admincredit($data)
     * Wroking :This function deletes the admin credits.
     * @author:Rabeesh
     * @param :[$admincredit_id]
     * @return: type: [Boolean]
     */

    function delete_admincredit($admincredit_id) {
        $this->db->query("DELETE FROM AdminCredit WHERE id=$admincredit_id");
    }

}

