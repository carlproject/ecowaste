<!DOCTYPE html>
<html>
<head>
    <title>Education & Awareness - EcoByte</title>
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

        .search-form {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #4caf50;
            border-radius: 4px;
            font-size: 16px;
        }

        .material-list {
            list-style: none;
            padding: 0;
        }

        .material {
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .material:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .material-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .material-content {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .material-link {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .material-link:hover {
            background-color: #45a049;
            text-decoration: none;
            color: white;
        }

        .material-link i {
            margin-right: 8px;
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
                    <a href="<?php echo site_url('materials'); ?>" class="nav-link active">
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
                    <div class="stat-value"><?php echo isset($total_materials) ? number_format($total_materials) : (isset($materials) ? count($materials) : '0'); ?></div>
                    <div class="stat-label">Available Materials</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo isset($total_categories) ? number_format($total_categories) : (isset($categories) ? count($categories) : '0'); ?></div>
                    <div class="stat-label">Categories</div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Search Materials</h2>
                </div>
                <div class="search-form">
                    <div class="form-group">
                        <input type="text" id="searchInput" onkeyup="filterMaterials()" placeholder="Type to search materials...">
                    </div>
                </div>
            </div>

            <?php if (isset($categories) && !empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title"><?php echo htmlspecialchars($category); ?></h2>
                        </div>
                        <ul class="material-list">
                            <?php if (isset($materials) && !empty($materials)): ?>
                                <?php foreach ($materials as $material): ?>
                                    <?php if (isset($material['category']) && $material['category'] === $category): ?>
                                        <li class="material">
                                            <div class="material-title">
                                                <?php echo isset($material['title']) ? htmlspecialchars($material['title']) : 'Untitled'; ?>
                                            </div>
                                            <div class="material-content">
                                                <?php echo isset($material['content']) ? htmlspecialchars($material['content']) : ''; ?>
                                            </div>
                                            <a href="<?php echo site_url('materials/download/' . $material['id']); ?>" class="material-link">
                                                <i class="fas fa-download"></i> Download Material
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No materials available in this category.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="content-section">
                    <p>No educational materials available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function filterMaterials() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const materials = document.querySelectorAll('.material');
            materials.forEach(material => {
                const content = material.textContent.toLowerCase();
                material.style.display = content.includes(searchQuery) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
