<div class="container ps-0 pe-0">
    <div class="row  ps-0">
        <div class="col-7 ps-0">
            <div class="card">
                <div class="card-header">
                    <h1 class="fs-3">Data Penjualan per Bulan</h1>
                </div>
                <div class="card-body">
                <?php if($this->session->flashdata('berhasil')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                    <?= $this->session->flashdata('berhasil') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('gagal')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                    <?= $this->session->flashdata('gagal') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap">
                            <thead>
                                <tr >
                                    <th style="width: 5px; text-align: center;">No</th>
                                    <th style="width: 110px; text-align: center;" >Tanggal</th>
                                    <th style="width: 130px; text-align: center;">Penjualan</th>
                                    <th style="width: 60px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penjualan as $index => $sale): ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $index + 1 ?></td>
                                        <td><?= $sale['tanggal'] ?></td>
                                        <td style="text-align: right;"><?= $sale['jmlhPenjualan'] ?></td>
                                        <td style="text-align: center;">
                                            <a href="<?= base_url('penjualan/edit/' . $sale['idPenjualan']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?= base_url('penjualan/hapus/' . $sale['idPenjualan']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('penjualan?page=' . ($currentPage - 1)) ?>" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <a class="page-link" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                            <?php endif; ?>

                            <!-- Tampilkan halaman-halaman yang tersedia -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= base_url('penjualan?page=' . $i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('penjualan?page=' . ($currentPage + 1)) ?>">Next</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <a class="page-link">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-5 pe-0">
            <div class="card">
                <div class="card-header">
                    <h1 class="fs-3">Menambah data penjualan</h1>
                </div>
                <div class="card-body">
                <?php if($this->session->flashdata('benar')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                    <?= $this->session->flashdata('benar') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('salah')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                    <?= $this->session->flashdata('salah') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                    <form action="<?= base_url('penjualan/tambah') ?>" method="post">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal (Usahakan gunakan akhir tanggal per bulan)</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal">
                        </div>
                        <div class="mb-3">
                            <label for="jual" class="form-label">Jumlah Penjualan (pcs)</label>
                            <input type="number" class="form-control" id="jual" name="jual" placeholder="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <h1 class="fs-4">Menambahkan Data dari Excel (xlsx/xls)</h1>
                </div>
                <div class="card-body">
                    <!-- Form tambah data dari Excel -->
                    <form action="<?= base_url('penjualan/excel') ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">Unggah Excel</label>
                            <input type="file" class="form-control" id="file_excel" name="file_excel" accept=".xls, .xlsx" required>
                            <div class="mt-1">
                                <span class="text-secondary">File yang harus diupload : .xls, xlsx</span>
                            </div>
                            <?php if($this->session->flashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <?= $this->session->flashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <?php if($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <?= $this->session->flashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-success">Unggah Excel</button>
                    </form>
                </div>
            </div>
            <br>
        <?php if (!empty($chart)): ?>
            <div class="card">
                <div class="card-header">
                        <h1 class="fs-4">Chart Data Penjualan</h1>
                </div>
                <div class="card-body">
                    <canvas id="penjualanChart" width="400" height="200"></canvas>
                </div>
            </div>
                
                <!-- Script JavaScript untuk Chart -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var ctx = document.getElementById('penjualanChart').getContext('2d');
                        var chartData = {
                            labels: <?= json_encode(array_column($chart, 'tanggal')) ?>,
                            data: <?= json_encode(array_column($chart, 'jmlhPenjualan')) ?>
                        };
                        
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartData.labels,
                                datasets: [{
                                    label: 'Penjualan',
                                    data: chartData.data,
                                    // backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>
        <?php endif; ?>
        </div>
    </div>
</div>

