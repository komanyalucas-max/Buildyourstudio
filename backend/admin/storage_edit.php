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
    $storageId = isset($_POST['id']) ? trim($_POST['id']) : null;
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $capacity_gb = (int)$_POST['capacity_gb'];
    $price_usd = (float)$_POST['price_usd'];

    // Auto-generate ID if empty
    if (empty($storageId) && !$id) {
        $storageId = strtolower($type . '-' . $capacity_gb . ($capacity_gb < 100 ? '-gb' : ''));
    }

    if (empty($storageId) || empty($name) || empty($type)) {
        $error = "ID, Name, and Type are required.";
    } else {
        try {
            if ($id) {
                // Update
                $stmt = $pdo->prepare("UPDATE storage_devices SET name=?, type=?, capacity_gb=?, price_usd=? WHERE id=?");
                $stmt->execute([$name, $type, $capacity_gb, $price_usd, $id]);
                $success = "Storage device updated successfully!";
                $id = $storageId;
            } else {
                // Insert
                // Check ID
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM storage_devices WHERE id=?");
                $stmtCheck->execute([$storageId]);
                if ($stmtCheck->fetchColumn() > 0) {
                    $error = "Storage ID already exists. Try a different manual ID.";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO storage_devices (id, name, type, capacity_gb, price_usd) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$storageId, $name, $type, $capacity_gb, $price_usd]);
                    $success = "Storage device created successfully!";
                    $id = $storageId;
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch Data if Edit
if ($id && !$item) {
    $stmt = $pdo->prepare("SELECT * FROM storage_devices WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        header("Location: storage.php");
        exit;
    }
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold"><?php echo $id ? 'Edit Storage Device' : 'Add Storage Device'; ?></h2>
    <a href="storage.php" class="btn btn-outline-secondary d-flex align-items-center gap-2">
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
            <?php if (!$id): ?>
                <div class="mb-3">
                    <label class="form-label">Device ID (Optional - Auto-generated)</label>
                    <input type="text" name="id" class="form-control" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>" placeholder="e.g. usb-64">
                </div>
            <?php else: ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" required placeholder="e.g. USB Flash Drive">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="usb" <?php echo (isset($item['type']) && $item['type'] === 'usb') ? 'selected' : ''; ?>>USB</option>
                        <option value="hdd" <?php echo (isset($item['type']) && $item['type'] === 'hdd') ? 'selected' : ''; ?>>HDD</option>
                        <option value="sata-ssd" <?php echo (isset($item['type']) && $item['type'] === 'sata-ssd') ? 'selected' : ''; ?>>SATA SSD</option>
                        <option value="nvme-ssd" <?php echo (isset($item['type']) && $item['type'] === 'nvme-ssd') ? 'selected' : ''; ?>>NVMe SSD</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Capacity (GB)</label>
                    <input type="number" name="capacity_gb" class="form-control" value="<?php echo htmlspecialchars($item['capacity_gb'] ?? '0'); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (USD)</label>
                    <input type="number" step="0.01" name="price_usd" class="form-control" value="<?php echo htmlspecialchars($item['price_usd'] ?? '0.00'); ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                <i data-lucide="save" size="18"></i> Save Device
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>