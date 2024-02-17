<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referensi extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Mtes');
        $this->load->model('Mdata');
    }

    public function index(){
        $data['detailPrediksi'] = $this->Mdata->getDetailPrediksi();
        $data['active_page'] = 'referensi';
        
        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('referensi_view.php');
        $this->load->view('template/footer.php');
    }

    public function buka($idPrediksi){
        $data['detailPrediksi'] = $this->Mdata->getDetailbyId($idPrediksi);
        $data['active_page'] = 'referensi';

        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('referensi_detail.php', $data);
        $this->load->view('template/footer.php');
    }

    public function hapus($idPrediksi){
        $this->Mdata->hapusPrediksi($idPrediksi);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('benar', 'Data berhasil dihapus');
        }
        else{
            $this->session->set_flashdata('salah', 'Data gagal dihapus');
        }    
        redirect('referensi');
    }
}
