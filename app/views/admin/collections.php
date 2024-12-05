<style>
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

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filter-dropdown {
    min-width: 150px;
}

.status-badge {
    padding: 4px 8px;
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
</style>

<div class="content-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Collections Management</h1>
        <div class="header-actions">
            <select class="form-select filter-dropdown" id="statusFilter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <!--<option value="completed">Completed</option>-->
                <option value="cancelled">Cancelled</option>
            </select>
            <a href="<?php echo site_url('admin/export_report'); ?>" class="btn btn-success">
                <i class="fas fa-download me-2"></i> Export Report
            </a>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Tracking Number</th>
                    <th>User</th>
                    <th>Date & Time</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($collections as $collection): ?>
                    <tr data-status="<?php echo isset($collection['status']) ? $collection['status'] : ''; ?>">
                        <td><?php echo isset($collection['tracking_number']) ? $collection['tracking_number'] : 'N/A'; ?></td>
                        <td><?php echo isset($collection['username']) ? htmlspecialchars($collection['username']) : 'Unknown User'; ?></td>
                        <td>
                            <?php 
                            if (isset($collection['pickup_date'])) {
                                echo date('M d, Y', strtotime($collection['pickup_date']));
                            }
                            if (isset($collection['pickup_time'])) {
                                echo '<br>' . date('h:i A', strtotime($collection['pickup_time']));
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if (isset($collection['items'])) {
                                $items = strlen($collection['items']) > 50 ? 
                                        substr($collection['items'], 0, 50) . '...' : 
                                        $collection['items'];
                                echo htmlspecialchars($items);
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
                            <div class="action-buttons">
                                <a href="<?php echo site_url('admin/collections/view/' . $collection['id']); ?>" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if (isset($collection['status']) && $collection['status'] === 'pending'): ?>
                                    <button class="btn btn-success btn-sm" onclick="updateStatus(<?php echo $collection['id']; ?>, 'confirmed')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="updateStatus(<?php echo $collection['id']; ?>, 'cancelled')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('.table tbody tr');

    rows.forEach(row => {
        if (!status || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function updateStatus(collectionId, status) {
    const action = status === 'confirmed' ? 'confirm' : 'cancel';
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to ${action} this collection?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: status === 'confirmed' ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action} it`,
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo site_url('admin/update_collection_status'); ?>';
            
            const collectionIdInput = document.createElement('input');
            collectionIdInput.type = 'hidden';
            collectionIdInput.name = 'collection_id';
            collectionIdInput.value = collectionId;
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            
            form.appendChild(collectionIdInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
