<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$search = trim((string) ($_GET['q'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));
$destinations = $pdo->query('SELECT * FROM destinations ORDER BY id')->fetchAll();
$sql = 'SELECT p.*, d.name AS destination_name, d.location
        FROM packages p JOIN destinations d ON d.id = p.destination_id';
$conditions = [];
$params = [];
if ($search !== '') {
    $conditions[] = '(p.package_name LIKE :search_package OR d.name LIKE :search_destination OR d.location LIKE :search_location)';
    $params['search_package'] = '%' . $search . '%';
    $params['search_destination'] = '%' . $search . '%';
    $params['search_location'] = '%' . $search . '%';
}
if ($category !== '') {
    $conditions[] = 'p.category = :category';
    $params['category'] = $category;
}
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY p.id';
$packageQuery = $pdo->prepare($sql);
$packageQuery->execute($params);
$packages = $packageQuery->fetchAll();
$categories = $pdo->query('SELECT DISTINCT category FROM packages ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
$pageTitle = 'NusantaraTrip | Jelajahi Keindahan Indonesia';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
$flash = get_flash();
?>
<main>
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <span class="eyebrow text-white"><i class="bi bi-stars me-2"></i>JELAJAHI PESONA NUSANTARA</span>
                <h1 class="display-4 fw-bold mt-3">Jelajahi Keindahan Indonesia Bersama NusantaraTrip</h1>
                <p class="lead mt-3">Temukan destinasi istimewa, cerita baru, dan pengalaman perjalanan yang dirancang untuk Anda.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a class="btn btn-accent btn-lg" href="#paket">Jelajahi Paket Wisata <i class="bi bi-arrow-right ms-2"></i></a>
                    <a class="btn btn-outline-light btn-lg" href="#tentang">Kenali Kami</a>
                </div>
                <div class="hero-points mt-5"><span><i class="bi bi-shield-check"></i> Partner perjalanan tepercaya</span><span><i class="bi bi-geo-alt"></i> 6 destinasi pilihan</span><span><i class="bi bi-star-fill"></i> Pengalaman berkesan</span></div>
            </div>
        </div>
    </section>

    <section class="container search-panel" aria-label="Cari paket wisata">
        <form class="row g-3 align-items-end" method="get" action="index.php">
            <div class="col-lg-5"><label class="form-label" for="search">Cari destinasi atau paket</label><input class="form-control form-control-lg" id="search" name="q" value="<?= e($search) ?>" placeholder="Contoh: Labuan Bajo"></div>
            <div class="col-lg-4"><label class="form-label" for="category">Kategori wisata</label><select class="form-select form-select-lg" id="category" name="category"><option value="">Semua kategori</option><?php foreach ($categories as $item): ?><option value="<?= e((string) $item) ?>" <?= $category === $item ? 'selected' : '' ?>><?= e((string) $item) ?></option><?php endforeach; ?></select></div>
            <div class="col-lg-3 d-grid"><button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-search me-2"></i>Cari Paket</button></div>
        </form>
    </section>

    <?php if ($flash): ?><div class="container mt-4"><div class="alert alert-<?= e($flash['type']) ?> mb-0"><?= e($flash['message']) ?></div></div><?php endif; ?>

    <section class="container section-space" id="destinasi">
        <div class="section-heading"><div><span class="eyebrow">DESTINASI PILIHAN</span><h2>Destinasi Wisata Populer</h2></div><p>Temukan keindahan Indonesia dari pantai tropis hingga kekayaan budaya.</p></div>
        <div class="row g-4">
            <?php foreach ($destinations as $destination): ?>
                <div class="col-sm-6 col-lg-4"><article class="destination-card h-100"><img src="<?= e($destination['image']) ?>" alt="<?= e($destination['name']) ?>" loading="lazy"><div class="p-3"><span class="text-secondary small"><i class="bi bi-geo-alt me-1"></i><?= e($destination['location']) ?></span><h3 class="h5 mt-2"><?= e($destination['name']) ?></h3><p class="text-secondary mb-0"><?= e($destination['description']) ?></p><a class="stretched-link" aria-label="Lihat paket <?= e($destination['name']) ?>" href="index.php?q=<?= urlencode($destination['name']) ?>"></a></div></article></div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section-tint section-space" id="paket">
        <div class="container">
            <div class="section-heading"><div><span class="eyebrow">REKOMENDASI TERBAIK</span><h2>Paket Wisata Unggulan</h2></div><p>Informasi paket transparan untuk membantu Anda merencanakan perjalanan.</p></div>
            <?php if (!$packages): ?><div class="alert alert-light border">Tidak ada paket yang cocok dengan pencarian Anda.</div><?php endif; ?>
            <div class="row g-4">
                <?php foreach ($packages as $package): ?>
                    <div class="col-md-6 col-lg-4"><article class="package-card h-100"><img src="<?= e($package['image']) ?>" alt="<?= e($package['package_name']) ?>" loading="lazy"><div class="p-3 d-flex flex-column"><div class="d-flex justify-content-between gap-2"><span class="badge text-bg-light"><?= e($package['category']) ?></span><span class="small text-secondary"><i class="bi bi-clock me-1"></i><?= e($package['duration']) ?></span></div><h3 class="h5 mt-3"><?= e($package['package_name']) ?></h3><p class="text-secondary small mb-2"><i class="bi bi-geo-alt me-1"></i><?= e($package['destination_name']) ?>, <?= e($package['location']) ?></p><p class="text-secondary flex-grow-1"><?= e($package['description']) ?></p><div class="d-flex justify-content-between align-items-center mt-2"><div><small class="text-secondary d-block">Mulai dari</small><strong class="price"><?= e(format_rupiah($package['price'])) ?></strong></div><a class="btn btn-primary btn-sm" href="detail.php?id=<?= (int) $package['id'] ?>">Lihat Detail</a></div></div></article></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="container section-space" id="tentang">
        <div class="row align-items-center g-5">
            <div class="col-lg-6"><img class="about-image" src="https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=1200&q=85" alt="Wisatawan menikmati perjalanan di alam Indonesia" loading="lazy"></div>
            <div class="col-lg-6"><span class="eyebrow">TENTANG KAMI</span><h2 class="mt-2">Membuka Pintu Petualangan Autentik di Tanah Nusantara</h2><p class="text-secondary">NusantaraTrip adalah perusahaan travel dan tour yang menghadirkan informasi perjalanan domestik, destinasi pilihan, dan paket wisata dengan pelayanan yang hangat serta tepercaya.</p><div class="row g-3 mt-3"><div class="col-md-6"><div class="feature-box"><i class="bi bi-eye"></i><strong>Visi</strong><span>Menjadi teman perjalanan tepercaya untuk mengenal keindahan Indonesia.</span></div></div><div class="col-md-6"><div class="feature-box"><i class="bi bi-flag"></i><strong>Misi</strong><span>Menghadirkan informasi wisata yang inspiratif, jelas, dan menghargai budaya lokal.</span></div></div><div class="col-6"><div class="feature-box"><i class="bi bi-map"></i><strong>Destinasi pilihan</strong><span>Pengalaman lokal yang berkesan.</span></div></div><div class="col-6"><div class="feature-box"><i class="bi bi-cash-coin"></i><strong>Harga transparan</strong><span>Informasi paket jelas sejak awal.</span></div></div><div class="col-6"><div class="feature-box"><i class="bi bi-person-check"></i><strong>Pemandu profesional</strong><span>Didampingi tim berpengalaman.</span></div></div><div class="col-6"><div class="feature-box"><i class="bi bi-chat-dots"></i><strong>Siap membantu</strong><span>Dukungan selama merencanakan trip.</span></div></div></div><a class="btn btn-primary mt-4" href="register.php">Mulai Jelajahi <i class="bi bi-arrow-right ms-1"></i></a></div>
        </div>
    </section>

    <section class="section-tint section-space" id="testimoni">
        <div class="container">
            <div class="section-heading"><div><span class="eyebrow">CERITA WISATAWAN</span><h2>Pengalaman Bersama NusantaraTrip</h2></div><p>Testimoni contoh untuk menunjukkan pengalaman perjalanan para wisatawan.</p></div>
            <div class="row g-4">
                <div class="col-md-4"><blockquote class="testimonial-card h-100"><div class="testimonial-stars" aria-label="5 dari 5 bintang">★★★★★</div><p>“Perjalanan ke Labuan Bajo terasa nyaman. Informasi paket dan itinerary-nya jelas dari awal.”</p><footer><strong>Andi Wijaya</strong><span>Jakarta · Labuan Bajo</span></footer></blockquote></div>
                <div class="col-md-4"><blockquote class="testimonial-card h-100"><div class="testimonial-stars" aria-label="5 dari 5 bintang">★★★★★</div><p>“Pemandunya ramah dan membantu kami menikmati setiap destinasi di Yogyakarta.”</p><footer><strong>Clara Stephanie</strong><span>Surabaya · Yogyakarta</span></footer></blockquote></div>
                <div class="col-md-4"><blockquote class="testimonial-card h-100"><div class="testimonial-stars" aria-label="5 dari 5 bintang">★★★★★</div><p>“Trip keluarga ke Bali menyenangkan, pilihan tempatnya menarik untuk semua usia.”</p><footer><strong>Dimas Satria</strong><span>Bandung · Bali</span></footer></blockquote></div>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
