<?php
require_once 'config.php';

try {
    echo "Checking database schema updates...\n";

    // Add price_tzs if not exists
    try {
        $pdo->query("SELECT price_tzs FROM products LIMIT 1");
        echo " - price_tzs column already exists.\n";
    } catch (Exception $e) {
        echo " - Adding price_tzs column...\n";
        $pdo->exec("ALTER TABLE products ADD COLUMN price_tzs DECIMAL(10, 2) DEFAULT 0.00 AFTER price_usd");
    }

    // Add tier if not exists
    try {
        $pdo->query("SELECT tier FROM products LIMIT 1");
        echo " - tier column already exists.\n";
    } catch (Exception $e) {
        echo " - Adding tier column...\n";
        $pdo->exec("ALTER TABLE products ADD COLUMN tier ENUM('cheap', 'medium', 'expensive') DEFAULT 'medium' AFTER price_tzs");
    }

    echo "Schema update completed.\n";
} catch (PDOException $e) {
    echo "Error updating schema: " . $e->getMessage();
}
