<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>EcoByte Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            min-height: 100vh;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #4b545c;
            text-align: center;
        }

        .sidebar-header h2 {
            color: #4caf50;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .admin-info {
            font-size: 14px;
            color: #6c757d;
            padding: 10px 0;
        }

        .nav-menu {
            list-style: none;
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #c2c7d0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: #4caf50;
            color: white;
        }

        .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            background-color: #f4f6f9;
        }

        /* Logout Button */
        .logout-btn {
            margin: 20px;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: calc(100% - 40px);
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .admin-container {
                flex-direction: column;
            }
        }
    </style>
    <?php if(isset($extra_css)): ?>
        <?php echo $extra_css; ?>
    <?php endif; ?>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>EcoByte Admin</h2>
                <div class="admin-info">
                    Administrator Panel
                </div>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo site_url('admin'); ?>" class="nav-link <?php echo segment(1) === 'admin' && !segment(2) ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/users'); ?>" class="nav-link <?php echo segment(2) === 'users' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/collections'); ?>" class="nav-link <?php echo segment(2) === 'collections' ? 'active' : ''; ?>">
                        <i class="fas fa-truck-loading"></i> Collections
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/rewards'); ?>" class="nav-link <?php echo segment(2) === 'rewards' ? 'active' : ''; ?>">
                        <i class="fas fa-gift"></i> Rewards
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/analytics'); ?>" class="nav-link <?php echo segment(2) === 'analytics' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-bar"></i> Analytics
                    </a>
                </li>
            </ul>
            <a href="<?php echo site_url('logout'); ?>" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <?php 
            $alert = get_flash_alert();
            if ($alert): 
            ?>
                <div class="alert alert-<?php echo $alert['type']; ?>">
                    <?php echo $alert['message']; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($content)): ?>
                <?php echo $content; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if(isset($extra_js)): ?>
        <?php echo $extra_js; ?>
    <?php endif; ?>
</body>
</html>
