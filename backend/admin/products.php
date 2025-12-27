<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold">Products</h2>
    <a href="product_edit.php" class="btn btn-primary d-flex align-items-center gap-2">
        <i data-lucide="plus" size="18"></i> Add Product
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>File Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $pdo->query("
                            SELECT p.*, c.name as category_name 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            ORDER BY p.created_at DESC
                        ");
                        $products = $stmt->fetchAll();

                        if (count($products) > 0):
                            foreach ($products as $product):
                    ?>
                                <tr>
                                    <td class="ps-4 fw-medium"><?php echo htmlspecialchars($product['name'] ?? ''); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></span></td>
                                    <td><?php echo $product['is_free'] ? '<span class="badge bg-success">Free</span>' : '$' . number_format($product['price_usd'], 2); ?></td>
                                    <td><?php echo $product['file_size_gb']; ?> GB</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-light" title="Edit">
                                                <i data-lucide="edit-2" size="14"></i>
                                            </a>
                                            <a href="product_delete.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i data-lucide="trash" size="14"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">No products found. Add one to get started!</td>
                            </tr>
                    <?php endif;
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='5' class='text-danger p-4'>Error loading products: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>