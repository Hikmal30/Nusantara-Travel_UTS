<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold brand-mark" href="index.php"><i class="bi bi-compass-fill me-2"></i>NusantaraTrip</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <a class="nav-link" href="index.php">Beranda</a>
                <a class="nav-link" href="index.php#destinasi">Destinasi</a>
                <a class="nav-link" href="index.php#paket">Paket Wisata</a>
                <a class="nav-link" href="index.php#tentang">Tentang Kami</a>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a class="btn btn-outline-primary btn-sm px-3" href="dashboard.php">Dashboard</a>
                    <a class="nav-link" href="logout.php">Keluar</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Masuk</a>
                    <a class="btn btn-primary btn-sm px-3" href="register.php">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
