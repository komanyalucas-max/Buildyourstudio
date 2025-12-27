<?php
require_once 'cors.php';

try {
    // Get all products
    $stmt = $pdo->query("
        SELECT 
            p.id,
            p.name,
            p.description,
            p.price_usd as priceUSD,
            p.price_tzs as priceTZS,
            p.tier,
            p.category_id as category,
            p.image_url as imageUrl,
            p.file_size_gb as fileSize,
            p.features,
            c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.category_id, p.name
    ");
    $products = $stmt->fetchAll();

    // Decode features JSON for frontend compatibility
    foreach ($products as &$product) {
        if ($product['features']) {
            $product['features'] = json_decode($product['features']);
        }
    }

    echo json_encode(['data' => $products]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
