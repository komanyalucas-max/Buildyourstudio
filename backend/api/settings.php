<?php
require_once 'cors.php';
require_once '../config.php';

header('Content-Type: application/json');

try {
    // Fetch General Settings
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'general'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $general = $row ? json_decode($row['setting_value'], true) : [];

    // Determine Admin URL dynamically based on script location
    // Script is in /backend/api/settings.php
    // Admin is in /backend/admin/
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname(dirname($_SERVER['SCRIPT_NAME'])); // /backend
    $adminUrl = $protocol . "://" . $host . $path . '/admin/';

    $data = [
        'systemName' => $general['system_name'] ?? 'Studio Builder',
        'logoUrl' => $general['logo_url'] ?? '',
        'adminUrl' => $adminUrl
    ];

    echo json_encode(['data' => $data]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
