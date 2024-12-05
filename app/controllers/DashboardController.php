<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DashboardController extends Controller {
    public function __construct() {
        parent::__construct();
        check_auth(); // Require authentication for all methods in this controller
        $this->call->model('Collection');
        $this->call->model('Users_model');
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $user = $this->users_model->get_one($user_id);

        // Get collection statistics
        $total_pickups = $this->Collection->getUserCollectionsCount($user_id);
        $pending_pickups = $this->Collection->getUserCollectionsByStatus($user_id, 'pending');
        $completed_pickups = $this->Collection->getUserCollectionsByStatus($user_id, 'confirmed');

        // Get recent pickups
        $recent_pickups = $this->Collection->get_user_recent_pickups($user_id, 5); // Get last 5 pickups

        $data = array(
            'username' => isset($_SESSION['username']) ? $_SESSION['username'] : 'User',
            'points' => $user['points'] ?? 0,
            'total_pickups' => $total_pickups,
            'pending_pickups' => $pending_pickups,
            'completed_pickups' => $completed_pickups,
            'recent_pickups' => $recent_pickups
        );

        $this->call->view('dashboard', $data);
    }
}
?>
