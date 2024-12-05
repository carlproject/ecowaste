<!DOCTYPE html>
<html>
<head>
    <title>Schedule Pickup - EcoByte</title>
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

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="date"],
        input[type="time"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
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

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
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
                    <a href="<?php echo site_url('schedule'); ?>" class="nav-link active">
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
                    <h2 class="section-title">Schedule a Pickup</h2>
                </div>
                <form action="<?php echo site_url('schedule'); ?>" method="POST">
                    <div class="form-group">
                        <label for="pickup_date">Pickup Date</label>
                        <input type="date" id="pickup_date" name="pickup_date" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="pickup_time">Preferred Time</label>
                        <input type="time" id="pickup_time" name="pickup_time" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Pickup Address</label>
                        <input type="text" id="address" name="address" required 
                               placeholder="Enter complete pickup address"
                               value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="items">Items Description</label>
                        <textarea id="items" name="items" required 
                                  placeholder="Please list the e-waste items you want to be picked up"></textarea>
                    </div>

                    <button type="submit">Schedule Pickup</button>
                </form>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Your Pickup History</h2>
                </div>
                <?php if (isset($pickups) && !empty($pickups)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tracking Number</th>
                                    <th>Date & Time</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pickups as $pickup): ?>
                                    <tr>
                                        <td><?php echo $pickup['tracking_number']; ?></td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($pickup['pickup_date'])); ?>
                                            <br>
                                            <small><?php echo date('h:i A', strtotime($pickup['pickup_time'])); ?></small>
                                        </td>
                                        <td>
                                            <?php 
                                            $items = strlen($pickup['items']) > 50 ? 
                                                    substr($pickup['items'], 0, 50) . '...' : 
                                                    $pickup['items'];
                                            echo htmlspecialchars($items);
                                            ?>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo $pickup['status']; ?>">
                                                <?php echo ucfirst($pickup['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (isset($pickup['status']) && $pickup['status'] === 'pending'): ?>
                                                <div class="action-buttons">
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="updateStatus('<?php echo $pickup['tracking_number']; ?>', 'cancelled')">
                                                        <i class="fas fa-times"></i> Cancel Request
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>You haven't scheduled any pickups yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Set minimum date to today
        document.getElementById('pickup_date').min = new Date().toISOString().split('T')[0];

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const date = new Date(document.getElementById('pickup_date').value);
            const today = new Date();
            
            if (date < today) {
                e.preventDefault();
                alert('Please select a future date for pickup.');
            }
        });

        function updateStatus(trackingNumber, status) {
            const action = 'cancel';
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${action} this collection?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${action} it`,
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?php echo site_url('schedule/cancel'); ?>';
                    
                    const trackingInput = document.createElement('input');
                    trackingInput.type = 'hidden';
                    trackingInput.name = 'tracking_number';
                    trackingInput.value = trackingNumber;
                    
                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    statusInput.value = status;
                    
                    form.appendChild(trackingInput);
                    form.appendChild(statusInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
