<div class="card">
        <div class="card-header">
            <h1 class="fs-3">Histori Prediksi</h1>
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
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr style="text-align: center;">
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">Alpha</th>
                            <th style="width: 10%;">Beta</th>
                            <th style="width: 10%;">Gamma</th>
                            <th style="width: 10%;">Koefisien</th>
                            <th style="width: 25%;">MAPE</th>
                            <th>Tipe</th>
                            <th style="width: 10%;" >Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($detailPrediksi as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td style="text-align: right;"><?= $item['alpha'] ?></td>
                            <td style="text-align: right;"><?= $item['beta'] ?></td>
                            <td style="text-align: right;"><?= $item['gamma'] ?></td>
                            <td style="text-align: right;"><?= $item['koefisien'] ?></td>
                            <td style="text-align: right;"><?php echo round($item['mape'], 6); ?>%</td>
                            <td>
                                <?php
                                if ($item['tipe'] == '1') {
                                    echo 'Additive';
                                } elseif ($item['tipe'] == '2') {
                                    echo 'Multiplicative';
                                } else {
                                    echo 'Tipe tidak valid';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?= base_url('referensi/buka/' . $item['idPrediksi']) ?>" class="btn btn-secondary btn-sm">Detail</a>
                                <a href="<?= base_url('referensi/hapus/' . $item['idPrediksi']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
</div>    