<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth_check.php';
$statement = $pdo->prepare('SELECT full_name, email, username, phone, created_at FROM users WHERE id = :id');
$statement->execute(['id' => $_SESSION['user_id']]);
$user = $statement->fetch();
if (!$user) {
    set_flash('warning', 'Akun tidak ditemukan. Silakan masuk kembali.');
    header('Location: logout.php');
    exit;
}
$pageTitle = 'Profil Saya | NusantaraTrip';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>
<main class="container section-space"><div class="content-panel profile-page"><span class="eyebrow">INFORMASI AKUN</span><h1 class="h2 mt-2">Profil Saya</h1><p class="text-secondary">Informasi akun yang digunakan untuk masuk ke NusantaraTrip.</p><dl class="profile-list profile-list-wide"><dt>Nama lengkap</dt><dd><?= e($user['full_name']) ?></dd><dt>Email</dt><dd><?= e($user['email']) ?></dd><dt>Username</dt><dd><?= e($user['username']) ?></dd><dt>Nomor telepon</dt><dd><?= e($user['phone']) ?></dd><dt>Terdaftar sejak</dt><dd><?= e(date('d M Y', strtotime($user['created_at']))) ?></dd></dl><a class="btn btn-primary" href="dashboard.php">Kembali ke Dashboard</a></div></main>
<?php require __DIR__ . '/includes/footer.php'; ?>
