<!DOCTYPE html>
<html>
<head>
    <title>E-Waste Collection Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2ecc71;
            margin: 10px 0;
        }
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        .filter-form {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .form-group {
            display: inline-block;
            margin-right: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .form-group input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-scheduled { background: #fff3cd; color: #856404; }
        .status-pickup { background: #cce5ff; color: #004085; }
        .status-transit { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1ecf1; color: #0c5460; }
        .export-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .chart-container {
            margin-top: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>E-Waste Collection Reports</h1>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Collections</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['confirmed']; ?></div>
                <div class="stat-label">Completed Pickups</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">Pending Pickups</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['completion_rate'], 1); ?>%</div>
                <div class="stat-label">Completion Rate</div>
            </div>
        </div>

        <div class="filter-form">
            <form action="<?php echo site_url('collection/generate-report'); ?>" method="GET">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']); ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo isset($_GET['end_date']); ?>">
                </div>
                <button type="submit" class="export-btn">Apply Filter</button>
            </form>
        </div>

        <a href="<?php echo site_url('collection/export-report'); ?>" class="export-btn">Export to CSV</a>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Waste Type</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td>#<?php echo $report['id']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($report['created_at'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($report['status']); ?>">
                                <?php echo $report['status']; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($report['current_location']); ?></td>
                        <td><?php echo htmlspecialchars($report['waste_type']); ?></td>
                        <td><?php echo date('M j, Y g:i A', strtotime($report['updated_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="chart-container">
            <h2>Collection Trends</h2>
            <!-- Add a canvas for charts if using a charting library -->
            <canvas id="collectionTrends"></canvas>
        </div>
    </div>

    <!-- Optional: Add JavaScript for interactive charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example chart initialization
        const ctx = document.getElementById('collectionTrends').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Monthly Collections',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#2ecc71',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
