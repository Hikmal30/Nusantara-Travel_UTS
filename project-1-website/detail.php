<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$statement = $pdo->prepare('SELECT p.*, d.name AS destination_name, d.location, d.image AS destination_image
    FROM packages p JOIN destinations d ON d.id = p.destination_id WHERE p.id = :id');
$statement->execute(['id' => $id ?: 0]);
$package = $statement->fetch();
$pageTitle = $package ? $package['package_name'] . ' | NusantaraTrip' : 'Paket Tidak Ditemukan | NusantaraTrip';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>
<main class="container section-space">
    <?php if (!$package): ?>
        <div class="empty-state text-center"><i class="bi bi-map"></i><h1 class="h3 mt-3">Informasi paket tidak tersedia</h1><p class="text-secondary">Paket yang Anda cari mungkin sudah tidak tersedia atau tautannya tidak valid.</p><a class="btn btn-primary" href="index.php#paket">Kembali ke paket wisata</a></div>
    <?php else: ?>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Beranda</a></li><li class="breadcrumb-item"><a href="index.php#paket">Paket Wisata</a></li><li class="breadcrumb-item active" aria-current="page"><?= e($package['package_name']) ?></li></ol></nav>
        <div class="detail-hero"><img src="<?= e($package['image']) ?>" alt="<?= e($package['package_name']) ?>"><div><span class="badge text-bg-light mb-3"><?= e($package['category']) ?></span><h1><?= e($package['package_name']) ?></h1><p><i class="bi bi-geo-alt me-1"></i><?= e($package['destination_name']) ?>, <?= e($package['location']) ?></p><div class="detail-meta"><span><i class="bi bi-clock me-2"></i><?= e($package['duration']) ?></span><span><i class="bi bi-people me-2"></i><?= (int) $package['quota'] ?> kuota</span></div></div></div>
        <div class="row g-4 mt-2">
            <div class="col-lg-8">
                <section class="content-panel mb-4"><span class="eyebrow">SEKILAS PERJALANAN</span><h2 class="h4 mt-2">Deskripsi Paket</h2><p><?= nl2br(e($package['description'])) ?></p><h3 class="h5 mt-4">Itinerary perjalanan</h3><p class="mb-0"><?= nl2br(e($package['itinerary'])) ?></p></section>
                <div class="row g-4"><div class="col-md-6"><section class="content-panel h-100"><h2 class="h5"><i class="bi bi-check-circle text-success me-2"></i>Termasuk</h2><p class="mb-0"><?= nl2br(e($package['included_facilities'])) ?></p></section></div><div class="col-md-6"><section class="content-panel h-100"><h2 class="h5"><i class="bi bi-x-circle text-danger me-2"></i>Tidak termasuk</h2><p class="mb-0"><?= nl2br(e($package['excluded_facilities'])) ?></p></section></div></div>
            </div>
            <aside class="col-lg-4"><div class="content-panel price-panel"><span class="text-secondary small">MULAI DARI</span><p class="price display-6 mb-0"><?= e(format_rupiah($package['price'])) ?></p><span class="text-secondary">per orang</span><hr><p><i class="bi bi-calendar-check me-2 text-primary"></i>Informasi paket tersedia</p><a class="btn btn-primary w-100" href="index.php#paket">Lihat Paket Lainnya</a><a class="btn btn-outline-primary w-100 mt-2" href="index.php">Kembali ke Beranda</a></div></aside>
        </div>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
