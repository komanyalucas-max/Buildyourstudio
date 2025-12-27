<?php
require_once 'cors.php';
require_once '../config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM storage_devices ORDER BY type, capacity_gb ASC");
    $storage = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transform for frontend if needed, but array of objects is fine
    // Frontend expects: type, capacity, price, etc.
    // Actually frontend `StorageSelector` currently has hardcoded structure. 
    // We will need to return simpler list and let frontend group it, or group it here.
    // Let's return raw list.

    // Convert to camelCase keys for JS consistency
    $data = array_map(function ($item) {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'type' => $item['type'],
            'capacityGB' => (int)$item['capacity_gb'],
            'priceUSD' => (float)$item['price_usd'],
            // We might need priceTZS here too if we want multi-currency for storage
            // Schema doesn't have price_tzs for storage_devices yet.
            // I should add it or just calculate it for now.
            'priceTZS' => (float)$item['price_usd'] * 2500 // Fallback conversion
        ];
    }, $storage);

    echo json_encode(['data' => $data]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
