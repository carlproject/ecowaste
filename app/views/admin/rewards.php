<div class="content-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Rewards Management</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRewardModal">
            <i class="fas fa-plus me-2"></i>Add New Reward
        </button>
    </div>
</div>

<div class="content-card">
    <?php if (isset($rewards) && !empty($rewards)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Points Required</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rewards as $reward): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reward['name']); ?></td>
                            <td><?php echo htmlspecialchars($reward['description']); ?></td>
                            <td><?php echo number_format($reward['points_required']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $reward['status'] === 'active' ? 'success' : 'danger'; ?>">
                                    <?php echo ucfirst($reward['status']); ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editReward(<?php echo htmlspecialchars(json_encode($reward)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteReward(<?php echo $reward['id']; ?>, '<?php echo htmlspecialchars($reward['name']); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No rewards available.</p>
    <?php endif; ?>
</div>

<!-- Add Reward Modal -->
<div class="modal fade" id="addRewardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Reward</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo site_url('admin/rewards/add'); ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="points_required" class="form-label">Points Required</label>
                        <input type="number" class="form-control" id="points_required" name="points_required" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Reward</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Reward Modal -->
<div class="modal fade" id="editRewardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Reward</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRewardForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_points_required" class="form-label">Points Required</label>
                        <input type="number" class="form-control" id="edit_points_required" name="points_required" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Reward</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const editRewardModal = new bootstrap.Modal(document.getElementById('editRewardModal'));

function editReward(reward) {
    document.getElementById('edit_name').value = reward.name;
    document.getElementById('edit_description').value = reward.description;
    document.getElementById('edit_points_required').value = reward.points_required;
    document.getElementById('editRewardForm').action = `<?php echo site_url('admin/rewards/update/'); ?>${reward.id}`;
    editRewardModal.show();
}

function deleteReward(id, name) {
    if (confirm(`Are you sure you want to delete the reward "${name}"?`)) {
        window.location.href = `<?php echo site_url('admin/rewards/delete/'); ?>${id}`;
    }
}
</script>
