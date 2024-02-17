<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
class Penjualan extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('Mdata');
        $this->load->database();
    }

    public function index(){
        $itemsPerPage = 15;

        $totalItems = $this->Mdata->getTotalPenjualan();
    
        // Hitung total halaman
        $totalPages = ceil($totalItems / $itemsPerPage);
    
        // Ambil halaman yang diminta dari parameter URL
        $currentPage = $this->input->get('page') ?? 1;
        $currentPage = max(1, min($totalPages, $currentPage)); // Pastikan tidak melebihi batas halaman

        // Tentukan offset untuk query database
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Ambil data penjualan sesuai halaman
        $data['penjualan'] = $this->Mdata->getPenjualanPagination($itemsPerPage, $offset);
        $data['chart'] = $this->Mdata->getPenjualan()->result_array();

        // Masukkan data total halaman dan halaman saat ini ke dalam data yang akan dikirimkan ke tampilan
        $data['totalPages'] = $totalPages;
        $data['currentPage'] = $currentPage;

        $data['active_page'] = 'penjualan';

        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('penjualan_view.php', $data);
        $this->load->view('template/footer.php');
    }

    public function tambah(){
        $this->form_validation->set_rules('jual', 'Jumlah Penjualan', 'required|numeric', array(
            'required' => '%s Harus Diisi',
            'numeric' => '%s Harus Berupa Angka'
        ));
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required', array('required' => '%s Harus Diisi'));
        
        if($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('salah', validation_errors());
            redirect('penjualan');
        }
        else{
            $data = [
                'tanggal' => $this->input->post('tanggal'),
                'jmlhPenjualan' => $this->input->post('jual')
            ];
            $this->Mdata->tambahPenjualan($data);
            $this->session->set_flashdata('benar', 'Data berhasil ditambahkan');
            redirect('penjualan');
        }
    }

    public function edit($id){
        $data['penjualan'] = $this->Mdata->getPenjualanById($id);
        $data['active_page'] = 'penjualan';

        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('penjualan_edit.php', $data);
        $this->load->view('template/footer.php');
    }

    public function update(){
        $this->form_validation->set_rules('jual', 'Jumlah Penjualan', 'required|numeric', array('required' => '%s Harus Diisi'));
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required', array('required' => '%s Harus Diisi'));
        
        if($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('salah', validation_errors());
            redirect('penjualan/edit/'.$this->input->post('idPenjualan'));
        }
        else{
            $data = [
                'tanggal' => $this->input->post('tanggal'),
                'jmlhPenjualan' => $this->input->post('jual')
            ];
            $this->Mdata->updatePenjualan($data, $this->input->post('idPenjualan'));
            $this->session->set_flashdata('berhasil', 'Data berhasil diubah');
            redirect('penjualan');
        }
    }

    public function hapus($id){
        $this->Mdata->hapusPenjualan($id);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('berhasil', 'Data berhasil dihapus');
        }
        else{
            $this->session->set_flashdata('gagal', 'Data gagal dihapus');
        }
        redirect('penjualan');
    }

    public function excel(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $upload_status = $this->uploadDoc();
            if($upload_status!=false){
                $inputFileName = 'assets/uploads/imports/'.$upload_status;
                $inputTileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputTileType);
                $spreadsheet = $reader->load($inputFileName);
                $sheet = $spreadsheet->getSheet(0);
                $count_Rows = 0;
                $data = [];

                foreach ($sheet->getRowIterator(2) as $row) {
                    $rowData = $spreadsheet->getActiveSheet()->rangeToArray('A' . $row->getRowIndex() . ':Z' . $row->getRowIndex(), null, true, false);

                    if (empty(array_filter($rowData[0]))) {
                        continue; // Lewati baris jika seluruh kolom kosong
                    }               

                    $tanggalExcel = $spreadsheet->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue();
                    $tanggal = false;
                    // Pemeriksaan apakah kolom A tidak kosong dan memiliki tanggal yang valid
                    if (!empty($tanggalExcel) && $tanggalExcel != '0000-00-00') {
                        $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalExcel);
                        if ($tanggal !== false) {
                            $tanggalFormatted = $tanggal->format('Y-m-d');
                            $jmlhPenjualan = $spreadsheet->getActiveSheet()->getCell('B'.$row->getRowIndex())->getValue();
                            
                            // Pemeriksaan apakah data sudah ada dalam database
                            $existingData = $this->db->get_where('penjualan', ['tanggal' => $tanggalFormatted])->row_array();
                            if (empty($existingData)) {
                                $data[] = [
                                    'tanggal' => $tanggalFormatted,
                                    'jmlhPenjualan' => $jmlhPenjualan
                                ];
                            } else {
                                // Data sudah ada dalam database, bisa diabaikan atau ditangani sesuai kebutuhan
                                $this->session->set_flashdata('error', 'Data pada tanggal ' . $tanggalFormatted . ' sudah ada dalam database.');
                            }
                        } else {
                            // Tanggal tidak valid, mungkin perlu ditangani sesuai kebutuhan
                            $this->session->set_flashdata('error', 'Format tanggal tidak valid pada baris ' . $row->getRowIndex());
                            redirect('penjualan');
                        }
                    }
                    $count_Rows++;
                }
                
                if (!empty($data)) {
                    $this->Mdata->tambahPenjualanBatch($data);
                    $this->session->set_flashdata('success', 'Data berhasil diimport');
                    redirect('penjualan');
                } else {
                    $this->session->set_flashdata('error', 'Tidak ada data yang dapat diimport.');
                    redirect('penjualan');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Data gagal diimport');
                redirect('penjualan');
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Gagal upload file');
            redirect('penjualan');
        }
    }

    function uploadDoc(){
        $uploadPath = 'assets/uploads/imports/';
        if(!is_dir($uploadPath)){
            mkdir($uploadPath, 0777, TRUE); //buat bikin direktori
        }
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'csv|xlsx|xls';
        $config['max_size'] = '1000000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('file_excel')){
            $fileData = $this->upload->data(); 
            return $fileData['file_name'];;
        }
        else
        {
            return false;
        }
    }

}

