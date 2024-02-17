<?php defined('BASEPATH') or exit('No direct script access allowed');

class Mtes extends CI_Model{

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function prediksi($data, $alpha, $beta, $gamma, $tipe, $koefisien){
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $ramalan = array();
        $Ls = $this->iniLevel($data, $koefisien);
        $ts = $this->iniTren($data, $koefisien);
        $ms = array();

        if ($tipe == 1) {
            $ramalan = $this->additive($data, $alpha, $beta, $gamma, $Ls, $ts, $koefisien);
        } else {
            $ramalan = $this->multiplicative($data, $alpha, $beta, $gamma, $Ls, $ts, $koefisien);
        }
        
        $aktual = array_slice($data, $koefisien);
        $aktual = array_map('floatval', $aktual);
        $ramal_e = array_slice($ramalan, 0, count($ramalan) - $koefisien);

        if (count($aktual) !== count($ramal_e)) {
            print_r("Jumlah data aktual dan ramalan tidak sama");
        }

        $mape = 0;
        $mse = 0;
        for ($i = 0; $i < count($aktual); $i++) {
            $mape += abs(($aktual[$i] - $ramal_e[$i])/ $aktual[$i]);
            $mse += pow($aktual[$i] - $ramal_e[$i], 2);
        }

        $mape = ($mape/count($ramal_e)) * 100;
        $mse = $mse / count($ramal_e);

        return array(
            'ramalan' => $ramalan,
            'mape' => $mape,
            'mse' => $mse
        );
    }
    
    public function iniLevel($data, $koefisien){
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $sum = array_sum(array_slice($data, 0, $koefisien));
        $level = $sum / $koefisien;
    
        return $level;
    }

    public function iniTren($data, $koefisien){
        if (!is_array($data) || empty($data)) {
            return null;
        }
        
        $koef2 = $koefisien * 2;
        $Y = array_slice($data, 0, $koef2);
        $tren = 0;
        $sum = 0;
        for ($i = 0; $i < $koefisien; $i++) {
            $sum += ($Y[$i + $koefisien] - $Y[$i]) / $koefisien;
        }
    
        $tren = $sum / $koefisien;
    
        return $tren;
    }

    public function multiplicative($data, $alpha, $beta, $gamma, $Ls, $ts, $koefisien){
        $musim = array_slice($data, 0, $koefisien);
        $ms = array();
        for ($i = 0; $i < $koefisien; $i++) {
            $ms[] = $musim[$i] / $Ls;
        }

        $Y = array_slice($data, $koefisien);
        $n = count($Y);
        $ramalan = array();
        $Ls_1 = 0;

        for ($i = 0; $i < $n; $i++) {
            $Ls_1 = $Ls;
            $ramalan[] = ($Ls + $ts) * $ms[$i];

            $Ls = $alpha * ($Y[$i] / $ms[$i]) + (1 - $alpha) * ($Ls + $ts);
            $ts = $beta * ($Ls - $Ls_1) + (1 - $beta) * $ts;
            $ms[] = $gamma * ($Y[$i] / $Ls) + (1 - $gamma) * $ms[$i];
        }
        
        $levelakhir = $Ls;
        $trenakhir = $ts;
        $last12Data = array_slice($ms, -$koefisien);

        for ($i = 0; $i < $koefisien; $i++) {
            $ramalan[] = ($levelakhir + (($i+1) * $trenakhir)) * $last12Data[$i];
        }

        return $ramalan;
    }

    public function additive($data, $alpha, $beta, $gamma, $Ls, $ts, $koefisien){
        $musim = array_slice($data, 0, $koefisien);
        $ms = array();
        for ($i = 0; $i < $koefisien; $i++) {
            $ms[] = $musim[$i] - $Ls;
        }

        $Y = array_slice($data, $koefisien);
        $n = count($Y);
        $ramalan = array();
        $Ls_1 = 0;
        $ts_1 = 0;

        for ($i = 0; $i < $n; $i++) {
            $Ls_1 = $Ls;
            $ramalan[] = ($Ls + $ts) + $ms[$i];

            $Ls = $alpha * ($Y[$i] - $ms[$i]) + (1 - $alpha) * ($Ls + $ts);
            $ts = $beta * ($Ls - $Ls_1) + (1 - $beta) * $ts;
            $ms[] = $gamma * ($Y[$i] - $Ls) + (1 - $gamma) * $ms[$i];
        }
        
        $levelakhir = $Ls;
        $trenakhir = $ts;
        $last12Data = array_slice($ms, -$koefisien);
        
        for ($i = 0; $i < $koefisien; $i++) {
            $ramalan[] = $levelakhir + $last12Data[$i] + (($i+1) * $trenakhir);
        }

        return $ramalan;
    }
    
}
