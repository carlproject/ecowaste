<!DOCTYPE html>
<html>
<head>
    <title>View Collection - EcoByte Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Include the same base styles as admin dashboard */
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
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content-header h1 {
            color: #343a40;
            font-size: 24px;
        }

        .back-btn {
            padding: 8px 16px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .collection-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-item {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .detail-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #212529;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
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

        .items-description {
            grid-column: span 2;
        }

        .status-actions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .status-actions h3 {
            margin-bottom: 15px;
            color: #343a40;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-confirm {
            background-color: #4caf50;
            color: white;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
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

            .admin-container {
                flex-direction: column;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .items-description {
                grid-column: span 1;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
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
                    <a href="<?php echo site_url('admin'); ?>" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/users'); ?>" class="nav-link">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/collections'); ?>" class="nav-link active">
                        <i class="fas fa-truck-loading"></i> Collections
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/rewards'); ?>" class="nav-link">
                        <i class="fas fa-gift"></i> Rewards
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('admin/analytics'); ?>" class="nav-link">
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
            <div class="content-header">
                <h1>Collection Details</h1>
                <a href="<?php echo site_url('admin/collections'); ?>" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Collections
                </a>
            </div>

            <div class="collection-details">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Tracking Number</div>
                        <div class="detail-value"><?php echo $collection['tracking_number']; ?></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge status-<?php echo $collection['status']; ?>">
                                <?php echo ucfirst($collection['status']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">User</div>
                        <div class="detail-value"><?php echo $collection['username']; ?></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">User Email</div>
                        <div class="detail-value"><?php echo $collection['email']; ?></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Pickup Date</div>
                        <div class="detail-value">
                            <?php echo date('F d, Y', strtotime($collection['pickup_date'])); ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Pickup Time</div>
                        <div class="detail-value">
                            <?php echo date('h:i A', strtotime($collection['pickup_time'])); ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Pickup Address</div>
                        <div class="detail-value"><?php echo $collection['address']; ?></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value">
                            <?php echo date('F d, Y h:i A', strtotime($collection['created_at'])); ?>
                        </div>
                    </div>

                    <div class="detail-item items-description">
                        <div class="detail-label">Items Description</div>
                        <div class="detail-value"><?php echo $collection['items']; ?></div>
                    </div>
                </div>

                <?php if ($collection['status'] === 'pending'): ?>
                    <div class="status-actions">
                        <h3>Update Status</h3>
                        <div class="action-buttons">
                            <form action="<?php echo site_url('admin/update_collection_status'); ?>" method="POST">
                                <input type="hidden" name="collection_id" value="<?php echo $collection['id']; ?>">
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-confirm">
                                    <i class="fas fa-check"></i> Confirm Pickup
                                </button>
                            </form>

                            <form action="<?php echo site_url('admin/update_collection_status'); ?>" method="POST">
                                <input type="hidden" name="collection_id" value="<?php echo $collection['id']; ?>">
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-cancel">
                                    <i class="fas fa-times"></i> Cancel Pickup
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
