<?php
require_once '../config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Buildyourstudio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            /* Slate 900 */
            color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            background-color: #1e293b;
            /* Slate 800 */
            border: 1px solid #334155;
            width: 100%;
            max-width: 400px;
        }

        .form-control {
            background-color: #0f172a;
            border-color: #334155;
            color: white;
        }

        .form-control:focus {
            background-color: #0f172a;
            color: white;
            border-color: #6366f1;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }

        .text-muted {
            color: #94a3b8 !important;
        }
    </style>
</head>

<body>

    <div class="card p-4 rounded-3 shadow-lg">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Admin Access</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted">Email Address</label>
                    <input type="email" name="email" class="form-control" autocomplete="email" required placeholder="admin@example.com">
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted">Password</label>
                    <input type="password" name="password" class="form-control" autocomplete="current-password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">Default: admin@example.com / password123</small>
            </div>
        </div>
    </div>

</body>

</html>