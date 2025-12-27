<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

// Fetch Stats
try {
    $stats = [
        'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
        'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
        'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
        'revenue' => $pdo->query("SELECT SUM(total_usd) FROM orders WHERE status = 'completed'")->fetchColumn() ?: 0
    ];
} catch (PDOException $e) {
    $stats = ['products' => 0, 'categories' => 0, 'orders' => 0, 'revenue' => 0];
}

require_once 'includes/header.php';
?>

<style>
    .dashboard-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #334155;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #475569;
        background-color: #1e293b;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold m-0">Dashboard Overview</h2>
        <p class="text-secondary m-0">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Products Card -->
    <div class="col-6 col-md-3">
        <a href="products.php" class="card h-100 p-3 dashboard-card text-decoration-none">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-primary bg-opacity-10">
                        <i data-lucide="package" class="text-primary"></i>
                    </div>
                    <i data-lucide="arrow-up-right" class="text-secondary opacity-50" size="16"></i>
                </div>
                <div class="small text-secondary fw-semibold mb-1">Total Products</div>
                <div class="h3 fw-bold text-white mb-0"><?php echo number_format($stats['products']); ?></div>
            </div>
        </a>
    </div>

    <!-- Categories Card -->
    <div class="col-6 col-md-3">
        <a href="categories.php" class="card h-100 p-3 dashboard-card text-decoration-none">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-info bg-opacity-10">
                        <i data-lucide="folder-tree" class="text-info"></i>
                    </div>
                    <i data-lucide="arrow-up-right" class="text-secondary opacity-50" size="16"></i>
                </div>
                <div class="small text-secondary fw-semibold mb-1">Categories</div>
                <div class="h3 fw-bold text-white mb-0"><?php echo number_format($stats['categories']); ?></div>
            </div>
        </a>
    </div>

    <!-- Orders Card -->
    <div class="col-6 col-md-3">
        <a href="orders.php" class="card h-100 p-3 dashboard-card text-decoration-none">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-success bg-opacity-10">
                        <i data-lucide="shopping-cart" class="text-success"></i>
                    </div>
                    <i data-lucide="arrow-up-right" class="text-secondary opacity-50" size="16"></i>
                </div>
                <div class="small text-secondary fw-semibold mb-1">Total Orders</div>
                <div class="h3 fw-bold text-white mb-0"><?php echo number_format($stats['orders']); ?></div>
            </div>
        </a>
    </div>

    <!-- Revenue Card -->
    <div class="col-6 col-md-3">
        <a href="orders.php" class="card h-100 p-3 dashboard-card text-decoration-none">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-warning bg-opacity-10">
                        <i data-lucide="dollar-sign" class="text-warning"></i>
                    </div>
                    <i data-lucide="arrow-up-right" class="text-secondary opacity-50" size="16"></i>
                </div>
                <div class="small text-secondary fw-semibold mb-1">Total Revenue</div>
                <div class="h3 fw-bold text-white mb-0">$<?php echo number_format($stats['revenue'], 2); ?></div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Actions & Recent Orders -->
<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-md-4 order-md-2">
        <div class="card">
            <div class="card-header bg-transparent py-3 border-secondary border-opacity-25">
                <h5 class="card-title m-0 fw-bold fs-6">Quick Actions</h5>
            </div>
            <div class="card-body p-2">
                <div class="d-grid gap-2">
                    <a href="product_edit.php" class="btn btn-outline-light d-flex align-items-center justify-content-start gap-3 p-3 text-start border-secondary border-opacity-25 bg-dark bg-opacity-25 hover-bg-dark">
                        <div class="bg-primary bg-opacity-25 p-2 rounded">
                            <i data-lucide="plus" class="text-primary" size="18"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Add Product</div>
                            <div class="small text-secondary">Create a new item</div>
                        </div>
                    </a>
                    <a href="category_edit.php" class="btn btn-outline-light d-flex align-items-center justify-content-start gap-3 p-3 text-start border-secondary border-opacity-25 bg-dark bg-opacity-25 hover-bg-dark">
                        <div class="bg-info bg-opacity-25 p-2 rounded">
                            <i data-lucide="folder-plus" class="text-info" size="18"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Add Category</div>
                            <div class="small text-secondary">Create a new category</div>
                        </div>
                    </a>
                    <a href="storage_edit.php" class="btn btn-outline-light d-flex align-items-center justify-content-start gap-3 p-3 text-start border-secondary border-opacity-25 bg-dark bg-opacity-25 hover-bg-dark">
                        <div class="bg-warning bg-opacity-25 p-2 rounded">
                            <i data-lucide="hard-drive" class="text-warning" size="18"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Add Storage</div>
                            <div class="small text-secondary">New storage device</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-md-8 order-md-1">
        <div class="card h-100">
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center border-secondary border-opacity-25">
                <h5 class="card-title m-0 fw-bold fs-6">Recent Orders</h5>
                <a href="orders.php" class="btn btn-sm btn-link text-decoration-none p-0">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-dark bg-opacity-25">
                            <tr>
                                <th class="ps-4">Order ID</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentOrders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();
                            if (count($recentOrders) > 0):
                                foreach ($recentOrders as $order):
                            ?>
                                    <tr onclick="window.location='orders.php?id=<?php echo $order['id']; ?>'" style="cursor: pointer;">
                                        <td class="ps-4 fw-medium text-primary">#<?php echo htmlspecialchars($order['id']); ?></td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                                                <span class="small text-secondary"><?php echo date('M d', strtotime($order['created_at'])); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-<?php
                                                                                echo match ($order['status']) {
                                                                                    'completed' => 'success',
                                                                                    'pending' => 'warning',
                                                                                    'cancelled' => 'danger',
                                                                                    default => 'secondary'
                                                                                };
                                                                                ?> bg-opacity-25 text-<?php
                                                                                            echo match ($order['status']) {
                                                                                                'completed' => 'success',
                                                                                                'pending' => 'warning',
                                                                                                'cancelled' => 'danger',
                                                                                                default => 'secondary'
                                                                                            };
                                                                                            ?> px-3 py-2">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold">$<?php echo number_format($order['total_usd'], 2); ?></td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <i data-lucide="inbox" size="32" class="opacity-50"></i>
                                            <div>No recent orders</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>