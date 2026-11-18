<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth_check.php';
$userQuery = $pdo->prepare('SELECT id, full_name, email, username, phone, created_at FROM users WHERE id = :id');
$userQuery->execute(['id' => $_SESSION['user_id']]);
$user = $userQuery->fetch();
if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}
$counts = [
    'packages' => (int) $pdo->query('SELECT COUNT(*) FROM packages')->fetchColumn(),
    'destinations' => (int) $pdo->query('SELECT COUNT(*) FROM destinations')->fetchColumn(),
    'categories' => (int) $pdo->query('SELECT COUNT(DISTINCT category) FROM packages')->fetchColumn(),
];
$packages = $pdo->query('SELECT p.*, d.name AS destination_name FROM packages p JOIN destinations d ON d.id = p.destination_id ORDER BY p.id')->fetchAll();
$pageTitle = 'Dashboard | NusantaraTrip';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
$flash = get_flash();
?>
<main class="container section-space">
    <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
    <section class="dashboard-welcome"><div><span class="eyebrow text-white">PORTAL ANGGOTA NUSANTARATRIP</span><h1 class="h2 mt-2">Selamat datang, <?= e($user['full_name']) ?>!</h1><p class="mb-0">Temukan inspirasi perjalanan dan lihat paket wisata pilihan kami.</p></div><a class="btn btn-accent" href="index.php#paket">Jelajahi Paket</a></section>
    <div class="row g-3 my-4">
        <div class="col-md-4"><div class="stat-card"><span>Total Paket Wisata</span><strong><?= $counts['packages'] ?></strong><i class="bi bi-suitcase"></i></div></div>
        <div class="col-md-4"><div class="stat-card"><span>Total Destinasi</span><strong><?= $counts['destinations'] ?></strong><i class="bi bi-geo-alt"></i></div></div>
        <div class="col-md-4"><div class="stat-card"><span>Kategori Wisata</span><strong><?= $counts['categories'] ?></strong><i class="bi bi-grid"></i></div></div>
    </div>
    <div class="row g-4">
        <section class="col-lg-8" id="data-paket"><div class="content-panel"><div class="d-flex justify-content-between align-items-center mb-3"><div><span class="eyebrow">REKOMENDASI</span><h2 class="h4 mb-0">Daftar Paket Wisata</h2></div><a href="index.php#paket">Lihat semua</a></div><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Paket</th><th>Destinasi</th><th>Durasi</th><th>Harga</th><th></th></tr></thead><tbody><?php foreach ($packages as $package): ?><tr><td class="fw-semibold"><?= e($package['package_name']) ?><div class="small text-secondary"><?= e($package['category']) ?></div></td><td><?= e($package['destination_name']) ?></td><td><?= e($package['duration']) ?></td><td><?= e(format_rupiah($package['price'])) ?></td><td><a class="btn btn-sm btn-outline-primary" href="detail.php?id=<?= (int) $package['id'] ?>">Detail</a></td></tr><?php endforeach; ?></tbody></table></div></div></section>
        <aside class="col-lg-4" id="profil"><div class="content-panel"><span class="eyebrow">PROFIL AKUN</span><h2 class="h4 mt-2"><?= e($user['full_name']) ?></h2><p class="text-secondary mb-3">Anggota NusantaraTrip</p><dl class="profile-list"><dt>Email</dt><dd><?= e($user['email']) ?></dd><dt>Username</dt><dd><?= e($user['username']) ?></dd><dt>Telepon</dt><dd><?= e($user['phone']) ?></dd></dl><a class="btn btn-outline-primary w-100" href="profile.php">Kelola Profil Saya</a><a class="btn btn-link text-danger w-100 mt-2" href="logout.php">Keluar (Logout)</a></div></aside>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
