<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

session_name('nusa_karsa_session');
session_start();

function respond(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function request_data(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (str_contains($contentType, 'application/json')) {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    return $_POST;
}

function database(): PDO
{
    static $connection = null;
    if ($connection instanceof PDO) {
        return $connection;
    }

    $configFile = __DIR__ . '/config.php';
    if (!is_file($configFile)) {
        respond(['success' => false, 'message' => 'Konfigurasi database belum dibuat. Salin api/config.example.php menjadi api/config.php.'], 500);
    }

    $config = require $configFile;
    try {
        $connection = new PDO(
            sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['db_host'], $config['db_port'], $config['db_name']),
            $config['db_user'],
            $config['db_pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        return $connection;
    } catch (PDOException) {
        respond(['success' => false, 'message' => 'Koneksi database gagal. Periksa api/config.php dan pastikan MySQL menyala.'], 500);
    }
}

function require_login(): int
{
    $userId = $_SESSION['user_id'] ?? null;
    if (!is_int($userId) && !ctype_digit((string) $userId)) {
        respond(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
    }
    return (int) $userId;
}
