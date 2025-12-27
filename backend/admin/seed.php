<?php
require_once __DIR__ . '/../config.php';

$categories = [
    ['id' => 'computer', 'name' => 'Computer', 'icon' => 'Laptop', 'description' => 'The brain of your studio', 'sort_order' => 1],
    ['id' => 'microphones', 'name' => 'Microphones', 'icon' => 'Mic', 'description' => 'Essential for recording', 'sort_order' => 2],
    ['id' => 'soundcard', 'name' => 'Sound Card', 'icon' => 'Radio', 'description' => 'Audio Interface', 'sort_order' => 3],
    ['id' => 'headphones', 'name' => 'Headphones', 'icon' => 'Headphones', 'description' => 'Monitor your recordings', 'sort_order' => 4],
    ['id' => 'monitors', 'name' => 'Monitor Speakers', 'icon' => 'Speaker', 'description' => 'Accurate sound reproduction', 'sort_order' => 5],
    ['id' => 'midi', 'name' => 'MIDI Keyboards', 'icon' => 'Piano', 'description' => 'Control virtual instruments', 'sort_order' => 6],
    ['id' => 'accessories', 'name' => 'Accessories', 'icon' => 'Package', 'description' => 'Stands, cables, etc.', 'sort_order' => 7],
    ['id' => 'acoustics', 'name' => 'Acoustics', 'icon' => 'Music2', 'description' => 'Room treatment', 'sort_order' => 8],
    ['id' => 'instruments', 'name' => 'Instruments', 'icon' => 'Guitar', 'description' => 'Guitars, drums, etc.', 'sort_order' => 9],
];

$products = [
    // COMPUTERS
    ['id' => 'laptop-cheap-1', 'category_id' => 'computer', 'name' => 'HP Pavilion 15 - i5', 'price_usd' => 450, 'price_tzs' => 650000, 'tier' => 'cheap', 'image_url' => 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?w=400'],
    ['id' => 'laptop-medium-1', 'category_id' => 'computer', 'name' => 'Dell Inspiron 15 - i7', 'price_usd' => 750, 'price_tzs' => 1200000, 'tier' => 'medium', 'image_url' => 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?w=400'],
    ['id' => 'laptop-expensive-1', 'category_id' => 'computer', 'name' => 'MacBook Pro M2', 'price_usd' => 1500, 'price_tzs' => 2500000, 'tier' => 'expensive', 'image_url' => 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?w=400'],

    // MICS
    ['id' => 'mic-cheap-1', 'category_id' => 'microphones', 'name' => 'Behringer XM8500', 'price_usd' => 30, 'price_tzs' => 80000, 'tier' => 'cheap', 'image_url' => 'https://images.unsplash.com/photo-1689771455000-7ca175aa03fe?w=400'],
    ['id' => 'mic-medium-1', 'category_id' => 'microphones', 'name' => 'Audio-Technica AT2020', 'price_usd' => 150, 'price_tzs' => 400000, 'tier' => 'medium', 'image_url' => 'https://images.unsplash.com/photo-1689771455000-7ca175aa03fe?w=400'],
    ['id' => 'mic-expensive-1', 'category_id' => 'microphones', 'name' => 'Shure SM7B', 'price_usd' => 400, 'price_tzs' => 1100000, 'tier' => 'expensive', 'image_url' => 'https://images.unsplash.com/photo-1689771455000-7ca175aa03fe?w=400'],

    // HEADPHONES
    ['id' => 'hp-cheap-1', 'category_id' => 'headphones', 'name' => 'Audio-Technica M20x', 'price_usd' => 50, 'price_tzs' => 135000, 'tier' => 'cheap', 'image_url' => 'https://images.unsplash.com/photo-1718217028088-a23cb3b277c4?w=400'],
    ['id' => 'hp-medium-1', 'category_id' => 'headphones', 'name' => 'Beyerdynamic DT 770', 'price_usd' => 160, 'price_tzs' => 450000, 'tier' => 'medium', 'image_url' => 'https://images.unsplash.com/photo-1718217028088-a23cb3b277c4?w=400'],
];

$storage_devices = [
    // USB
    ['id' => 'usb-32', 'name' => 'USB Flash Drive', 'type' => 'usb', 'capacity_gb' => 32, 'price_usd' => 10],
    ['id' => 'usb-64', 'name' => 'USB Flash Drive', 'type' => 'usb', 'capacity_gb' => 64, 'price_usd' => 15],
    ['id' => 'usb-128', 'name' => 'USB Flash Drive', 'type' => 'usb', 'capacity_gb' => 128, 'price_usd' => 25],

    // HDD
    ['id' => 'hdd-256', 'name' => 'Hard Drive', 'type' => 'hdd', 'capacity_gb' => 256, 'price_usd' => 30],
    ['id' => 'hdd-500', 'name' => 'Hard Drive', 'type' => 'hdd', 'capacity_gb' => 500, 'price_usd' => 45],
    ['id' => 'hdd-1000', 'name' => 'Hard Drive', 'type' => 'hdd', 'capacity_gb' => 1000, 'price_usd' => 60],
    ['id' => 'hdd-2000', 'name' => 'Hard Drive', 'type' => 'hdd', 'capacity_gb' => 2000, 'price_usd' => 90],

    // SATA SSD
    ['id' => 'sata-256', 'name' => 'SATA SSD', 'type' => 'sata-ssd', 'capacity_gb' => 256, 'price_usd' => 40],
    ['id' => 'sata-500', 'name' => 'SATA SSD', 'type' => 'sata-ssd', 'capacity_gb' => 500, 'price_usd' => 65],
    ['id' => 'sata-1000', 'name' => 'SATA SSD', 'type' => 'sata-ssd', 'capacity_gb' => 1000, 'price_usd' => 110],
    ['id' => 'sata-2000', 'name' => 'SATA SSD', 'type' => 'sata-ssd', 'capacity_gb' => 2000, 'price_usd' => 200],

    // NVMe SSD
    ['id' => 'nvme-256', 'name' => 'NVMe SSD', 'type' => 'nvme-ssd', 'capacity_gb' => 256, 'price_usd' => 50],
    ['id' => 'nvme-500', 'name' => 'NVMe SSD', 'type' => 'nvme-ssd', 'capacity_gb' => 500, 'price_usd' => 80],
    ['id' => 'nvme-1000', 'name' => 'NVMe SSD', 'type' => 'nvme-ssd', 'capacity_gb' => 1000, 'price_usd' => 140],
    ['id' => 'nvme-2000', 'name' => 'NVMe SSD', 'type' => 'nvme-ssd', 'capacity_gb' => 2000, 'price_usd' => 250],
];

try {
    // Clear existing (implicit commit, so do outside transaction)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE products");
    $pdo->exec("TRUNCATE TABLE categories");
    $pdo->exec("TRUNCATE TABLE storage_devices");

    // Modify ENUM to support new types if not already
    try {
        $pdo->exec("ALTER TABLE storage_devices CHANGE COLUMN type type ENUM('usb', 'hdd', 'sata-ssd', 'nvme-ssd') NOT NULL");
    } catch (Exception $e) { /* Ignore if already exists or fails, likely already correct or flexible */
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $pdo->beginTransaction();

    // Insert Categories
    $stmtCat = $pdo->prepare("INSERT INTO categories (id, name, icon, description, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($categories as $c) {
        $stmtCat->execute([$c['id'], $c['name'], $c['icon'], $c['description'], $c['sort_order']]);
    }

    // Insert Products
    $stmtProd = $pdo->prepare("INSERT INTO products (id, category_id, name, price_usd, price_tzs, tier, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmtProd->execute([$p['id'], $p['category_id'], $p['name'], $p['price_usd'], $p['price_tzs'], $p['tier'], $p['image_url']]);
    }

    // Insert Storage Devices
    $stmtStorage = $pdo->prepare("INSERT INTO storage_devices (id, name, type, capacity_gb, price_usd) VALUES (?, ?, ?, ?, ?)");
    foreach ($storage_devices as $s) {
        $stmtStorage->execute([$s['id'], $s['name'], $s['type'], $s['capacity_gb'], $s['price_usd']]);
    }

    $pdo->commit();
    echo "Seed successful. Inserted " . count($categories) . " categories, " . count($products) . " products, and " . count($storage_devices) . " storage devices.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Seed failed: " . $e->getMessage();
}
