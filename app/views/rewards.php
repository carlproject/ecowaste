<!DOCTYPE html>
<html>
<head>
    <title>Rewards - EcoByte</title>
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

        .rewards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .reward-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .reward-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .reward-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .reward-points {
            background-color: #4caf50;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .reward-description {
            color: #666;
            margin-bottom: 15px;
        }

        .claim-button {
            background-color: #4caf50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .claim-button:hover {
            background-color: #45a049;
            text-decoration: none;
            color: white;
        }

        .claim-button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
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

            .rewards-grid {
                grid-template-columns: 1fr;
            }

            .table {
                display: block;
                overflow-x: auto;
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
                    <a href="<?php echo site_url('track-pickup'); ?>" class="nav-link">
                        <i class="fas fa-truck-loading"></i> Track Pickup
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('trends'); ?>" class="nav-link">
                        <i class="fas fa-chart-line"></i> E-Waste Trends
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('rewards'); ?>" class="nav-link active">
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
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo number_format($user_points); ?></div>
                    <div class="stat-label">Available Points</div>
                </div>
            </div>

            <?php 
            $alert = get_flash_alert();
            if ($alert): 
            ?>
                <div class="alert alert-<?php echo $alert['type']; ?>">
                    <?php echo $alert['message']; ?>
                </div>
            <?php endif; ?>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Available Rewards</h2>
                </div>
                <div class="rewards-grid">
                    <?php if (isset($rewards) && !empty($rewards)): ?>
                        <?php foreach ($rewards as $reward): ?>
                            <?php if ($reward['status'] === 'active'): ?>
                                <div class="reward-card">
                                    <div class="reward-header">
                                        <div class="reward-title"><?php echo htmlspecialchars($reward['name']); ?></div>
                                        <div class="reward-points"><?php echo number_format($reward['points_required']); ?> Points</div>
                                    </div>
                                    <div class="reward-description"><?php echo htmlspecialchars($reward['description']); ?></div>
                                    <a href="<?php echo site_url('claim-reward/' . $reward['id']); ?>" 
                                       class="claim-button" 
                                       <?php echo ($user_points < $reward['points_required']) ? 'onclick="return false;" style="background-color: #cccccc;"' : ''; ?>>
                                        <?php echo ($user_points < $reward['points_required']) ? 'Not Enough Points' : 'Claim Reward'; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No rewards available at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Your Claimed Rewards</h2>
                </div>
                <?php if (!empty($claimed_rewards)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Reward</th>
                                    <th>Points Used</th>
                                    <th>Claimed Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($claimed_rewards as $claimed): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($claimed['name']); ?></td>
                                        <td><?php echo number_format($claimed['points_used']); ?></td>
                                        <td><?php echo date('M d, Y h:i A', strtotime($claimed['claimed_at'])); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $claimed['status']; ?>">
                                                <?php echo ucfirst($claimed['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>You haven't claimed any rewards yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
