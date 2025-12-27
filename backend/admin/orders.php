<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold">Orders</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Customer</th>
                        <th>Storage Needs</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
                        $items = $stmt->fetchAll();

                        if (count($items) > 0):
                            foreach ($items as $item):
                    ?>
                                <tr>
                                    <td class="ps-4 text-monospace small">#<?php echo htmlspecialchars($item['id'] ?? ''); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($item['customer_name'] ?? ''); ?></div>
                                        <div class="small text-secondary"><?php echo htmlspecialchars($item['customer_email'] ?? ''); ?></div>
                                    </td>
                                    <td><?php echo $item['total_storage_gb']; ?> GB</td>
                                    <td class="fw-bold">$<?php echo number_format($item['total_usd'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php
                                                                echo match ($item['status']) {
                                                                    'completed' => 'success',
                                                                    'pending' => 'warning',
                                                                    'cancelled' => 'danger',
                                                                    default => 'secondary'
                                                                };
                                                                ?>">
                                            <?php echo ucfirst($item['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-light">View</a>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-secondary">No orders yet.</td>
                            </tr>
                    <?php endif;
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='7' class='text-danger p-4'>Error: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>