<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

$id = $_GET['id'] ?? null;
$item = null;
$error = '';
$success = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catId = trim($_POST['id']);
    $name = trim($_POST['name']);
    $name_sw = trim($_POST['name_sw']);
    $icon = trim($_POST['icon']);
    $description = trim($_POST['description']);
    $sort_order = (int)$_POST['sort_order'];

    if (empty($catId) || empty($name)) {
        $error = "ID and Name are required.";
    } else {
        try {
            if ($id) {
                // Update
                $stmt = $pdo->prepare("UPDATE categories SET name=?, name_sw=?, icon=?, description=?, sort_order=? WHERE id=?");
                $stmt->execute([$name, $name_sw, $icon, $description, $sort_order, $id]);
                $success = "Category updated successfully!";
                // Refresh item data
                $id = $catId; // In case we allow ID editing? No, usually ID is fixed.
            } else {
                // Insert
                // Check if ID exists
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE id=?");
                $stmtCheck->execute([$catId]);
                if ($stmtCheck->fetchColumn() > 0) {
                    $error = "Category ID already exists.";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO categories (id, name, name_sw, icon, description, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$catId, $name, $name_sw, $icon, $description, $sort_order]);
                    $success = "Category created successfully!";
                    $id = $catId;
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch Data if Edit
if ($id && !$item) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        header("Location: categories.php");
        exit;
    }
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold"><?php echo $id ? 'Edit Category' : 'Add Category'; ?></h2>
    <a href="categories.php" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i data-lucide="arrow-left" size="18"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Category ID (Slug)</label>
                <input type="text" name="id" class="form-control" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>" <?php echo $id ? 'readonly' : ''; ?> required>
                <div class="form-text">Unique identifier, e.g., 'headphones' or 'music-software'. Cannot be changed once created.</div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name (English)</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name (Swahili)</label>
                    <input type="text" name="name_sw" class="form-control" value="<?php echo htmlspecialchars($item['name_sw'] ?? ''); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Icon (Lucide Name)</label>
                    <input type="text" name="icon" class="form-control" value="<?php echo htmlspecialchars($item['icon'] ?? ''); ?>" placeholder="e.g. Headphones, Mic, Music">
                    <div class="form-text">Case-sensitive name from Lucide React icons.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?php echo htmlspecialchars($item['sort_order'] ?? '0'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                <i data-lucide="save" size="18"></i> Save Category
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>