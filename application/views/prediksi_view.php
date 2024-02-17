<div class="container ps-0 pe-0">
    <div class="row ps-0 pe-0">
        <div class="col-8 ps-0 pe-0">
            <div class="card">
                <div class="card-header">
                    <h1 class="fs-5">Prediksi dengan Triple Exponential Smoothing</h1>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('salah')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                        <?= $this->session->flashdata('salah') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('prediksi/coba') ?>" method="post">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="alpha" class="form-label">Masukkan nilai error prediksi atau alpha antara 0,01 sampai 0,99:</label>
                                <input type="number" class="form-control" id="alpha" name="alpha" placeholder="Masukkan nilai alpha (0.01-0.99)" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="beta" class="form-label">Masukkan nilai penyesuaian tren atau beta antara 0,01 sampai 0,99:</label>
                                <input type="number" class="form-control" id="beta" name="beta" placeholder="Masukkan nilai beta (0.01-0.99)" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="gamma" class="form-label">Masukkan nilai kontrol musiman atau gamma antara 0,01 sampai 0,99:</label>
                                <input type="number" class="form-control" id="gamma" name="gamma" placeholder="Masukkan nilai gamma (0.01-0.99)" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-6">
                                <label for="koefisien" class="form-label">Masukkan nilai panjang musiman:</label>
                                <input type="number" class="form-control" id="koefisien" name="koefisien" placeholder="Masukkan nilai panjang musiman (nilai lebih dari 0)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="tipe" class="form-label">Tipe</label>
                                <select class="form-select" id="tipe" name="tipe" required>
                                    <option value="1">Additive</option>
                                    <option value="2">Multiplicative</option>
                                </select>
                            </div>
                        </div>
                    </div>   
                        <button type="submit" class="btn btn-primary btn-sm">Prediksi</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-4 pe-0">
            <div class="card">
                <div class="card-header">
                    <h1 class="fs-5">Keterangan</h1>
                </div>
                <div class="card-body">
                    <b>Petunjuk Pengisian Panjang Musiman</b>
                        <ol style="text-align: justify;">
                            <li>Lihat grafik pada halaman data penjualan dan cari pola siklus yang berulang secara teratur. Pola ini menunjukkan fluktuasi yang terjadi pada waktu yang sama setiap siklusnya.</li>
                            <li>Hitung berapa kali pola tersebut terulang. Jika Anda melihat pola yang sama setiap 12 bulan, maka panjang musiman adalah 12 bulan.</li>
                        </ol>
                    <p><b>Petunjuk Pengisian Tipe</b><br>
                    Tipe <b>additive</b> artinya pola musiman pada data cenderung konsisten, sedangkan tipe <b>multiplicative</b> artinya pola musiman pada data cenderung fluktiatif</p>
                    
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row ps-0 pe-0">
        <div class="col-12 ps-0 pe-0">
            <?php if (!empty($forecasting)): ?>
                <div class="card">
                    <div class="card-header">
                        <h1 class="fs-5">Hasil Prediksi</h1>
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
                                <?php foreach ($forecasting as $tanggal => $dataPerTanggal): ?>
                                    <tr>
                                        <td><?php echo $tanggal; ?></td>
                                        <td><?php echo $dataPerTanggal['penjualan']; ?></td>
                                        <td><?php echo $dataPerTanggal['peramalan']; ?></td>
                                        <td><?php echo ($dataPerTanggal['penjualan'] - $dataPerTanggal['peramalan']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="result-container">
                            <h6>MAPE: <?php echo round($mape, 2); ?> %</h6>
                            <h6>MSE: <?php echo round($mse, 2); ?></h6>
                            
                            <?php
                            $mape = round($mape, 2);
                            $mse = round($mse, 2);
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
            <?php if (!empty($forecasting)): ?>
                <div class="card">
                    <div class="card-header">
                        <h1 class="fs-5">Hasil Prediksi</h1>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="450" height="150"></canvas>
                    </div>
                </div>
                <script>
                    // Ambil data dari PHP dan konversi ke JavaScript
                    const forecastingData = <?php echo json_encode($forecasting); ?>;

                    // Ambil label dan data untuk Chart.js
                    const labels = Object.keys(forecastingData);
                    const actualData = labels.map(label => forecastingData[label]['penjualan']).filter(value => value !== 0);
                    const forecastData = labels.map(label => forecastingData[label]['peramalan']);

                    // Chart.js konfigurasi
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
    