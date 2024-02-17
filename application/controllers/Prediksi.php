<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('Mtes');
        $this->load->model('Mdata');
    }

    public function index(){
        $data['active_page'] = 'prediksi';
        
        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('prediksi_view.php');
        $this->load->view('template/footer.php');
    }

    public function coba(){
        $data['active_page'] = 'prediksi';
        
        $this->form_validation->set_rules('alpha', 'Alpha', 'required|numeric|greater_than[0]|less_than[1]',
        array(
            'required' => '%s Harus Diisi',
            'numeric' => '%s harus berupa angka',
            'greater_than' => '%s harus lebih dari 0',
            'less_than' => '%s harus kurang dari 1'
        ));
        $this->form_validation->set_rules('beta', 'Beta', 'required|numeric|greater_than[0]|less_than[1]',
        array(
            'required' => '%s Harus Diisi',
            'numeric' => '%s harus berupa angka',
            'greater_than' => '%s harus lebih dari 0',
            'less_than' => '%s harus kurang dari 1'
        ));
        $this->form_validation->set_rules('gamma', 'Gamma', 'required|numeric|greater_than[0]|less_than[1]',
        array(
            'required' => '%s Harus Diisi',
            'numeric' => '%s harus berupa angka',
            'greater_than' => '%s harus lebih dari 0',
            'less_than' => '%s harus kurang dari 1'
        ));
        $this->form_validation->set_rules('koefisien', 'Panjang Musiman', 'required|numeric|greater_than[0]',
        array(
            'required' => '%s Harus Diisi',
            'numeric' => '%s harus berupa angka',
            'greater_than' => '%s harus lebih dari 0',
        ));
        $this->form_validation->set_rules('tipe', 'Tipe', 'required', array('required' => '%s Harus Diisi'));
        
        if($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('salah', validation_errors());
            redirect('prediksi');
        }
        else
        {
            $alpha = $this->input->post('alpha');
            $beta = $this->input->post('beta');
            $gamma = $this->input->post('gamma');
            $koefisien = $this->input->post('koefisien');
            $tipe = $this->input->post('tipe');

            $penjualan = $this->Mdata->getPenjualan()->result();
            $jual = array_column($penjualan, 'jmlhPenjualan');
            
            $ramalan = $this->Mtes->prediksi($jual, $alpha, $beta, $gamma, $tipe, $koefisien);
            $dataPeramalan = array();

            if ($ramalan !== null) {
                $dataPeramalan = $ramalan['ramalan'];
                $data['mape'] = $ramalan['mape'];
                $data['mse'] = $ramalan['mse'];
            }
            
            $tglBlmPtng = array_column($penjualan, 'tanggal');
            $tglPtg = array_slice($tglBlmPtng, 12);

            $tanggalTerakhir = end($tglPtg);

            $timestampTerakhir = strtotime($tanggalTerakhir);

            $array12BulanTahunDepan = array();
            for ($i = 1; $i <= 12; $i++) {
                $tanggalTerakhir = date('Y-m-d', strtotime('+' . $i . ' months', $timestampTerakhir));
                $array12BulanTahunDepan[] = $tanggalTerakhir;
            }
            $tanggal = array_merge($tglPtg, $array12BulanTahunDepan);
            $dataPenjualan = array_slice($jual, 12);

            $arrayAsosiatif = array();
            for ($i = 0; $i < count($tanggal); $i++) {
                $arrayPerTanggal = array();

                if (isset($dataPenjualan[$i])) {
                    $arrayPerTanggal['penjualan'] = $dataPenjualan[$i];
                } else {
                    $arrayPerTanggal['penjualan'] = 0;
                }
                $arrayPerTanggal['peramalan'] = $dataPeramalan[$i];
                $arrayAsosiatif[$tanggal[$i]] = $arrayPerTanggal;
            }

            $dataPrediksi = array(
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
                'koefisien' => $koefisien,
                'MAPE' => $data['mape'],
                'MSE' => $data['mse'],
                'tipe' => $tipe
            );
            $this->db->insert('prediksi', $dataPrediksi);
            $idPrediksi = $this->db->insert_id();

            foreach ($arrayAsosiatif as $tanggal => $dataPerTanggal) {
                $dataDetail = array(
                    'tanggal' => $tanggal,
                    'penjualan' => $dataPerTanggal['penjualan'],
                    'hasil' => $dataPerTanggal['peramalan'],
                    'idPrediksi' => $idPrediksi
                );
            
                $this->db->insert('detailprediksi', $dataDetail);
            }

            $data['forecasting'] = $arrayAsosiatif;
            
            $this->load->view('template/header.php');
            $this->load->view('template/sidebar.php', $data);
            $this->load->view('prediksi_view.php', $data);
            $this->load->view('template/footer.php');
        }
    }
    
    
}
