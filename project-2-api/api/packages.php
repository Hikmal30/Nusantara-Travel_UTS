<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/controllers/PackageController.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = require dirname(__DIR__) . '/config/database.php';
    $model = new Package($pdo);
    $controller = new PackageController($model, $pdo);
    $idValue = $_GET['id'] ?? null;
    $id = $idValue === null ? null : filter_var($idValue, FILTER_VALIDATE_INT);
    if ($idValue !== null && ($id === false || $id < 1)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID harus berupa bilangan bulat positif', 'data' => null], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $body = null;
    if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'], true)) {
        $rawBody = file_get_contents('php://input');
        $body = json_decode($rawBody, true);
        if (!is_array($body) || json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Request body harus berupa JSON yang valid', 'data' => null], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    $controller->handle($_SERVER['REQUEST_METHOD'], $id === false ? null : $id, $body);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada database', 'data' => null], JSON_UNESCAPED_UNICODE);
}
