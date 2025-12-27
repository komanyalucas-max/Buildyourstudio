<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

$id = $_GET['id'] ?? null;
$item = null;
$error = '';
$success = '';

// Fetch Categories for Dropdown
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY sort_order ASC")->fetchAll();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prodId = isset($_POST['id']) ? trim($_POST['id']) : null;
    $category_id = trim($_POST['category_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price_usd = (float)$_POST['price_usd'];
    $price_tzs = (float)$_POST['price_tzs'];
    $tier = trim($_POST['tier']);
    $file_size_gb = (float)$_POST['file_size_gb'];
    $image_url = trim($_POST['image_url']);
    $is_free = isset($_POST['is_free']) ? 1 : 0;

    // Handle Image Upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $filename = uniqid('prod_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $filename)) {
                // Determine base URL dynamically
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                $baseUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME']));
                $image_url = $baseUrl . '/uploads/products/' . $filename;
            }
        }
    }

    // Auto-generate ID if empty (simple slug)
    if (empty($prodId) && !$id) {
        $prodId = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }

    if (empty($prodId) || empty($name) || empty($category_id)) {
        $error = "ID, Name, and Category are required.";
    } else {
        try {
            if ($id) {
                // Update
                $stmt = $pdo->prepare("UPDATE products SET category_id=?, name=?, description=?, price_usd=?, price_tzs=?, tier=?, file_size_gb=?, image_url=?, is_free=? WHERE id=?");
                $stmt->execute([$category_id, $name, $description, $price_usd, $price_tzs, $tier, $file_size_gb, $image_url, $is_free, $id]);
                $success = "Product updated successfully!";
                $id = $prodId; // Keep ID as is (usually ID isn't editable)
            } else {
                // Insert
                // Check ID
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE id=?");
                $stmtCheck->execute([$prodId]);
                if ($stmtCheck->fetchColumn() > 0) {
                    // Append random suffix if duplicate
                    $prodId .= '-' . rand(100, 999);
                }

                $stmt = $pdo->prepare("INSERT INTO products (id, category_id, name, description, price_usd, price_tzs, tier, file_size_gb, image_url, is_free) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$prodId, $category_id, $name, $description, $price_usd, $price_tzs, $tier, $file_size_gb, $image_url, $is_free]);
                $success = "Product created successfully!";
                $id = $prodId; // Redirect to edit mode
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch Data if Edit
if ($id && !$item) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        header("Location: products.php");
        exit;
    }
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold"><?php echo $id ? 'Edit Product' : 'Add Product'; ?></h2>
    <a href="products.php" class="btn btn-outline-secondary d-flex align-items-center gap-2">
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

        <form method="POST" enctype="multipart/form-data">
            <?php if (!$id): ?>
                <div class="mb-3">
                    <label class="form-label">Product ID (Optional - Auto-generated from name if empty)</label>
                    <input type="text" name="id" class="form-control" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
                </div>
            <?php else: ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo (isset($item['category_id']) && $item['category_id'] === $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tier</label>
                    <select name="tier" class="form-select">
                        <option value="cheap" <?php echo (isset($item['tier']) && $item['tier'] === 'cheap') ? 'selected' : ''; ?>>Cheap / Budget</option>
                        <option value="medium" <?php echo (isset($item['tier']) && $item['tier'] === 'medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="expensive" <?php echo (isset($item['tier']) && $item['tier'] === 'expensive') ? 'selected' : ''; ?>>Expensive / Premium</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Price (USD)</label>
                    <input type="number" step="0.01" name="price_usd" class="form-control" value="<?php echo htmlspecialchars($item['price_usd'] ?? '0.00'); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Price (TZS)</label>
                    <input type="number" step="0.01" name="price_tzs" class="form-control" value="<?php echo htmlspecialchars($item['price_tzs'] ?? '0.00'); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">File Size (GB)</label>
                    <input type="number" step="0.01" name="file_size_gb" class="form-control" value="<?php echo htmlspecialchars($item['file_size_gb'] ?? '0.00'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="image_file" class="form-control" accept="image/*">
                <div class="form-text">Upload a new image or leave empty to keep current.</div>

                <div class="mt-2">
                    <label class="form-label text-secondary small">Or External URL</label>
                    <input type="text" name="image_url" class="form-control form-control-sm" value="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" placeholder="https://...">
                </div>

                <?php if (!empty($item['image_url'])): ?>
                    <div class="mt-3 p-2 border rounded bg-light d-inline-block">
                        <div class="small text-secondary mb-1">Current Preview:</div>
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Current Image" style="max-height: 100px; width: auto; object-fit: contain;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_free" name="is_free" <?php echo (isset($item['is_free']) && $item['is_free']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_free">Is Free?</label>
            </div>

            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                <i data-lucide="save" size="18"></i> Save Product
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>