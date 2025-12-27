<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM storage_devices WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Handle error
    }
}

header("Location: storage.php");
exit;
