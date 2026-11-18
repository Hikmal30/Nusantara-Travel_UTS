<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
$values = ['full_name' => '', 'email' => '', 'username' => '', 'phone' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $_) {
        $values[$key] = trim((string) ($_POST[$key] ?? ''));
    }
    $password = (string) ($_POST['password'] ?? '');
    $confirmation = (string) ($_POST['password_confirmation'] ?? '');
    if (!verify_csrf_token()) {
        $error = 'Sesi formulir tidak valid. Muat ulang halaman dan coba kembali.';
    } elseif (in_array('', $values, true) || $password === '' || $confirmation === '') {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Kata sandi minimal 8 karakter.';
    } elseif ($password !== $confirmation) {
        $error = 'Konfirmasi kata sandi tidak sama.';
    } elseif (empty($_POST['terms'])) {
        $error = 'Anda harus menyetujui syarat dan ketentuan.';
    } else {
        $statement = $pdo->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
        $statement->execute(['email' => $values['email'], 'username' => $values['username']]);
        if ($statement->fetch()) {
            $error = 'Email atau username sudah digunakan.';
        } else {
            $insert = $pdo->prepare('INSERT INTO users (full_name, email, username, phone, password) VALUES (:full_name, :email, :username, :phone, :password)');
            $insert->execute([
                'full_name' => $values['full_name'],
                'email' => $values['email'],
                'username' => $values['username'],
                'phone' => $values['phone'],
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            set_flash('success', 'Pendaftaran berhasil. Silakan masuk menggunakan akun Anda.');
            header('Location: login.php');
            exit;
        }
    }
}
$pageTitle = 'Daftar | NusantaraTrip';
$pageScript = 'validation.js';
require __DIR__ . '/includes/header.php';
?>
<main class="auth-page">
    <div class="container">
        <div class="auth-top"><a class="navbar-brand fw-bold brand-mark" href="index.php"><i class="bi bi-compass-fill me-2"></i>NusantaraTrip</a><a href="index.php">← Kembali ke Beranda</a></div>
        <div class="auth-card register-card">
            <aside class="auth-aside register-aside"><span class="eyebrow text-white">KOMUNITAS PETUALANG NUSANTARA</span><h2>Temukan Cerita,<br>Jelajahi Indonesia.</h2><p>Gabung dan mulai rencanakan petualangan tak terlupakan.</p><div class="auth-aside-note"><i class="bi bi-map me-2"></i>Itinerary terkurasi · Pemandu profesional</div></aside>
            <section class="auth-form"><h1>Buat Akun Baru</h1><p class="text-secondary">Isi data berikut untuk bergabung bersama NusantaraTrip.</p>
                <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
                <form method="post" id="registerForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label" for="full_name">Nama lengkap</label><input class="form-control" id="full_name" name="full_name" value="<?= e($values['full_name']) ?>" required><div class="invalid-feedback">Nama lengkap wajib diisi.</div></div>
                        <div class="col-md-6"><label class="form-label" for="email">Alamat email</label><input class="form-control" id="email" name="email" type="email" value="<?= e($values['email']) ?>" required><div class="invalid-feedback">Masukkan email yang valid.</div></div>
                        <div class="col-md-6"><label class="form-label" for="username">Username</label><input class="form-control" id="username" name="username" value="<?= e($values['username']) ?>" required><div class="invalid-feedback">Username wajib diisi.</div></div>
                        <div class="col-md-6"><label class="form-label" for="phone">Nomor telepon</label><input class="form-control" id="phone" name="phone" type="tel" value="<?= e($values['phone']) ?>" required><div class="invalid-feedback">Nomor telepon wajib diisi.</div></div>
                        <div class="col-md-6"><label class="form-label" for="password">Kata sandi (min. 8 karakter)</label><div class="input-group"><input class="form-control" id="password" name="password" type="password" minlength="8" required><button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Tampilkan kata sandi"><i class="bi bi-eye"></i></button></div><div class="invalid-feedback">Kata sandi minimal 8 karakter.</div></div>
                        <div class="col-md-6"><label class="form-label" for="password_confirmation">Konfirmasi kata sandi</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required><div class="invalid-feedback">Konfirmasi harus sama dengan kata sandi.</div></div>
                    </div>
                    <div class="form-check my-3"><input class="form-check-input" id="terms" name="terms" type="checkbox" required><label class="form-check-label" for="terms">Saya menyetujui syarat dan ketentuan.</label><div class="invalid-feedback">Persetujuan wajib diberikan.</div></div>
                    <button class="btn btn-primary btn-lg w-100" type="submit">Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i></button>
                </form>
                <p class="text-center mt-4 mb-0">Sudah memiliki akun? <a href="login.php" class="fw-semibold">Masuk di sini</a></p>
            </section>
        </div>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
