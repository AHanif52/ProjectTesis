<body>
<div class="main-container d-flex">
    <div class="sidebar" id="side_nav">
        <div class="header-box px-3 pt-3 pb-2 d-flex justify-content-between">
            <h1 class="fs-4">
                <span class="brand-initials">AP</span>
                <span class="brand-name">Aplikasi Prediksi</span>
            </h1>
            <button class="btn d-md-none d-block close-btn px-1 py-0 text-white">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </div>
            <hr class="h-color mx-2">
            <ul class="list-unstyled px-2 nav-pills flex-column mb-auto">
                <li class="<?= ($active_page == 'home') ? 'active' : ''; ?>">
                    <a href="<?= base_url('home')?>" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fa-solid fa-house"></i>
                        <span class="ps-1">Dashboard</span>
                    </a>
                </li>
                <li class="<?= ($active_page == 'penjualan') ? 'active' : ''; ?>">
                    <a href="<?= base_url('penjualan')?>" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fa-solid fa-receipt"></i>
                        <span class="ps-2">Data Penjualan</span>
                    </a>
                </li>
                <li class="<?= ($active_page == 'prediksi') ? 'active' : ''; ?>">
                    <a href="<?= base_url('prediksi')?>" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="ps-1">Prediksi</span>
                    </a>
                </li>
                <li class="<?= ($active_page == 'referensi') ? 'active' : ''; ?>">
                    <a href="<?= base_url('referensi')?>" class="text-decoration-none px-3 py-2 d-block">
                    <i class="fa-regular fa-bookmark"></i>
                        <span class="ps-2">Histori Prediksi</span>
                    </a>
                </li>
            </ul>
            <hr class="h-color mx-2">
    </div>
    <div class="content">
        <nav class="navbar navbar-expand-md navbar-light bg-light d-md-none d-block">
            <div class="container-fluid">
                <div class="d-flex justify-content-between d-md-none d-block">
                    <button class="btn px-1 py-0 open-btn me-2"><i class="fa-solid fa-bars-staggered"></i></button>
                    <a class="navbar-brand fs-4" href="#"><span class="brand-initials bg-dark text-white">AP</span></a>   
                </div>
            </div>
        </nav>
        <div class="dashboard-content pt-3">