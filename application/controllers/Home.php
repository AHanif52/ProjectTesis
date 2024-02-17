<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller{
    public function index(){
        $data['active_page'] = 'home';

        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('home_view.php');
        $this->load->view('template/footer.php');
    }
}
