<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

if (empty($_SESSION['user_id'])) {
    set_flash('warning', 'Silakan masuk terlebih dahulu untuk membuka dashboard.');
    header('Location: login.php');
    exit;
}
