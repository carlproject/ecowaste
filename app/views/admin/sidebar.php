<div class="sidebar">
    <div class="sidebar-header">
        <h2>EcoByte Admin</h2>
        <div class="admin-info">
            Administrator Panel
        </div>
    </div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="<?php echo site_url('admin'); ?>" class="nav-link <?php echo site_url() === 'admin' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('admin/users'); ?>" class="nav-link <?php echo site_url() === 'admin/users' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('admin/collections'); ?>" class="nav-link <?php echo site_url() === 'admin/collections' ? 'active' : ''; ?>">
                <i class="fas fa-truck-loading"></i> Collections
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('admin/rewards'); ?>" class="nav-link <?php echo site_url() === 'admin/rewards' ? 'active' : ''; ?>">
                <i class="fas fa-gift"></i> Rewards
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo site_url('admin/analytics'); ?>" class="nav-link <?php echo site_url() === 'admin/analytics' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
        </li>
    </ul>
    <a href="<?php echo site_url('logout'); ?>" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>
