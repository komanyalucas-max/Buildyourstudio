<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold">Categories</h2>
    <a href="category_edit.php" class="btn btn-primary d-flex align-items-center gap-2">
        <i data-lucide="plus" size="18"></i> Add Category
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Swahili Name</th>
                        <th>Icon</th>
                        <th>Sort Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC");
                        $items = $stmt->fetchAll();

                        if (count($items) > 0):
                            foreach ($items as $item):
                    ?>
                                <tr>
                                    <td class="ps-4 fw-medium"><?php echo htmlspecialchars($item['name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($item['name_sw'] ?? ''); ?></td>
                                    <td><code><?php echo htmlspecialchars($item['icon'] ?? ''); ?></code></td>
                                    <td><?php echo $item['sort_order']; ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="category_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-light"><i data-lucide="edit-2" size="14"></i></a>
                                            <a href="category_delete.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure? This will delete all products in this category.');"><i data-lucide="trash" size="14"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">No categories found.</td>
                            </tr>
                    <?php endif;
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='5' class='text-danger p-4'>Error: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>