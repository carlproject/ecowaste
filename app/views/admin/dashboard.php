<style>
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

.content-card {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
}

.h5 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #343a40;
}
</style>

<div class="content-header mb-4">
    <h1>Dashboard</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?php echo isset($total_users) ? number_format($total_users) : '0'; ?></div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo isset($total_collections) ? number_format($total_collections) : '0'; ?></div>
        <div class="stat-label">Total Collections</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo isset($pending_collections) ? number_format($pending_collections) : '0'; ?></div>
        <div class="stat-label">Pending Collections</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo isset($confirmed_collections) ? number_format($confirmed_collections) : '0'; ?></div>
        <div class="stat-label">Completed Collections</div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0">Recent Collections</h2>
        <a href="<?php echo site_url('admin/collections'); ?>" class="btn btn-primary btn-sm">View All</a>
    </div>
    
    <?php if (isset($recent_collections) && !empty($recent_collections)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tracking Number</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_collections as $collection): ?>
                        <tr>
                            <td><?php echo isset($collection['tracking_number']) ? $collection['tracking_number'] : 'N/A'; ?></td>
                            <td><?php echo isset($collection['username']) ? htmlspecialchars($collection['username']) : 'Unknown User'; ?></td>
                            <td>
                                <?php 
                                if (isset($collection['pickup_date'])) {
                                    echo date('M d, Y', strtotime($collection['pickup_date']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if (isset($collection['status'])): ?>
                                    <span class="badge bg-<?php 
                                        echo $collection['status'] === 'confirmed' ? 'success' : 
                                            ($collection['status'] === 'pending' ? 'warning' : 
                                            ($collection['status'] === 'cancelled' ? 'danger' : 'primary')); 
                                    ?>">
                                        <?php echo ucfirst($collection['status']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('admin/collections/view/' . $collection['id']); ?>" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No recent collections found.</p>
    <?php endif; ?>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0">Recent Users</h2>
        <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-primary btn-sm">View All</a>
    </div>
    
    <?php if (isset($recent_users) && !empty($recent_users)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'success'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo site_url('admin/users/edit/' . $user['id']); ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No recent users found.</p>
    <?php endif; ?>
</div>
