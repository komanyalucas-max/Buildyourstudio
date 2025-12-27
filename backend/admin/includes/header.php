<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Buildyourstudio</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .sidebar {
            background-color: #1e293b;
            border-right: 1px solid #334155;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .nav-link {
            color: #94a3b8;
            padding: 0.85rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: #f8fafc;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-link.active {
            background-color: #3b82f6;
            /* Blue 500 */
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .main-content {
            padding: 2rem;
        }

        /* Card Styling */
        .card {
            background-color: #1e293b;
            /* Slate 800 */
            border: 1px solid #334155;
            border-radius: 1rem;
            /* Rounded-2xl */
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            border-bottom: 1px solid #334155;
        }

        /* Table Styling */
        .table {
            color: #cbd5e1;
            margin-bottom: 0;
        }

        .table thead th {
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            background-color: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 1rem;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: rgba(255, 255, 255, 0.02);
            color: #cbd5e1;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #334155;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Mobile Navbar */
        .mobile-nav {
            background-color: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 1rem;
            display: none;
        }

        @media (max-width: 767.98px) {
            .mobile-nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 1030;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1040;
                width: 280px;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                padding: 1rem;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1035;
                display: none;
                backdrop-filter: blur(2px);
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Header -->
    <div class="mobile-nav d-md-none">
        <div class="d-flex align-items-center gap-2">
            <i data-lucide="layout-dashboard" class="text-primary"></i>
            <h5 class="m-0 fw-bold">Studio Admin</h5>
        </div>
        <button class="btn btn-dark p-2" id="sidebarToggle">
            <i data-lucide="menu"></i>
        </button>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-4" id="sidebar">
                <div class="d-flex align-items-center justify-content-between mb-5 px-2">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="layout-dashboard" class="text-primary" size="24"></i>
                        <h5 class="m-0 fw-bold fs-5">Studio Admin</h5>
                    </div>
                    <button class="btn btn-link text-white d-md-none p-0" id="sidebarClose">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <nav class="nav flex-column gap-2">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                        <i data-lucide="bar-chart-3" size="18"></i> Overview
                    </a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>" href="categories.php">
                        <i data-lucide="folder-tree" size="18"></i> Categories
                    </a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>" href="products.php">
                        <i data-lucide="package" size="18"></i> Products
                    </a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>" href="orders.php">
                        <i data-lucide="shopping-cart" size="18"></i> Orders
                    </a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'storage.php' ? 'active' : ''; ?>" href="storage.php">
                        <i data-lucide="hard-drive" size="18"></i> Storage
                    </a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>" href="settings.php">
                        <i data-lucide="settings" size="18"></i> Settings
                    </a>
                    <hr class="border-secondary my-3 opacity-25">
                    <a class="nav-link text-danger hover-danger" href="logout.php">
                        <i data-lucide="log-out" size="18"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">