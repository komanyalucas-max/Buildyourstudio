<?php
require_once 'cors.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $orderId = 'ord_' . uniqid();
        $status = 'pending';

        // Insert into orders
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                id, customer_name, customer_email, customer_phone, 
                shipping_country, shipping_city, shipping_address, 
                total_storage_gb, subtotal_usd, shipping_cost_usd, total_usd, 
                status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $orderId,
            $input['customer']['name'] ?? '',
            $input['customer']['email'] ?? '',
            $input['customer']['phone'] ?? '',
            $input['customer']['country'] ?? '',
            $input['customer']['city'] ?? '',
            $input['customer']['address'] ?? '',
            $input['storage']['total_gb'] ?? 0,
            $input['pricing']['subtotal'] ?? 0,
            $input['pricing']['shipping'] ?? 0,
            $input['pricing']['total'] ?? 0,
            $status
        ]);

        // Insert Order Items (Product IDs)
        if (isset($input['items']) && is_array($input['items'])) {
            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, is_pack) VALUES (?, ?, ?)");
            foreach ($input['items'] as $item) {
                // Assuming item is just an ID string or simple object
                $prodId = is_array($item) ? $item['id'] : $item;
                $stmtItem->execute([$orderId, $prodId, 0]);
            }
        }

        $pdo->commit();

        echo json_encode(['success' => true, 'order_id' => $orderId]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
}
