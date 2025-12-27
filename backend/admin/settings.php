<?php
require_once 'auth.php';
require_once '../config.php';

checkLogin();

// Fetch all settings
$settings = [];
try {
    $stmt = $pdo->query("SELECT * FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = json_decode($row['setting_value'], true);
    }
} catch (PDOException $e) {
    // Table might not exist or empty
}

// Defaults
$general = $settings['general'] ?? ['system_name' => 'BuildYourStudio', 'logo_url' => ''];
$smtp = $settings['smtp'] ?? ['host' => '', 'port' => '587', 'username' => '', 'password' => '', 'encryption' => 'tls'];
$sms = $settings['sms'] ?? ['provider' => 'twilio', 'sid' => '', 'token' => '', 'from' => ''];
$payment = $settings['payment'] ?? ['pesapal_enabled' => false, 'pesapal_consumer_key' => '', 'pesapal_consumer_secret' => '', 'offline_enabled' => true, 'offline_instructions' => ''];

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process Form
    $general = [
        'system_name' => trim($_POST['system_name']),
        'logo_url' => trim($_POST['logo_url'])
    ];

    // Handle Logo Upload
    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/settings/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'svg', 'webp'];

        if (in_array($ext, $allowed)) {
            $filename = 'logo_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $uploadDir . $filename)) {
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                $baseUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME']));
                $general['logo_url'] = $baseUrl . '/uploads/settings/' . $filename;
            }
        }
    }

    $smtp = [
        'host' => trim($_POST['smtp_host']),
        'port' => trim($_POST['smtp_port']),
        'username' => trim($_POST['smtp_username']),
        'password' => trim($_POST['smtp_password']),
        'encryption' => trim($_POST['smtp_encryption'])
    ];

    $sms = [
        'provider' => 'twilio',
        'sid' => trim($_POST['sms_sid']),
        'token' => trim($_POST['sms_token']),
        'from' => trim($_POST['sms_from'])
    ];

    $payment = [
        'pesapal_enabled' => isset($_POST['pesapal_enabled']),
        'pesapal_consumer_key' => trim($_POST['pesapal_key']),
        'pesapal_consumer_secret' => trim($_POST['pesapal_secret']),
        'offline_enabled' => isset($_POST['offline_enabled']),
        'offline_instructions' => trim($_POST['offline_instructions'])
    ];

    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");

        $stmt->execute(['general', json_encode($general), json_encode($general)]);
        $stmt->execute(['smtp', json_encode($smtp), json_encode($smtp)]);
        $stmt->execute(['sms', json_encode($sms), json_encode($sms)]);
        $stmt->execute(['payment', json_encode($payment), json_encode($payment)]);

        $success = "Settings saved successfully!";
    } catch (PDOException $e) {
        $error = "Detailed Error: " . $e->getMessage();
    }
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold">General Settings</h2>
</div>

<?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<form method="POST" class="row" enctype="multipart/form-data">
    <!-- General -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent fw-bold">General Information</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">System Name</label>
                    <input type="text" name="system_name" class="form-control" value="<?php echo htmlspecialchars($general['system_name'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo_file" class="form-control" accept="image/*">
                    <div class="form-text">Upload new logo to replace.</div>

                    <div class="mt-2">
                        <label class="form-label text-secondary small">Or Logo URL</label>
                        <input type="text" name="logo_url" class="form-control form-control-sm" value="<?php echo htmlspecialchars($general['logo_url'] ?? ''); ?>" placeholder="https://...">
                    </div>

                    <?php if (!empty($general['logo_url'])): ?>
                        <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                            <img src="<?php echo htmlspecialchars($general['logo_url']); ?>" alt="Logo" style="max-height: 50px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- SMTP -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent fw-bold">SMTP Settings (Email)</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Host</label>
                        <input type="text" name="smtp_host" class="form-control" value="<?php echo htmlspecialchars($smtp['host'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Port</label>
                        <input type="text" name="smtp_port" class="form-control" value="<?php echo htmlspecialchars($smtp['port'] ?? '587'); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="smtp_username" class="form-control" value="<?php echo htmlspecialchars($smtp['username'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="smtp_password" class="form-control" value="<?php echo htmlspecialchars($smtp['password'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Encryption</label>
                    <select name="smtp_encryption" class="form-select">
                        <option value="tls" <?php echo ($smtp['encryption'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                        <option value="ssl" <?php echo ($smtp['encryption'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                        <option value="none" <?php echo ($smtp['encryption'] ?? '') == 'none' ? 'selected' : ''; ?>>None</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- SMS (Twilio) -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent fw-bold">SMS Settings (Twilio)</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Twilio SID</label>
                    <input type="text" name="sms_sid" class="form-control" value="<?php echo htmlspecialchars($sms['sid'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Auth Token</label>
                    <input type="password" name="sms_token" class="form-control" value="<?php echo htmlspecialchars($sms['token'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">From Number</label>
                    <input type="text" name="sms_from" class="form-control" value="<?php echo htmlspecialchars($sms['from'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent fw-bold">Payment Methods</div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="offlineSwitch" name="offline_enabled" <?php echo ($payment['offline_enabled'] ?? false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="offlineSwitch">Enable Offline Payment (Cash/Transfer)</label>
                </div>
                <div class="mb-3 ps-4 border-start border-secondary">
                    <label class="form-label text-sm">Instructions</label>
                    <textarea name="offline_instructions" class="form-control form-control-sm" rows="2" placeholder="Bank details, MPESA number..."><?php echo htmlspecialchars($payment['offline_instructions'] ?? ''); ?></textarea>
                </div>

                <hr class="border-secondary">

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="pesapalSwitch" name="pesapal_enabled" <?php echo ($payment['pesapal_enabled'] ?? false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="pesapalSwitch">Enable PesaPal</label>
                </div>
                <div class="ps-4 border-start border-secondary">
                    <div class="mb-2">
                        <label class="form-label text-sm">Consumer Key</label>
                        <input type="text" name="pesapal_key" class="form-control form-control-sm" value="<?php echo htmlspecialchars($payment['pesapal_consumer_key'] ?? ''); ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-sm">Consumer Secret</label>
                        <input type="password" name="pesapal_secret" class="form-control form-control-sm" value="<?php echo htmlspecialchars($payment['pesapal_consumer_secret'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 text-end mb-5">
        <button type="submit" class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2">
            <i data-lucide="save"></i> Save Settings
        </button>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>