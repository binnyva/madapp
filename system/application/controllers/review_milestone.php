<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_milestone extends Controller  {
    function review_milestone() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if(!$logged_user_id) {
            redirect('auth/login');
        }


    }


    function dashboard_view() {

        $data['title'] = 'Review and Milestones';

        $this->load->view('layout/flatui/header',$data);
        $this->load->view('review_milestone/review_milestone_dashboard');
        $this->load->view('layout/flatui/footer',$data);
    }
}