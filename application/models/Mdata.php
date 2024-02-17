<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdata extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function getPenjualan(){
		return $this->db->get('penjualan');
	}

	public function getPenjualanPagination($limit, $offset) {
		$this->db->select('idPenjualan, tanggal, jmlhPenjualan');
		$this->db->from('penjualan');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getTotalPenjualan() {
		return $this->db->count_all('penjualan'); // Sesuaikan dengan nama tabel yang sesuai
	}

	public function getPenjualanById($idPenjualan){
		$this->db->select('idPenjualan, tanggal, jmlhPenjualan');
		$this->db->from('penjualan');
		$this->db->where('idPenjualan', $idPenjualan);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function tambahPenjualan($data){
		$this->db->insert('penjualan', $data);
	}

	public function tambahPenjualanBatch($data){
		$this->db->insert_batch('penjualan', $data);
	}

	public function updatePenjualan($data, $id){
		$this->db->where('idPenjualan', $id);
		$this->db->update('penjualan', $data);
	}
	
	public function hapusPenjualan($id){
		$this->db->where('idPenjualan', $id);
		$this->db->delete('penjualan');
	}

	public function getDetailPrediksi(){
		$this->db->select('idPrediksi, alpha, beta, gamma, tipe, mape, koefisien');
		$this->db->from('prediksi');
		$this->db->order_by('idPrediksi', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getDetailbyId($idPrediksi){
		$this->db->select('d.tanggal, d.penjualan, d.hasil, p.mse, p.mape, ');
		$this->db->from('detailPrediksi as d');
		$this->db->join('prediksi as p', 'p.idPrediksi = d.idPrediksi');
		$this->db->where('p.idPrediksi', $idPrediksi);
		$this->db->group_by('d.tanggal');        
        $query = $this->db->get();
        return $query->result_array();
	}

	public function hapusPrediksi($idPrediksi){
		$this->hapusDetail($idPrediksi);
		$this->db->where('idPrediksi', $idPrediksi);
		$this->db->delete('prediksi');
	}

	public function hapusDetail($idPrediksi){
		$this->db->where('idPrediksi', $idPrediksi);
		$this->db->delete('detailPrediksi');
	}
}