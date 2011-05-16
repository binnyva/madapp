<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 * @package		madapp
 * @author		Rabeesh
 */
class Settings_model extends Model {
    function Settings_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
    }
    /**
    * Function to getsettings
    * @author : Rabeesh
    * @param  : [$data]
    * @return : type: [Array]
    **/
    function getsettings() {
    	$settings = $this->db->orderby('name')->get('Setting')->result();
    	return $settings;
    }
    /**
    * Function to addsetting
    * @author : Rabeesh
    * @param  : [$data]
    * @return : type: [Array]
    **/
    function addsetting($data) {
		$success = $this->db->insert('Setting', 
			array(
				'name'			=>	$data['name'], 
				'value'	=>	$data['value'],
				'data'		=>	$data['data'],
			));
		
		
    }
    /**
    * Function to editsetting
    * @author : Rabeesh
    * @param  : [$data]
    * @return : type: [Array]
    **/
    function editsetting($data) {
    	$this->db->where('id', $this->input->post('id'))->update('Setting', $data);
    }
    /**
    * Function to get_settings
    * @author : Rabeesh
    * @param  : [$data]
    * @return : type: [Array]
    **/
    function get_settings($setting_id) {
    	return $this->db->where('id',$setting_id)->get('Setting')->row_array();
    }
    
	function deletesetting($id)
	{
	$this->db->where('id', $id)->delete('Setting');
	
	}
}
