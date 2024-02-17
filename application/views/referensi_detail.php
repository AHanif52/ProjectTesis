<div class="container ps-0 pe-0">
    <div class="row ps-0 pe-0">
        <div class="col-12 ps-0 pe-0">
            <?php if (!empty($detailPrediksi)): ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h1 class="fs-4">Histori Prediksi</h1>
                            </div>
                            <div class="col-1 ps-4">
                                <a href="<?= base_url('referensi') ?>" class="btn btn-primary btn-sm ps-2">Kembali</a>    
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nilai Aktual</th>
                                        <th>Hasil Peramalan</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($detailPrediksi as $index => $item): ?>
                                    <tr>
                                        <td><?php echo $item['tanggal']; ?></td>
                                        <td><?php echo $item['penjualan']; ?></td>
                                        <td><?php echo $item['hasil']; ?></td>
                                        <td><?php echo ($item['penjualan'] - $item['hasil']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="result-container">
                            <h6>MAPE: <?php echo number_format($item['mape'], 2); ?> %</h6>
                            <h6>MSE: <?php echo number_format($item['mse'], 3); ?></h6>
                            
                            <?php
                            $mape = round($item['mape'], 3);
                            $mse = round($item['mse'], 3);
                            $message = "<p>Model peramalan memiliki tingkat akurasi ";
                            if ($mape < 5) {
                                $message .= "sangat baik, dengan nilai MAPE yang rendah sebesar <b>$mape</b> % dan MSE sebesar <b>$mse</b>. Hal ini menunjukkan bahwa algoritma ini dapat dijadikan rekomendasi untuk melakukan prediksi stok.</p>";
                            } elseif ($mape>= 5 && $mape <= 15) {
                                $message .= "kurang baik, dengan nilai MAPE sebesar  <b>$mape</b> % dan MSE sebesar <b>$mse</b>. Algoritma ini memberikan hasil yang dapat diterima untuk peramalan stok, namun perlu dipertimbangkan untuk melakukan evaluasi lebih lanjut.</p>";
                            } else {
                                $message .= "buruk, dengan nilai MAPE yang tinggi sebesar  <b>$mape</b> % dan MSE sebesar <b>$mse</b>. Perlu dilakukan evaluasi mendalam dan perbaikan pada model peramalan untuk mendapatkan hasil yang lebih akurat.</p>";
                            }

                            echo $message;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <div class="row ps-0 pe-0">
        <div class="col-12 ps-0 pe-0">
        <?php if (!empty($detailPrediksi)): ?>
            <?php
                // Mengambil data dari $detailPrediksi
                $tanggalArray = array();
                $penjualanArray = array();
                $hasilArray = array();
                foreach ($detailPrediksi as $item) {
                    $tanggalArray[] = $item['tanggal'];
                    $penjualanArray[] = $item['penjualan'];
                    $hasilArray[] = $item['hasil'];
                }

                $jumlahData = count($penjualanArray);
                $dataTerakhir = array_slice($penjualanArray, 0, $jumlahData-12);
            ?>
            <div class="card">
                <div class="card-header">
                    <h1 class="fs-5">Hasil Prediksi</h1>
                </div>
                <div class="card-body">
                    <canvas id="myChart" width="450" height="150"></canvas>
                </div>
            </div>
            <script>
                const labels = <?php echo json_encode($tanggalArray); ?>;
                const actualData = <?php echo json_encode($dataTerakhir); ?>;
                console.log(actualData);
                const forecastData = <?php echo json_encode($hasilArray); ?>;

                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Nilai Aktual',
                                data: actualData,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                fill: false
                            },
                            {
                                label: 'Hasil Peramalan',
                                data: forecastData,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                fill: false
                            }
                        ]
                    }
                });
            </script>
        <?php endif; ?>
        </div>
    </div>
</div>