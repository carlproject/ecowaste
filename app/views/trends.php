<!DOCTYPE html>
<html>
<head>
    <title>E-Waste Trends - EcoByte</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        .item-list {
            list-style: none;
            padding: 0;
        }

        .item-list li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-list li:last-child {
            border-bottom: none;
        }

        .item-name {
            color: #333;
            font-weight: bold;
        }

        .item-value {
            color: #4caf50;
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
                    <a href="<?php echo site_url('trends'); ?>" class="nav-link active">
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
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">
                        <?php echo isset($collection_stats['total']) ? number_format($collection_stats['total']) : '0'; ?>
                    </div>
                    <div class="stat-label">Total Collections</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php echo isset($collection_stats['confirmed']) ? number_format($collection_stats['confirmed']) : '0'; ?>
                    </div>
                    <div class="stat-label">Completed Collections</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php echo isset($collection_stats['pending']) ? number_format($collection_stats['pending']) : '0'; ?>
                    </div>
                    <div class="stat-label">Pending Collections</div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Monthly Collection Trends</h2>
                </div>
                <div class="chart-container">
                    <canvas id="monthlyTrendsChart"></canvas>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">E-Waste Categories Distribution</h2>
                </div>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Most Common E-Waste Items</h2>
                </div>
                <?php if (isset($common_items) && !empty($common_items)): ?>
                    <ul class="item-list">
                        <?php foreach ($common_items as $item): ?>
                            <li>
                                <span class="item-name"><?php echo isset($item['items']) ? htmlspecialchars($item['items']) : 'Unknown'; ?></span>
                                <span class="item-value"><?php echo isset($item['count']) ? number_format($item['count']) : '0'; ?> collections</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No collection data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Monthly Trends Chart
        const monthlyData = <?php 
            $chartData = [];
            if (isset($collection_stats) && is_array($collection_stats)) {
                foreach ($collection_stats as $stat) {
                    if (isset($stat['month'])) {
                        $chartData[] = [
                            'month' => date('M Y', strtotime($stat['month'])),
                            'confirmed' => isset($stat['confirmed']) ? (int)$stat['confirmed'] : 0,
                            'pending' => isset($stat['pending']) ? (int)$stat['pending'] : 0
                        ];
                    }
                }
            }
            echo json_encode($chartData);
        ?>;

        if (monthlyData && monthlyData.length > 0) {
            new Chart(document.getElementById('monthlyTrendsChart'), {
                type: 'line',
                data: {
                    labels: monthlyData.map(d => d.month),
                    datasets: [{
                        label: 'Completed Collections',
                        data: monthlyData.map(d => d.confirmed),
                        borderColor: '#4caf50',
                        tension: 0.1
                    }, {
                        label: 'Pending Collections',
                        data: monthlyData.map(d => d.pending),
                        borderColor: '#ffc107',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Category Distribution Chart
        const categoryData = <?php 
            $categoryChartData = [];
            if (isset($category_trends) && is_array($category_trends)) {
                foreach ($category_trends as $trend) {
                    if (isset($trend['category']) && isset($trend['total'])) {
                        $categoryChartData[] = [
                            'category' => $trend['category'],
                            'total' => (int)$trend['total']
                        ];
                    }
                }
            }
            echo json_encode($categoryChartData);
        ?>;

        if (categoryData && categoryData.length > 0) {
            new Chart(document.getElementById('categoryChart'), {
                type: 'pie',
                data: {
                    labels: categoryData.map(d => d.category),
                    datasets: [{
                        data: categoryData.map(d => d.total),
                        backgroundColor: [
                            '#4caf50',
                            '#2196f3',
                            '#ff9800',
                            '#9c27b0',
                            '#f44336'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
