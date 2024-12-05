<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_admin();
        $this->call->model('Users_model');
        $this->call->model('Collection');
        $this->call->model('Analytics');
        $this->call->model('Rewards');
    }

    private function check_admin() {
        if (!isset($_SESSION['user_id'])) {
            redirect('');
            return;
        }

        $user = $this->users_model->get_one($_SESSION['user_id']);
        if (!$user || $user['role'] !== 'admin') {
            redirect('dashboard');
            return;
        }
    }

    private function load_template($view, $data = array()) {
        ob_start();
        $this->call->view($view, $data);
        $data['content'] = ob_get_clean();
        $this->call->view('admin/template', $data);
    }

    public function index() {
        $recent_collections = $this->Collection->get_recent_collections();
        if ($recent_collections) {
            foreach ($recent_collections as &$collection) {
                $user = $this->users_model->get_one($collection['user_id']);
                $collection['username'] = $user ? $user['username'] : 'Unknown User';
            }
        }

        $data = array(
            'title' => 'Dashboard',
            'total_users' => $this->users_model->count_users(),
            'total_collections' => $this->Collection->count_all(),
            'pending_collections' => $this->Collection->count_by_status('pending'),
            'confirmed_collections' => $this->Collection->count_by_status('confirmed'),
            'recent_collections' => $recent_collections,
            'recent_users' => $this->users_model->get_recent_users()
        );
        
        $this->load_template('admin/dashboard', $data);
    }

    public function users() {
        $data = array(
            'title' => 'User Management',
            'users' => $this->users_model->get_all_users(),
            'admin_count' => $this->users_model->count_admins()
        );
        
        foreach ($data['users'] as &$user) {
            $user['collections_count'] = $this->users_model->get_user_collections_count($user['id']);
        }
        
        $this->load_template('admin/users', $data);
    }

    public function editUser($id) {
        $user = $this->users_model->get_one($id);
        if (!$user) {
            set_flash_alert('danger', 'User not found.');
            redirect('admin/users');
            return;
        }

        if ($this->form_validation->submitted()) {
            $data = array(
                'username' => $this->io->post('username'),
                'email' => $this->io->post('email'),
                'role' => $this->io->post('role'),
                'points' => $this->io->post('points')
            );

            $new_password = $this->io->post('password');
            if (!empty($new_password)) {
                $data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
            }

            if ($user['role'] === 'admin' && $data['role'] !== 'admin' && $this->users_model->count_admins() <= 1) {
                set_flash_alert('danger', 'Cannot change role of the last admin user.');
                redirect('admin/users');
                return;
            }

            if ($this->users_model->update_user($id, $data)) {
                set_flash_alert('success', 'User updated successfully.');
            } else {
                set_flash_alert('danger', 'Failed to update user. Username or email might already exist.');
            }
            redirect('admin/users');
            return;
        }

        $data = array(
            'title' => 'Edit User',
            'user' => $user,
            'is_last_admin' => $user['role'] === 'admin' && $this->users_model->count_admins() <= 1
        );
        
        $this->load_template('admin/edit_user', $data);
    }

    public function deleteUser($id) {
        $user = $this->users_model->get_one($id);
        if (!$user) {
            set_flash_alert('danger', 'User not found.');
            redirect('admin/users');
            return;
        }

        if ($user['role'] === 'admin' && $this->users_model->count_admins() <= 1) {
            set_flash_alert('danger', 'Cannot delete the last admin user.');
            redirect('admin/users');
            return;
        }

        if ($this->users_model->delete_user($id)) {
            set_flash_alert('success', 'User deleted successfully.');
        } else {
            set_flash_alert('danger', 'Failed to delete user.');
        }
        redirect('admin/users');
    }

    public function collections() {
        $collections = $this->Collection->get_all_with_users();
        if ($collections) {
            foreach ($collections as &$collection) {
                $user = $this->users_model->get_one($collection['user_id']);
                $collection['username'] = $user ? $user['username'] : 'Unknown User';
            }
        }

        $data = array(
            'title' => 'Collections Management',
            'collections' => $collections
        );
        
        $this->load_template('admin/collections', $data);
    }

    public function viewCollection($id) {
        $collection = $this->Collection->get_one($id);
        if (!$collection) {
            set_flash_alert('danger', 'Collection not found.');
            redirect('admin/collections');
            return;
        }

        $user = $this->users_model->get_one($collection['user_id']);
        $collection['username'] = $user ? $user['username'] : 'Unknown User';
        $collection['email'] = $user ? $user['email'] : 'N/A';

        $data = array(
            'title' => 'View Collection',
            'collection' => $collection
        );
        
        $this->load_template('admin/view_collection', $data);
    }

    public function update_collection_status() {
        if ($this->form_validation->submitted()) {
            $collection_id = $this->io->post('collection_id');
            $status = $this->io->post('status');
            
            $collection = $this->Collection->get_one($collection_id);
            if (!$collection) {
                set_flash_alert('danger', 'Collection not found.');
                redirect('admin/collections');
                return;
            }

            if ($this->Collection->update_collection($collection_id, ['status' => $status])) {
                if ($status === 'confirmed') {
                    $this->Rewards->addPoints($collection['user_id'], 100);
                }
                set_flash_alert('success', 'Collection status updated successfully.');
            } else {
                set_flash_alert('danger', 'Failed to update collection status.');
            }
        }
        redirect('admin/collections');
    }

    public function analytics() {
        // Get user participation data and ensure it's properly formatted
        $user_participation = $this->Analytics->getUserParticipation();
        if (!is_array($user_participation)) {
            $user_participation = [];
        }

        $data = array(
            'title' => 'Analytics',
            'total_collections' => $this->Collection->count_all(),
            'collection_stats' => $this->Collection->get_monthly_stats(),
            'category_trends' => $this->Analytics->getCategoryTrends(),
            'completion_rate' => $this->Analytics->getCompletionRate(),
            'monthly_comparison' => $this->Analytics->getMonthlyComparison(),
            'user_participation' => array_map(function($user) {
                return [
                    'username' => $user['username'] ?? 'Unknown',
                    'total_collections' => $user['total_collections'] ?? 0,
                    'confirmed' => $user['confirmed'] ?? 0
                ];
            }, $user_participation)
        );
        
        $this->load_template('admin/analytics', $data);
    }

    public function rewards() {
        $data = array(
            'title' => 'Rewards Management',
            'rewards' => $this->Rewards->getAllRewards(),
            'claimed_rewards' => $this->Rewards->getAllClaimedRewards()
        );
        
        $this->load_template('admin/rewards', $data);
    }

    public function add_reward() {
        if ($this->form_validation->submitted()) {
            $data = array(
                'name' => $this->io->post('name'),
                'description' => $this->io->post('description'),
                'points_required' => $this->io->post('points_required'),
                'status' => 'active'
            );
            
            if ($this->Rewards->add_reward($data)) {
                set_flash_alert('success', 'Reward added successfully.');
            } else {
                set_flash_alert('danger', 'Failed to add reward.');
            }
        }
        redirect('admin/rewards');
    }

    public function update_reward($id) {
        if ($this->form_validation->submitted()) {
            $data = array(
                'name' => $this->io->post('name'),
                'description' => $this->io->post('description'),
                'points_required' => $this->io->post('points_required')
            );
            
            if ($this->Rewards->update_reward($id, $data)) {
                set_flash_alert('success', 'Reward updated successfully.');
            } else {
                set_flash_alert('danger', 'Failed to update reward.');
            }
        }
        redirect('admin/rewards');
    }

    public function delete_reward($id) {
        if ($this->Rewards->delete_reward($id)) {
            set_flash_alert('success', 'Reward deleted successfully.');
        } else {
            set_flash_alert('danger', 'Failed to delete reward.');
        }
        redirect('admin/rewards');
    }

    public function get_reward($id) {
        $reward = $this->Rewards->get_reward_details($id);
        if ($reward) {
            echo json_encode($reward);
        } else {
            echo json_encode(['error' => 'Reward not found']);
        }
        exit();
    }

    public function export_report() {
        $collections = $this->Collection->get_all_with_users();
        if ($collections) {
            foreach ($collections as &$collection) {
                $user = $this->users_model->get_one($collection['user_id']);
                $collection['username'] = $user ? $user['username'] : 'Unknown User';
            }
        }

        $filename = "collections_report_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, array('ID', 'User', 'Date', 'Time', 'Address', 'Items', 'Status', 'Tracking Number'));
        
        if ($collections) {
            foreach ($collections as $collection) {
                fputcsv($output, array(
                    $collection['id'],
                    $collection['username'],
                    $collection['pickup_date'],
                    $collection['pickup_time'],
                    $collection['address'],
                    $collection['items'],
                    $collection['status'],
                    $collection['tracking_number']
                ));
            }
        }
        
        fclose($output);
        exit();
    }

    public function getStats() {
        $data = array(
            'total_users' => $this->users_model->count_users(),
            'total_collections' => $this->Collection->count_all(),
            'pending_collections' => $this->Collection->count_by_status('pending'),
            'confirmed_collections' => $this->Collection->count_by_status('confirmed')
        );
        echo json_encode($data);
        exit();
    }

    public function getRecentCollections() {
        $collections = $this->Collection->get_recent_collections();
        if ($collections) {
            foreach ($collections as &$collection) {
                $user = $this->users_model->get_one($collection['user_id']);
                $collection['username'] = $user ? $user['username'] : 'Unknown User';
            }
        }
        echo json_encode($collections);
        exit();
    }

    public function getRecentUsers() {
        $users = $this->users_model->get_recent_users();
        echo json_encode($users);
        exit();
    }
}
?>
