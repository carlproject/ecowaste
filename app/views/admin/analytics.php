<style>
.content-card {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 20px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: 500;
    font-size: 12px;
}
</style>

<div class="content-header mb-4">
    <h1>Analytics Dashboard</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?php echo isset($total_collections) ? number_format($total_collections) : '0'; ?></div>
        <div class="stat-label">Total Collections</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo isset($completion_rate['rate']) ? number_format($completion_rate['rate'], 1) : '0'; ?>%</div>
        <div class="stat-label">Completion Rate</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo isset($monthly_comparison['growth']) ? number_format($monthly_comparison['growth'], 1) : '0'; ?>%</div>
        <div class="stat-label">Monthly Growth</div>
    </div>
</div>

<div class="content-card">
    <h2 class="h5 mb-4">Monthly Collection Comparison</h2>
    <div class="chart-container">
        <canvas id="monthlyComparisonChart"></canvas>
    </div>
</div>

<div class="content-card">
    <h2 class="h5 mb-4">Category Distribution</h2>
    <div class="chart-container">
        <canvas id="categoryChart"></canvas>
    </div>
</div>

<div class="content-card">
    <h2 class="h5 mb-4">Top Contributors</h2>
    <?php if (isset($user_participation) && !empty($user_participation)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Total Collections</th>
                        <th>Completed</th>
                        <th>Completion Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (isset($user_participation) && is_array($user_participation)) {
                        foreach ($user_participation as $user): ?>
                            <tr>
                                <td><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'Unknown'; ?></td>
                                <td><?php echo isset($user['total_collections']) ? number_format($user['total_collections']) : '0'; ?></td>
                                <td><?php echo isset($user['confirmed']) ? number_format($user['confirmed']) : '0'; ?></td>
                                <td>
                                    <?php
                                    if (isset($user['total_collections']) && $user['total_collections'] > 0) {
                                        echo number_format(($user['confirmed'] / $user['total_collections']) * 100, 1) . '%';
                                    } else {
                                        echo '0%';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No user participation data available.</p>
    <?php endif; ?>
</div>

<script>
// Monthly Comparison Chart
const monthlyComparison = <?php echo json_encode($monthly_comparison ?? []); ?>;

if (monthlyComparison) {
    new Chart(document.getElementById('monthlyComparisonChart'), {
        type: 'bar',
        data: {
            labels: ['Previous Month', 'Current Month'],
            datasets: [{
                label: 'Collections',
                data: [monthlyComparison.previous, monthlyComparison.current],
                backgroundColor: ['#2196f3', '#4caf50']
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
const categoryData = <?php echo json_encode($category_trends ?? []); ?>;

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
                    '#f44336',
                    '#009688',
                    '#795548',
                    '#607d8b'
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
