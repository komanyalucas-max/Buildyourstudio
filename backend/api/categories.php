<?php
require_once 'cors.php';

try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC");
    $categories = $stmt->fetchAll();
    echo json_encode(['data' => $categories]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
