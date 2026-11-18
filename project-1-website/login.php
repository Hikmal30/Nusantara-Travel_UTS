<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
$identifier = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim((string) ($_POST['identifier'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if (!verify_csrf_token()) {
        $error = 'Sesi formulir tidak valid. Muat ulang halaman dan coba kembali.';
    } elseif ($identifier === '' || $password === '') {
        $error = 'Email/username dan kata sandi wajib diisi.';
    } else {
        $statement = $pdo->prepare('SELECT id, full_name, email, username, phone, password FROM users WHERE email = :email OR username = :username LIMIT 1');
        $statement->execute(['email' => $identifier, 'username' => $identifier]);
        $user = $statement->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            set_flash('success', 'Selamat datang kembali, ' . $user['full_name'] . '!');
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Email/username atau kata sandi tidak sesuai.';
    }
}
$pageTitle = 'Masuk | NusantaraTrip';
$pageScript = 'validation.js';
require __DIR__ . '/includes/header.php';
$flash = get_flash();
?>
<main class="auth-page">
    <div class="container">
        <div class="auth-top"><a class="navbar-brand fw-bold brand-mark" href="index.php"><i class="bi bi-compass-fill me-2"></i>NusantaraTrip</a><a href="index.php">← Kembali ke Beranda</a></div>
        <div class="auth-card">
            <aside class="auth-aside login-aside"><span class="eyebrow text-white">EKSPOLORASI INDONESIA</span><h2>Jelajahi Indonesia, simpan setiap kenangan indah bersama NusantaraTrip.</h2><p>Temukan cerita baru di setiap perjalanan.</p><div class="auth-aside-note"><i class="bi bi-shield-check me-2"></i>Informasi perjalanan tepercaya</div></aside>
            <section class="auth-form"><h1>Selamat Datang Kembali</h1><p class="text-secondary">Masuk untuk melihat rekomendasi destinasi dan paket wisata.</p>
                <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
                <form method="post" id="loginForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <div class="mb-3"><label class="form-label" for="identifier">Email atau username</label><input class="form-control form-control-lg" id="identifier" name="identifier" value="<?= e($identifier) ?>" autocomplete="username" required><div class="invalid-feedback">Masukkan email atau username.</div></div>
                    <div class="mb-3"><label class="form-label" for="password">Kata sandi</label><div class="input-group input-group-lg"><input class="form-control" id="password" type="password" name="password" autocomplete="current-password" required><button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Tampilkan kata sandi"><i class="bi bi-eye"></i></button></div><div class="invalid-feedback">Masukkan kata sandi.</div></div>
                    <button class="btn btn-primary btn-lg w-100 mt-2" type="submit">Masuk ke Akun <i class="bi bi-arrow-right ms-1"></i></button>
                </form>
                <p class="text-center mt-4 mb-0">Belum memiliki akun? <a href="register.php" class="fw-semibold">Daftar sekarang</a></p>
            </section>
        </div>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
