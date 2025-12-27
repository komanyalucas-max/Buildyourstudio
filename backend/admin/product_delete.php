<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // In a real app we might pass the error via session to show it
    }
}

header("Location: products.php");
exit;
