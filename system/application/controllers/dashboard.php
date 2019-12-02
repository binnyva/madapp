<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends Controller  {
    function dashboard() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->accessControl();
    }

    function dashboard_view() {
        $data['title'] = 'MADApp';

        set_city_year($this);

        $user_id = $this->session->userdata('user_id');

        $this->load->view('layout/flatui/header',$data);
        $this->load->view('common_dashboard/common_dashboard');
        $this->load->view('layout/flatui/footer',$data);
    }
}
