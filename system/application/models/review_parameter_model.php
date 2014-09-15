<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_parameter_model extends Model {
	function Review_parameter_model() {
		parent::model();
		$this->ci = &get_instance();
		//$this->city_id = $this->ci->session->userdata('city_id'); // This maybe run from a cron job.
		$this->project_id = 1;//$this->ci->session->userdata('project_id');
	}

	function get($review_parameter_id, $type, $cycle, $user_id, $name='') {
		$this->db->from('Review_Data');
		$this->db->where('review_parameter_id',$review_parameter_id)->where('type',$type);
		$this->db->where('cycle',$cycle)->where('user_id', $user_id);
		if($name) $this->db->where('name',$name);

		return $this->db->get()->row();
	}
	
	function delete($name, $cycle) {
		return $this->db->where('name',$name)->where('cycle',$cycle)->delete('Review_Data');
	}
	
	function save($data) {
		if(!isset($data['type'])) $data['type'] = 'parameter';
		
		$review = $this->get($data['review_parameter_id'], $data['type'], $data['cycle'], $data['user_id'], $data['name']); // Check for existance
		if($review) $this->db->update('Review_Data', $data, array('id'=>$review->id));
		else $this->db->insert('Review_Data', $data);
	}
	function save_value($parameter_id, $value) {
		$this->db->update('Review_Data',array('value' => $value), array('id'=> $parameter_id));
	}

	function get_reviews($user_id, $cycle, $type) {
		if($type == 'milestone') {
			return $this->db->query("SELECT M.name,D.* FROM Review_Data D INNER JOIN Review_Milestone M ON D.review_parameter_id=M.id
				WHERE D.user_id=$user_id AND D.cycle=$cycle AND D.type='$type'")->result();

		} elseif($type == 'parameter') {
			return $this->db->query("SELECT P.name,D.* FROM Review_Data D INNER JOIN Review_Parameter P ON D.review_parameter_id=P.id
				WHERE D.user_id=$user_id AND D.cycle=$cycle AND D.type='$type'")->result();
		
		} elseif($type == 'survey') {
			return $this->db->query("SELECT D.*, Q.question AS name,D.name AS description FROM Review_Data D INNER JOIN SS_Question Q ON D.review_parameter_id=Q.id
				WHERE D.user_id=$user_id AND D.cycle=$cycle AND D.type='$type' ORDER BY description")->result();
		}
	}
	
	function set_comment($parameter_id) {
		$comment = $this->input->post('comment');
		$this->db->update('Review_Data',array('comment' => $comment), array('id'=> $parameter_id));
	}
	
	function get_comment($parameter_id) {
		$data = $this->db->select('comment')->from('Review_Data')->where('id',$parameter_id)->get()->row();
		return $data->comment;
	}


	/////////////////////////// Parameters //////////////////////
	function get_all_review_parameters($vertical_id=0) {
		$where = array();
		if($vertical_id) $where['vertical_id'] = $vertical_id;

		$this->db->from("Review_Parameter");
		if($where) $this->db->where($where);
		$parameters = $this->db->get()->result();

		return $parameters;
	}

	/////////////////////// Stakeholder Survey /////////////////
	function get_all_ss_parameters($vertical_id = 0) {
		$where = array();
		if($vertical_id) $where['vertical_id'] = $vertical_id;

		$this->db->from("Review_SS_Parameter");
		if($where) $this->db->where($where);
		$parameters = $this->db->get()->result();

		return $parameters;	
	}


	////////////////////////// Milestone stuff //////////////////

	function get_all_milestones($user_id, $cycle=0) {
		$this->db->from('Review_Milestone')->where('user_id', $user_id);
		if($cycle) $this->db->where('cycle',$cycle);
		$this->db->orderby('due_on ASC');

		return $this->db->get()->result();
	}

	function get_overdue_milestones($user_id, $cycle) {
		$this->db->from('Review_Milestone')->where('user_id', $user_id)->where('cycle < ', $cycle)->where('status', '0');

		return $this->db->get()->result();
	}

	function get_milestone($milestone_id) {
		return $this->db->from('Review_Milestone')->where('id', $milestone_id)->get()->row();
	}

	function edit_milestone($milestone_id, $data) {
		return $this->db->update('Review_Milestone',$data, array('id'=>$milestone_id));
	}

	function delete_milestone($milestone_id) {
		$user_id = $this->db->select('user_id')->from("Review_Milestone")->where('id', $milestone_id)->get()->row();

		$this->db->where('id',$milestone_id)->delete('Review_Milestone');

		return $user_id->user_id;
	}


	function create_milestone($data) {
		return $this->db->insert('Review_Milestone',$data);
	}

	function get_timeframes_with_milestone($user_id) {
		return $this->db->query("SELECT DISTINCT cycle FROM Review_Milestone WHERE user_id=$user_id ORDER BY cycle")->result();
	}

	function do_milestone($milestone_id, $status = '1', $done_on = 0) {
		if(!$done_on) $done_on = date('Y-m-d H:i:s');
		else $done_on = date('Y-m-d H:i:s', strtotime($done_on));

		$this->edit_milestone($milestone_id, array('status' => $status, 'done_on' => $done_on));
	}

	function find_cycle($due_on) {
		return 1; // :TODO:
	}
}
