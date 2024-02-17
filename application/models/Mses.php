<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mses extends CI_Model{
    
    public function prediksi($alpha, $jual, $idProduk) {
        $n = count($jual);
        $ramalan = array();

        $bulan = array_column($jual, 'bulan');
        $sales = array_column($jual, 'jual');

        $ramalan[0] = $sales[0];
        for ($i = 1; $i < $n; $i++) {
            $ramalan[$i] = $ramalan[$i-1] + $alpha * ($sales[$i-1] - $ramalan[$i-1]);
            $ramalan[$i] = round($ramalan[$i], 3);
        }
        
        $ape = $this->hitungApe($sales, $ramalan);
    
        //ramalan bulan selanjutnya(ramalan paling akhir)
        $ramalan_terbaru = end($ramalan) + $alpha * (end($sales) - end($ramalan));
        $ramalan[] = round($ramalan_terbaru, 3);
        
        // Pisahkan bulan terakhir dan tambahkan 1 bulan pada array bulan
        $bulan_terakhir = end($bulan);        
        $bulan_selanjutnya = date('Y-m-d', strtotime("$bulan_terakhir +1 month"));
        $bulan[] = $bulan_selanjutnya;

        // Hubungkan kembali bulan dan forecast
        $result = array();
        foreach ($bulan as $key => $bulan) {
            $result[] = array(
                'bulan' => $bulan,
                'ramalan' => isset($ramalan[$key]) ? $ramalan[$key] : null,
                'ape' => isset($ape[$key]) ? $ape[$key] : null
            );
        }

        $data = array();
        foreach ($result as $item) {
            $data[] = array(
                'idProduk' => $idProduk,
                'alpha' => $alpha,
                'bulan' => $item['bulan'],
                'hasil' => $item['ramalan'],
                'ape' => $item['ape']
            );
        }
        $this->db->insert_batch('prediksi', $data);
 
        return $result;
    }

    function hitungApe($aktual, $ramalan) {
        if (count($aktual) !== count($ramalan)) {
            return "Error: Panjang array actual dan forecast tidak sama";
        }
    
        $n = count($aktual);
        $errorPersen = array();
    
        for ($i = 0; $i < $n; $i++) {
            // if ($aktual[$i] != 0) {
            //     $ape = abs($aktual[$i] - $ramalan[$i]) / $aktual[$i];
            //     $ape = $ape * 100;
            //     $errorPersen[] = $ape;
            // } else {
            //     $errorPersen[] = 0;
            // }
            //versi singkat dari kode diatas
            $errorPersen[$i] = ($aktual[$i] != 0) ? abs(($aktual[$i] - $ramalan[$i]) / $aktual[$i] * 100) : 0;
            $errorPersen[$i] = round($errorPersen[$i], 3);
        }

        return $errorPersen;
    }

    // function hitungMape($jual, $ramalan) {

    //     $aktual = array_column($jual, 'jual');
    //     $ramal = array_column($ramal, 'ramalan');

    //     $n = count($aktual);
    //     $jumlahError = 0;
    
    //     for ($i = 0; $i < $n; $i++) {
    //         if ($aktual[$i] != 0) {
    //             $error = abs($aktual[$i] - $ramal[$i])/$aktual[$i];
    //             $persenError = $error * 100;
    //             $jumlahError += $persenError;
    //         } else {
    //             $jumlahError += 0;
    //         }
    //     }
    
    //     $mape = $jumlahError / $n;
    //     return $mape;
    // }

}
