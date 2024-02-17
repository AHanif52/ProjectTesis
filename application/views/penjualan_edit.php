<div class="container ps-0 pe-0">
    <div class="row ps-0 pe-0">
        <div class="col-6 ps-0 pe-0">
            <div class="card">
                <div class="card-header">
                    <h1 class="fs-3">Mengubah data penjualan</h1>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('salah')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                        <?= $this->session->flashdata('salah') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('penjualan/update') ?>" method="post">
                        <input type="hidden" name="idPenjualan" value="<?= $penjualan[0]['idPenjualan'] ?>">    
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal (Usahakan gunakan akhir tanggal per bulan)</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?= set_value('tanggal', $penjualan[0]['tanggal']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="jual" class="form-label">Jumlah Penjualan (pcs)</label>
                            <input type="number" class="form-control" id="jual" name="jual" placeholder="0" value="<?= set_value('jmlhPenjualan', $penjualan[0]['jmlhPenjualan']) ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Edit</button>
                        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary btn-sm">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6 ps-0 pe-0">
        </div>
    </div>
</div>
    