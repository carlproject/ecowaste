<!DOCTYPE html>
<html>
<head>
    <title>Track Pickup - EcoByte</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .dashboard-container {
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

        .user-info {
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
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #4caf50;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .content-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        .tracking-number {
            font-size: 24px;
            color: #4caf50;
            text-align: center;
            padding: 10px;
            background-color: #e8f5e9;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .status-container {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border-radius: 4px;
        }

        .status {
            font-size: 18px;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 4px;
            display: inline-block;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .detail-item {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .detail-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 16px;
        }

        .items-description {
            grid-column: span 2;
        }

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

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .dashboard-container {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .items-description {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>EcoByte</h2>
                <div class="user-info">
                    Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?>
                </div>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo site_url('dashboard'); ?>" class="nav-link">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('schedule'); ?>" class="nav-link">
                        <i class="fas fa-calendar"></i> Schedule Pickup
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('track-pickup'); ?>" class="nav-link active">
                        <i class="fas fa-truck-loading"></i> Track Pickup
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('trends'); ?>" class="nav-link">
                        <i class="fas fa-chart-line"></i> E-Waste Trends
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('rewards'); ?>" class="nav-link">
                        <i class="fas fa-gift"></i> Rewards
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('materials'); ?>" class="nav-link">
                        <i class="fas fa-book"></i> Education
                    </a>
                </li>
            </ul>
            <a href="<?php echo site_url('logout'); ?>" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!--<div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo isset($total_pickups) ? number_format($total_pickups) : '0'; ?></div>
                    <div class="stat-label">Total Pickups</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo isset($pending_pickups) ? number_format($pending_pickups) : '0'; ?></div>
                    <div class="stat-label">Pending Pickups</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo isset($completed_pickups) ? number_format($completed_pickups) : '0'; ?></div>
                    <div class="stat-label">Completed Pickups</div>
                </div>
            </div>-->

            <?php if (isset($pickup)): ?>
                <div class="content-section">
                    <div class="tracking-number">
                        Tracking Number: <?php echo $pickup['tracking_number']; ?>
                    </div>

                    <div class="status-container">
                        <div class="status status-<?php echo $pickup['status']; ?>">
                            Status: <?php echo ucfirst($pickup['status']); ?>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Pickup Date</div>
                            <div class="detail-value">
                                <?php echo date('F d, Y', strtotime($pickup['pickup_date'])); ?>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Pickup Time</div>
                            <div class="detail-value">
                                <?php echo date('h:i A', strtotime($pickup['pickup_time'])); ?>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Pickup Address</div>
                            <div class="detail-value"><?php echo $pickup['address']; ?></div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Scheduled On</div>
                            <div class="detail-value">
                                <?php echo date('F d, Y h:i A', strtotime($pickup['created_at'])); ?>
                            </div>
                        </div>

                        <div class="detail-item items-description">
                            <div class="detail-label">Items Description</div>
                            <div class="detail-value"><?php echo $pickup['items']; ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
